<?php

# Sem herança

// Dois tipos diferentes repetem o mesmo construtor e o mesmo dormir().

class Pato
{
    public function __construct(protected string $nome)
    {
    }

    public function dormir(): string
    {
        return "{$this->nome} está dormindo, ebaaa";
    }

    public function grasnar(): string
    {
        return "Quack";
    }
}

class Galinha
{
    public function __construct(protected string $nome)
    {
    } // construtor repetido

    public function dormir(): string
    {
        return "{$this->nome} está dormindo, ebaaa"; // dormir() idêntico ao do Pato
    }

    public function cacarejar(): string
    {
        return "Có có có";
    }
}

echo (new Pato("Donald"))->dormir() . PHP_EOL;
echo (new Galinha("Cocó"))->dormir() . PHP_EOL;
// Se a regra de dormir() mudar, é preciso editar em cada classe.
// Cada novo bicho copia dormir() e o construtor outra vez.

# Com herança

// O comportamento comum sobe para uma classe geral (Animal).
// As específicas usam extends e só acrescentam o que é próprio delas.

abstract class Animal
{
    public function __construct(protected string $nome)
    {
    }

    public function dormir(): string
    {
        return "{$this->nome} está dormindo, ebaaa";
    }

    public abstract function fazerSom(): string;
}

class Cachorro extends Animal
{
    public function fazerSom(): string
    {
        return "Au au";
    }
}

class Gato extends Animal
{
    public function fazerSom(): string
    {
        return "Miau";
    }
}

$dog = new Cachorro("Rex");
echo $dog->dormir() . PHP_EOL; // herdado de Animal, sem reescrever
echo $dog->fazerSom() . PHP_EOL;

echo (new Gato("Mingau"))->dormir() . PHP_EOL; // mesmo dormir(), definido num lugar só

// dormir() existe num único ponto. Uma correção em Animal vale para todas as subclasses.
// $nome é protected: a subclasse acessa o atributo herdado.
// Se fosse private, só a própria Animal o enxergaria.
