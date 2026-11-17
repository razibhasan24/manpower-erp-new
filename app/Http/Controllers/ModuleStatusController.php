<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Clearly identified development-status page for a planned module.
 *
 * This controller exists so the sidebar never contains a dead link and an
 * unfinished module is never presented as a working business feature. It
 * renders no CRUD forms and no action buttons. Permission is checked here
 * on the server, not only hidden in the menu.
 */
class ModuleStatusController extends Controller
{
    public function show(string $module): View|RedirectResponse
    {
        $definition = Menu::module($module);

        abort_if($definition === null, 404);

        $user = auth()->user();

        abort_if($user === null, 403);

        foreach ((array) ($definition['permission'] ?? []) as $permission) {
            abort_unless($user->can($permission), 403, 'You are not authorised to view this module.');
        }

        $workingModules = [
            'medical-centres' => 'medical-centres.index',
            'training-centres' => 'training-centres.index',
            'training-courses' => 'training-courses.index',
        ];

        if (isset($workingModules[$module])) {
            return redirect()->route($workingModules[$module]);
        }

        return view('modules.status', [
            'moduleKey' => $module,
            'module' => $definition,
        ]);
    }
}
