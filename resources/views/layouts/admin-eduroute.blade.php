@extends('layouts.admin')

@php
    $program = $program ?? null;
    $title = $title ?? 'Kelola EduRoute';
    $description = $description ?? null;
    $backUrl = $backUrl ?? null;
    $backLabel = $backLabel ?? 'Kembali';
@endphp

@section('content')
    <header class="mb-6">
        <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
            Kelola EduRoute
            @if ($program)
                <span aria-hidden="true">&mdash;</span>
                <span class="font-semibold text-slate-600">{{ $program->short_name }} &mdash; {{ $program->name }}</span>
            @endif
        </p>

        <h1 class="mt-1 font-display text-2xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>

        @if ($description)
            <p class="mt-1 max-w-2xl text-sm text-slate-500">{{ $description }}</p>
        @endif

        <div class="mt-4 flex flex-wrap items-center gap-2">
            @if ($program)
                <a href="{{ route('admin.eduroute.index') }}"
                   class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                    Ganti Program Keahlian
                </a>
            @endif

            @if ($backUrl)
                <a href="{{ $backUrl }}"
                   class="rounded-lg border border-slate-300 px-3.5 py-2 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                    &larr; {{ $backLabel }}
                </a>
            @endif

            @yield('action')
        </div>
    </header>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            <p class="font-semibold">Periksa kembali isian berikut:</p>
            <ul class="mt-1 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('eduroute-content')
@endsection