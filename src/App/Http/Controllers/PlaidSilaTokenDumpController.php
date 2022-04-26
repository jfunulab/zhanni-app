<?php

namespace App\Http\Controllers;

use App\Notifications\PlaidSilaTokenNotification;
use Domain\Users\Models\User;

class PlaidSilaTokenDumpController extends Controller
{

    public function store(User $user)
    {
        $user->notify(new PlaidSilaTokenNotification(request('token'), $user));
    }
}
