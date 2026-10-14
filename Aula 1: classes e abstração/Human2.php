<?php

class Human2 {

    public $name;
    public $age;
    public $height;
    
    public function darOi(): void {
        echo "Oi, meu nome é {$this->name} e tenho {$this->age} anos." . PHP_EOL;
    }

}

$joao = new Human2();

$joao->name = 'João';
$joao->age = -25;
$joao->height = 1.71;

$joao->darOi();