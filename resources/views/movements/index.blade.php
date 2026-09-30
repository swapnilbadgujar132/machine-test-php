@extends('layouts.app')

@section('title', 'Stock movements')

@section('content')
    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-800">Inventory / Ledger</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight">Stock movements</h1>
            <p class="mt-2 text-sm text-stone-600">Every adjustment is recorded against a material and date.</p>
        </div>
        <a href="{{ route('material-movements.create') }}" class="inline-flex min-h-10 items-center justify-center rounded bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">Record movement</a>
    </div>

    <section class="mt-8 overflow-hidden rounded border border-stone-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                    <tr>
                        <th class="px-5 py-3 font-medium">Movement ID</th>
                        <th class="px-5 py-3 font-medium">Date</th>
                        <th class="px-5 py-3 font-medium">Category</th>
                        <th class="px-5 py-3 font-medium">Material</th>
                        <th class="px-5 py-3 text-right font-medium">Quantity change</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($movements as $movement)
                        <tr>
                            <td class="px-5 py-4 font-mono text-xs text-stone-500">MOV-{{ str_pad((string) $movement->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-5 py-4 text-stone-600">{{ $movement->movement_date->format('d M Y') }}</td>
                            <td class="px-5 py-4 text-stone-600">{{ $movement->material->category->name }}</td>
                            <td class="px-5 py-4 font-medium">{{ $movement->material->name }}</td>
                            <td class="px-5 py-4 text-right font-semibold tabular-nums {{ (float) $movement->quantity < 0 ? 'text-red-700' : 'text-emerald-800' }}">{{ (float) $movement->quantity > 0 ? '+' : '' }}{{ number_format((float) $movement->quantity, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-12 text-center text-stone-500">No stock movements have been recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection