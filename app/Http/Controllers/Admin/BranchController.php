<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Modules\Service\app\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        checkAdminHasPermissionAndThrowException('branch.view');
        
        $query = Branch::with(['service.translation']);

        // Filter by service
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name, address, city, province
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('address', 'like', '%' . $request->keyword . '%')
                  ->orWhere('city', 'like', '%' . $request->keyword . '%')
                  ->orWhere('province', 'like', '%' . $request->keyword . '%');
            });
        }

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';

        if ($request->filled('par-page')) {
            $branches = $request->get('par-page') == 'all' 
                ? $query->orderBy('order', 'asc')->orderBy('id', $orderBy)->get() 
                : $query->orderBy('order', 'asc')->orderBy('id', $orderBy)->paginate($request->get('par-page'))->withQueryString();
        } else {
            $branches = $query->orderBy('order', 'asc')->orderBy('id', $orderBy)->paginate(10)->withQueryString();
        }

        $services = Service::with('translation')->active()->get();

        return view('admin.branch.index', compact('branches', 'services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        checkAdminHasPermissionAndThrowException('branch.create');
        
        $services = Service::with('translation')->active()->get();
        
        return view('admin.branch.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('branch.create');

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'map' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'order' => 'integer|min:0'
        ]);

        Branch::create([
            'service_id' => $request->service_id,
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'map' => $request->map,
            'description' => $request->description,
            'status' => (bool) $request->status,
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.branch.index')->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch): View
    {
        checkAdminHasPermissionAndThrowException('branch.view');
        
        $branch->load(['service.translation']);
        
        return view('admin.branch.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch): View
    {
        checkAdminHasPermissionAndThrowException('branch.edit');
        
        $services = Service::with('translation')->active()->get();
        
        return view('admin.branch.edit', compact('branch', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('branch.edit');

        $request->validate([
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'map' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'order' => 'integer|min:0'
        ]);

        $branch->update([
            'service_id' => $request->service_id,
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'map' => $request->map,
            'description' => $request->description,
            'status' => (bool) $request->status,
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.branch.index')->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('branch.delete');
        
        $branch->delete();

        return redirect()->route('admin.branch.index')->with('success', 'Branch deleted successfully.');
    }

    /**
     * Update the status of the specified resource.
     */
    public function statusUpdate(Request $request, $id): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('branch.edit');
        
        $branch = Branch::findOrFail($id);
        $branch->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    /**
     * Get current wording settings from the first active branch.
     */
    public function getWording(): JsonResponse
    {
        checkAdminHasPermissionAndThrowException('branch.view');
        
        $branch = Branch::active()->first();
        
        return response()->json([
            'success' => true,
            'section_title' => $branch?->section_title ?? 'Our Store Locations',
            'section_description' => $branch?->section_description ?? 'Select a store to view its location and details'
        ]);
    }

    /**
     * Update wording settings for all branches.
     */
    public function updateWording(Request $request): JsonResponse
    {
        checkAdminHasPermissionAndThrowException('branch.edit');

        $request->validate([
            'section_title' => 'nullable|string|max:255',
            'section_description' => 'nullable|string',
        ]);

        // Update all active branches with the same wording
        Branch::active()->update([
            'section_title' => $request->section_title,
            'section_description' => $request->section_description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section wording updated successfully!'
        ]);
    }
}