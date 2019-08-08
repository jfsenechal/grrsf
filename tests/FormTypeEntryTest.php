<?php

namespace App\Tests;

use App\Form\EntryType;
use App\Form\Type\EntryTypeField;
use PHPUnit\Framework\TestCase;
use App\Form\Type\TestedType;
use App\Model\TestObject;
use Symfony\Component\Form\Test\TypeTestCase;

//https://symfony.com/doc/current/form/unit_testing.html

class FormTypeEntryTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'test' => 'test',
            'test2' => 'test2',
        ];

        $objectToCompare = new EntryType();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(EntryTypeField::class, $objectToCompare);

        $object = new EntryType();
        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
