<?php

class ErrorExTest extends PHPUnit_Framework_TestCase
{
    public function testRule3()
    {

        $rule = new \Aw\Validator\DateValidator();



        $rule->validate('20');
        var_dump($rule->message);
//        var_dump($rule->getErrors());
//
//        $rule->setMode(\Aw\Validator\Rules::MODE_SINGLE);
//        $this->assertFalse($rule->validate());
//        $this->assertEquals(count($rule->getErrors()) ,1);
//        var_dump($rule->getErrors());
//
//        $rule->setRules(array(
//            'foo' => 'bail|str:2|required:qq',
//            'bar' => 'bail|str:3|required:xx'
//        ));
//        $rule->setMode(\Aw\Validator\Rules::MODE_MUT);
//        $this->assertFalse($rule->validate());
//        $this->assertEquals(count($rule->getErrors()) ,4);
//        var_dump($rule->getErrors());
//
//
//        $rule->setMode(\Aw\Validator\Rules::MODE_SINGLE);
//        $this->assertFalse($rule->validate());
//        $this->assertEquals(count($rule->getErrors()) ,2);
//        var_dump($rule->getErrors());
    }
}

