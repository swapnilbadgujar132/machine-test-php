@extends('layouts.app')

@section('title', 'Edit category')

@section('content')
    <div class="mb-7">
        <p class="text-sm font-medium text-emerald-800">Inventory / Categories / Edit</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight">Edit category</h1>
    </div>
    @include('categories._form', ['category' => $category])
@endsection