<?php

namespace App\Http\Controllers;


use Domain\Bids\Models\Bid;

class BidsController extends Controller
{

    public function index()
    {
        $bids = Bid::paginate(15);

        return response()->json($bids);
    }
}
