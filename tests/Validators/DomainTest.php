<?php namespace Mirelap\Validators;

use Illuminate\Http\Request;
use Mirelap\Http\Validators\Domain;
use PHPUnit_Framework_TestCase;

class DomainTest extends PHPUnit_Framework_TestCase
{
    public function testValidationSuccess()
    {
        $validator = new Domain('test.dev');
        $this->assertTrue($validator->validate(Request::create('http://test.dev:12345/a', 'GET')));
    }

    public function testValidationFailsWithInvalidDomain()
    {
        $validator = new Domain('test.dev');
        $this->assertFalse($validator->validate(Request::create('http://www.test.dev/a', 'GET')));
    }
}