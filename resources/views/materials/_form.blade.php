<form method="POST" action="{{ isset($material) ? route('materials.update', $material) : route('materials.store') }}" class="max-w-3xl rounded border border-stone-200 bg-white p-5 sm:p-7">
    @csrf
    @if (isset($material))
        @method('PUT')
    @endif
    <div class="grid gap-5 sm:grid-cols-2">
        <label class="grid gap-2 text-sm font-medium text-stone-700 sm:col-span-2">
            Material category <span class="text-red-700">*</span>
            <select name="category_id" required class="min-h-11 rounded border border-stone-300 bg-white px-3 font-normal text-stone-900 focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20">
                <option value="">Select a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('category_id', $material->category_id ?? '') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="grid gap-2 text-sm font-medium text-stone-700 sm:col-span-2">
            Material name <span class="text-red-700">*</span>
            <input type="text" name="name" value="{{ old('name', $material->name ?? '') }}" required maxlength="150" pattern="[A-Za-z0-9À-ÿ][A-Za-z0-9À-ÿ ._-]*" class="min-h-11 rounded border border-stone-300 px-3 font-normal text-stone-900 focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20" placeholder="e.g. Portland Cement">
        </label>
        <label class="grid gap-2 text-sm font-medium text-stone-700">
            Opening balance <span class="text-red-700">*</span>
            <input type="number" name="opening_balance" value="{{ old('opening_balance', $material->opening_balance ?? '') }}" required min="0" step="0.01" inputmode="decimal" class="min-h-11 rounded border border-stone-300 px-3 font-normal text-stone-900 focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20" placeholder="0.00">
            <span class="text-xs font-normal text-stone-500">Enter a non-negative quantity, up to two decimal places.</span>
        </label>
    </div>
    <div class="mt-7 flex flex-wrap gap-3">
        <button type="submit" class="min-h-10 rounded bg-emerald-800 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-900">{{ isset($material) ? 'Save changes' : 'Save material' }}</button>
        <a href="{{ route('materials.index') }}" class="inline-flex min-h-10 items-center rounded border border-stone-300 px-5 py-2 text-sm font-semibold text-stone-700 hover:bg-stone-50">Cancel</a>
    </div>
</form>