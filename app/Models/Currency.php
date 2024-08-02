<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    private string $code;
    private int $number;
    private int $decimal;
    private string $currency_name;
    private string $currency_locations;

    protected $guarded = [];

    protected $hidden = [ "id", 'created_at', 'updated_at' ];
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'currency_locations' => 'array'
    ];

    private int $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getDecimal(): int
    {
        return $this->decimal;
    }

    /**
     * @param int $decimal
     */
    public function setDecimal(int $decimal): void
    {
        $this->decimal = $decimal;
    }

    /**
     * @return string
     */
    public function getCurrencyName(): string
    {
        return $this->currency_name;
    }

    /**
     * @param string $currency_name
     */
    public function setCurrencyName(string $currency_name): void
    {
        $this->currency_name = $currency_name;
    }

    /**
     * @return array
     */
    public function getCurrencyLocations(): array
    {
        return json_decode($this->currency_locations, true);
    }

    /**
     * @param string $currency_locations
     */
    public function setCurrencyLocations(string $currency_locations): void
    {
        $this->currency_locations = $currency_locations;
    }

//    public function toArray(): array
//    {
//        return [
//            "code" => $this->code,
//            "number" => $this->number,
//            "decimal" => $this->decimal,
//            "currency_name" => $this->currency_name,
//            "currency_locations" => $this->currency_locations
//        ];
//    }

//    public static function createFromArray(array $data): array
//    {
//        $code = $data['code'];
//        $number = $data['number'];
//        $decimal = $data['decimal'];
//        $currency_name = $data['currency_name'];
//        $currency_locations = ['currency_locations'];
//
//        return $currency;
//    }
}
