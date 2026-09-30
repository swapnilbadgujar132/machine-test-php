<?php

namespace App\Http\Controllers;

use App\Models\MaterialCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaterialCategoryController extends Controller
{
    public function index(): View
    {
        $categories = MaterialCategory::query()
            ->withCount('materials')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('material_categories', 'name')->whereNull('deleted_at')],
        ]);

        MaterialCategory::create($validated);

        return redirect()->route('material-categories.index')->with('status', 'Category created.');
    }

    public function edit(MaterialCategory $material_category): View
    {
        return view('categories.edit', ['category' => $material_category]);
    }

    public function update(Request $request, MaterialCategory $material_category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('material_categories', 'name')->whereNull('deleted_at')->ignore($material_category->id),
            ],
        ]);

        $material_category->update($validated);

        return redirect()->route('material-categories.index')->with('status', 'Category updated.');
    }

    public function destroy(MaterialCategory $material_category): RedirectResponse
    {
        if ($material_category->materials()->exists()) {
            return back()->with('error', 'A category with materials cannot be deleted. Move or delete its materials first.');
        }

        $material_category->delete();

        return redirect()->route('material-categories.index')->with('status', 'Category deleted.');
    }
}
