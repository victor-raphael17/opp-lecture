<?php
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