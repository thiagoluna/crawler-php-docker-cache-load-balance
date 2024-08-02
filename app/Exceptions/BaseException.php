<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BaseException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = "",
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'code' => $this->getCode(),
        ];
    }
}
