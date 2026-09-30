<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Stockroom') | {{ config('app.name', 'Stockroom') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-stone-100 text-stone-900 antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[240px_1fr]">
        <aside class="bg-emerald-950 px-6 py-7 text-white">
            <a href="{{ route('materials.index') }}" class="text-xl font-semibold tracking-tight">Stockroom</a>
            <p class="mt-1 text-xs uppercase tracking-[0.18em] text-emerald-300">Materials control</p>
            <nav class="mt-10 flex gap-2 overflow-x-auto lg:flex-col">
                <a href="{{ route('materials.index') }}" class="whitespace-nowrap rounded px-3 py-2 text-sm hover:bg-emerald-900">Materials</a>
                <a href="{{ route('material-categories.index') }}" class="whitespace-nowrap rounded px-3 py-2 text-sm hover:bg-emerald-900">Categories</a>
                <a href="{{ route('material-movements.index') }}" class="whitespace-nowrap rounded px-3 py-2 text-sm hover:bg-emerald-900">Stock movements</a>
            </nav>
        </aside>
        <main class="min-w-0 px-5 py-8 sm:px-8 lg:px-12">
            @if (session('status'))
                <div class="mb-6 rounded border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900" role="status">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
                    <p class="font-semibold">Please check the submitted fields.</p>
                    <ul class="mt-2 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>