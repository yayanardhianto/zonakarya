<?php

namespace Modules\Installer\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Installer\app\Enums\InstallerInfo;

class PuchaseVerificationController extends Controller
{
    public function __construct()
    {
        set_time_limit(8000000);
    }

    public function index()
    {
        // InstallerInfo::writeAssetUrl();

        return view('installer::index');
    }

    /**
     * @param Request $request
     */
    public function validatePurchase(Request $request)
    {
        session()->flush();

            session()->put('step-1-complete', true);

            if (true) {
                return response()->json(['success' => true, 'message' => 'Done. Nulled by codingshop.org'], 200);
            }



    }
}
