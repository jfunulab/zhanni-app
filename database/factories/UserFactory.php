<?php

namespace Database\Factories;

use Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->unique()->userName,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'birth_date' => now()->subYears(30),
            'phone_number' => $this->faker->phoneNumber,
            'identity_number' => $this->faker->isbn10(),
        ];
    }

    public function newUser(): UserFactory
    {
      return $this->state(function(array $attributes){
        return [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => null,
            'verification_code_expires_at' => now()->addMinutes(60),
            'password' => null,
            'remember_token' => null,
        ];
      });
    }

    public function verified(): UserFactory
    {
        return $this->state(function(array $attributes){
            return [
                'first_name' => $this->faker->name,
                'last_name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'verification_code_expires_at' => now()->subMinutes(60),
                'password' => null,
                'remember_token' => null,
            ];
        });
    }
}
