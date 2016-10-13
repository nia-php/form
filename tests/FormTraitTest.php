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
namespace Test\Nia\Form;

use PHPUnit_Framework_TestCase;
use Nia\Form\FormTrait;
use Nia\Form\FormInterface;
use Nia\Collection\Map\StringMap\Map;
use Nia\Validation\LengthValidator;
use Nia\Sanitizing\TrimSanitizer;

/**
 * Unit test for \Nia\Form\FormTrait.
 */
class FormTraitTest extends PHPUnit_Framework_TestCase
{

    /**
     * @covers \Nia\Form\FormTrait
     */
    public function testMethods()
    {
        $form = $this->createForm();
        $form->addField('username', new LengthValidator(4, 16), new TrimSanitizer());
        $form->addField('password');
        $form->addField('dummy');

        // success
        $context = new Map([
            'username' => ' a b c ',
            'password' => ' d e f ',
            'unused' => 'foobar i am unused',
            'dummy' => 'foobar'
        ]);
        $data = new Map([
            'username' => ' my-nick ',
            'password' => ' xxx '
        ]);

        $violations = $form->validate($data, $context);

        $this->assertSame(0, count($violations));
        $this->assertSame([
            'username' => 'my-nick',
            'password' => ' xxx ',
            'unused' => 'foobar i am unused',
            'dummy' => 'foobar'
        ], iterator_to_array($context));

        // fail
        $context = new Map([
            'username' => ' a b c ',
            'password' => ' d e f ',
            'unused' => 'foobar i am unused'
        ]);

        $data = new Map([
            'username' => ' a ',
            'password' => ' xxx ',
        ]);
        $violations = $form->validate($data, $context);

        $this->assertSame(1, count($violations));
        $this->assertSame(true, array_key_exists('username', $violations));
        $this->assertSame(1, count($violations['username']));
        $this->assertSame('length:to-short', $violations['username'][0]->getId());

        $this->assertSame([
            'username' => 'a',
            'password' => ' xxx ',
            'unused' => 'foobar i am unused',
            'dummy' => ''
        ], iterator_to_array($context));
    }

    private function createForm(): FormInterface
    {
        return new class() implements FormInterface {
            use FormTrait;
        };
    }
}
