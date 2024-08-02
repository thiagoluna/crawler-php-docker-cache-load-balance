<?php

declare(strict_types=1);

namespace App\Docs\Api\V1;

/**
 * @codeCoverageIgnore
 */
class CurrencyController {
    /**
     * @OA\Post(
     *  path="/api/v1/currencies",
     *  summary="List Currencies Data",
     *  operationId="currency",
     *  tags={"Currencies"},
     *  description="List Currencies Data from Wikipedia",
     * @OA\RequestBody(
     *      description="You should send at least 1 field (code or code_list or number or number_lists) and no more than 1",
     *      required=false,
     * @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(property="code", type="string", example="GRP"),
     *              @OA\Property(
     *                   property="code_list",
     *                   type="array",
     *                   example={"GBP", "GEL", "HKD"},
     *                   @OA\Items(),
     *               ),
     *              @OA\Property(
     *                  property="number",
     *                  type="array",
     *                  example={826},
     *                  @OA\Items(),
     *              ),
     *              @OA\Property(
     *                 property="number_lists",
     *                 type="array",
     *                 example={826, 981},
     *                 @OA\Items(),
     *              ),
     *          )
     *      )
     *  ),
     * @OA\Response(
     *      response=200,
     *      description="Ok",
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  example=
     *                  {
     *                      {
     *                          "code": "GBP",
     *                          "number": "826",
     *                          "decimal": "2",
     *                          "currency": "Libra Esterlina",
     *                          "currency_locations": {
     *                              {
     *                                  "location: ": "Reino Unido",
     *                                  "flag: ": "https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_the_United_Kingdom.png"
     *                              },
     *                              {
     *                                  "location: ": "Ilha de Man",
     *                                  "flag: ": ""
     *                              },
     *                          },
     *                      },
     *                      {
     *                          "code": "GEL",
     *                          "number": "981",
     *                          "decimal": "2",
     *                          "currency": "Lari",
     *                          "currency_locations": {
     *                              {
     *                                  "location: ": "Georgia",
     *                                  "flag: ": "https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_Georgia.png"
     *                              },
     *                          },
     *                      },
     *                  },
     *                  @OA\Items(),
     *              ),
     *          ),
     *      ),
     * ),
     * @OA\Response(
     *   response=422,
     *   description="Validate Errors",
     *   @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *           @OA\Property(property="message", type="string", example="The number field must be an array."),
     *           @OA\Property(
     *              property="errors",
     *              type="array",
     *              @OA\Items(
     *                   @OA\Property(property="number", type="string", example="The number field must be an array."),
     *              ),
     *           ),
     *       ),
     *   ),
     * ),
     * )
     */
    public function getCurrenciesData() {

    }
}
