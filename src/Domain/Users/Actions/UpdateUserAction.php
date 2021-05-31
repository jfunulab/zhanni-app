<?php


namespace Domain\Users\Actions;


use Domain\Users\DTOs\UserData;
use Domain\Users\Models\User;

class UpdateUserAction
{
    /**
     * @var UpdateUserAddressAction
     */
    private UpdateUserAddressAction $updateUserAddressAction;
    /**
     * @var AddUserAddressAction
     */
    private AddUserAddressAction $addUserAddressAction;

    /**
     * UpdateUserAction constructor.
     * @param UpdateUserAddressAction $updateUserAddressAction
     * @param AddUserAddressAction $addUserAddressAction
     */
    public function __construct(UpdateUserAddressAction $updateUserAddressAction,
                                AddUserAddressAction $addUserAddressAction)
    {
        $this->updateUserAddressAction = $updateUserAddressAction;
        $this->addUserAddressAction = $addUserAddressAction;
    }

    public function __invoke(User $user, UserData $userData): User
    {
        $user->fill([
            'first_name' => $userData->firstName ?? $user->first_name,
            'last_name' => $userData->lastName ?? $user->last_name,
            'phone_number' => $userData->phoneNumber ?? $user->phone_number
        ])->save();

        ($user->address) ?
            ($this->updateUserAddressAction)($user->address, $userData) :
            ($this->addUserAddressAction)($user, $userData);

        return $user->fresh(['address.country', 'address.state']);
    }
}
