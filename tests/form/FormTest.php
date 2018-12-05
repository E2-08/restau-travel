<?php
namespace App\tests\form;

use Symfony\Component\Form\Test\TypeTestCase;
use App\Form\RegistrationType;

class RegisterTypeTest extends TypeTestCase
{
    /**
     * Registion test
     *
     * @return void
     */
    public function testSubmitValidData()
    {
        $form = $this->factory->create(RegistrationType::class);

        //here we submit data
        $form->submit(array(
            'lastName' => 'Eudes',
            'firstName' => 'KOBEMBA',
            'hash' => 'password',
            'email' => 'eudes@ici08.fr',
            'avatar' => '',
            'passwordConfirme' => 'password'
        ));
        static::assertTrue(
            $form->isSubmitted()
        );
        static::assertEquals(
            array(
                'lastName' => 'Eudes',
                'firstName' => 'KOBEMBA',
                'hash' => 'password',
                'email' => 'eudes@ici08.fr',
                'avatar' => '',
                'passwordConfirme' => 'password'
            ),
            $form->getData()
        );
        static::assertTrue(
            $form->isValid()
        );
    }
}