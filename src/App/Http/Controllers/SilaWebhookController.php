<?php

namespace App\Http\Controllers;


use App\Jobs\LinkBankAccountToSila;
use Domain\Users\Models\User;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SilaWebhookController extends Controller
{

    public function handle()
    {
        $payload = request()->all();
        $method = 'handle'.Str::studly($payload['event_type']);

        if (method_exists($this, $method)){
            return $this->{$method}($payload['event_details']);
        }

        return $this->missingMethod();
    }

    public function handleKyc($eventDetails)
    {
        if($user = User::where('sila_username', $eventDetails['entity'])->first()){
            $user->update(['kyc_status' => $eventDetails['outcome']]);
            if($eventDetails['outcome'] == 'passed'){
                $user->bankAccounts->each(function($bankAccount) use ($user){
                    LinkBankAccountToSila::dispatch($user, $bankAccount);
                });
            }
        }

        return $this->successMethod();
    }

    public function handleTransaction($eventDetails)
    {
        info('data from sila webhook');
        info($eventDetails);
    }

    /**
     * Handle successful calls on the controller.
     *
     * @param array $parameters
     */
    protected function successMethod(array $parameters = []): Response
    {
        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param array $parameters
     */
    protected function missingMethod(array $parameters = []): Response
    {
        return new Response;
    }
}
