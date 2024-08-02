<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\CurrenciesDataRequest;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\NoReturn;
use Tests\TestCase;

class CurrenciesDataRequestTest extends TestCase
{
    public function testFormRequestIsAuthorized(): void
    {
        $request = new CurrenciesDataRequest();
        $this->assertTrue($request->authorize());

    }
    public function testCurrenciesDataRequestWithAllValidData(): void
    {
        $request = new CurrenciesDataRequest();

        $validator = Validator::make([
            "code" => "GBP",
            "code_list" => ["GBP", "GEL", "HKD"],
            "number_lists" => [242, 324],
            "number" => [978]
        ], $request->rules());

        $this->assertTrue($validator->passes());
    }

    public function testCurrenciesDataRequestWithAllInvalidData(): void
    {
        $request = new CurrenciesDataRequest();

        $validator = Validator::make([
            "code" => 123,
            "code_list" => 456,
            "number_lists" => "abc",
            "number" => ["abc"]
        ], $request->rules());

        $this->assertContains('code', $validator->errors()->keys());
        $this->assertContains('code_list', $validator->errors()->keys());
        $this->assertContains('number_lists', $validator->errors()->keys());
    }

    public function testCurrenciesDataRequestWithInvalidCode(): void
    {
        $request = new CurrenciesDataRequest();

        $validator = Validator::make([
            "code" => 123
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('code', $validator->errors()->keys());
    }

    public function testCurrenciesDataRequestWithInvalidCodeList(): void
    {
        $request = new CurrenciesDataRequest();

        $validator = Validator::make([
            "code_list" => [123]
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('code_list.0', $validator->errors()->keys());
    }

    public function testCurrenciesDataRequestWithInvalidNumber(): void
    {
        $request = new CurrenciesDataRequest();

        $validator = Validator::make([
            "number" => ["abc"]
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('number.0', $validator->errors()->keys());
    }

    public function testCurrenciesDataRequestWithInvalidNumberList(): void
    {
        $request = new CurrenciesDataRequest();

        $validator = Validator::make([
            "number_lists" => ["abc", "def"]
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('number_lists.0', $validator->errors()->keys());
        $this->assertContains('number_lists.1', $validator->errors()->keys());

        $validator = Validator::make([
            "number_lists" => ["abc", 456]
        ], $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('number_lists.0', $validator->errors()->keys());
    }
}
