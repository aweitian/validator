<?php

class ErrorTest extends PHPUnit_Framework_TestCase
{
    public function testRule1()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'required:qq',
            'bar' => 'gt:cmp',
            'cmp' => 'int:4,9'
        ));

        $rule->setText(array(
            'foo' => "[[foo-text]]",
            "bar" => "--{{var}}--"
        ));

        $rule->setOverrideErrors(array(
           "foo" =>  "{attribute} only accept char is qq."
        ));

        $rule->setData(
            array(
                'bar' => 54,
                'cmp' => 74
            )
        );

        $this->assertFalse($rule->validate());
        var_dump($rule->getErrors());
    }

}

