<?php

namespace Tests\Feature;

use App\Support\Menu;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;

/**
 * Navigation contract tests.
 *
 * These tests intentionally derive their expectations from config/menu.php
 * and config/modules.php, so adding a menu item without a real route or
 * permission fails loudly instead of becoming a dead sidebar link.
 */
class NavigationMenuTest extends TestCase
{
    use CreatesUsers, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_the_required_menu_is_complete_and_every_route_exists(): void
    {
        $admin = $this->makeUser('Super Admin');

        $groups = Menu::groups($admin);
        $items = array_merge(...array_map(fn (array $group) => $group['items'], $groups));

        // 22 required groups / 186 required items. The role-only "My Portal"
        // group is deliberately hidden from staff accounts.
        $this->assertCount(22, $groups);
        $this->assertCount(186, $items);

        foreach ($items as $item) {
            $this->assertTrue(
                Route::has($item['route']),
                "Menu item [{$item['label']}] points at missing route [{$item['route']}]."
            );
        }
    }

    public function test_every_development_status_page_is_referenced_and_defined(): void
    {
        $admin = $this->makeUser('Super Admin');
        $referenced = [];
        foreach (Menu::groups($admin) as $group) {
            foreach ($group['items'] as $item) {
                if ($item['route'] === 'modules.show') {
                    $referenced[] = $item['params']['module'];
                }
            }
        }

        $defined = array_keys((array) config('modules', []));

        sort($referenced);
        sort($defined);

        $this->assertSame(array_values(array_unique($defined)), array_values(array_unique($referenced)));
    }

    public function test_agent_gets_only_own_record_and_ledger_destinations(): void
    {
        $agent = $this->makeUser('Agent');

        $groups = collect(Menu::groups($agent))->keyBy('key');

        $this->assertTrue($groups->has('my-portal'));
        $this->assertFalse($groups->has('accounting'));
        $this->assertFalse($groups->has('access'));
        $this->assertFalse($groups->has('settings'));
        $this->assertFalse($groups->has('hrm'));

        $labels = collect($groups->get('my-portal')['items'])->pluck('label')->all();
        $this->assertContains('My Ledger', $labels);
        $this->assertContains('My Payments', $labels);
        $this->assertContains('My Documents', $labels);
        $this->assertContains('My Candidates', $labels);
        $this->assertNotContains('My Applications', $labels);

        $agentLabels = collect($groups->get('agents')['items'] ?? [])->pluck('label')->all();
        $this->assertNotContains('Add Agent', $agentLabels);
    }

    public function test_passport_holder_gets_only_own_destinations(): void
    {
        $holder = $this->makeUser('Passport Holder');

        $groups = collect(Menu::groups($holder))->keyBy('key');

        $this->assertTrue($groups->has('my-portal'));
        $this->assertFalse($groups->has('agents'));
        $this->assertFalse($groups->has('accounting'));

        $labels = collect($groups->get('my-portal')['items'])->pluck('label')->all();
        $this->assertContains('My Applications', $labels);
        $this->assertNotContains('My Candidates', $labels);
        $this->assertContains('My Ledger', $labels);
    }

    public function test_branch_manager_sees_branch_menu_but_not_add_branch(): void
    {
        $manager = $this->makeUser('Branch Manager');

        $groups = collect(Menu::groups($manager))->keyBy('key');

        $this->assertTrue($groups->has('branches'));

        $labels = collect($groups->get('branches')['items'])->pluck('label')->all();
        $this->assertContains('All Branches', $labels);
        $this->assertContains('Branch Reports', $labels);
        $this->assertNotContains('Add Branch', $labels);
    }

    public function test_dashboard_shell_renders_sidebar_header_and_full_menu(): void
    {
        $admin = $this->makeUser('Super Admin');

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertOk()
            ->assertSee('id="appSidebar"', false)
            ->assertSee('data-sidebar-toggle', false)
            ->assertSee('data-theme-toggle', false)
            ->assertSee('Recruitment Management')
            ->assertSee('Accounting & Finance')
            ->assertSee('User & Access Management')
            ->assertSee('Settings & Configuration')
            ->assertSee('Reports &amp; Analytics', false);
    }

    public function test_filtered_menu_item_is_highlighted_for_the_matching_query(): void
    {
        $admin = $this->makeUser('Super Admin');

        $response = $this->actingAs($admin)->get('/recruitments?current_status=pending');

        $response->assertOk()
            ->assertSee('Pending Registrations')
            ->assertSee('current_status=pending', false);
    }
}
