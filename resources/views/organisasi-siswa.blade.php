@extends('layouts.app')

@php
    $seoTitle = 'Organisasi Siswa';
    $seoDescription = 'Organisasi siswa di '.\App\Support\Site::name().' seperti OSIS, MPK, Rohis, PKS, PMR, dan lainnya.';
@endphp

@section('content')
    <x-page-title text="Organisasi Siswa"
                  :description="'Organisasi dan kepemimpinan siswa di '.\App\Support\Site::name().'.'" />

    <div class="container-page max-w-5xl space-y-10 py-10 lg:py-14">
        <x-breadcrumbs :breadcrumbs="[['label' => 'Organisasi Siswa']]" />

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-parent>
            @foreach ($items as $item)
                <x-item-card :name="$item->name" :image-url="$item->logo ? asset($item->logo) : null" />
            @endforeach
        </div>
    </div>
@endsection
