<?php

namespace App\Http\Controllers;


use App\CreditPayment;
use App\Jobs\CheckSilaUserKycJob;
use App\Jobs\InitiateRemittancePayoutJob;
use App\Jobs\LinkBankAccountToSilaJob;
use App\Jobs\TransferFundsToZhanniWalletJob;
use Domain\Users\Models\User;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SilaWebhookController extends Controller
{

    public function handle()
    {
        $payload = request()->all();
        $method = 'handle' . Str::studly($payload['event_type']);

        if (method_exists($this, $method)) {
            return $this->{$method}($payload['event_details']);
        }

        return $this->missingMethod();
    }

    public function handleKyc($eventDetails)
    {
        if ($user = User::where('sila_username', $eventDetails['entity'])->first()) {
            $user->update(['kyc_status' => $eventDetails['outcome']]);

            if ($eventDetails['outcome'] == 'passed') {
                $user->bankAccounts->each(function ($bankAccount) use ($user) {
                    LinkBankAccountToSilaJob::dispatch($user, $bankAccount);
                });
            }

            if ($eventDetails['outcome'] == 'failed' || $eventDetails['outcome'] == 'documents_required') {
                CheckSilaUserKycJob::dispatch($user);
            }
        }

        return $this->successMethod();
    }

    public function handleTransaction($eventDetails)
    {
        if ($eventDetails['transaction_type'] == 'issue') {
            $creditPayment = CreditPayment::where('reference_id', $eventDetails['transaction'])
                ->where('status', '!=', 'success')
                ->first();
            if($creditPayment){
                $creditPayment->update([
                    'status' => $eventDetails['outcome'],
                    'amount_in_cents' => $eventDetails['sila_amount'],
                    'processing_type' => $eventDetails['processing_type']
                ]);

                if ($eventDetails['outcome'] == 'success') {
                    TransferFundsToZhanniWalletJob::dispatch($creditPayment);
                    InitiateRemittancePayoutJob::dispatch($creditPayment);
                }
            }
        }
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
