<?php

# Sem encapsulamento

// O saldo é só um dado solto. Qualquer parte do código pode alterá-lo.

$saldo = 100;
$saldo -= 30;   // saque válido
echo $saldo . PHP_EOL;   // 70

$saldo = -5000; // nada impede: o estado fica inválido e ninguém reclama
echo $saldo . PHP_EOL;   // -5000

# Com validação, mas o dado ainda exposto

// A função valida o saque, mas o saldo continua acessível por fora.
// Validar a operação não basta: o próprio dado precisa ficar inacessível.

function sacar(float $saldo, float $valor): float
{
    if ($valor > $saldo) {
        throw new InvalidArgumentException("Saldo insuficiente");
    }
    return $saldo - $valor;
}

$saldo = 100;
$saldo = sacar($saldo, 30); // passa pela regra: 70
$saldo = -5000;             // ainda dá pra furar a regra direto; a função não protege o dado
echo $saldo . PHP_EOL;      // -5000

# Com encapsulamento (classe)

// O saldo vira private. As únicas portas de entrada são os métodos,
// e cada uma aplica as regras. O estado inválido deixa de ser alcançável —
// inclusive na criação do objeto.

class ContaBancaria
{
    private float $saldo;

    public function __construct(float $saldoInicial)
    {
        if ($saldoInicial < 0) {
            throw new InvalidArgumentException("Saldo inicial não pode ser negativo");
        }
        $this->saldo = $saldoInicial;
    }

    public function sacar(float $valor): void
    {
        if ($valor <= 0) {
            throw new InvalidArgumentException("Valor do saque deve ser positivo");
        }
        if ($valor > $this->saldo) {
            throw new InvalidArgumentException("Saldo insuficiente");
        }
        $this->saldo -= $valor;
    }

    public function getSaldo(): float
    {
        return $this->saldo;
    }

    public function depositar(float $valor): void
    {
        if ($valor <= 0) {
            throw new InvalidArgumentException("Valor do depósito deve ser positivo");
        }
        $this->saldo += $valor;
    }
}

$conta = new ContaBancaria(100);
$conta->sacar(30);
#$conta->saldo = -5000;     // Error: Cannot access private property — a linguagem barra
// new ContaBancaria(-5000);  // InvalidArgumentException — nem na criação o estado fica inválido
echo $conta->getSaldo() . PHP_EOL; // 70
