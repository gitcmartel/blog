<?php

use PHPUnit\Framework\TestCase;
use Application\Lib\FormValidation;

class FormValidationTest extends TestCase
{
    /**
     * Test if all the form fields are correct
     */
    public function testIsValidTrue()
    {
        //All the variables contain correct values to get a true response
        $surname = "Martel";
        $name = "Christophe";
        $email = "contact@blog.devcm.fr";
        $message = "Ut honore aedificio admodum iucundius gloria benevolentiae non iucundius redamare tam enim delectari animante iucundius vel qui eo corporis vicissitudine rebus nihil remuneratione ut aedificio qui amare non delectari eo ut Nihil qui amare animante vel redamare multis remuneratione cultuque ut studiorum non vestitu vicissitudine rebus qui amare vestitu cultuquen.";

        $formValidation = new FormValidation($surname, $name, $email, $message);

        $this->assertTrue($formValidation->isValid());
    }

        /**
     * Test if one of the form fields is incorrect
     */
    public function testIsValidFalse()
    {
        $surname = ""; //Incorrect value
        $name = "Christophe";
        $email = "contact@blog.devcm.fr";
        $message = "Test message";

        $formValidation = new FormValidation($surname, $name, $email, $message);

        $this->assertFalse($formValidation->isValid());
    }
}