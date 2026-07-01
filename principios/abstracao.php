<?php

# Sem abstração

// Fazer um café — sem classe, sem abstração

$temperatura = 20;
while ($temperatura < 92) {
    $temperatura++; // aquece de 1 em 1 grau até 92°C
}

$gramas = 18;
$po = "{$gramas}g de pó"; // mói os grãos

$cafe = "café extraído com {$po} a {$temperatura}°C"; // extrai

echo $cafe;

# Com abstração

function fazerCafe(): string
{
    $temperatura = 20;
    while ($temperatura < 92) {
        $temperatura++;
    }
    $po = "18g de pó";
    return "café extraído com {$po} a {$temperatura}°C";
}

echo fazerCafe(); // a intenção
echo fazerCafe(); // de novo, sem repetir nada

# Com abstração e em uma classe

class Cafeteira
{
    public function fazerCafe(): string
    {
        $this->aquecerAgua();
        $this->moerGraos();
        return "café pronto";
    }

    private function aquecerAgua(): void
    { /* ... */
    }
    private function moerGraos(): void
    { /* ... */
    }
}

$cafeteira = new Cafeteira();
echo $cafeteira->fazerCafe(); // quem usa não precisa saber das etapas internas
