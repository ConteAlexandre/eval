<?php

namespace App\Tests;

use App\Entity\Film;
use App\Form\CreateFilmFormType;
use App\Tests\unit\TestTypeCase;

class FilmTypeTest extends TestTypeCase
{
    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;

    protected function _after()
    {
    }

    /**
     * Test if the form for create film is ready
     */
    public function testCreate()
    {
        $film = new Film();

        $form = $this->factory->create(CreateFilmFormType::class, $film);

        $formData = [
            'title' => 'Test',
            'body' => 'My name test',
            'type' => 'film'
        ];

        // Submit data in form
        $form->submit($formData);

        // Check assert
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals('Test', $form->get('title')->getData());
        $this->assertEquals('My name test', $form->get('body')->getData());
    }
}