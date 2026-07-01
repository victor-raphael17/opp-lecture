<?php

namespace Arena\Interfaces;

interface Fighter
{
    public function attack(Fighter $opponent): string;
    public function defend(): string;
    public function useSpecialAbility(Fighter $opponent): string;
    public function takeDamage(int $damage): void;
    public function heal(int $amount): void;
    public function isAlive(): bool;
    public function getName(): string;
    public function getType(): string;
    public function getHealth(): int;
    public function getMaxHealth(): int;
    public function getEnergy(): int;
    public function getMaxEnergy(): int;
    public function getDefense(): int;
    public function getAbilityDescription(): string;
    public function applyEffect(string $type, int $damage, int $duration): void;
    public function resetForDuel(): void;
    public function displayStatus(): string;
    public function displayHealthBar(): string;
    public function startTurn(): void;
}
