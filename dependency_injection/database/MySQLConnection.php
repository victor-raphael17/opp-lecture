<?php

require_once __DIR__ . '/../interfaces/ConnectionInterface.php';

class MySQLConnection implements ConnectionInterface
{
    private \PDO $pdo;

    public function __construct(string $host, string $user, string $pass, string $db)
    {
        $this->pdo = new \PDO("mysql:host=$host;dbname=$db", $user, $pass);
    }

    // Cumprindo a promessa feita na interface: aqui tem corpo, tem lógica
    public function query(string $sql): array
    {
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
