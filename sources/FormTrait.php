<?php
/*
 * This file is part of the nia framework architecture.
 *
 * (c) Patrick Ullmann <patrick.ullmann@nat-software.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types = 1);
namespace Nia\Form;

use Nia\Collection\Map\StringMap\MapInterface;
use Nia\Collection\Map\StringMap\Map;
use Nia\Collection\Map\StringMap\WriteableMapInterface;
use Nia\Sanitizing\SanitizerInterface;
use Nia\Validation\ValidatorInterface;
use Nia\Sanitizing\NullSanitizer;
use Nia\Validation\NullValidator;

/**
 * Basic functionality for implementations of the Nia\Form\FormInterface.
 */
trait FormTrait
{

    /**
     * List with registred field names.
     *
     * @var string[]
     */
    private $fieldNames = [];

    /**
     * Map with validators associated with field names as keys.
     *
     * @var ValidatorInterface[]
     */
    private $validators = [];

    /**
     * Map with sanitizers associated with field names as keys.
     *
     * @var SanitizerInterface[]
     */
    private $sanitizers = [];

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Form\FormInterface::addField($fieldName, $validator, $sanitizer)
     */
    public function addField(string $fieldName, ValidatorInterface $validator = null, SanitizerInterface $sanitizer = null): FormInterface
    {
        $this->fieldNames[] = $fieldName;
        $this->validators[$fieldName] = $validator ?? new NullValidator();
        $this->sanitizers[$fieldName] = $sanitizer ?? new NullSanitizer();

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Nia\Form\FormInterface::validate($data, $context)
     */
    public function validate(MapInterface $data, WriteableMapInterface $context = null): array
    {
        $context = $context ?? new Map();

        $result = [];

        foreach ($this->fieldNames as $fieldName) {
            $value = $data->tryGet($fieldName, $context->tryGet($fieldName, ''));

            $value = $this->sanitizers[$fieldName]->sanitize($value);
            $context->set($fieldName, $value);
        }

        foreach ($this->fieldNames as $fieldName) {
            $violations = $this->validators[$fieldName]->validate($context->get($fieldName), $context);

            if (count($violations) !== 0) {
                $result[$fieldName] = $violations;
            }
        }

        return $result;
    }
}
