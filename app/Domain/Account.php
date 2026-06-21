<?php

namespace App\Domain;

class Account
{
    public function __construct(
        public readonly string $id,
        public int $balance = 0,
    ) {
    }

    public function deposit(int $amount): void
    {
        $this->balance += $amount;
    }

    public function withdraw(int $amount): void
    {
        $this->balance -= $amount;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'balance' => $this->balance,
        ];
    }
}