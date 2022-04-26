<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyBidRequest;
use Domain\Bids\Actions\BuyBidAction;
use Domain\Bids\DTOs\BidPurchaseData;
use Domain\Bids\Models\Bid;
use Domain\Users\Models\User;
use Illuminate\Http\JsonResponse;

class UserBidOrdersController extends Controller
{

    public function buyIndex(User $user): JsonResponse
    {
        $bids = $user->bidBuyOrders()->with(['seller'])->paginate(15);

        return response()->json([
            'message' => 'Bids bought',
            'data' => $bids
        ]);
    }

    public function sellIndex(User $user): JsonResponse
    {
        $bids = $user->bidSellOrders()->with(['buyer'])->paginate(15);

        return response()->json([
            'message' => 'Bids sold',
            'data' => $bids
        ]);
    }

    public function store(Bid $bid, BuyBidRequest $bidRequest, BuyBidAction $buyBidAction): JsonResponse
    {
        $remittanceData = BidPurchaseData::fromArray($bid, $bidRequest->toArray());
        $bid = $buyBidAction(auth()->user(), $bid, $remittanceData);

        return response()->json([
            'message' => 'Bid successfully created.',
            'data' => $bid
        ], 201);
    }
}
