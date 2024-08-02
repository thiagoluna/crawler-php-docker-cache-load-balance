<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currency_locations = json_encode([
            [
                "location" => "Brasil",
                "flag" => "https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Flag_of_Brazil.svg/22px-Flag_of_Brazil.svg.png"
            ]
        ]);
        DB::table('currencies')->insert([
            'code' => 'BRL',
            'number' => 986,
            'decimal' => 2,
            'currency_name' => 'Real',
            'currency_locations' => $currency_locations
        ]);

        $currency_locations = json_encode([
            [
                "flag" => "https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Flag_of_the_United_Arab_Emirates.svg/22px-Flag_of_the_United_Arab_Emirates.svg.png",
                "location" => "Emirados Árabes Unidos"
            ]
        ]);
        DB::table('currencies')->insert([
            'code' => 'AED',
            'number' => 784,
            'decimal' => 2,
            'currency_name' => 'Dirham dos Emirados',
            'currency_locations' => $currency_locations
        ]);
    }
}
