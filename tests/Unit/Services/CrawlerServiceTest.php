<?php

namespace Tests\Unit\Services;

use DOMNode;
use DOMDocument;
use Tests\TestCase;
use App\Services\CrawlerService;
use Illuminate\Support\Facades\Http;
use App\Exceptions\ErrorToRunCrawlerException;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\ErrorFindDataByPathException;

class CrawlerServiceTest extends TestCase
{
    public function testRunCrawlerReturnsDomDocumentOnSuccess(): void
    {
        Http::fake([
            'http://logocomex.com.br' => Http::response(
                '<html><body>Desafio backend Logcomex</body></html>',
                Response::HTTP_OK)
        ]);

        $crawler = new CrawlerService();
        $dom = $crawler->runCrawler('http://logocomex.com.br');

        $this->assertInstanceOf(\DOMDocument::class, $dom);
        $this->assertStringContainsString('Desafio backend Logcomex', $dom->saveHTML());
    }

    public function testRunCrawlerThrowsExceptionOnHttpError(): void
    {
        $this->expectException(ErrorToRunCrawlerException::class);
        $this->expectExceptionMessage("Error to Crawler the url");

        Http::fake([
            'http://logocomex.com.br' => Http::response('Not Found', Response::HTTP_NOT_FOUND)
        ]);

        $crawler = new CrawlerService();
        $crawler->runCrawler('http://logocomex.com.br');
    }

    public function testFindDataByPathReturnsCorrectNode(): void
    {
        $html = '<html><body><div id="test">Desafio backend Logcomex</div></body></html>';
        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        $crawler = new CrawlerService();
        $node = $crawler->findDataByPath($dom, '//*[@id="test"]');

        $this->assertInstanceOf(DOMNode::class, $node);
        $this->assertEquals('Desafio backend Logcomex', $node->textContent);
    }

    public function testFindDataByPathReturnsNullForInvalidPath(): void
    {
        $html = '<html><body><div id="test">Desafio backend Logcomex</div></body></html>';
        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        $this->expectException(ErrorFindDataByPathException::class);
        $this->expectExceptionMessage("Error to find data with Path informed");

        $crawler = new CrawlerService();
        $crawler->findDataByPath($dom, '//*[@id="invalid"]');
    }

    public function testFindDataByPathReturnsCorrectNodeWithDifferentItemIndex(): void
    {
        $html = '<html><body><div class="test">First</div><div class="test">Second</div></body></html>';
        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        $crawler = new CrawlerService();
        $node = $crawler->findDataByPath($dom, '//*[@class="test"]', 1);

        $this->assertInstanceOf(DOMNode::class, $node);
        $this->assertEquals('Second', $node->textContent);
    }
}
