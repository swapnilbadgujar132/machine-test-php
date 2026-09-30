@extends('layouts.app')

@section('title', 'Edit material')

@section('content')
    <div class="mb-7">
        <p class="text-sm font-medium text-emerald-800">Inventory / Materials / Edit</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight">Edit {{ $material->name }}</h1>
        <p class="mt-2 text-sm text-stone-600">Internal ID MAT-{{ str_pad((string) $material->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>
    @include('materials._form', ['material' => $material])
@endsection