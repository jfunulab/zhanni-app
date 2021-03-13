<?php


namespace Support\PasswordReset;


use Illuminate\Auth\Passwords\DatabaseTokenRepository as BaseDatabaseTokenRepository;

class DatabaseTokenRepository extends BaseDatabaseTokenRepository
{

    public function createNewToken(): int
    {
        return rand(100000, 999999);
    }
}
