<?php namespace Mirelap\Http\Parsers;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Accept implements ParserInterface
{
    /** @var string */
    protected $standardsTree;

    /** @var string */
    protected $subtype;

    /** @var string */
    protected $version;

    /** @var string */
    protected $format;

    public function __construct(string $standardsTree, string $subtype, string $version, string $format)
    {
        $this->standardsTree = $standardsTree;
        $this->subtype = $subtype;
        $this->version = $version;
        $this->format = $format;
    }

    public function parse(Request $request) : array
    {
        $expected = 'application/'.$this->standardsTree.'.'.$this->subtype.'.'.$this->version.'+'.$this->format;

        if ($request->header('accept') !== $expected) {
            throw new BadRequestHttpException('Accept header could not be properly parsed. Expected ' . $expected);
        }

        return [
            'subtype' => $this->subtype,
            'version' => $this->version,
            'format' => $this->format
        ];
    }
}