<?php

class CafeDoVictor
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
        echo "Filtrando café e colocando chocolate" . PHP_EOL;
    }
}