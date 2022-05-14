<?php

namespace Database\Seeders;

use App\VerificationDocument;
use Illuminate\Database\Seeder;

class VerificationDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VerificationDocument::insert([
            ['document_type' => "State-issued driver's license", 'document_type_name' => 'id_drivers_license', 'identity_type' => 'license'],
            ['document_type' => "State ID card", 'document_type_name' => 'id_state', 'identity_type' => 'other'],
            ['document_type' => "US passport card", 'document_type_name' => 'id_passport_card', 'identity_type' => 'passport'],
            ['document_type' => "Utility bill", 'document_type_name' => 'doc_utility', 'identity_type' => 'utility'],
            ['document_type' => "Passport", 'document_type_name' => 'id_passport', 'identity_type' => 'passport'],
        ]);
    }
}
