<?php

namespace App\Http\Controllers;


use App\Http\Requests\CreateBidRequest;
use Domain\Bids\Actions\AddUserBidAction;
use Domain\Bids\DTOs\BidData;
use Domain\Users\Models\User;
use Illuminate\Http\JsonResponse;

class UserBidsController extends Controller
{

    public function store(User $user, CreateBidRequest $createBidRequest, AddUserBidAction $addUserBidAction): JsonResponse
    {
        $remittanceData = BidData::fromArray($createBidRequest->toArray());
        $bid = $addUserBidAction($user, $remittanceData);

        return response()->json([
            'message' => 'Bid successfully created.',
            'data' => $bid
        ], 201);
    }
}
