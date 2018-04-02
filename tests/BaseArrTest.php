<?php


class BaseArrTest extends PHPUnit_Framework_TestCase
{
    public function testUrl()
    {
        $validator = new \Aw\Validator\UrlValidator();
        $validator->allowEmpty = true;
        $validator->isArray = true;
        $this->assertFalse($validator->validate(array(null, null)));
        $this->assertTrue($validator->validate(array('', '')));
        $validator->allowEmpty = false;
        $this->assertFalse($validator->validate(array(null, null)));
        $this->assertFalse($validator->validate(array('', '')));
        $this->assertTrue($validator->validate(array('http://foo.com','https://www.baidu.com')));
        $this->assertFalse($validator->validate('http://www.foo.com'));
        $this->assertFalse($validator->validate('http://www.foo.bin'));
        $this->assertFalse($validator->validate('https://foo.com'));
        $this->assertFalse($validator->validate('foo.com'));
    }
}

