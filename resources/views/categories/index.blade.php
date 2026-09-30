@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-800">Inventory / Setup</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight">Categories</h1>
            <p class="mt-2 text-sm text-stone-600">Organize materials into stock categories.</p>
        </div>
        <a href="{{ route('material-categories.create') }}" class="inline-flex min-h-10 items-center justify-center rounded bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Add category</a>
    </div>

    <section class="mt-8 overflow-hidden rounded border border-stone-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[520px] text-left text-sm">
                <thead class="bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="px-5 py-3 font-medium">Category</th>
                        <th class="px-5 py-3 font-medium">Active materials</th>
                        <th class="px-5 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-5 py-4 font-medium">{{ $category->name }}</td>
                            <td class="px-5 py-4 text-stone-600">{{ $category->materials_count }}</td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('material-categories.edit', $category) }}" class="font-medium text-emerald-800 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('material-categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-700 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-12 text-center text-stone-500">No categories have been created.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection