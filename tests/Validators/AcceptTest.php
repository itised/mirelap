<?php namespace Mirelap\Validators;

use Illuminate\Http\Request;
use Mirelap\Http\Parsers\Accept as Parser;
use Mirelap\Http\Validators\Accept;
use PHPUnit_Framework_TestCase;

class AcceptTest extends PHPUnit_Framework_TestCase
{
    public function testValidationUsesParser()
    {
        $parser = \Mockery::mock(Parser::class);
        $parser->shouldReceive('parse');

        $validator = new Accept($parser);
        $valid = $validator->validate(Request::create('http://www.test.dev/a', 'GET'));

        $this->assertTrue($valid);
    }

    public function testOptionsRequestReturnTrue()
    {
        $parser = \Mockery::mock(Parser::class);
        $validator = new Accept($parser);

        $valid = $validator->validate(Request::create('http://www.test.dev/a', 'OPTIONS'));

        $this->assertTrue($valid);
    }
}