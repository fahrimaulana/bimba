<?php

namespace App\Exceptions;

class RestApiValidationErrorException extends \Exception
{
    private $errors;

    public function __construct($validationErrors, $message = 'Validation Error', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $validationErrors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}