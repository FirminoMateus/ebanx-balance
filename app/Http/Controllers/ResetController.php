<?php

namespace App\Http\Controllers;

use App\Domain\BankService;
use Illuminate\Http\Response;

class ResetController extends Controller
{
    public function __construct(
        private readonly BankService $bank,
    ) {
    }

    public function __invoke(): Response
    {
        $this->bank->reset();

        return response('OK', Response::HTTP_OK);
    }
}