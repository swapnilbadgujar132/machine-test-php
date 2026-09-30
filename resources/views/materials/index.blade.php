@extends('layouts.app')

@section('title', 'Materials')

@section('content')
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-800">Inventory / Overview</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight">Materials</h1>
            <p class="mt-2 max-w-2xl text-sm text-stone-600">Manage opening stock and monitor every quantity change.</p>
        </div>
            <div class="flex gap-2">
                <a href="{{ route('material-movements.create') }}" class="inline-flex min-h-10 items-center justify-center rounded border border-stone-300 bg-white px-4 py-2 text-sm font-semibold text-stone-800 hover:bg-stone-50">Adjust stock</a>
                <a href="{{ route('materials.create') }}" class="inline-flex min-h-10 items-center justify-center rounded bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Add material</a>
            </div>
    </div>

    <section class="mt-8 overflow-hidden rounded border border-stone-200 bg-white" aria-labelledby="material-list-heading">
        <div class="flex items-center justify-between border-b border-stone-200 px-5 py-4">
            <h2 id="material-list-heading" class="font-semibold">Material balance</h2>
            <span class="text-sm text-stone-500">{{ $materials->count() }} records</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] text-left text-sm">
                <thead class="bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="px-5 py-3 font-medium">Internal ID</th>
                        <th class="px-5 py-3 font-medium">Category</th>
                        <th class="px-5 py-3 font-medium">Material</th>
                        <th class="px-5 py-3 text-right font-medium">Opening balance</th>
                        <th class="px-5 py-3 text-right font-medium">Current balance</th>
                        <th class="px-5 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($materials as $material)
                        <tr>
                            <td class="px-5 py-4 font-mono text-xs text-stone-500">MAT-{{ str_pad((string) $material->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-5 py-4 text-stone-600">{{ $material->category->name }}</td>
                            <td class="px-5 py-4 font-medium">{{ $material->name }}</td>
                            <td class="px-5 py-4 text-right tabular-nums">{{ number_format((float) $material->opening_balance, 2) }}</td>
                            <td class="px-5 py-4 text-right font-semibold tabular-nums">{{ number_format($material->current_balance, 2) }}</td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('materials.edit', $material) }}" class="font-medium text-emerald-800 hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('materials.destroy', $material) }}" onsubmit="return confirm('Delete this material? Its movement history will be retained.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-700 hover:underline">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-stone-500">No materials have been added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection