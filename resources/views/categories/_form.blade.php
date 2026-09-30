<form method="POST" action="{{ isset($category) ? route('material-categories.update', $category) : route('material-categories.store') }}" class="max-w-2xl rounded border border-stone-200 bg-white p-5 sm:p-7">
    @csrf
    @if (isset($category))
        @method('PUT')
    @endif
    <label class="grid gap-2 text-sm font-medium text-stone-700">
        Category name <span class="text-red-700">*</span>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required maxlength="100" class="min-h-11 rounded border border-stone-300 px-3 font-normal text-stone-900 focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20" placeholder="e.g. Raw material">
    </label>
    <div class="mt-7 flex flex-wrap gap-3">
        <button type="submit" class="min-h-10 rounded bg-emerald-800 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-900">{{ isset($category) ? 'Save changes' : 'Save category' }}</button>
        <a href="{{ route('material-categories.index') }}" class="inline-flex min-h-10 items-center rounded border border-stone-300 px-5 py-2 text-sm font-semibold text-stone-700 hover:bg-stone-50">Cancel</a>
    </div>
</form>