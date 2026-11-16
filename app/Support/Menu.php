<?php

declare(strict_types=1);

namespace App\Support;

use App\Services\ReportService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Sidebar menu and breadcrumb resolver.
 *
 * The menu is declared once in config/menu.php. This class filters it for
 * the signed-in user (permission + role), resolves the destination URL,
 * detects the active group/item, and derives breadcrumbs. It never grants
 * access by itself — every destination route keeps its own middleware.
 */
class Menu
{
    /** @return array<int, array<string, mixed>> */
    public static function groups(?object $user = null): array
    {
        $user ??= auth()->user();

        if ($user === null) {
            return [];
        }

        $groups = [];

        foreach ((array) config('menu.groups', []) as $group) {
            if (! self::matches($group, $user)) {
                continue;
            }

            $items = [];

            foreach ((array) ($group['items'] ?? []) as $item) {
                if (! self::matches($item, $user, $group)) {
                    continue;
                }

                if (! Route::has($item['route'])) {
                    // Never render a dead link. A missing route is caught by
                    // the navigation test suite during development.
                    continue;
                }

                $item['url'] = self::url($item);
                $item['label'] = __($item['label']);
                $item['active'] = false;
                $items[] = $item;
            }

            if ($items === []) {
                continue;
            }

            $anyActive = false;

            foreach ($items as $index => $item) {
                $active = self::isActive($item, $items);
                $items[$index]['active'] = $active;
                $anyActive = $anyActive || $active;
            }

            $groups[] = [
                'key' => (string) ($group['key'] ?? ''),
                'label' => __($group['label']),
                'icon' => (string) ($group['icon'] ?? 'circle-help'),
                'items' => $items,
                'active' => $anyActive,
                'open' => $anyActive || self::belongsToGroup($group),
            ];
        }

        return $groups;
    }

    /** Whether a menu node is allowed for the given user. */
    public static function matches(array $node, object $user, ?array $parent = null): bool
    {
        $roles = $node['roles'] ?? null;
        $exceptRoles = (array) ($node['except_roles'] ?? []);
        $parentRoles = $parent['roles'] ?? null;

        if ($exceptRoles !== [] && method_exists($user, 'hasAnyRole') && $user->hasAnyRole($exceptRoles)) {
            return false;
        }

        if ($roles !== null && ! (method_exists($user, 'hasAnyRole') && $user->hasAnyRole((array) $roles))) {
            return false;
        }

        if ($parentRoles !== null && ! (method_exists($user, 'hasAnyRole') && $user->hasAnyRole((array) $parentRoles))) {
            return false;
        }

        foreach ((array) ($node['permission'] ?? []) as $permission) {
            if ($permission !== '' && ! $user->can($permission)) {
                return false;
            }
        }

        return true;
    }

    /** Resolve a menu item to a URL, including real query filters and anchors. */
    public static function url(array $item): string
    {
        $params = array_merge((array) ($item['params'] ?? []), (array) ($item['query'] ?? []));

        $url = route($item['route'], $params);

        if (! empty($item['anchor'])) {
            $url .= '#'.$item['anchor'];
        }

        return $url;
    }

    /**
     * Active-state matching. Items with query filters win over the plain
     * "all records" item that points at the same route.
     */
    public static function isActive(array $item, array $siblings = []): bool
    {
        $patterns = (array) ($item['active'] ?? [$item['route']]);

        if (! request()->routeIs(...$patterns)) {
            return false;
        }

        if ($item['route'] === 'modules.show') {
            return (string) request()->route('module') === (string) ($item['params']['module'] ?? '');
        }

        foreach ((array) ($item['params'] ?? []) as $key => $value) {
            if ((string) request()->route($key) !== (string) $value) {
                return false;
            }
        }

        if (! empty($item['query'])) {
            return self::queryMatches((array) $item['query']);
        }

        foreach ($siblings as $sibling) {
            if (($sibling['route'] ?? null) === $item['route']
                && ! empty($sibling['query'])
                && self::queryMatches((array) $sibling['query'])) {
                return false;
            }
        }

        return true;
    }

    /** @param array<string, mixed> $query */
    public static function queryMatches(array $query): bool
    {
        foreach ($query as $key => $value) {
            if ((string) request()->query($key) !== (string) $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Group is considered "current" for detail pages (show/edit) whose route
     * prefix matches one of its real destinations. modules.show is skipped
     * because it is shared by every development-status page.
     */
    protected static function belongsToGroup(array $group): bool
    {
        $current = (string) (request()->route()?->getName() ?? '');

        if ($current === '') {
            return false;
        }

        $prefix = Str::before($current, '.');

        foreach ((array) ($group['items'] ?? []) as $item) {
            $route = (string) ($item['route'] ?? '');

            if ($route === '' || $route === 'modules.show') {
                continue;
            }

            if (Str::before($route, '.') === $prefix) {
                return true;
            }
        }

        return false;
    }

    /**
     * Breadcrumb trail for the current page: [label, url|null].
     *
     * @return array<int, array{label: string, url: string|null}>
     */
    public static function breadcrumb(?object $user = null): array
    {
        $route = (string) (request()->route()?->getName() ?? '');

        if ($route === '') {
            return [];
        }

        foreach (self::groups($user) as $group) {
            foreach ($group['items'] as $item) {
                if (! empty($item['active'])) {
                    if (($item['route'] ?? null) === 'dashboard') {
                        return [['label' => __('Dashboard'), 'url' => null]];
                    }

                    return [
                        ['label' => $group['label'], 'url' => null],
                        ['label' => $item['label'], 'url' => null],
                    ];
                }
            }
        }

        if ($route === 'reports.show') {
            try {
                $definition = ReportService::definition((string) request()->route('report'));

                return [
                    ['label' => __('Reports & Analytics'), 'url' => route('reports.index')],
                    ['label' => $definition['title'], 'url' => null],
                ];
            } catch (\Throwable) {
                // Fall through to the generic resolver.
            }
        }

        $prefix = Str::before($route, '.');

        foreach ((array) config('menu.groups', []) as $group) {
            foreach ((array) ($group['items'] ?? []) as $item) {
                if (($item['route'] ?? null) === 'modules.show') {
                    continue;
                }

                if (Str::before((string) ($item['route'] ?? ''), '.') === $prefix) {
                    return [['label' => __($group['label']), 'url' => null]];
                }
            }
        }

        $special = [
            'profile.edit' => __('My Profile'),
            'profile.update' => __('My Profile'),
            'profile.password' => __('My Profile'),
            'settings.index' => __('Settings'),
            'settings.update' => __('Settings'),
            'company-profile.edit' => __('Company Profile'),
            'company-profile.update' => __('Company Profile'),
            'backups.index' => __('Backup & Restore'),
            'backups.restore-form' => __('Backup & Restore'),
            'reports.index' => __('Reports & Analytics'),
            'reports.financial.trial-balance' => __('Trial Balance'),
            'reports.financial.general-ledger' => __('General Ledger'),
            'reports.financial.balance-sheet' => __('Balance Sheet'),
            'reports.financial.income-statement' => __('Income Statement'),
            'reports.financial.cash-book' => __('Cash Book'),
            'reports.financial.bank-book' => __('Bank Book'),
            'reports.financial.day-book' => __('Day Book'),
            'password.request' => __('Forgot Password'),
            'password.reset' => __('Reset Password'),
            'password.reset.success' => __('Password Updated'),
            'login' => __('Log in'),
        ];

        if (isset($special[$route])) {
            return [['label' => $special[$route], 'url' => null]];
        }

        return [['label' => Str::headline(str_replace('.', ' ', $route)), 'url' => null]];
    }

    /**
     * Planned-module metadata. Returns null for an unknown key.
     *
     * @return array<string, mixed>|null
     */
    public static function module(string $key): ?array
    {
        $module = config('modules.'.$key);

        return is_array($module) ? $module : null;
    }

    /**
     * Flat list used by tests and verification scripts.
     *
     * @return array<int, array{group: string, label: string, route: string}>
     */
    public static function flatten(?object $user = null): array
    {
        $rows = [];

        foreach (self::groups($user) as $group) {
            foreach ($group['items'] as $item) {
                $rows[] = [
                    'group' => $group['label'],
                    'label' => $item['label'],
                    'route' => $item['route'],
                ];
            }
        }

        return $rows;
    }
}
