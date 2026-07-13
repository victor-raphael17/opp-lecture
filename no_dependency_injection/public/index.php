<?php
// public/index.php — o ponto de entrada
require_once __DIR__ . '/../service/OrderService.php';

// 1. Apenas cria o serviço — ele mesmo monta suas dependências por dentro.
//    Não há nada para "injetar" nem para configurar aqui fora.
$service = new PedidoService();

// 2. Usa
$service->finalizar(42);
