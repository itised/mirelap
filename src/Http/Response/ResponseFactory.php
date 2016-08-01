<?php namespace Mirelap\Http\Response;

use Illuminate\Pagination\LengthAwarePaginator;
use Mirelap\Http\Transformers\TransformerFactory;

class ResponseFactory
{
    /** @var TransformerFactory */
    protected $transformerFactory;

    /**
     * ResponseFactory constructor.
     *
     * @param TransformerFactory $transformerFactory
     */
    public function __construct(TransformerFactory $transformerFactory)
    {
        $this->transformerFactory = $transformerFactory;
    }

    public function created(string $location, $content = null, string $transformerClass = null, array $headers = []) : Created
    {
        $content = $this->getTransformedItem($content, $transformerClass);
        return new Created($location, $content, $headers);
    }

    public function updated($content = null, string $transformerClass = null, array $headers = []) : Updated
    {
        $content = $this->getTransformedItem($content, $transformerClass);
        return new Updated($content, $headers);
    }

    public function deleted($content = null, string $transformerClass = null, array $headers = []) : Deleted
    {
        $content = $this->getTransformedItem($content, $transformerClass);
        return new Deleted($content, $headers);
    }

    public function noContent(array $headers = []) : NoContent
    {
        return new NoContent($headers);
    }

    public function item($item, string $transformerClass, array $headers = []) : Response
    {
        $content = $this->getTransformedItem($item, $transformerClass);
        return new Response($content, Response::HTTP_OK, $headers);
    }

    public function collection($collection, string $transformerClass, array $headers = []) : Response
    {
        $transformer = $this->transformerFactory->make($transformerClass);

        $content = [];
        foreach ($collection as $item) {
            $content[] = $transformer->deepTransform($item);
        }

        if ($collection instanceof LengthAwarePaginator) {
            $links = $this->getHeaderPagingLinks($collection);

            $headers['X-Total-Count'] = $collection->total();

            if (!empty($links)) {
                $headers['Link'] = $links;
            }
        }

        return new Response($content, Response::HTTP_OK, $headers);
    }

    protected function getTransformedItem($content = null, string $transformerClass = null)
    {
        if ($content !== null && $transformerClass !== null) {
            return $this->transformerFactory->make($transformerClass)->deepTransform($content);
        }

        return $content;
    }

    protected function getHeaderPagingLinks(LengthAwarePaginator $paginator)
    {
        $links = [];

        if ($paginator->hasMorePages()) {
            $links[] = $this->getHeaderPagingLink($paginator->nextPageUrl(), 'next');
            $links[] = $this->getHeaderPagingLink($paginator->url($paginator->lastPage()), 'last');
        }

        if ($paginator->currentPage() > 1) {
            $links[] = $this->getHeaderPagingLink($paginator->previousPageUrl(), 'prev');
            $links[] = $this->getHeaderPagingLink($paginator->url(1), 'first');
        }

        return implode(',', $links);
    }

    protected function getHeaderPagingLink($uri, $rel)
    {
        return sprintf('<%s>; rel="%s"', $uri, $rel);
    }
}