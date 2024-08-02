<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CrawlerService;
use App\Services\CurrencyService;
use App\Repositories\Contracts\CurrencyRepositoryInterface;

class CurrencyServiceTest extends TestCase
{
    protected $crawlerServiceMock;
    protected $currencyService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->crawlerServiceMock = \Mockery::mock(CrawlerService::class);
        $this->currencyRepositoryInterfaceeMock = \Mockery::mock(CurrencyRepositoryInterface::class);
        $this->currencyService = new CurrencyService($this->crawlerServiceMock, $this->currencyRepositoryInterfaceeMock);
    }

    public function testFilterResultCurrencyCodeReturnTrue(): void
    {
        $rowData = [ "USD" ];
        $dataToFilter = [ "code" => [ "USD" ] ];

        $result = $this->callPrivateMethod($this->currencyService, "filterResult", [$rowData, $dataToFilter]);
        $this->assertTrue($result);
    }

    public function testFilterResultCurrenciesCodeReturnTrue(): void
    {
        $rowData = ["USD"];
        $dataToFilter = [ "codeList" => [ "USD" ] ];

        $result = $this->callPrivateMethod($this->currencyService, "filterResult", [$rowData, $dataToFilter]);
        $this->assertTrue($result);
    }

    public function testFilterResultCurrencyNumberReturnTrue(): void
    {
        $rowData = [ "USD", 171 ];
        $dataToFilter = [
            "number" => [ 171 ]
        ];

        $result = $this->callPrivateMethod($this->currencyService, "filterResult", [$rowData, $dataToFilter]);
        $this->assertTrue($result);
    }

    public function testFilterResultCurrenciesNumberReturnTrue(): void
    {
        $rowData = [ "USD", 172 ];
        $dataToFilter = [
            "numberList" => [ 171, 172 ]
        ];

        $result = $this->callPrivateMethod($this->currencyService, "filterResult", [$rowData, $dataToFilter]);
        $this->assertTrue($result);
    }

    public function testFilterResultReturnFalseWhenNoCurrencyToShow(): void
    {
        $rowData = [ "USD", 172 ];
        $dataToFilter = [
            "numberList" => []
        ];

        $result = $this->callPrivateMethod($this->currencyService, "filterResult", [$rowData, $dataToFilter]);
        $this->assertFalse($result);
    }

    public function testExtractDataFromColumns()
    {
        $dom = new \DOMDocument();
        $row = $dom->createElement('tr');

        for ($i = 0; $i < 6; $i++) {
            $col = $dom->createElement('td', "data$i");
            $row->appendChild($col);
        }

        $columnsData = $row->getElementsByTagName('td');
        $result = $this->callPrivateMethod($this->currencyService, 'extractDataFromColumns', [$columnsData]);
        $this->assertIsArray($result);
    }

    public function testExtractCurrencyLocation()
    {
        $dom = new \DOMDocument();
        $col = $dom->createElement('td', "location1,location2");

        $img1 = $dom->createElement('img');
        $img1->setAttribute('src', '/path/to/flag1.png');
        $col->appendChild($img1);

        $img2 = $dom->createElement('img');
        $img2->setAttribute('src', '/path/to/flag2.png');
        $col->appendChild($img2);

        $result = $this->callPrivateMethod($this->currencyService, 'extractCurrencyLocation', [$col]);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('location1', $result[0]['location:']);
        $this->assertEquals('https:/path/to/flag1.png', $result[0]['flag:']);
        $this->assertEquals('location2', $result[1]['location:']);
        $this->assertEquals('https:/path/to/flag2.png', $result[1]['flag:']);
    }
}
