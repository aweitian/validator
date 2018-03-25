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

    public function testRule2()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'str:2|required:qq'
        ));


        $rule->setData(
            array(
                'foo' => '123',
            )
        );

        $this->assertFalse($rule->validate());
        $this->assertEquals(count($rule->getErrors()) ,1);

        $rule->setRules(array(
            'foo' => 'bail|str:2|required:qq'
        ));
        $this->assertFalse($rule->validate());
        $this->assertEquals(count($rule->getErrors()) ,1);
    }


    public function testRule3()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'str:2|required:qq',
            'bar' => 'str:3|required:xx'
        ));


        $rule->setData(
            array(
                'foo' => '123',
                'd' => '22',
            )
        );

        $this->assertFalse($rule->validate());
        $this->assertEquals(count($rule->getErrors()) ,2);
//        var_dump($rule->getErrors());

        $rule->setMode(\Aw\Validator\Rules::MODE_SINGLE);
        $this->assertFalse($rule->validate());
        $this->assertEquals(count($rule->getErrors()) ,1);
//        var_dump($rule->getErrors());

        $rule->setRules(array(
            'foo' => 'bail|str:2|required:qq',
            'bar' => 'bail|str:3|required:xx'
        ));
        $rule->setMode(\Aw\Validator\Rules::MODE_MUT);
        $this->assertFalse($rule->validate());
//        var_dump($rule->getErrors());
        $err = $rule->getErrors();
        $this->assertEquals(count($err) ,2);
        var_dump($err);
        $this->assertTrue(is_array($err['foo']));
//        var_dump($rule->getErrors());


        $rule->setMode(\Aw\Validator\Rules::MODE_SINGLE);
        $this->assertFalse($rule->validate());
        $this->assertEquals(count($rule->getErrors()) ,1);
//        var_dump($rule->getErrors());
    }
}

