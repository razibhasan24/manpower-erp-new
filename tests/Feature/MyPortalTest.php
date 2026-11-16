<?php

namespace Tests\Feature;

use App\Models\AgentDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

class MyPortalTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected array $portalPermissions = ['dashboard.view', 'ledger.view', 'document.view', 'document.download'];

    public function test_agent_can_open_own_ledger_payments_and_documents(): void
    {
        $user = $this->makeUser('Agent', [], $this->portalPermissions);
        $branch = $this->makeBranch();
        $this->makeAgent($user, $branch);

        $this->actingAs($user)->get('/my/ledger')->assertOk()->assertSee('My Ledger');
        $this->actingAs($user)->get('/my/payments')->assertOk()->assertSee('My Payments');
        $this->actingAs($user)->get('/my/documents')->assertOk()->assertSee('My Documents');
    }

    public function test_passport_holder_can_open_own_portal(): void
    {
        $user = $this->makeUser('Passport Holder', [], $this->portalPermissions);
        $this->makePassportHolder($user);

        $this->actingAs($user)->get('/my/ledger')->assertOk();
        $this->actingAs($user)->get('/my/payments')->assertOk();
        $this->actingAs($user)->get('/my/documents')->assertOk();
    }

    public function test_staff_without_ledger_permission_cannot_open_the_agent_portal(): void
    {
        $staff = $this->makeUser('General Staff', [], ['dashboard.view']);

        $this->actingAs($staff)->get('/my/ledger')->assertForbidden();
    }

    public function test_agent_cannot_download_another_agents_document(): void
    {
        $agentA = $this->makeUser('Agent', [], $this->portalPermissions);
        $agentB = $this->makeUser('Agent', [], $this->portalPermissions);
        $this->makeAgent($agentA);
        $agentBRecord = $this->makeAgent($agentB);

        $document = AgentDocument::create([
            'agent_id' => $agentBRecord->id,
            'type' => 'trade_license',
            'file_path' => 'agents/secret.pdf',
            'original_name' => 'secret.pdf',
        ]);

        $this->actingAs($agentA)
            ->get("/my/documents/{$document->id}/download")
            ->assertNotFound();
    }
}
