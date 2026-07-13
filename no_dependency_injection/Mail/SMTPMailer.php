<?php

class SMTPMailer
{
    public function __construct(private string $host)
    {
    }

    public function send(string $to, string $body): void
    {
        mail($to, 'Notificação', $body); // lógica real de envio
    }
}
