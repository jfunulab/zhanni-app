<?php


namespace Tests\Unit\PaymentMethod\Actions;


use App\Http\Requests\AddCardRequest;
use Domain\PaymentMethods\DTOs\UserCardData;
use Domain\PaymentMethods\Actions\AddUserCardAction;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddUserCardActionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var AddUserCardAction
     */
    private AddUserCardAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(AddUserCardAction::class);
    }

    /** @test */
    function can_add_payment_to_user_as_a_new_customer_on_stripe()
    {
        $user = User::factory()->create();
        $request = new AddCardRequest([
            'payment_method_id' => getStripeToken()['id'],
            'expiry_month' => 05,
            'expiry_year' => 2022,
            'postal_code' => 004455
        ]);

        $userCardData = UserCardData::fromRequest($request);
        $userCard = ($this->action)($user, $userCardData);

        $this->assertNotNull($userCardData);
        $this->assertNotNull($userCard->platform_id);
    }
}
