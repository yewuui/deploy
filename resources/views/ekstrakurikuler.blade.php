@extends('layouts.app')

@php
    $seoTitle = 'Ekstrakurikuler';
    $seoDescription = 'Daftar kegiatan ekstrakurikuler di '.\App\Support\Site::name().' untuk mengembangkan bakat dan minat siswa.';
@endphp

@section('content')
    <x-page-title text="Ekstrakurikuler"
                  :description="'Wadah pengembangan bakat, minat, dan karakter siswa di '.\App\Support\Site::name().'.'" />

    <div class="container-page max-w-5xl space-y-10 py-10 lg:py-14">
        <x-breadcrumbs :breadcrumbs="[['label' => 'Ekstrakurikuler']]" />

        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-parent>
            @foreach ($items as $item)
                <x-item-card :name="$item->name" :image-url="$item->image_url" />
            @endforeach
        </div>
    </div>
@endsection