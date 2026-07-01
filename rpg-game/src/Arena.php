<?php

namespace Arena;

use Arena\Interfaces\Fighter;
use Arena\Characters\Warrior;
use Arena\Characters\Mage;
use Arena\Characters\Assassin;
use Arena\Monsters\Monster;
use Arena\Exceptions\InsufficientEnergyException;
use Arena\Exceptions\InvalidInputException;

class Arena
{
    private Fighter $player1;
    private Fighter $player2;
    private int $turn = 0;
    private array $combatLog = [];

    public function start(): void
    {
        $this->displayTitle();

        echo "\n=== PLAYER 1 - Choose your character ===\n";
        $this->player1 = $this->selectCharacter(1);

        echo "\n=== PLAYER 2 - Choose your character ===\n";
        $this->player2 = $this->selectCharacter(2);

        // PvE Phase - each player faces a monster before the 1v1
        $this->monsterPhase($this->player1, 1);
        $this->monsterPhase($this->player2, 2);

        // PvP Phase
        $this->turn = 0;
        $this->combatLog = [];

        echo "\n========================================\n";
        echo "         PVP ARENA - 1v1\n";
        echo "========================================\n";
        echo "\n>>> {$this->player1->getName()} ({$this->player1->getType()}) VS {$this->player2->getName()} ({$this->player2->getType()}) <<<\n";
        echo "\nPress ENTER to begin the duel...";
        readline();

        $this->combatLoop();
    }

    private function displayTitle(): void
    {
        system('clear');
        echo "========================================\n";
        echo "         RPG COMBAT ARENA\n";
        echo "========================================\n";
        echo "    Turn-based combat game (1v1)\n";
        echo "    Two players, same computer\n";
        echo "========================================\n";
    }

    private function selectCharacter(int $playerNum): Fighter
    {
        $types = [
            1 => [
                'class' => Warrior::class,
                'name' => 'Warrior',
                'desc' => 'HP: 130 | ATK: 18 | DEF: 12 | Energy: 80 | Ability: Berserker Fury (2x damage, halves enemy DEF, loses 10% HP)',
            ],
            2 => [
                'class' => Mage::class,
                'name' => 'Mage',
                'desc' => 'HP: 85  | ATK: 14 | DEF: 6  | Energy: 120 | Ability: Fireball (38 magic damage, ignores defense)',
            ],
            3 => [
                'class' => Assassin::class,
                'name' => 'Assassin',
                'desc' => 'HP: 95  | ATK: 16 | DEF: 8  | Energy: 100 | Ability: Poison Strike (8 damage/turn for 3 turns)',
            ],
        ];

        while (true) {
            echo "\nAvailable characters:\n";
            foreach ($types as $num => $type) {
                echo "  [{$num}] {$type['name']}\n";
                echo "      {$type['desc']}\n";
            }

            try {
                $choice = $this->readInput("\nEnter the character number: ");
                $choice = (int) $choice;

                if (!isset($types[$choice])) {
                    throw new InvalidInputException("Invalid option! Choose between 1 and " . count($types) . ".");
                }

                $characterName = $this->readInput("Enter your character's name: ");
                $characterName = trim($characterName);

                if (empty($characterName)) {
                    throw new InvalidInputException("Name cannot be empty!");
                }

                $class = $types[$choice]['class'];
                $character = new $class($characterName);

                echo "\nPlayer {$playerNum} chose: {$characterName} ({$types[$choice]['name']})\n";
                return $character;

            } catch (InvalidInputException $e) {
                echo "\n[ERROR] {$e->getMessage()}\n";
            }
        }
    }

    private function monsterPhase(Fighter $player, int $playerNum): void
    {
        $monsters = [
            Monster::createGoblin(),
            Monster::createOrc(),
            Monster::createDragon(),
        ];
        $monster = $monsters[array_rand($monsters)];

        system('clear');
        echo "========================================\n";
        echo "       MONSTER ENCOUNTER - PLAYER {$playerNum}\n";
        echo "========================================\n\n";
        echo "  A wild {$monster->getName()} appears!\n";
        echo "  {$player->getName()}, defeat it before the duel!\n";
        echo "\nPress ENTER to fight...";
        readline();

        $monsterTurn = 0;
        while ($player->isAlive() && $monster->isAlive()) {
            $monsterTurn++;

            // Player turn
            $player->startTurn();
            if (!$player->isAlive()) break;

            system('clear');
            echo "========================================\n";
            echo "   MONSTER FIGHT - TURN {$monsterTurn} (PLAYER {$playerNum})\n";
            echo "========================================\n\n";

            echo "--- Status ---\n";
            echo $player->displayStatus() . "\n";
            echo $monster->displayStatus() . "\n";
            echo "--------------\n\n";

            echo "Your turn, {$player->getName()}!\n";
            echo "Energy: {$player->getEnergy()}/{$player->getMaxEnergy()}\n\n";

            $result = $this->chooseAction($player, $monster);
            echo "\n>>> {$result}\n";

            if (!$player->isAlive()) {
                echo "\n>>> {$player->getName()} was defeated! <<<\n";
                echo "\nPress ENTER to continue...";
                readline();
                break;
            }

            if (!$monster->isAlive()) {
                echo "\n>>> {$monster->getName()} has been slain! <<<\n";
                echo "\nPress ENTER to continue...";
                readline();
                break;
            }

            // Monster turn (automatic)
            $monster->startTurn();
            if (!$monster->isAlive()) {
                echo "\n>>> {$monster->getName()} succumbed to its wounds! <<<\n";
                echo "\nPress ENTER to continue...";
                readline();
                break;
            }

            $monsterResult = $monster->chooseAction($player);
            echo ">>> {$monsterResult}\n";

            echo "\nPress ENTER to continue...";
            readline();
        }

        if (!$player->isAlive()) {
            echo "\n{$player->getName()} was defeated...\n";
            echo "The arena grants a second chance!\n";
        }

        // Fully restore player for the PvP duel
        $player->resetForDuel();
    }

    private function combatLoop(): void
    {
        while ($this->player1->isAlive() && $this->player2->isAlive()) {
            $this->turn++;

            // Player 1's Turn
            $this->player1->startTurn();
            if (!$this->player1->isAlive()) break;

            $this->executeTurn($this->player1, $this->player2, 1);
            if (!$this->player1->isAlive() || !$this->player2->isAlive()) break;

            // Player 2's Turn
            $this->player2->startTurn();
            if (!$this->player2->isAlive()) break;

            $this->executeTurn($this->player2, $this->player1, 2);
        }

        $this->displayFinalSummary();
    }

    private function executeTurn(Fighter $attacker, Fighter $defender, int $playerNum): void
    {
        system('clear');
        echo "========================================\n";
        echo "         TURN {$this->turn} - PLAYER {$playerNum}\n";
        echo "========================================\n\n";

        echo "--- Fighters Status ---\n";
        echo $this->player1->displayStatus() . "\n";
        echo $this->player2->displayStatus() . "\n";
        echo "-----------------------\n\n";

        echo "Turn: {$attacker->getName()} ({$attacker->getType()})\n";
        echo "Energy: {$attacker->getEnergy()}/{$attacker->getMaxEnergy()}\n\n";

        $result = $this->chooseAction($attacker, $defender);
        $this->combatLog[] = "Turn {$this->turn} (P{$playerNum}): {$result}";

        echo "\n>>> {$result}\n";
        echo "\nPress ENTER to continue...";
        readline();
    }

    private function chooseAction(Fighter $attacker, Fighter $defender): string
    {
        while (true) {
            echo "Available actions:\n";
            echo "  [1] Attack\n";
            echo "  [2] Defend\n";

            echo "  [3] Special Ability - {$attacker->getAbilityDescription()}\n";

            try {
                $choice = $this->readInput("\nChoose your action: ");
                $choice = (int) $choice;

                return match ($choice) {
                    1 => $attacker->attack($defender),
                    2 => $attacker->defend(),
                    3 => $attacker->useSpecialAbility($defender),
                    default => throw new InvalidInputException("Invalid action! Choose between 1 and 3."),
                };
            } catch (InvalidInputException $e) {
                echo "\n[ERROR] {$e->getMessage()}\n\n";
            } catch (InsufficientEnergyException $e) {
                echo "\n[ERROR] {$e->getMessage()} Choose another action.\n\n";
            }
        }
    }

    private function displayFinalSummary(): void
    {
        system('clear');
        echo "========================================\n";
        echo "            GAME OVER!\n";
        echo "========================================\n\n";

        if (!$this->player1->isAlive() && !$this->player2->isAlive()) {
            echo "  DRAW! Both fighters fell in combat!\n";
            echo "  {$this->player1->getName()} ({$this->player1->getType()}) - 0 HP\n";
            echo "  {$this->player2->getName()} ({$this->player2->getType()}) - 0 HP\n";
        } else {
            $winner = $this->player1->isAlive() ? $this->player1 : $this->player2;
            $loser = $this->player1->isAlive() ? $this->player2 : $this->player1;

            echo "  Winner: {$winner->getName()} ({$winner->getType()})\n";
            echo "  Remaining HP: {$winner->getHealth()}/{$winner->getMaxHealth()} {$winner->displayHealthBar()}\n";
            echo "  Defeated: {$loser->getName()} ({$loser->getType()})\n";
        }

        echo "  Total turns: {$this->turn}\n";
        echo "\n========================================\n";
        echo "           COMBAT LOG\n";
        echo "========================================\n";
        foreach ($this->combatLog as $log) {
            echo "  {$log}\n";
        }
        echo "========================================\n";
    }

    private function readInput(string $prompt): string
    {
        $input = readline($prompt);
        if ($input === false) {
            return '';
        }
        return $input;
    }
}
