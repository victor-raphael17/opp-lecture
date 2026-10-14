<?php

abstract class CoffeeMachine
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

    abstract protected function extractCoffee(): void;
}

class EspressoMachine extends CoffeeMachine
{
    protected function extractCoffee(): void
    {
        echo "Extracting espresso under high pressure..." . PHP_EOL;
    }
}

class FilterCoffeeMachine extends CoffeeMachine
{
    protected function extractCoffee(): void
    {
        echo "Filtering coffee through hot water..." . PHP_EOL;
    }
}

class CafeDoVictor extends CoffeeMachine
{
    protected function extractCoffee(): void
    {
        echo "Filtrando café e colocando chocolate" . PHP_EOL;
    }
}

$maquinaDeCafe1 = new EspressoMachine();
echo $maquinaDeCafe1->makeCoffee() . PHP_EOL;

echo PHP_EOL;

$maquinaDeCafe2 = new FilterCoffeeMachine();
echo $maquinaDeCafe2->makeCoffee() . PHP_EOL;

echo PHP_EOL;

$maquinaDeCafe3 = new CafeDoVictor();
echo $maquinaDeCafe3->makeCoffee() . PHP_EOL;