<?php

namespace App\Services;

use App\Exceptions\ErrorFindDataByPathException;
use App\Exceptions\ErrorToRunCrawlerException;
use DOMDocument;
use DOMNode;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class CrawlerService
{
    /**
     * @param string $url
     * @return DOMDocument
     * @throws ErrorToRunCrawlerException
     * '@'$dom to suppress HTML parsing warnings
     */
    public function runCrawler(string $url): DOMDocument
    {
        try {
            $response = Http::acceptJson()->get($url);
            if ($response->status() != Response::HTTP_OK)
                throw new ErrorToRunCrawlerException("Error to Crawler the url", Response::HTTP_BAD_REQUEST);

            $dom = new DOMDocument();
            @$dom->loadHTML($response);

            return $dom;
        } catch (\Throwable $throwable) {
            throw new ErrorToRunCrawlerException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param DOMDocument $dom
     * @param string $path
     * @param int $item
     * @return DOMNode|null
     * @throws ErrorFindDataByPathException
     */
    public function findDataByPath(DOMDocument $dom, string $path, int $item = 0): ?DOMNode
    {
        $xpath = new \DOMXPath($dom);
        $result = $xpath->query($path)->item($item);

        if (is_null($result))
            throw new ErrorFindDataByPathException("Error to find data with Path informed", Response::HTTP_NOT_FOUND);

        return $result;
    }
}
