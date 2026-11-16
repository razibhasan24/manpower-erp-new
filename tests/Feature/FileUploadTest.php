<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=FileUploadTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * File upload security (Phase 01 §13 items 9–11): MIME + extension +
 * size validated server-side, stored on the private disk, served only
 * through an authorised controller.
 *
 * The shipped contract (Group B, StorePassportHolderRequest): the photo
 * field is `photo`, must be an image (jpg/jpeg/png), max 2048 KB.
 * Uploads are stored on the `private` disk under
 * passport-holders/{id}/.
 */
class FileUploadTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected function payload(array $overrides = []): array
    {
        $branch = $this->makeBranch('BR-FU');
        $country = $this->makeCountry('FUP');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);

        return array_merge([
            'full_name' => 'Test Candidate',
            'phone' => '01700000000',
            'passport_number' => 'P'.uniqid(),
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'status' => 'active',
        ], $overrides);
    }

    public function test_executable_upload_is_rejected(): void
    {
        Storage::fake('private');
        $staff = $this->makeUser('General Staff', [], ['passport-holder.create']);

        $file = UploadedFile::fake()->create('malicious.php', 10, 'application/x-php');

        $this->actingAs($staff)->postJson('/api/v1/passport-holders', $this->payload(['photo' => $file]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('photo');
    }

    public function test_oversized_upload_is_rejected(): void
    {
        Storage::fake('private');
        $staff = $this->makeUser('General Staff', [], ['passport-holder.create']);

        // 3 MB image — above the 2048 KB limit in StorePassportHolderRequest.
        $file = UploadedFile::fake()->image('photo.jpg')->size(3072);

        $this->actingAs($staff)->postJson('/api/v1/passport-holders', $this->payload(['photo' => $file]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('photo');
    }

    public function test_valid_image_upload_is_stored_on_private_disk(): void
    {
        Storage::fake('private');
        $staff = $this->makeUser('General Staff', [], ['passport-holder.create']);

        $file = UploadedFile::fake()->image('photo.jpg', 800, 600)->size(500);

        $response = $this->actingAs($staff)->postJson('/api/v1/passport-holders', $this->payload(['photo' => $file]));

        $response->assertCreated();

        $holder = \App\Models\PassportHolder::find($response->json('data.id'));
        $this->assertNotNull($holder->photo_path);
        $this->assertStringStartsWith('passport-holders/'.$holder->id, $holder->photo_path);
        Storage::disk('private')->assertExists($holder->photo_path);
    }

    public function test_uploaded_file_is_not_reachable_via_public_url(): void
    {
        // No uploaded file may live under public/ (Phase 01 §14).
        $this->assertFileDoesNotExist(public_path('storage/passport-holders'));
        $this->assertDirectoryDoesNotExist(public_path('uploads'));
    }
}
