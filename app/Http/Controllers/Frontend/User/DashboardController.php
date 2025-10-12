<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\GetGlobalInformationTrait;
use Modules\GlobalSetting\app\Models\CustomPagination;

class DashboardController extends Controller {
    use GetGlobalInformationTrait;
    
    public function index() {
        $user = User::with([
            'country'             => function ($query) {
                $query->select('id');
            },
            'country.translation' => function ($query) {
                $query->select('country_id', 'name');
            },
        ])->find(userAuth()->id);

        return view('frontend.profile.dashboard', compact('user'));
    }
    
}
