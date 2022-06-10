<?php

namespace Database\Seeders;

use App\VerificationCategory;
use App\VerificationDocument;
use Illuminate\Database\Seeder;

class VerificationCategoryDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ssn = VerificationCategory::where(['name' => 'SSN'])->first();
        $ssn->documents()->attach(VerificationDocument::whereIn('document_type_name', ['id_drivers_license', 'id_state', 'id_passport_card'])->pluck('id')->toArray());

        $address = VerificationCategory::where(['name' => 'Address'])->first();
        $address->documents()->attach(VerificationDocument::whereIn('document_type_name', ['id_drivers_license', 'id_state', 'doc_utility'])->pluck('id')->toArray());

        $address = VerificationCategory::where(['name' => 'General Identity'])->first();
        $address->documents()->attach(VerificationDocument::whereIn('document_type_name', ['id_drivers_license', 'id_state', 'id_passport'])->pluck('id')->toArray());

        $birthdate = VerificationCategory::where(['name' => 'Date of birth'])->first();
        $birthdate->documents()->attach(VerificationDocument::whereIn('document_type_name', ['id_drivers_license', 'id_state', 'id_passport_card'])->pluck('id')->toArray());
    }
}
