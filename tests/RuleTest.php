<?php

class RuleTest extends PHPUnit_Framework_TestCase
{
    public function testRule1()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'required:qq|bail',
            'bar' => 'gt:cmp',
            'cmp' => 'int:4,9'
        ));

        $rule->setData(
            array(
                'foo' => 'qq',
                'bar' => 5,
                'cmp' => 4
            )
        );

        $this->assertTrue($rule->validate());

        $rule->setRules(array(
            'foo' => 'required:qq|bail',
            'bar' => 'gt:cmp',
            'cmp' => 'int:4,9'
        ));

        $rule->setData(
            array(
                'bar' => 4,
                'cmp' => 4
            )
        );

        $this->assertFalse($rule->validate());
    }


    public function testRule2()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'required:qq|bail',
            'bar' => 'gt:cmp',
            'cmp' => 'int:4,9'
        ));

        $rule->setData(
            array(
                'bar' => 5,
                'cmp' => 4
            )
        );

        $this->assertFalse($rule->validate());
        //var_dump($rule->getErrors());
    }


    public function testRule3()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'required|bail',
            'bar' => 'range:a,b,c,dd,e',
            'cmp' => 'int:4,9'
        ));

        $rule->setData(
            array(
                'foo' => 'ba',
                'bar' => 'a',
                'cmp' => 4
            )
        );

        $this->assertTrue($rule->validate());

        $rule->setRules(array(
            'foo' => 'required|bail',
            'bar' => 'range:a,b,c,dd,e',
            'cmp' => 'str:5'
        ));

        $rule->setData(
            array(
                'foo' => 'ba',
                'bar' => 'a',
                'cmp' => '12135'
            )
        );

        $this->assertTrue($rule->validate());

        $rule->setData(
            array(
                'foo' => 'ba',
                'bar' => 'a',
                'cmp' => '1234'
            )
        );
        $this->assertFalse($rule->validate());

        //var_dump($rule->getErrors());
    }




    public function testRule4()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'required|bail',
            'bar' => 'regexp:#^aa\d{3}$#',
            'cmp' => 'email',
            'url' => 'url'
        ));

        $rule->setData(
            array(
                'foo' => 'ba',
                'bar' => 'aa123',
                'cmp' => 'awei.tian@qqq.com',
                'url' => 'http://a.com'
            )
        );

        $this->assertTrue($rule->validate());


        $rule->setData(
            array(
                'foo' => 'ba',
                'bar' => 'a',
                'cmp' => '12135'
            )
        );

        $this->assertFalse($rule->validate());

    }

    public function testRule5()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'fun:check::c',

        ));

        $rule->setData(
            array(
                'foo' => 'foo',
            )
        );

        $this->assertTrue($rule->validate());


        $rule->setData(
            array(
                'foo' => 'ba',
            )
        );
        ;
//        var_dump($rule->getErrors());
        $this->assertFalse($rule->validate());
//        var_dump($rule->getErrors());
    }

    public function testRule6()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'str',
        ));
        $rule->setData(
            array(
                //'foo' => '',
            )
        );
        $this->assertFalse($rule->validate());
        $rule->setData(
            array(
                'foo' => 'ba',
            )
        );
        $this->assertTrue($rule->validate());
    }


    public function testRule7()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'empty|str:2',
        ));
        $rule->setData(
            array(
                'foo' => '',
            )
        );
        $this->assertTrue($rule->validate());
        $rule->setData(
            array(
                'foo' => 'bar',
            )
        );
        $this->assertFalse($rule->validate());
        $rule->setData(
            array(
                'foo' => 'dd',
            )
        );
        $this->assertTrue($rule->validate());
    }



    public function testRule8()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'date',
            'bar' => 'datetime',
            'lol' => 'time',
            'x' => 'year',
        ));
        $rule->setData(
            array(
                'foo' => '2017-2-2',
                'bar' => '2018-10-25 1:10:1',
                'lol' => '1:10:1',
                'x' => '2018',
            )
        );
        $this->assertTrue($rule->validate());
        $rule->setData(
            array(
                'foo' => 'bar',
            )
        );
        $this->assertFalse($rule->validate());
    }

}

class check
{
    public function c($value)
    {
        return $value == 'foo';
    }
}
