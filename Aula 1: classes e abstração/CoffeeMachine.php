<?php

class CoffeeMachine
{
    public function makeCoffee(): string
    {
        $this->heatWater();
        $this->grindCoffee();
        $this->extractCoffee();

        return "Coffee is ready!";
    }

    private function heatWater(): void
    {
        echo "Heating water..." . PHP_EOL;
    }

    private function grindCoffee(): void
    {
        echo "Grinding coffee beans..." . PHP_EOL;
    }

    private function extractCoffee(): void
    {
        echo "Extracting coffee..." . PHP_EOL;
    }
}

$machine = new CoffeeMachine();

echo $machine->makeCoffee() . PHP_EOL;