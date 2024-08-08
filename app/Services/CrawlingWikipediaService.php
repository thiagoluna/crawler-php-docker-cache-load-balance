<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use DOMDocument;
use DOMNode;
class CrawlingWikipediaService
{
    public function getDataInCrawler(float $searchValue)
    {
        $url ="https://pt.wikipedia.org/wiki/Lista_das_maiores_empresas_do_Brasil";
        $response = Http::acceptJson()->get($url);
        $dom = new DOMDocument();
        @$dom->loadHTML($response);

        $path = './/table[@class="wikitable sortable"]';

        $xpath = new \DOMXPath($dom);
        $table = $xpath->query($path)->item(0);

        $rows = $table->getElementsByTagName(qualifiedName: 'tr');

        $lucro = [];
        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            /** @var DOMDocument $value */
            $value = $row->getElementsByTagName('td')->item(4);
            $res = $value->nodeValue;
            $lucro = str_replace(",", ".", $res);
            if(str_contains($lucro, 'milhões')) {
                $newValue = str_replace("milhões", "", $lucro);
                $lucro = (int) $newValue / 1000;
            }

            if ($lucro > $searchValue) {
                $newLucroArray[] = [
                    "company_name" => $row->getElementsByTagName('td')->item(2)->nodeValue,
                    "profit" => (int) $lucro,
                    "rank" => $row->getElementsByTagName('td')->item(0)->nodeValue,
                ];

            }
        }

        return $newLucroArray;
    }
}
