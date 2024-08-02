<?php

namespace App\DTO;

abstract class DTOAbstract
{
    abstract function toArray(): array;

    /**
     * @return false|string
     */
    public function toJson(): false|string
    {
        return json_encode($this->toArray());
    }
}
