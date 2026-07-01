<?php

namespace Arena\Characters;

use Arena\Interfaces\Fighter;

class Mage extends Character
{
    private const INITIAL_HEALTH = 85;
    private const INITIAL_ATTACK = 14;
    private const INITIAL_DEFENSE = 6;
    private const INITIAL_ENERGY = 120;
    private const ABILITY_COST = 40;
    private const FIREBALL_DAMAGE = 38;

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
        return 'Mage';
    }

    public function getAbilityDescription(): string
    {
        return "Fireball - Launches a magic fireball that completely ignores the opponent's defense, dealing " . self::FIREBALL_DAMAGE . " damage. (Cost: " . self::ABILITY_COST . " energy)";
    }

    public function getAbilityCost(): int
    {
        return self::ABILITY_COST;
    }

    public function useSpecialAbility(Fighter $opponent): string
    {
        $this->consumeEnergy(self::ABILITY_COST);

        $opponent->takeDamage(self::FIREBALL_DAMAGE);

        return "{$this->name} cast FIREBALL! Dealt " . self::FIREBALL_DAMAGE . " magic damage to {$opponent->getName()} ignoring defense! {$opponent->getName()} now has {$opponent->getHealth()} HP.";
    }
}
