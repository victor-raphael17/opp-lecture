<?php

require_once __DIR__ . '/../database/MySQLConnection.php';
require_once __DIR__ . '/../Mail/SMTPMailer.php';

class PedidoService
{
    private MySQLConnection $db;
    private SMTPMailer $mailer;

    public function __construct()
    {
        // SEM injeção de dependência:
        // o próprio serviço cria e configura suas dependências concretas.
        // Repare que os detalhes de conexão e do SMTP ficam "presos" aqui dentro.
        $this->db = new MySQLConnection('localhost', 'user', 'pass', 'loja');
        $this->mailer = new SMTPMailer('smtp.gmail.com');
    }

    public function finalizar(int $pedidoId): void
    {
        $dados = $this->db->query("SELECT * FROM pedidos WHERE id = $pedidoId");
        $this->mailer->send('cliente@exemplo.com', 'Pedido finalizado');
    }
}
