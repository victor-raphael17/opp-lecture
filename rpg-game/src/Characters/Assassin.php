<?php

namespace Arena\Characters;

use Arena\Interfaces\Fighter;

class Assassin extends Character
{
    private const INITIAL_HEALTH = 95;
    private const INITIAL_ATTACK = 16;
    private const INITIAL_DEFENSE = 8;
    private const INITIAL_ENERGY = 100;
    private const ABILITY_COST = 25;
    private const POISON_DAMAGE = 8;
    private const POISON_DURATION = 3;

    public function __construct(string $name)
    {
        parent::__construct(
            $name,
            self::INITIAL_HEALTH,
            self::INITIAL_ATTACK,
            self::INITIAL_DEFENSE,
            self::INITIAL_ENERGY
        );
    }

    public function getType(): string
    {
        return 'Assassin';
    }

    public function getAbilityDescription(): string
    {
        return "Poison Strike - Attacks the opponent and applies poison dealing " . self::POISON_DAMAGE . " damage per turn for " . self::POISON_DURATION . " turns. (Cost: " . self::ABILITY_COST . " energy)";
    }

    public function getAbilityCost(): int
    {
        return self::ABILITY_COST;
    }

    public function useSpecialAbility(Fighter $opponent): string
    {
        $this->consumeEnergy(self::ABILITY_COST);

        $damage = max(static::MIN_DAMAGE, $this->attack - $opponent->getDefense());
        $opponent->takeDamage($damage);
        $opponent->applyEffect('Poison', self::POISON_DAMAGE, self::POISON_DURATION);

        return "{$this->name} used POISON STRIKE! Dealt {$damage} damage to {$opponent->getName()} and applied poison (" . self::POISON_DAMAGE . " damage/turn for " . self::POISON_DURATION . " turns)! {$opponent->getName()} now has {$opponent->getHealth()} HP.";
    }
}
