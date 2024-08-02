<?php

namespace App\Repositories;

use AllowDynamicProperties;
use Illuminate\Foundation\Application;
use App\Exceptions\NoModelDefinedExceptions;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use App\Repositories\Contracts\BaseRepositoryInterface;

#[AllowDynamicProperties] class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @throws NoModelDefinedExceptions
     */
    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getAll()
    {
        return $this->model->get();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $arrayData
     * @param string $databaseColumn
     * @return array
     */
    public function whereValuesInColumn(array $arrayData, string $databaseColumn): array
    {
        return $this->model->whereIn($databaseColumn, $arrayData)->get()->toArray();
    }

    /**
     * @return $this
     */
    public function toArray(): array
    {
        return $this->model->toArray();
    }

    /**
     * @return Application|mixed
     * @throws NoModelDefinedExceptions
     */
    public function resolveModel()
    {
        if (!method_exists($this, 'model')) {
            throw new NoModelDefinedExceptions();
        }

        return app($this->model());
    }
}
