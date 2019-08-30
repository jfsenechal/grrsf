<?php

namespace App\Tests\Form;

use App\Entity\Entry;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Bridge\Doctrine\Tests\TestManagerRegistry;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;
use App\Factory\DurationFactory;
use App\Form\EntryType;
use App\Form\Type\EntryTypeSelectField;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

//https://symfony.com/doc/current/form/unit_testing.html

class FormTypeEntryTest extends TypeTestCase
{
    /**
     * @var DurationFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $durationFactory;

    protected function setUp()
    {
        // mock any dependencies
        $this->durationFactory = $this->createMock(DurationFactory::class);

        parent::setUp();
    }

    protected function getExtensions()
    {
        // create a type instance with the mocked dependencies
        $type = new EntryType($this->durationFactory);

        $t = [
            new PreloadedExtension([$type], []),
        ];

        return  array_merge(parent::getExtensions(), $t);
    }

    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'test',
            'test2' => 'test2',
        ];

        $durationFactory = new DurationFactory();
        $objectToCompare = new Entry();

        // Instead of creating a new instance, the one created in
        // getExtensions() will be used.
        $form = $this->factory->create(EntryType::class, $objectToCompare);

        $object = new Entry();
        $object->setName('test');
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
