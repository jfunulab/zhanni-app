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
            'phone_number' => $userData->phoneNumber ?? $user->phone_number,
            'username' => $userData->username ?? $user->username,
            'birth_date' => $userData->birthDate ?? $user->birth_date
        ])->save();


        if($user->address) {
            ($this->updateUserAddressAction)($user->address, $userData->addressData, false) ;
        }else {
            ($this->addUserAddressAction)($user, $userData->addressData);
        }

        return $user->fresh(['address.country', 'address.state']);
    }
}
