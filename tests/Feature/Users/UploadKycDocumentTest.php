<?php

namespace Tests\Feature\Users;

use App\VerificationDocument;
use Domain\Users\Actions\UploadSilaKycDocsAction;
use Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Tests\TestCase;

class UploadKycDocumentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    function a_user_can_upload_document_for_kyc_verification()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->newUser()->create();
        $documentType = VerificationDocument::factory()->create();

        $this->mock(UploadSilaKycDocsAction::class, function (MockInterface $mock) use ($user) {
            $mock->shouldReceive('__invoke')
                ->once()
                ->andReturn($user);
        });

        Sanctum::actingAs($user);

        $updateDetails = [
            'document' => UploadedFile::fake()->image('avatar.jpg'),
            'document_type_id' => $documentType->id,
            'side' => 'front'
        ];

        $response = $this->postJson("/api/users/$user->id/kyc-docs", $updateDetails);

        $response->assertStatus(200);
    }
}
