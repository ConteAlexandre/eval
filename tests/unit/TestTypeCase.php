<?php

namespace App\Tests\unit;

use Codeception\Test\Unit;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Class TestTypeCase
 */
abstract class TestTypeCase extends Unit
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    /**
     * Setup the variable factory before unit test
     */
    public function _before()
    {
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addTypeExtensions($this->getTypeExtensions())
            ->addTypes($this->getTypes())
            ->addTypeGuessers($this->getTypeGuessers())
            ->getFormFactory();
    }

    /**
     * @return ValidatorExtension[]
     */
    protected function getExtensions()
    {
        $metadata = new ClassMetadata(Form::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')->will($this->returnValue(new ConstraintViolationList()));
        $validator->method('getMetadataFor')->will($this->returnValue($metadata));

        return [
            new ValidatorExtension($validator),
        ];
    }



    /**
     * @return array
     */
    protected function getTypeExtensions()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getTypes()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getTypeGuessers()
    {
        return [];
    }

}