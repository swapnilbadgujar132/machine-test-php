<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $materials = Material::query()
            ->with('category')
            ->withSum('movements', 'quantity')
            ->latest()
            ->get();

        return view('materials.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = MaterialCategory::query()->orderBy('name')->get();

        return view('materials.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules($request));

        $material = Material::create($validated);

        return redirect()->route('materials.index')->with('status', 'Material created with ID MAT-'.str_pad((string) $material->id, 5, '0', STR_PAD_LEFT).'.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material): View
    {
        $categories = MaterialCategory::query()->orderBy('name')->get();

        return view('materials.edit', compact('material', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material): RedirectResponse
    {
        $validated = $request->validate($this->rules($request, $material));

        $material->update($validated);

        return redirect()->route('materials.index')->with('status', 'Material updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material): RedirectResponse
    {
        $material->delete();

        return redirect()->route('materials.index')->with('status', 'Material moved to deleted records.');
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rules(Request $request, ?Material $material = null): array
    {
        $uniqueName = Rule::unique('materials', 'name')
            ->where('category_id', $request->input('category_id'))
            ->whereNull('deleted_at');

        if ($material !== null) {
            $uniqueName->ignore($material->id);
        }

        return [
            'category_id' => ['required', 'integer', 'exists:material_categories,id,deleted_at,NULL'],
            'name' => ['required', 'string', 'max:150', 'regex:/^[\pL\pN][\pL\pN ._-]*$/u', $uniqueName],
            'opening_balance' => ['required', 'numeric', 'decimal:0,2', 'min:0'],
        ];
    }
}
