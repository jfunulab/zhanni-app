<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyBidRequest;
use Domain\Bids\Actions\BuyBidAction;
use Domain\Bids\DTOs\BidPurchaseData;
use Domain\Bids\Models\Bid;
use Illuminate\Http\JsonResponse;

class UserBidOrdersController extends Controller
{

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
