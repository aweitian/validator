<?php

use Aw\Validator\CompareValidator;

class UrlTest extends PHPUnit_Framework_TestCase
{
    public function testBoolean()
    {
        $validator = new \Aw\Validator\UrlValidator();
        $validator->strict = true;
        $validator->isDomain = false;
        $this->assertFalse($validator->validate("www.a.com"));
        $this->assertTrue($validator->validate('https://www.a.com'));

        $validator->isDomain = true;
        $this->assertFalse($validator->validate("https://www.a.com"));
        $this->assertTrue($validator->validate('www.a.com'));

        $validator->strict = false;
        $this->assertTrue($validator->validate("https://www.a.com"));
        $this->assertTrue($validator->validate('www.a.com'));
    }
}

