<?php
// public/index.php — o ponto de entrada
require_once __DIR__ . '/../database/MySQLConnection.php';
require_once __DIR__ . '/../Mail/SMTPMailer.php';
require_once __DIR__ . '/../service/OrderService.php';

// 1. Monta as dependências concretas
$db = new MySQLConnection('localhost', 'user', 'pass', 'loja');
$mailer = new SMTPMailer('smtp.gmail.com');

// 2. Injeta no serviço
$service = new PedidoService($db, $mailer);

// 3. Usa
$service->finalizar(42);
