<?php

namespace Tests\Feature;

use Domain\PaymentMethods\Actions\GetBankListAction;
use Domain\PaymentMethods\Models\Bank;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetBankListActionTest extends TestCase
{
    use RefreshDatabase;

    private $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(GetBankListAction::class);
    }

    /** @test */
    function returns_list_banks_available()
    {
        Bank::factory()->count(2)->create();

        $banks = ($this->action)();

        $this->assertCount(2, $banks);
    }
}
