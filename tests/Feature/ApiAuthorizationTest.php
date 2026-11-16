<?php

// ---------------------------------------------------------------------------
// NOT EXECUTED HERE: this workspace has no PHP / Composer / MySQL, so this
// test file was written but NOT run. Run on the XAMPP machine with:
//   php artisan test --filter=ApiAuthorizationTest
// Do not claim it passes until that run succeeds.
// ---------------------------------------------------------------------------

namespace Tests\Feature;

use App\Models\Recruitment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * API authorisation (Phase 01 §11, §13 item 20): every API endpoint
 * enforces the same Policies as the web UI. Unauthenticated requests
 * get 401, unauthorised ones get 403, and error bodies are JSON —
 * never HTML.
 */
class ApiAuthorizationTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    public function test_unauthenticated_api_requests_return_401_json(): void
    {
        $response = $this->getJson('/api/v1/recruitments');

        $response->assertUnauthorized()
            ->assertJsonStructure(['message']);
        $this->assertStringNotContainsString('<html', strtolower($response->getContent()));
    }

    public function test_api_errors_are_json_never_html(): void
    {
        // An existing recruitment the user has no permission to see:
        // authorisation (not route-model binding) must produce the 403,
        // and the body must be JSON, never an HTML error page.
        $branch = $this->makeBranch('BR-API2');
        $country = $this->makeCountry('AP2');
        $agent = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);
        $holder = $this->makePassportHolder($this->makeUser('Passport Holder'), $agent, $branch, $country);
        $recruitment = Recruitment::create([
            'tracking_number' => '2026-BR-API2-000001',
            'passport_holder_id' => $holder->id,
            'agent_id' => $agent->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'current_stage' => 'registration',
            'current_status' => 'pending',
        ]);

        $user = $this->makeUser('General Staff');

        $response = $this->actingAs($user)->getJson('/api/v1/recruitments/'.$recruitment->id);

        $response->assertStatus(403);
        $this->assertStringNotContainsString('<html', strtolower($response->getContent()));
    }

    public function test_api_list_responses_are_paginated(): void
    {
        $user = $this->makeSuperAdmin();

        $response = $this->actingAs($user)->getJson('/api/v1/countries');

        $response->assertOk()
            ->assertJsonStructure(['data', 'meta' => ['current_page', 'per_page', 'total', 'last_page']]);
    }

    public function test_api_login_is_rate_limited(): void
    {
        $user = $this->makeUser('General Staff', ['password' => \Illuminate\Support\Facades\Hash::make('secret123')]);

        for ($i = 0; $i < 6; $i++) {
            $this->postJson('/api/v1/login', ['email' => $user->email, 'password' => 'wrong', 'device_name' => 'test-device']);
        }

        $this->postJson('/api/v1/login', ['email' => $user->email, 'password' => 'wrong', 'device_name' => 'test-device'])
            ->assertStatus(429);
    }

    public function test_revoked_token_cannot_access_api(): void
    {
        $user = $this->makeSuperAdmin();
        $token = $user->createToken('test', ['*'], now()->addDay())->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/me')
            ->assertOk();

        $user->tokens()->delete();

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/me')
            ->assertUnauthorized();
    }

    public function test_agent_cannot_transition_recruitment_they_do_not_own(): void
    {
        $branch = $this->makeBranch('BR-API');
        $country = $this->makeCountry('APX');
        $agentA = $this->makeAgent($this->makeUser('Agent'), $branch, [$country]);
        $agentBUser = $this->makeUser('Agent', [], ['recruitment.edit']);
        $this->makeAgent($agentBUser, $branch, [$country]);
        $holder = $this->makePassportHolder($this->makeUser('Passport Holder'), $agentA, $branch, $country);

        $recruitment = Recruitment::create([
            'tracking_number' => '2026-BR-API-000001',
            'passport_holder_id' => $holder->id,
            'agent_id' => $agentA->id,
            'branch_id' => $branch->id,
            'country_id' => $country->id,
            'current_stage' => 'registration',
            'current_status' => 'pending',
        ]);

        $this->actingAs($agentBUser)->postJson('/api/v1/recruitments/'.$recruitment->id.'/transition', [
            'to_stage' => 'selection',
            'to_status' => 'approved',
        ])->assertForbidden();
    }
}
