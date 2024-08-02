<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function getAll();
    public function toArray();
    public function whereValuesInColumn(array $arrayData, string $databaseColumn): array;
    public function resolveModel();
}
