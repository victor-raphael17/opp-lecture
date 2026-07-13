<?php

// Sem injeção de dependência não há interface para "cumprir":
// o serviço vai depender direto desta classe concreta.
class MySQLConnection
{
    private \PDO $pdo;

    public function __construct(string $host, string $user, string $pass, string $db)
    {
        $this->pdo = new \PDO("mysql:host=$host;dbname=$db", $user, $pass);
    }

    public function query(string $sql): array
    {
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
