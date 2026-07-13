<?php

require_once __DIR__ . '/../interfaces/MailerInterface.php';

class SMTPMailer implements MailerInterface
{
    public function __construct(private string $host)
    {
    }

    public function send(string $to, string $body): void
    {
        mail($to, 'Notificação', $body); // lógica real de envio
    }
}
