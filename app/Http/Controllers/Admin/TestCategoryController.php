<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TestCategoryController extends Controller
{
    public function index()
    {
        $categories = TestCategory::withCount('testPackages')->paginate(10);
        return view('admin.test-category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.test-category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        TestCategory::create($request->all());

        return redirect()->route('admin.test-category.index')
            ->with('success', 'Test category created successfully.');
    }

    public function show(TestCategory $testCategory)
    {
        $testCategory->load('testPackages');
        return view('admin.test-category.show', compact('testCategory'));
    }

    public function edit(TestCategory $testCategory)
    {
        return view('admin.test-category.edit', compact('testCategory'));
    }

    public function update(Request $request, TestCategory $testCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $testCategory->update($request->all());

        return redirect()->route('admin.test-category.index')
            ->with('success', 'Test category updated successfully.');
    }

    public function destroy(TestCategory $testCategory)
    {
        $testCategory->delete();

        return redirect()->route('admin.test-category.index')
            ->with('success', 'Test category deleted successfully.');
    }
}