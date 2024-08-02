<?php

namespace App\Providers;

use App\Repositories\Contracts\CurrencyRepositoryInterface;
use App\Repositories\CurrencyRepository;
use Carbon\Laravel\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
          CurrencyRepositoryInterface::class,
          CurrencyRepository::class
        );
    }
}
