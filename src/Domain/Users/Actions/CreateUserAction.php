<?php


namespace Domain\Users\Actions;


use Domain\Users\DTOs\UserData;
use Domain\Users\Models\User;

class CreateUserAction
{
    /**
     * @var AddUserAddressAction
     */
    private AddUserAddressAction $addUserAddressAction;

    /**
     * CreateUserAction constructor.
     * @param AddUserAddressAction $addUserAddressAction
     */
    public function __construct(AddUserAddressAction $addUserAddressAction)
    {
        $this->addUserAddressAction = $addUserAddressAction;
    }

    public function __invoke(UserData $userData): User
    {
        $user = User::firstOrCreate(['email' => $userData->email]);

        $user->fill([
            'first_name' => $userData->firstName,
            'last_name' => $userData->lastName,
            'identity_number' => $userData->identityNumber,
            'phone_number' => $userData->phoneNumber,
            'password' => bcrypt($userData->password)
        ])->save();

        ($this->addUserAddressAction)($user, $userData->addressData);

        return $user->fresh(['address']);
    }
}
