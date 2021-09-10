<?php

namespace App\Http\Controllers;


use Domain\Bids\Models\Bid;

class BidsController extends Controller
{

    public function index()
    {
        $bids = Bid::with('user')->paginate(15);

        return response()->json($bids);
    }
}
