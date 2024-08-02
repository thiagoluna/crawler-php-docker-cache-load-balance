<?php

namespace Tests\Feature\Repositories;

use Tests\TestCase;
use App\Models\Currency;
use App\Repositories\CurrencyRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CurrencyRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->currencyRepository = app(CurrencyRepository::class);
    }

    public function testCreateCurrency(): void
    {
        $data = [
            'code' => "REAL",
            'number' => 171,
            'decimal' => 2,
            'currency_name' => "Real",
            'currency_locations' => "Patria Amada Brasil"
        ];

        $currency = $this->currencyRepository->create($data);

        $this->assertEquals($data['code'], $currency->code);
        $this->assertEquals($data['number'], $currency->number);
        $this->assertEquals($data['decimal'], $currency->decimal);
        $this->assertEquals($data['currency_name'], $currency->currency_name);
        $this->assertEquals($data['currency_locations'], $currency->currency_locations);
    }

    public function testGetWhereValuesInColumn()
    {
        Currency::factory()->create();

        $res = $this->currencyRepository->whereValuesInColumn([171], "number");

        $this->assertEquals("REAL", $res[0]["code"]);
        $this->assertEquals(171, $res[0]["number"]);
    }
}
