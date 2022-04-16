<?php


namespace App\Exceptions;


use Exception;

class SilaTransactionCancellationException extends Exception
{
    public array $errors = [];

    public function __construct($message, $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
