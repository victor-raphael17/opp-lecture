<?php

interface ConnectionInterface
{
    public function query(string $sql): array;
}