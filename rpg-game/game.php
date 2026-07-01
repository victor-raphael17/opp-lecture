<?php

require_once __DIR__ . '/src/Interfaces/Fighter.php';
require_once __DIR__ . '/src/Exceptions/InsufficientEnergyException.php';
require_once __DIR__ . '/src/Exceptions/InvalidInputException.php';
require_once __DIR__ . '/src/Characters/Character.php';
require_once __DIR__ . '/src/Characters/Warrior.php';
require_once __DIR__ . '/src/Characters/Mage.php';
require_once __DIR__ . '/src/Characters/Assassin.php';
require_once __DIR__ . '/src/Monsters/Monster.php';
require_once __DIR__ . '/src/Arena.php';

use Arena\Arena;

$arena = new Arena();
$arena->start();
