<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;

class ShortUrlRedirectController extends Controller
{
    /**
     * Redirect to the original URL based on short code
     */
    public function redirect($code)
    {
        $shortUrl = ShortUrl::where('code', $code)->firstOrFail();

        // Record the click
        $shortUrl->recordClick();

        return redirect($shortUrl->original_url);
    }
}
