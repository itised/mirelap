<?php namespace Mirelap\Http\Parsers;

use Illuminate\Http\Request;
use PHPUnit_Framework_TestCase;

class AcceptTest extends PHPUnit_Framework_TestCase
{
    protected function createRequest($uri, $method, array $headers = [])
    {
        $request = Request::create($uri, $method);

        foreach ($headers as $key => $value) {
            $request->headers->set($key, $value);
        }

        return $request;
    }

    public function testParsingValidAcceptHeaderSuccess()
    {
        $standardsTree = 'vnd';
        $subtype = 'api';
        $version = 'v1';
        $format = 'json';

        $parser = new Accept($standardsTree, $subtype, $version, $format);
        $header = 'application/'.$standardsTree.'.'.$subtype.'.'.$version.'+'.$format;

        $accept = $parser->parse($this->createRequest('/', 'GET', ['accept' => $header]));

        $this->assertEquals($subtype, $accept['subtype']);
        $this->assertEquals($version, $accept['version']);
        $this->assertEquals($format, $accept['format']);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedMessage Accept header could not be properly parsed. Expected application/vnd.api.v1+json
     */
    public function testParsingInvalidAcceptHeaderThrowException()
    {
        $standardsTree = 'vnd';
        $subtype = 'api';
        $version = 'v1';
        $format = 'json';

        $parser = new Accept($standardsTree, $subtype, $version, $format);
        $header = 'application/'.$standardsTree.'.'.$subtype.'.'.$version.'+xml';

        $parser->parse($this->createRequest('/', 'GET', ['accept' => $header]));
    }
}