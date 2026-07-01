<?php

namespace Arena\Characters;

use Arena\Interfaces\Fighter;
use Arena\Exceptions\InsufficientEnergyException;

abstract class Character implements Fighter
{
    protected const MIN_DAMAGE = 1;
    protected const DEFENSE_BONUS = 5;
    protected const ENERGY_REGEN = 10;

    protected int $health;
    protected int $maxHealth;
    protected int $attack;
    protected int $defense;
    protected int $energy;
    protected int $maxEnergy;
    protected int $defenseBonus = 0;
    protected array $activeEffects = [];

    public function __construct(
        protected string $name,
        int $health,
        int $attack,
        int $defense,
        int $energy
    ) {
        $this->health = $health;
        $this->maxHealth = $health;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->energy = $energy;
        $this->maxEnergy = $energy;
    }

    abstract public function getType(): string;
    abstract public function useSpecialAbility(Fighter $opponent): string;
    abstract public function getAbilityDescription(): string;
    abstract public function getAbilityCost(): int;

    public function attack(Fighter $opponent): string
    {
        $damage = max(static::MIN_DAMAGE, $this->attack - $opponent->getDefense());
        $opponent->takeDamage($damage);

        return "{$this->name} attacked {$opponent->getName()} dealing {$damage} damage! {$opponent->getName()} now has {$opponent->getHealth()} HP.";
    }

    public function defend(): string
    {
        $this->defenseBonus = static::DEFENSE_BONUS;
        return "{$this->name} assumed a defensive stance! Defense increased by +{$this->defenseBonus} until the next turn.";
    }

    public function takeDamage(int $damage): void
    {
        $this->health = max(0, $this->health - $damage);
    }

    public function heal(int $amount): void
    {
        $this->health = min($this->maxHealth, $this->health + $amount);
    }

    protected function consumeEnergy(int $cost): void
    {
        if ($this->energy < $cost) {
            throw new InsufficientEnergyException($this->energy, $cost);
        }
        $this->energy -= $cost;
    }

    public function startTurn(): void
    {
        $this->defenseBonus = 0;
        $this->energy = min($this->maxEnergy, $this->energy + static::ENERGY_REGEN);
        $message = $this->processEffects();
        if (!empty($message)) {
            echo $message;
        }
    }

    public function applyEffect(string $type, int $damage, int $duration): void
    {
        $this->activeEffects[] = [
            'type' => $type,
            'damage' => $damage,
            'duration' => $duration,
        ];
    }

    protected function processEffects(): string
    {
        $messages = '';
        foreach ($this->activeEffects as $index => &$effect) {
            if ($effect['duration'] > 0) {
                $this->takeDamage($effect['damage']);
                $messages .= "  >> {$this->name} took {$effect['damage']} damage from {$effect['type']}! ({$this->health} HP)\n";
                $effect['duration']--;
            }
        }
        unset($effect);
        $this->activeEffects = array_values(array_filter($this->activeEffects, fn($e) => $e['duration'] > 0));
        return $messages;
    }

    public function getEffectsText(): string
    {
        if (empty($this->activeEffects)) {
            return '';
        }
        $texts = [];
        foreach ($this->activeEffects as $effect) {
            $texts[] = "{$effect['type']}({$effect['duration']} turns)";
        }
        return ' [' . implode(', ', $texts) . ']';
    }

    public function resetForDuel(): void
    {
        $this->health = $this->maxHealth;
        $this->energy = $this->maxEnergy;
        $this->defenseBonus = 0;
        $this->activeEffects = [];
    }

    public function isAlive(): bool
    {
        return $this->health > 0;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function getMaxHealth(): int
    {
        return $this->maxHealth;
    }

    public function getEnergy(): int
    {
        return $this->energy;
    }

    public function getMaxEnergy(): int
    {
        return $this->maxEnergy;
    }

    public function getDefense(): int
    {
        return $this->defense + $this->defenseBonus;
    }

    public function displayHealthBar(): string
    {
        $size = 20;
        $ratio = $this->maxHealth > 0 ? $this->health / $this->maxHealth : 0;
        $filled = (int) round($ratio * $size);
        $empty = $size - $filled;

        return '[' . str_repeat('#', $filled) . str_repeat('-', $empty) . ']';
    }

    public function displayEnergyBar(): string
    {
        $size = 10;
        $ratio = $this->maxEnergy > 0 ? $this->energy / $this->maxEnergy : 0;
        $filled = (int) round($ratio * $size);
        $empty = $size - $filled;

        return '[' . str_repeat('*', $filled) . str_repeat('-', $empty) . ']';
    }

    public function displayStatus(): string
    {
        $effects = $this->getEffectsText();
        $defenseText = $this->defenseBonus > 0
            ? sprintf("DEF: %d(+%d)", $this->defense, $this->defenseBonus)
            : sprintf("DEF: %d", $this->defense);

        return sprintf(
            "  %s (%s) | HP: %d/%d %s | %s | Energy: %d/%d %s%s",
            $this->name,
            $this->getType(),
            $this->health,
            $this->maxHealth,
            $this->displayHealthBar(),
            $defenseText,
            $this->energy,
            $this->maxEnergy,
            $this->displayEnergyBar(),
            $effects
        );
    }
}
