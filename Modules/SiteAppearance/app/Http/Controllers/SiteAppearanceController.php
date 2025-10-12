<?php

namespace Modules\SiteAppearance\app\Http\Controllers;

use App\Enums\ThemeList;
use App\Enums\RedirectType;
use Illuminate\Http\Request;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Modules\GlobalSetting\app\Models\Setting;

class SiteAppearanceController extends Controller {
    use RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('appearance.management');
        $themes = ThemeList::themes();
        return view('siteappearance::index',compact('themes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) {
        checkAdminHasPermissionAndThrowException('appearance.management');

        $request->validate([
            'theme' => ['required', 'in:' . implode(',', array_column(ThemeList::cases(), 'value'))],
        ], [
            'theme.required' => __('Theme is required'),
        ]);
        Setting::where('key', 'site_theme')->update(['value' => $request?->theme]);

        Session::forget('demo_theme');
        cache()->forget('setting');

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }
    public function showAllHomePage(Request $request) {
        checkAdminHasPermissionAndThrowException('appearance.management');

        Setting::where('key', 'show_all_homepage')->update(['value' => $request->show_all_homepage]);
        cache()->forget('setting');

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }
}
