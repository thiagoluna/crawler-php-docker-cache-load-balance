<?php

namespace App\Http\Controllers\Api;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Services\CrawlerService;
use App\Services\CrawlingWikipediaService;
use Illuminate\Http\Request;

#[AllowDynamicProperties] class CrawlerController extends Controller
{
    public function __construct(CrawlingWikipediaService $crawlingWikipediaService)
    {
        $this->crawlingWikipediaService = $crawlingWikipediaService;
    }

    public function getCrawler()
    {
        return $this->crawlingWikipediaService->getDataInCrawler(15);
    }
}
