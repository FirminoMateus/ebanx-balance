<?php

namespace App\Http\Controllers;

use App\Domain\BankService;
use App\Domain\Exceptions\AccountNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BalanceController extends Controller
{
    public function __construct(
        private readonly BankService $bank,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $accountId = (string) $request->query('account_id');

        try {
            $balance = $this->bank->balanceOf($accountId);
        } catch (AccountNotFoundException) {
            return response('0', Response::HTTP_NOT_FOUND);
        }

        return response((string) $balance, Response::HTTP_OK);
    }
}