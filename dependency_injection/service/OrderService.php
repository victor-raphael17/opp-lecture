<?php

require_once __DIR__ . '/../interfaces/ConnectionInterface.php';
require_once __DIR__ . '/../interfaces/MailerInterface.php';

class PedidoService
{
    private ConnectionInterface $db;
    private MailerInterface $mailer;

    public function __construct(ConnectionInterface $db, MailerInterface $mailer)
    {
        $this->db = $db;        // recebe e guarda
        $this->mailer = $mailer;
    }

    public function finalizar(int $pedidoId): void
    {
        $dados = $this->db->query("SELECT * FROM pedidos WHERE id = $pedidoId");
        $this->mailer->send('cliente@exemplo.com', 'Pedido finalizado');
    }
}