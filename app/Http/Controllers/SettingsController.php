<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsUpdateRequest;
use App\Models\Setting;
use App\Services\BrandingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Setting::class);

        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');

        return view('settings.index', compact('settings'));
    }

    public function update(SettingsUpdateRequest $request): RedirectResponse
    {
        $this->authorize('update', Setting::class);

        foreach ($request->validated() as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => (string) $value, 'group' => 'general']);
        }

        BrandingService::clearCache();

        return redirect()->route('settings.index')->with('success', __('Settings updated.'));
    }
}
