<?php

class DebugRuleTest extends PHPUnit_Framework_TestCase
{

    public function testRuleEmpty()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'empty|str:3,',
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
        $this->assertTrue($rule->validate());
        $rule->setData(
            array(
                'foo' => 'dd',
            )
        );
        $this->assertFalse($rule->validate());
    }

    public function testRuleArray()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'array|int',
        ));
        $rule->setData(
            array(
                'foo' => array('2017', 'dd'),
            )
        );
        $ret = $rule->validate();
        $this->assertFalse($ret);
        $this->assertEquals("dd",$rule->lastMalignantValue);
    }

    public function testRuleDm()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'array|url:dm',
        ));
        $rule->setData(
            array(
                'foo' => array('www.aa.com', 'dd.cn'),
            )
        );
        $ret = $rule->validate();
        $this->assertTrue($ret);
    }

    public function testRuleDm2()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'array|url',
        ));
        $rule->setData(
            array(
                'foo' => array('www.aa.com', 'dd.cn'),
            )
        );
        $ret = $rule->validate();
        $this->assertFalse($ret);
        $this->assertEquals("www.aa.com",$rule->lastMalignantValue);
    }

    public function testRuleDm3()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'array|url',
        ));
        $rule->setData(
            array(
                'foo' => array('http://www.aa.com', 'https://dd.cn'),
            )
        );
        $ret = $rule->validate();
        $this->assertTrue($ret);
    }

    public function testJson1()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'array|json',
        ));
        $rule->setData(
            array(
                'foo' => array('{"aa":1,"bbb":"22222222","cc":[1,2]}', ':{}'),
            )
        );
        $ret = $rule->validate();
        $this->assertFalse($ret);
        $this->assertEquals(":{}",$rule->lastMalignantValue);
    }

    public function testJson2()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'array|json',
        ));
        $rule->setData(
            array(
                'foo' => array('{}', '{"aa":1,"bbb":"22222222","cc":[1,2]}'),
            )
        );
        $ret = $rule->validate();
        $this->assertTrue($ret);
    }


    public function testSeparator()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'separator|int',
        ));
        $rule->setData(
            array(
                'foo' => "1,2,3,4,5",
            )
        );
        $ret = $rule->validate();
        $this->assertTrue($ret);
    }

    public function testSeparator2()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'separator:'.\Aw\Validator\Rules::STR_SEPARATOR_OR.'|int',
        ));
        $rule->setData(
            array(
                'foo' => "1|2|3|4|5",
            )
        );
        $ret = $rule->validate();
        $this->assertTrue($ret);
    }

    public function testSeparator3()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'separator:'.\Aw\Validator\Rules::STR_SEPARATOR_COLON.'|int',
        ));
        $rule->setData(
            array(
                'foo' => "1:2:3:4:5",
            )
        );
        $ret = $rule->validate();
        $this->assertTrue($ret);
    }
    public function testSeparator4()
    {
        $rule = new \Aw\Validator\Rules();

        $rule->setRules(array(
            'foo' => 'separator|int',
        ));
        $rule->setData(
            array(
                'foo' => "1,2,3,dd,5",
            )
        );
        $ret = $rule->validate();
        $this->assertFalse($ret);
        $this->assertEquals("dd",$rule->lastMalignantValue);
    }
}
