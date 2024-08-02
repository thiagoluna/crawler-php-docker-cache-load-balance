<?php

namespace App\DTO;

use AllowDynamicProperties;
use App\Exceptions\ValidateDataToFilterException;

#[AllowDynamicProperties] class DataToFilterCurrencyDTO extends DTOAbstract
{
    public function __construct(
        ?string $currencyCode,
        ?array $currenciesCodeArray,
        ?array $currencyNumber,
        ?array $currenciesNumberArray
    )
    {
        $this->currencyCode = $currencyCode;
        $this->currenciesCodeArray = $currenciesCodeArray;
        $this->currencyNumber = $currencyNumber;
        $this->currenciesNumberArray = $currenciesNumberArray;

        $this->validateData();
    }

    /**
     * @return bool
     * @throws ValidateDataToFilterException
     */
    public function validateData(): bool
    {
        $nullFields = array_filter($this->toArray(), function($value) {
            return is_null($value);
        });

        if (count($nullFields) != 3) {
            throw new ValidateDataToFilterException("You should send only 1 field");
        }

        return true;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'code' => $this->currencyCode,
            'codeList' => $this->currenciesCodeArray,
            'number' => $this->currencyNumber,
            'numberList' => $this->currenciesNumberArray
        ];
    }
}
