<?php

namespace App\Domain;

use App\Domain\Exceptions\AccountNotFoundException;

class BankService
{
    public function __construct(
        private readonly AccountRepository $accounts,
    ) {
    }

    public function reset(): void
    {
        $this->accounts->reset();
    }

    public function balanceOf(string $accountId): int
    {
        $account = $this->accounts->find($accountId);

        if ($account === null) {
            throw new AccountNotFoundException();
        }

        return $account->balance;
    }

    public function deposit(string $destinationId, int $amount): Account
    {
        $destination = $this->accounts->find($destinationId)
            ?? new Account($destinationId);

        $destination->deposit($amount);
        $this->accounts->save($destination);

        return $destination;
    }

    public function withdraw(string $originId, int $amount): Account
    {
        $origin = $this->accounts->find($originId);

        if ($origin === null) {
            throw new AccountNotFoundException();
        }

        $origin->withdraw($amount);
        $this->accounts->save($origin);

        return $origin;
    }

    /**
     * @return array{origin: Account, destination: Account}
     */
    public function transfer(string $originId, string $destinationId, int $amount): array
    {
        $origin = $this->accounts->find($originId);

        if ($origin === null) {
            throw new AccountNotFoundException();
        }

        $origin->withdraw($amount);
        $this->accounts->save($origin);

        $destination = $this->deposit($destinationId, $amount);

        return [
            'origin' => $origin,
            'destination' => $destination,
        ];
    }
}