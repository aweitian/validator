<?php

class SeparatorRuleTest extends PHPUnit_Framework_TestCase
{
    public function testSeparator5()
    {
        $v = new \Aw\Validator\NumberValidator();
        $v->integerOnly = true;
        $v->allowEmpty = false;
        $ret = $v->validate("");
        $this->assertFalse($ret);


        $v = new \Aw\Validator\NumberValidator();
        $v->isStrSeparator = true;
        $v->allowEmpty = false;

        $ret = $v->validate("1,3,");
        $this->assertFalse($ret);
    }
}
