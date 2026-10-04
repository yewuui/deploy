@extends('layouts.app')

@php
    $seoTitle = 'BLUD';
    $seoDescription = 'Unit produksi dan jasa yang dikelola '.\App\Support\Site::name().' sebagai ladang pengalaman kewirausahaan bagi siswa.';
@endphp

@section('content')
    <x-page-title text="BLUD"
                  description="Unit produksi dan jasa yang dikelola {{ \App\Support\Site::name() }} sebagai ladang pengalaman kewirausahaan bagi siswa." />

    <div class="container-page max-w-5xl py-10 lg:py-14">
        <x-breadcrumbs :breadcrumbs="[['label' => 'Layanan'], ['label' => 'BLUD']]" />

        <div class="mt-10">
            <h2 class="font-display text-xl font-bold tracking-tight text-slate-900 sm:text-2xl" data-reveal="up">
                Unit Produksi &amp; Jasa
            </h2>
            <p class="mt-2 text-sm text-slate-600">
                Total {{ $units->count() }} unit produksi dan barang jasa yang dikelola sekolah.
            </p>

            @if ($units->isEmpty())
                <p class="py-20 text-center text-slate-500">Belum ada data unit produksi.</p>
            @else
                <ul class="mt-8 columns-1 gap-x-10 sm:columns-2 lg:columns-3">
                    @foreach ($units as $unit)
                        <li class="mb-3 break-inside-avoid border-b border-slate-100 pb-3 text-[15px] text-slate-700"
                            data-reveal="up" data-reveal-delay="{{ ($loop->index % 9) * 50 }}">
                            {{ $unit->name }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection