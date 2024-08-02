<?php

namespace Tests\Unit\Http\Controllers;

use Mockery;
use Tests\TestCase;
use Illuminate\Http\Response;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use App\Jobs\WriteThrowableLogsJob;
use Illuminate\Support\Facades\Bus;
use App\Http\Requests\CurrenciesDataRequest;
use App\Http\Controllers\Api\CurrencyController;
use App\Exceptions\ValidateDataToFilterException;


class CurrencyControllerTest extends TestCase
{
    public function testGetCurrenciesDataReturnsNotFoundWhenResultIsEmpty()
    {
        $currencyServiceMock = Mockery::mock(CurrencyService::class);
        $currencyServiceMock->shouldReceive('getCurrenciesData')
            ->andReturn([]);

        $requestMock = Mockery::mock(CurrenciesDataRequest::class);
        $requestMock->shouldReceive('get')->with('code')->andReturn('USD');
        $requestMock->shouldReceive('get')->with('code_list')->andReturn(null);
        $requestMock->shouldReceive('get')->with('number')->andReturn(null);
        $requestMock->shouldReceive('get')->with('number_lists')->andReturn(null);

        $controller = new CurrencyController($currencyServiceMock);

        $response = $controller->getCurrenciesData($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals(['message' => 'Not found'], $response->getData(true));
    }

    public function testGetCurrenciesDataReturnsResultWhenNotEmpty()
    {
        $expectedResult = ['currency' => 'USD', 'rate' => 1.0];

        $currencyServiceMock = Mockery::mock(CurrencyService::class);
        $currencyServiceMock->shouldReceive('getCurrenciesData')
            ->andReturn($expectedResult);

        $requestMock = Mockery::mock(CurrenciesDataRequest::class);
        $requestMock->shouldReceive('get')->with('code')->andReturn('USD');
        $requestMock->shouldReceive('get')->with('code_list')->andReturn(null);
        $requestMock->shouldReceive('get')->with('number')->andReturn(null);
        $requestMock->shouldReceive('get')->with('number_lists')->andReturn(null);

        $controller = new CurrencyController($currencyServiceMock);

        $response = $controller->getCurrenciesData($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResult, $response->getData(true)["data"]);
    }

    public function testGetCurrenciesDataDispatchesJobOnException()
    {
        Bus::fake();

        $currencyServiceMock = Mockery::mock(CurrencyService::class);
        $currencyServiceMock->shouldReceive('getCurrenciesData')
            ->andThrow(new ValidateDataToFilterException('Test Exception'));

        $requestMock = Mockery::mock(CurrenciesDataRequest::class);
        $requestMock->shouldReceive('get')->with('code')->andReturn('USD');
        $requestMock->shouldReceive('get')->with('code_list')->andReturn(null);
        $requestMock->shouldReceive('get')->with('number')->andReturn(null);
        $requestMock->shouldReceive('get')->with('number_lists')->andReturn(null);

        $controller = new CurrencyController($currencyServiceMock);

        $response = $controller->getCurrenciesData($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertEquals(['message' => 'Test Exception'], $response->getData(true));

        Bus::assertDispatched(WriteThrowableLogsJob::class, function ($job) {
            return $job->throwable['message'] === 'Test Exception';
        });
    }
}
