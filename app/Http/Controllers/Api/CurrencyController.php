<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use App\Jobs\WriteThrowableLogsJob;
use App\DTO\DataToFilterCurrencyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrenciesDataRequest;
use Symfony\Component\HttpFoundation\Response;

class CurrencyController extends Controller
{
    public function __construct(private readonly CurrencyService $currencyService)
    {
    }

    /**
     * @param CurrenciesDataRequest $request
     * @return JsonResponse
     */
    public function getCurrenciesData(CurrenciesDataRequest $request): JsonResponse
    {
        try {
            $dataToFilter = new DataToFilterCurrencyDTO(
                $request->get("code"),
                $request->get("code_list"),
                $request->get("number"),
                $request->get("number_lists")
            );
            $result = $this->currencyService->getCurrenciesData(dataToFilterDTO: $dataToFilter);

            return match (empty($result)) {
                true => response()->json(["message" => "Not found"], Response::HTTP_NOT_FOUND),
                false => response()->json([ "data" => $result], Response::HTTP_OK)
            };
        } catch (Throwable $throwable) {
            WriteThrowableLogsJob::dispatch($throwable->toArray(), true);

            return response()->json(
                [ "message" => $throwable->getMessage() ],
                $throwable->getCode() ?? Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
