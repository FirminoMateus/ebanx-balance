<?php

namespace App\Http\Controllers;

use App\Domain\BankService;
use App\Domain\Exceptions\AccountNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class EventController extends Controller
{
    public function __construct(
        private readonly BankService $bank,
    ) {
    }

    public function __invoke(Request $request): BaseResponse
    {
        $type = $request->input('type');
        $amount = (int) $request->input('amount');

        try {
            return match ($type) {
                'deposit' => $this->handleDeposit($request, $amount),
                'withdraw' => $this->handleWithdraw($request, $amount),
                'transfer' => $this->handleTransfer($request, $amount),
                default => response('0', Response::HTTP_BAD_REQUEST),
            };
        } catch (AccountNotFoundException) {
            return response('0', Response::HTTP_NOT_FOUND);
        }
    }

    private function handleDeposit(Request $request, int $amount): JsonResponse
    {
        $destination = $this->bank->deposit(
            (string) $request->input('destination'),
            $amount,
        );

        return response()->json(
            ['destination' => $destination->toArray()],
            Response::HTTP_CREATED,
        );
    }

    private function handleWithdraw(Request $request, int $amount): JsonResponse
    {
        $origin = $this->bank->withdraw(
            (string) $request->input('origin'),
            $amount,
        );

        return response()->json(
            ['origin' => $origin->toArray()],
            Response::HTTP_CREATED,
        );
    }

    private function handleTransfer(Request $request, int $amount): JsonResponse
    {
        $result = $this->bank->transfer(
            (string) $request->input('origin'),
            (string) $request->input('destination'),
            $amount,
        );

        return response()->json(
            [
                'origin' => $result['origin']->toArray(),
                'destination' => $result['destination']->toArray(),
            ],
            Response::HTTP_CREATED,
        );
    }
}