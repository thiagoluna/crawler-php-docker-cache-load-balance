<?php

namespace App\Services;

use DOMNode;
use DOMNodeList;
use App\Enums\LanguageEnum;
use App\DTO\DataToFilterCurrencyDTO;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\ErrorToRunCrawlerException;
use App\Exceptions\ErrorFindDataByPathException;
use App\Repositories\Contracts\CurrencyRepositoryInterface;

class CurrencyService
{
    public function __construct(
        private readonly CrawlerService $crawlerService,
        private readonly CurrencyRepositoryInterface $currencyRepository,
    ){
    }

    /**
     * @param DataToFilterCurrencyDTO $dataToFilterDTO
     * @return array
     * @throws ErrorFindDataByPathException
     * @throws ErrorToRunCrawlerException
     */
    public function getCurrenciesData(DataToFilterCurrencyDTO $dataToFilterDTO): array
    {
        // Search in Cache
        $cacheKey = sha1($dataToFilterDTO->toJson());
        if (!empty(Cache::has($cacheKey))) return Cache::get($cacheKey);

        // Search in Database
        $dataFromDB = $this->getCurrenciesDataFromDatabase(dataToFilterDTO: $dataToFilterDTO);
        if(empty($dataFromDB["currenciesNotFounded"]) && !empty($dataFromDB["currenciesFounded"])) {
            return Cache::remember($cacheKey, 5, fn () => $dataFromDB["currenciesFounded"]);
        }

        // Search in Web
        $parcialResult = [];
        if (!empty($dataFromDB["currenciesFounded"])) $parcialResult = $dataFromDB["currenciesFounded"];

        $finalResult = $this->getCurrenciesDataFromCrawler(
            currenciesNotFoundedInDB: $dataFromDB["currenciesNotFounded"],
            parcialResult: $parcialResult
        );

        return Cache::remember($cacheKey, 5, fn () => $finalResult);
    }

    /**
     * @param DataToFilterCurrencyDTO $dataToFilterDTO
     * @return array
     */
    public function getCurrenciesDataFromDatabase(DataToFilterCurrencyDTO $dataToFilterDTO): array
    {
        $validValue = array_filter($dataToFilterDTO->toArray(), function($value) {
            return !is_null($value);
        });
        $validValueKey = array_key_first($validValue);

        $databaseColumn = $this->findDatabaseColumn(validValue: $validValue);
        $valuesToSearch = is_array($validValue[$validValueKey]) ? $validValue[$validValueKey] : [$validValue[$validValueKey]];
        $currenciesFounded = $this->currencyRepository->whereValuesInColumn(arrayData: $valuesToSearch, databaseColumn: $databaseColumn);

        $currenciesNotFounded = $this->currenciesNotFoundInDatabase(
            validValue: $validValue,
            validValueKey: $validValueKey,
            currencies: $valuesToSearch,
            currenciesFoundedInDatabase: $currenciesFounded);

        return [
            "currenciesFounded" => $currenciesFounded,
            "currenciesNotFounded" => $currenciesNotFounded
        ];
    }

    /**
     * @param array $validValue
     * @param string $validValueKey
     * @param array $currencies
     * @param array $currenciesFoundedInDatabase
     * @return array
     */
    public function currenciesNotFoundInDatabase(
        array $validValue,
        string $validValueKey,
        array $currencies,
        array $currenciesFoundedInDatabase
    ): array {
        $databaseColumn =$this->findDatabaseColumn($validValue);

        $numbersInArray = array_column($currenciesFoundedInDatabase, $databaseColumn);
        $valuesNotInArray = array_values(array_diff($currencies, $numbersInArray));
        if (empty($valuesNotInArray)) return [];

        return [ $validValueKey => $valuesNotInArray ];
    }

    /**
     * @param array $currenciesNotFoundedInDB
     * @param array $parcialResult
     * @return array
     * @throws ErrorFindDataByPathException
     * @throws ErrorToRunCrawlerException
     */
    public function getCurrenciesDataFromCrawler(array $currenciesNotFoundedInDB, array $parcialResult): array
    {
        $url = sprintf("https://%s.wikipedia.org/wiki/ISO_4217", LanguageEnum::BRAZILIAN->value);
        $path = './/table[@class="wikitable sortable"]';
        $dom = $this->crawlerService->runCrawler(url: $url);
        $table = $this->crawlerService->findDataByPath(dom: $dom, path: $path);
        $rows = $table->getElementsByTagName(qualifiedName: 'tr');

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $columnsData = $row->getElementsByTagName('td');
            $rowData = $this->extractDataFromColumns(columnsData: $columnsData);

            $result = [
                'code' => $rowData[0],
                'number' => (int) $rowData[1],
                'decimal' => (int) $rowData[2],
                'currency_name' => $rowData[3],
                'currency_locations' => $rowData[5]
            ];

            if ($this->filterResult(rowData:  $rowData, dataToFilter:  $currenciesNotFoundedInDB)) {
                $this->currencyRepository->create($result);
                $parcialResult[] = $result;
            }
        }

        return $parcialResult;
    }

    /**
     * @param array $rowData
     * @param array $dataToFilter
     * @return bool
     */
    private function filterResult(array $rowData, array $dataToFilter): bool
    {
        if ( isset($dataToFilter["code"]) && in_array($rowData[0], $dataToFilter["code"]) )
        {
            return true;
        }

        if ( isset($dataToFilter["codeList"]) && in_array($rowData[0], $dataToFilter["codeList"]) )
        {
            return true;
        }

        if ( isset($dataToFilter["number"][0]) && $rowData[1] == $dataToFilter["number"][0] ) {
            return true;
        }

        if ( isset($dataToFilter["numberList"]) && in_array($rowData[1], $dataToFilter["numberList"]) ) {
            return true;
        }

        return false;
    }

    /**
     * @param DOMNodeList $columnsData
     * @return array
     */
    private function extractDataFromColumns(DOMNodeList $columnsData): array
    {
        $rowData = [];

        /** @var DOMNode $col */
        foreach ($columnsData as $key => $col) {
            $rowData[] = trim($col->textContent);
            if ($key === 4) $currencyLocation = $this->extractCurrencyLocation(col: $col);

            if (!empty($currencyLocation)) $rowData[] = $currencyLocation;
        }

        return $rowData;
    }

    /**
     * @param DOMNode $col
     * @return array
     */
    private function extractCurrencyLocation(DOMNode $col): array
    {
        $urlFlagImage = [];
        $currencyLocation = [];

        $locations = trim($col->textContent);
        $locationsArray = explode(",", $locations);

        $img = $col->getElementsByTagName('img');
        foreach ($img as $imgRow) {
            $urlFlagImage[] = $imgRow->getAttribute('src');
        }

        foreach ($locationsArray as $locationKey => $location) {
            $currencyLocation[] = [
                "flag:" => !empty($urlFlagImage[$locationKey]) ? sprintf("https:%s", trim($urlFlagImage[$locationKey])) : "",
                "location:" => trim($location),
            ];
        }

        return $currencyLocation;
    }

    private function findDatabaseColumn(array $validValue): string
    {
        return match (array_key_first($validValue) === "code" || array_key_first($validValue) === "codeList"){
            true => "code",
            false => "number"
        };
    }
}
