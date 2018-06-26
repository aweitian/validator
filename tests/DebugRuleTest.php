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
}
