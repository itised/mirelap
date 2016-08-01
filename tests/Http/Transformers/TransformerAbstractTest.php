<?php namespace Mirelap\Http\Transformers;

use Illuminate\Http\Request;
use Mockery;
use PHPUnit_Framework_TestCase;

class TransformerAbstractTest extends PHPUnit_Framework_TestCase
{
    public function testDeepTransformWithIncludes()
    {
        $transformer = Mockery::mock(sprintf('%s[transform,getAvailableIncludes,getDefaultIncludes]', TransformerAbstract::class));
        $transformer->shouldReceive('getDefaultIncludes')->andReturn(['default1', 'default2']);
        $transformer->shouldReceive('getAvailableIncludes')->andReturn(['relation1', 'relation2', 'relation3', 'relation4']);
        $transformer->shouldReceive('transform')->andReturn(['field1' => 'value 1', 'field2' => 'value 2']);
        $transformer->shouldReceive('includeDefault2')->andReturn(['default2field' => 'default 2 value']);
        $transformer->shouldReceive('includeRelation1')->andReturn(['relation1field' => 'relation 1 value']);
        $transformer->shouldReceive('includeRelation2')->andReturn(['relation2field' => 'relation 2 value']);
        $transformer->shouldReceive('includeRelation3')->andReturn(['relation3field' => 'relation 3 value']);

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('get')->with('include')->andReturn('relation1,relation2,relation3');
        $request->shouldReceive('get')->with('exclude')->andReturn('default1');

        $transformer->setRequest($request);

        $transformed = $transformer->deepTransform(['field' => 'value']);

        $this->assertEquals([
            'field1' => 'value 1',
            'field2' => 'value 2',
            'default2' => ['default2field' => 'default 2 value'],
            'relation1' => ['relation1field' => 'relation 1 value'],
            'relation2' => ['relation2field' => 'relation 2 value'],
            'relation3' => ['relation3field' => 'relation 3 value']
        ], $transformed);
    }
}