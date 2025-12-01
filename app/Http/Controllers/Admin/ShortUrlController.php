<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShortUrl;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shortUrls = ShortUrl::with('creator')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.short-urls.index', compact('shortUrls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.short-urls.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'original_url' => 'required|url',
            'code' => 'nullable|string|max:50|unique:short_urls,code',
            'code_type' => 'required|in:random,custom', // random or custom
        ]);

        // Generate code if random
        if ($validated['code_type'] === 'random') {
            $validated['code'] = ShortUrl::generateUniqueCode();
        } else {
            // Use custom code (ensure uniqueness already validated)
            $validated['code'] = strtolower(str_replace(' ', '-', $validated['code']));
        }

        $validated['created_by'] = auth()->guard('admin')->id();

        $shortUrl = ShortUrl::create($validated);

        return redirect()->route('admin.short-urls.index')
            ->with('success', __('Short URL created successfully: ') . $shortUrl->short_url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing a short URL.
     */
    public function edit(ShortUrl $shortUrl)
    {
        return view('admin.short-urls.edit', compact('shortUrl'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShortUrl $shortUrl)
    {
        $validated = $request->validate([
            'original_url' => 'required|url',
        ]);

        $shortUrl->update($validated);

        return redirect()->route('admin.short-urls.index')
            ->with('success', __('Short URL updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShortUrl $shortUrl)
    {
        $shortUrl->delete();

        return redirect()->route('admin.short-urls.index')
            ->with('success', __('Short URL deleted successfully'));
    }
}
