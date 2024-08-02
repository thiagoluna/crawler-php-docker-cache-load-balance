<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('International pattern code');
            $table->integer('number')->unique()->comment('The number of the currencies');
            $table->integer('decimal')->comment('Defines how many decimal places the currency has');
            $table->string('currency_name')->unique()->comment('Currency name');
            $table->json('currency_locations')->comment('Locations where currency circulates');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
