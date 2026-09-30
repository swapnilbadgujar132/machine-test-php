@extends('layouts.app')

@section('title', 'Record movement')

@section('content')
    <div class="mb-7">
        <p class="text-sm font-medium text-emerald-800">Inventory / Stock movements</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight">Record stock movement</h1>
        <p class="mt-2 text-sm text-stone-600">Positive quantity adds stock; negative quantity removes stock.</p>
    </div>
    <form method="POST" action="{{ route('material-movements.store') }}" class="max-w-3xl rounded border border-stone-200 bg-white p-5 sm:p-7">
        @csrf
        <div class="grid gap-5 sm:grid-cols-2">
            <label class="grid gap-2 text-sm font-medium text-stone-700">
                Material category <span class="text-red-700">*</span>
                <select id="category_id" name="category_id" required class="min-h-11 rounded border border-stone-300 bg-white px-3 font-normal text-stone-900 focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20">
                    <option value="">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="grid gap-2 text-sm font-medium text-stone-700">
                Material <span class="text-red-700">*</span>
                <select id="material_id" name="material_id" required disabled class="min-h-11 rounded border border-stone-300 bg-white px-3 font-normal text-stone-900 focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20 disabled:bg-stone-100">
                    <option value="">Select a category first</option>
                    @foreach ($categories as $category)
                        @foreach ($category->materials as $material)
                            <option value="{{ $material->id }}" data-category="{{ $category->id }}" @selected((string) old('material_id') === (string) $material->id)>{{ $material->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </label>
            <label class="grid gap-2 text-sm font-medium text-stone-700">
                Movement date <span class="text-red-700">*</span>
                <input type="date" name="movement_date" value="{{ old('movement_date', now()->toDateString()) }}" required class="min-h-11 rounded border border-stone-300 px-3 font-normal text-stone-900 focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20">
            </label>
            <label class="grid gap-2 text-sm font-medium text-stone-700">
                Quantity change <span class="text-red-700">*</span>
                <input type="number" name="quantity" value="{{ old('quantity') }}" required step="0.01" inputmode="decimal" class="min-h-11 rounded border border-stone-300 px-3 font-normal text-stone-900 focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20" placeholder="e.g. 12.50 or -3.00">
                <span class="text-xs font-normal text-stone-500">Use a minus sign for an outward movement. Zero is not allowed.</span>
            </label>
        </div>
        <div class="mt-7 flex flex-wrap gap-3">
            <button type="submit" class="min-h-10 rounded bg-emerald-800 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Save movement</button>
            <a href="{{ route('material-movements.index') }}" class="inline-flex min-h-10 items-center rounded border border-stone-300 px-5 py-2 text-sm font-semibold text-stone-700 hover:bg-stone-50">Cancel</a>
        </div>
    </form>
    <script>
        const categorySelect = document.getElementById('category_id');
        const materialSelect = document.getElementById('material_id');
        const previousMaterial = @json(old('material_id'));

        function filterMaterials() {
            const categoryId = categorySelect.value;
            let selectedAvailable = false;

            for (const option of materialSelect.options) {
                if (!option.dataset.category) {
                    continue;
                }

                const matchesCategory = option.dataset.category === categoryId;
                option.hidden = !matchesCategory;
                option.disabled = !matchesCategory;
                if (matchesCategory && option.value === String(previousMaterial)) {
                    selectedAvailable = true;
                }
            }

            materialSelect.disabled = categoryId === '';
            if (!selectedAvailable) {
                materialSelect.value = '';
            }
        }

        categorySelect.addEventListener('change', filterMaterials);
        filterMaterials();
    </script>
@endsection