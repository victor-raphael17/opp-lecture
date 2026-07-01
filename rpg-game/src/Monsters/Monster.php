<?php

namespace Arena\Monsters;

use Arena\Interfaces\Fighter;

class Monster implements Fighter
{
    private int $health;
    private int $maxHealth;
    private int $attackPower;
    private int $defense;
    private int $defenseBonus = 0;
    private int $cyclePosition = 0;
    private array $activeEffects = [];

    private const CYCLE = ['attack', 'defend', 'special'];
    private const DEFENSE_BONUS = 3;

    public function __construct(
        private string $name,
        private string $type,
        int $health,
        int $attackPower,
        int $defense,
        private int $specialDamage,
        private string $specialName,
    ) {
        $this->health = $health;
        $this->maxHealth = $health;
        $this->attackPower = $attackPower;
        $this->defense = $defense;
    }

    public function chooseAction(Fighter $opponent): string
    {
        $action = self::CYCLE[$this->cyclePosition];
        $this->cyclePosition = ($this->cyclePosition + 1) % count(self::CYCLE);

        return match ($action) {
            'attack' => $this->attack($opponent),
            'defend' => $this->defend(),
            'special' => $this->useSpecialAbility($opponent),
        };
    }

    public function attack(Fighter $opponent): string
    {
        $damage = max(1, $this->attackPower - $opponent->getDefense());
        $opponent->takeDamage($damage);

        return "{$this->name} attacked {$opponent->getName()} dealing {$damage} damage! {$opponent->getName()} now has {$opponent->getHealth()} HP.";
    }

    public function defend(): string
    {
        $this->defenseBonus = self::DEFENSE_BONUS;
        return "{$this->name} roars and hardens its stance! Defense increased by +{$this->defenseBonus} until the next turn.";
    }

    public function useSpecialAbility(Fighter $opponent): string
    {
        $opponent->takeDamage($this->specialDamage);
        return "{$this->name} uses {$this->specialName} dealing {$this->specialDamage} damage! {$opponent->getName()} now has {$opponent->getHealth()} HP.";
    }

    public function takeDamage(int $damage): void
    {
        $this->health = max(0, $this->health - $damage);
    }

    public function heal(int $amount): void
    {
        $this->health = min($this->maxHealth, $this->health + $amount);
    }

    public function applyEffect(string $type, int $damage, int $duration): void
    {
        $this->activeEffects[] = [
            'type' => $type,
            'damage' => $damage,
            'duration' => $duration,
        ];
    }

    public function resetForDuel(): void
    {
        // Monsters are not reused between phases, but implement for interface compliance
        $this->health = $this->maxHealth;
        $this->defenseBonus = 0;
        $this->activeEffects = [];
        $this->cyclePosition = 0;
    }

    public function isAlive(): bool
    {
        return $this->health > 0;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
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
        return 0;
    }

    public function getMaxEnergy(): int
    {
        return 0;
    }

    public function getDefense(): int
    {
        return $this->defense + $this->defenseBonus;
    }

    public function getAbilityDescription(): string
    {
        return "{$this->specialName} - Deals {$this->specialDamage} damage.";
    }

    public function startTurn(): void
    {
        $this->defenseBonus = 0;
        $message = $this->processEffects();
        if (!empty($message)) {
            echo $message;
        }
    }

    private function processEffects(): string
    {
        $messages = '';
        foreach ($this->activeEffects as &$effect) {
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

    private function getEffectsText(): string
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

    public function displayHealthBar(): string
    {
        $size = 20;
        $ratio = $this->maxHealth > 0 ? $this->health / $this->maxHealth : 0;
        $filled = (int) round($ratio * $size);
        $empty = $size - $filled;

        return '[' . str_repeat('#', $filled) . str_repeat('-', $empty) . ']';
    }

    public function displayStatus(): string
    {
        $effects = $this->getEffectsText();
        $defenseText = $this->defenseBonus > 0
            ? sprintf("DEF: %d(+%d)", $this->defense, $this->defenseBonus)
            : sprintf("DEF: %d", $this->defense);

        return sprintf(
            "  %s (%s) | HP: %d/%d %s | %s%s",
            $this->name,
            $this->type,
            $this->health,
            $this->maxHealth,
            $this->displayHealthBar(),
            $defenseText,
            $effects
        );
    }

    public static function createGoblin(): self
    {
        return new self('Goblin', 'Monster', 60, 10, 4, 15, 'Poison Spit');
    }

    public static function createOrc(): self
    {
        return new self('Orc', 'Monster', 90, 14, 8, 20, 'Ground Slam');
    }

    public static function createDragon(): self
    {
        return new self('Dragon', 'Monster', 120, 18, 10, 30, 'Fire Breath');
    }
}
