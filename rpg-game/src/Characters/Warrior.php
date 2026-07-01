<?php

namespace Arena\Characters;

use Arena\Interfaces\Fighter;

class Warrior extends Character
{
    private const INITIAL_HEALTH = 130;
    private const INITIAL_ATTACK = 18;
    private const INITIAL_DEFENSE = 12;
    private const INITIAL_ENERGY = 80;
    private const ABILITY_COST = 30;
    private const SELF_DAMAGE_PERCENT = 10;

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
        return 'Warrior';
    }

    public function getAbilityDescription(): string
    {
        return "Berserker Fury - Delivers a devastating blow with double attack power and halved enemy defense, but loses 10% of own health. (Cost: " . self::ABILITY_COST . " energy)";
    }

    public function getAbilityCost(): int
    {
        return self::ABILITY_COST;
    }

    public function useSpecialAbility(Fighter $opponent): string
    {
        $this->consumeEnergy(self::ABILITY_COST);

        $baseDamage = $this->attack * 2;
        $opponentDefense = (int) ($opponent->getDefense() / 2);

        $damage = max(static::MIN_DAMAGE, $baseDamage - $opponentDefense);
        $opponent->takeDamage($damage);

        $selfDamage = (int) ceil($this->maxHealth * self::SELF_DAMAGE_PERCENT / 100);
        $this->takeDamage($selfDamage);

        return "{$this->name} used BERSERKER FURY! Dealt {$damage} damage to {$opponent->getName()}, but took {$selfDamage} self-damage! {$opponent->getName()}: {$opponent->getHealth()} HP | {$this->name}: {$this->health} HP";
    }
}
