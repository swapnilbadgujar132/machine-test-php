<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\MaterialMovement;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaterialMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $movements = MaterialMovement::query()
            ->with([
                'material' => fn ($query) => $query->withTrashed()
                    ->with(['category' => fn ($categoryQuery) => $categoryQuery->withTrashed()]),
            ])
            ->latest('movement_date')
            ->latest('id')
            ->get();

        return view('movements.index', compact('movements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = MaterialCategory::query()
            ->with(['materials' => fn ($query) => $query->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('movements.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:material_categories,id,deleted_at,NULL'],
            'material_id' => [
                'required',
                'integer',
                Rule::exists('materials', 'id')->where('category_id', $request->input('category_id'))->whereNull('deleted_at'),
            ],
            'movement_date' => ['required', 'date'],
            'quantity' => ['required', 'numeric', 'decimal:0,2', 'not_in:0'],
        ]);

        Material::findOrFail($validated['material_id'])->movements()->create([
            'movement_date' => $validated['movement_date'],
            'quantity' => $validated['quantity'],
        ]);

        return redirect()->route('material-movements.index')->with('status', 'Stock movement recorded.');
    }
}
