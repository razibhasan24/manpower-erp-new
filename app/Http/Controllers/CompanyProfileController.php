<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyProfileRequest;
use App\Models\CompanyProfile;
use App\Services\BrandingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanyProfileController extends Controller
{
    public function edit(): View
    {
        $profile = BrandingService::profile() ?? new CompanyProfile();
        $this->authorize('view', $profile);

        return view('settings.company-profile', compact('profile'));
    }

    public function update(CompanyProfileRequest $request): RedirectResponse
    {
        $profile = BrandingService::profile() ?? new CompanyProfile();
        $this->authorize('update', $profile);

        $data = $request->validated();

        foreach (['logo' => 'logo_path', 'favicon' => 'favicon_path'] as $field => $column) {
            if ($request->hasFile($field)) {
                if ($profile->{$column}) {
                    Storage::disk('private')->delete($profile->{$column});
                }
                $data[$column] = $request->file($field)->store('branding', 'private');
            }
            unset($data[$field]);
        }

        $data['is_active'] = true;
        $profile->fill($data);
        $profile->save();

        BrandingService::clearCache();

        return redirect()->route('company-profile.edit')->with('success', __('Company profile updated.'));
    }
}
