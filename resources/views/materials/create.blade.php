@extends('layouts.app')

@section('title', 'Add material')

@section('content')
    <div class="mb-7">
        <p class="text-sm font-medium text-emerald-800">Inventory / Materials</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight">Add material</h1>
        <p class="mt-2 text-sm text-stone-600">A unique internal ID is assigned when you save.</p>
    </div>
    @include('materials._form')
@endsection