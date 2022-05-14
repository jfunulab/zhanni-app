<?php

namespace App\Jobs;

use Domain\Users\Actions\UpdateSilaUserAction;
use Domain\Users\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSilaUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;
    private array $propertyChanges;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, array $propertyChanges)
    {
        $this->user = $user;
        $this->propertyChanges = $propertyChanges;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UpdateSilaUserAction $updateSilaUserAction)
    {
        ($updateSilaUserAction)($this->user, $this->propertyChanges);
    }
}
