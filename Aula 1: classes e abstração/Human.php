<?php

class Human {

    private $name;
    private $age;
    private $height;
    
    public function __construct(string $name, int $age, float $height) {
        
        if ($age < 0) {
            throw new InvalidArgumentException("A idade não pode ser negativa.");
        }
        
        $this->name = $name;
        $this->age = $age;
        $this->height = $height;
    }

    public function darOi(): void {
        echo "Oi, meu nome é {$this->name} e tenho {$this->age} anos." . PHP_EOL;
    }

}

$joao = new Human('João', 25, 1.71);

$joao->darOi();