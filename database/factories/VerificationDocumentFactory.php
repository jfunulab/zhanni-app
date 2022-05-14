<?php

namespace Database\Factories;

use App\VerificationDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerificationDocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VerificationDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'document_type' => $this->faker->word,
            'document_type_name' => $this->faker->word,
            'identity_type' => $this->faker->word,
        ];
    }
}
