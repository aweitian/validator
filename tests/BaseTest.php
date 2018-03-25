<?php

use Aw\Validator\CompareValidator;

class BaseTest extends PHPUnit_Framework_TestCase
{
    public function testBoolean()
    {
        $validator = new \Aw\Validator\BooleanValidator();
        $validator->allowEmpty = true;
        $this->assertFalse($validator->validate(null));
        $this->assertTrue($validator->validate(''));
        $validator->allowEmpty = false;
        $this->assertFalse($validator->validate(null));
        $this->assertFalse($validator->validate(''));
        $this->assertTrue($validator->validate(1));
        $this->assertFalse($validator->validate(0));
        $validator->strict = true;
        $this->assertFalse($validator->validate(null));
        $this->assertFalse($validator->validate(1));
        $this->assertFalse($validator->validate(0));
        $this->assertTrue($validator->validate(true));
    }

    public function testCompare()
    {
        $validator = new CompareValidator();
        $validator->allowEmpty = true;
        $this->assertTrue($validator->validate(null));
        $this->assertTrue($validator->validate(''));
        $validator->allowEmpty = false;
        $this->assertTrue($validator->validate(null)); //compare is null
        $validator->compareValue = 5;
        $this->assertFalse($validator->validate(null));

        $validator->operator = CompareValidator::OPERATOR_GE;
        $this->assertFalse($validator->validate(3));
        $this->assertTrue($validator->validate(5));
        $this->assertTrue($validator->validate(6));

        $validator->operator = CompareValidator::OPERATOR_LT;
        $this->assertTrue($validator->validate(3));
        $this->assertFalse($validator->validate(5));
        $this->assertFalse($validator->validate(6));

    }

    public function testDate()
    {
        $validator = new \Aw\Validator\DateValidator();
        $validator->allowEmpty = true;
        $this->assertFalse($validator->validate(null));
        $this->assertTrue($validator->validate(''));
        $validator->allowEmpty = false;
        $this->assertFalse($validator->validate(null));
        $this->assertFalse($validator->validate(''));
        $this->assertTrue($validator->validate('2017-1-20 10:1:1'));
        $this->assertTrue($validator->validate('2017/1/20 10:1:1'));
        $this->assertTrue($validator->validate('2017.1.20 10:1:1'));
        $this->assertFalse($validator->validate('2017#1#20 10:1:1'));//false
        $this->assertTrue($validator->validate('2017-01-20 10:01:01'));
        $this->assertFalse($validator->validate('2017-01-20 10:01'));//false
        $validator->mode = \Aw\Validator\DateValidator::MODE_DATE;
        $this->assertTrue($validator->validate('2017-1-20'));
        $this->assertTrue($validator->validate('2017.11.20'));
        $this->assertTrue($validator->validate('2017/11/20'));
        $this->assertFalse($validator->validate('02017/11/20'));//false

        $validator->mode = \Aw\Validator\DateValidator::MODE_TIME;
        $this->assertTrue($validator->validate('10:1:1'));
        $this->assertTrue($validator->validate('10:01:1'));
        $this->assertTrue($validator->validate('10:51:1'));
        $this->assertFalse($validator->validate('10/1/1'));//false

        $validator->mode = \Aw\Validator\DateValidator::MODE_YEAR;
        $this->assertTrue($validator->validate('2017'));
        $this->assertFalse($validator->validate('123'));//false
    }

    public function testEmail()
    {
        $validator = new \Aw\Validator\EmailValidator();
        $validator->allowEmpty = true;
        $this->assertFalse($validator->validate(null));
        $this->assertTrue($validator->validate(''));
        $validator->allowEmpty = false;
        $this->assertFalse($validator->validate(null));
        $this->assertFalse($validator->validate(''));
        $this->assertTrue($validator->validate("awei.tian@qq.com"));
        $this->assertFalse($validator->validate("gg#aa.c"));
    }

    public function testNumber()
    {
        $validator = new \Aw\Validator\NumberValidator();
        $validator->allowEmpty = true;
        $this->assertFalse($validator->validate(null));
        $this->assertTrue($validator->validate(''));
        $validator->allowEmpty = false;
        $this->assertFalse($validator->validate(null));
        $this->assertFalse($validator->validate(''));

        $validator->max = 123;
        $this->assertTrue($validator->validate(0.1));
        $this->assertTrue($validator->validate(110));
        $this->assertTrue($validator->validate(123));
        $this->assertFalse($validator->validate(124));
        $this->assertTrue($validator->validate(-1));
        $validator->min = 0;
        $this->assertFalse($validator->validate(-1));

        $validator->integerOnly = true;
        $this->assertFalse($validator->validate(0.1));
        $validator->min = -9;
        $this->assertTrue($validator->validate(-1));

        $validator->unsignedOnly = true;
        $this->assertFalse($validator->validate(-1));

    }

    public function testRange()
    {
        $validator = new \Aw\Validator\RangeValidator();
        $validator->allowEmpty = true;
        $this->assertFalse($validator->validate(null));
        $this->assertTrue($validator->validate(''));
        $validator->allowEmpty = false;
        $this->assertFalse($validator->validate(null));

        $validator->range = array('foo','bar',12);
        $this->assertTrue($validator->validate('foo'));
        $this->assertTrue($validator->validate('bar'));
        $this->assertTrue($validator->validate(12));
        $this->assertTrue($validator->validate('12'));
        $this->assertFalse($validator->validate('qq'));

        $validator->range = array('foo','bar','12');
        $this->assertTrue($validator->validate('foo'));
        $this->assertTrue($validator->validate('bar'));
        $this->assertTrue($validator->validate(12));
        $this->assertTrue($validator->validate('12'));
        $this->assertFalse($validator->validate('qq'));

        $validator->strict = true;
        $validator->range = array('foo','bar','12');
        $this->assertTrue($validator->validate('foo'));
        $this->assertTrue($validator->validate('bar'));
        $this->assertFalse($validator->validate(12));
        $this->assertTrue($validator->validate('12'));
        $this->assertFalse($validator->validate('qq'));

    }

    public function testRegular()
    {
        $validator = new \Aw\Validator\RegularExpressionValidator();
        $validator->allowEmpty = true;
        $this->assertFalse($validator->validate(null));
        $this->assertTrue($validator->validate(''));
        $validator->allowEmpty = false;
        $this->assertFalse($validator->validate(null));
        $this->assertFalse($validator->validate(''));

        $validator->pattern = '#^\d+$#';
        $this->assertTrue($validator->validate(123));
        $this->assertTrue($validator->validate('0125'));
        $this->assertFalse($validator->validate('0e125'));
    }

    public function testRequired()
    {
        $validator = new \Aw\Validator\RequiredValidator();
        $validator->allowEmpty = true;
        $this->assertFalse($validator->validate(null));
        $validator->allowEmpty = false;
        $this->assertFalse($validator->validate(null));
        $this->assertFalse($validator->validate(''));

        $this->assertTrue($validator->validate(123));

        $validator->requiredValue = 'foo';
        $this->assertFalse($validator->validate(123));
        $this->assertTrue($validator->validate('foo'));
    }

    public function testString()
    {
        $validator = new \Aw\Validator\StringValidator();
        $validator->allowEmpty = true;
        $this->assertFalse($validator->validate(null));
        $this->assertTrue($validator->validate(''));
        $validator->allowEmpty = false;
        $this->assertFalse($validator->validate(null));
        $this->assertTrue($validator->validate(''));

        $validator->max = 5;
        $this->assertTrue($validator->validate('12345'));
        $this->assertTrue($validator->validate('123'));
        $this->assertTrue($validator->validate('1'));
        $this->assertTrue($validator->validate(''));
        $this->assertFalse($validator->validate('1237890'));

        $validator->min = 2;
        $this->assertTrue($validator->validate('12345'));
        $this->assertTrue($validator->validate('123'));
        $this->assertFalse($validator->validate('1'));
        $this->assertFalse($validator->validate(''));
        $this->assertFalse($validator->validate('1237890'));

        $validator->is = 3;
        $this->assertFalse($validator->validate('12345'));
        $this->assertTrue($validator->validate('123'));
        $this->assertFalse($validator->validate('1'));
        $this->assertFalse($validator->validate(''));
        $this->assertFalse($validator->validate('1237890'));
    }

    public function testUrl()
    {
        $validator = new \Aw\Validator\UrlValidator();
        $validator->allowEmpty = true;
        $this->assertFalse($validator->validate(null));
        $this->assertTrue($validator->validate(''));
        $validator->allowEmpty = false;
        $this->assertFalse($validator->validate(null));
        $this->assertFalse($validator->validate(''));
        $this->assertTrue($validator->validate('http://foo.com'));
        $this->assertTrue($validator->validate('http://www.foo.com'));
        $this->assertTrue($validator->validate('http://www.foo.bin'));
        $this->assertTrue($validator->validate('https://foo.com'));
        $this->assertFalse($validator->validate('foo.com'));
    }
}

