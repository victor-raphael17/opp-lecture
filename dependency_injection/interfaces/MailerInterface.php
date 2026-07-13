<?php

interface MailerInterface
{
    public function send(string $to, string $body): void;
}
