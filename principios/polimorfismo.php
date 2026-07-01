<?php

# Sem polimorfismo

// As formas são arrays com um campo "tipo". Um if/elseif decide a área.

$formas = [
    ['tipo' => 'retangulo', 'base' => 2, 'altura' => 3],
    ['tipo' => 'circulo',   'raio' => 5],
];

function area(array $forma): float
{
    if ($forma['tipo'] === 'retangulo') {
        return $forma['base'] * $forma['altura'];
    } elseif ($forma['tipo'] === 'circulo') {
        return 3.14159 * $forma['raio'] ** 2;
    }
    
    throw new InvalidArgumentException("Forma desconhecida");
}

foreach ($formas as $forma) {
    echo area($forma) . PHP_EOL;
}
// Problema: cada novo tipo obriga a abrir area() e adicionar mais um elseif.
// O código que calcula precisa conhecer todos os tipos existentes.

# Com polimorfismo

// Cada forma é uma classe que implementa area() à sua maneira.
// O código cliente chama area() sem saber qual forma concreta está usando.

interface Forma
{
    public function area(): float;
}

class Retangulo implements Forma
{
    public function __construct(private float $base, private float $altura)
    {
        $this->base = $base;
        $this->altura = $altura;
    }

    public function area(): float
    {
        return $this->base * $this->altura;
    }
}

class Circulo implements Forma
{
    public function __construct(private float $raio)
    {
    }

    public function area(): float
    {
        return 3.14159 * $this->raio ** 2;
    }
}

$formas = [new Retangulo(2, 3), new Circulo(5)];
foreach ($formas as $forma) {
    echo $forma->area() . PHP_EOL; // mesma chamada, cada classe responde do seu jeito
}

# Adicionando um novo tipo

// Basta criar a classe e fazer implements Forma. O laço acima não muda.
// Isso é o princípio aberto/fechado: aberto para extensão, fechado para modificação.

class Triangulo implements Forma
{
    public function __construct(private float $base, private float $altura)
    {
    }

    public function area(): float
    {
        return ($this->base * $this->altura) / 2;
    }
}

$formas[] = new Triangulo(4, 6);

foreach ($formas as $forma) {
    echo $forma->area() . PHP_EOL; // a classe Triangulo entra sem tocar no código que calcula
}
