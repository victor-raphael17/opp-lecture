<?php

namespace Arena\Exceptions;

class InvalidInputException extends \Exception
{
    public function __construct(string $message = "Invalid input! Try again.")
    {
        parent::__construct($message);
    }
}
