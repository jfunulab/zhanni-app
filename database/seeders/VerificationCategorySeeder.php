<?php

namespace Database\Seeders;

use App\VerificationCategory;
use Illuminate\Database\Seeder;

class VerificationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VerificationCategory::insert([
            ['name' => 'SSN'],
            ['name' => 'Address'],
            ['name' => 'General Identity'],
            ['name' => 'Date of birth'],
        ]);
    }
}
