<?php

namespace Arena\Exceptions;

class InsufficientEnergyException extends \Exception
{
    public function __construct(int $currentEnergy, int $requiredEnergy)
    {
        parent::__construct(
            "Insufficient energy! You have {$currentEnergy} energy, but need {$requiredEnergy}."
        );
    }
}
