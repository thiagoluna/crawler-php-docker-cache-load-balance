<?php

namespace Tests\Unit\DTO;

use App\DTO\DataToFilterCurrencyDTO;
use App\Exceptions\ValidateDataToFilterException;
use Tests\TestCase;

class DataToFilterCurrencyDTOTest extends TestCase
{
    public function testInstantiateClassThrowException(): void
    {
        $this->expectException(ValidateDataToFilterException::class);
        $this->expectExceptionMessage("You should send only 1 field");

        new DataToFilterCurrencyDTO(
            currencyCode: "GBP",
            currenciesCodeArray: ["GBP", "GEL", "HKD"],
            currencyNumber: [242],
            currenciesNumberArray: [978, 171]
        );
    }

    public function testValidateDataCorrect(): void
    {
        $data = new DataToFilterCurrencyDTO(
            currencyCode: "GBP",
            currenciesCodeArray: null,
            currencyNumber: null,
            currenciesNumberArray: null
        );

        $this->assertTrue($data->validateData());
    }

    public function testToArray(): void
    {
        $dto = new DataToFilterCurrencyDTO(
            currencyCode: null,
            currenciesCodeArray: ["GBP", "GEL", "HKD"],
            currencyNumber: null,
            currenciesNumberArray: null
        );

        $this->assertIsArray($dto->toArray());
    }

    public function testToJson(): void
    {
        $dto = new DataToFilterCurrencyDTO(
            currencyCode: "USD",
            currenciesCodeArray: null,
            currencyNumber: null,
            currenciesNumberArray: null
        );

        $this->assertJsonStringEqualsJsonString(json_encode($dto->toArray()), $dto->toJson());
    }
}
