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
use Nia\Collection\Map\StringMap\WriteableMapInterface;
use Nia\Sanitizing\SanitizerInterface;
use Nia\Validation\ValidatorInterface;
use Nia\Validation\Violation\ViolationInterface;

/**
 * Interface for form validation implementations.
 */
interface FormInterface
{

    /**
     * Adds a field.
     *
     * @param string $fieldName
     *            Name of the field.
     * @param ValidatorInterface $validator
     *            Optional validator. If no validator is set the field is optional.
     * @param SanitizerInterface $sanitizer
     *            Optional sanitizer.
     * @return FormInterface Reference to this instance.
     */
    public function addField(string $fieldName, ValidatorInterface $validator = null, SanitizerInterface $sanitizer = null): FormInterface;

    /**
     * Validates the passed data against this form and returns the violations.
     *
     * @param MapInterface $data
     *            The data to validate against this form.
     * @param WriteableMapInterface $context
     *            The context. This context will be filled up with the matches.
     * @return ViolationInterface[] Map with violations associated with field names as keys. No violation is occurred if the map is empty.
     */
    public function validate(MapInterface $data, WriteableMapInterface $context = null): array;
}
