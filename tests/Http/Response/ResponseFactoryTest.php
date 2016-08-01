<?php namespace Mirelap\Http\Response;

use Mirelap\Http\Transformers\TransformerFactory;
use Mockery;
use PHPUnit_Framework_TestCase;

class ResponseFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreatedResponse()
    {
        $location = '/tests/1';

        $transformerFactory = Mockery::mock(TransformerFactory::class);
        $responseFactory = new ResponseFactory($transformerFactory);

        $response = $responseFactory->created($location);

        $this->assertEquals(201, $response->status());
        $this->assertEquals($location, $response->headers->get('Location'));
    }
}