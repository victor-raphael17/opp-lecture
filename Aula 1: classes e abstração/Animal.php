<?php

class Animal {
    private $name;
    private $species;
    private $sound;

    public function __construct($name, $species, $sound = "Generic sound") {
        
        $this->name = $name;
        $this->species = $species;
        $this->sound = $sound;
    }

    public function makeSound() {
        return $this->sound;
    }

    public function getName() {
        return $this->name;
    }

    public static function describeAnimal() {
        return "Animals are multicellular, eukaryotic organisms in the kingdom Animalia.";
    }
}

$leo = new Animal("Leo", "Lion", "Roar!");
echo $leo->makeSound() . PHP_EOL;

$titan = new Animal("Titan", "Dog", "Woof Woof!");
echo $titan->makeSound() . PHP_EOL;

echo Animal::describeAnimal() . PHP_EOL;