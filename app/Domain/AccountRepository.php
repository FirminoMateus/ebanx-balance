<?php

namespace App\Domain;

class AccountRepository
{
    private string $storagePath;

    public function __construct()
    {
        $this->storagePath = storage_path('app/accounts.json');
    }

    public function find(string $accountId): ?Account
    {
        $accounts = $this->load();

        if (!isset($accounts[$accountId])) {
            return null;
        }

        return new Account(
            (string) $accounts[$accountId]['id'],
            (int) $accounts[$accountId]['balance'],
        );
    }

    public function save(Account $account): void
    {
        $accounts = $this->load();
        $accounts[$account->id] = $account->toArray();
        $this->persist($accounts);
    }

    public function reset(): void
    {
        $this->persist([]);
    }

    private function load(): array
    {
        if (!file_exists($this->storagePath)) {
            return [];
        }

        $contents = file_get_contents($this->storagePath);

        if ($contents === false || $contents === '') {
            return [];
        }

        return json_decode($contents, true) ?? [];
    }

    private function persist(array $accounts): void
    {
        $directory = dirname($this->storagePath);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents(
            $this->storagePath,
            json_encode($accounts),
        );
    } 
}