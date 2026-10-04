@extends('layouts.admin-eduroute', ['title' => 'Kelola EduRoute', 'description' => 'Pilih jurusan yang akan dikelola. Setiap jurusan memiliki kontennya masing-masing.'])

@section('eduroute-content')
    @if($programs->isEmpty())
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-8 text-center">
            <p class="text-sm font-semibold text-gray-700">Belum ada jurusan.</p>
            <p class="mt-1 text-sm text-gray-500">Tambahkan data program keahlian melalui seeder atau database.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($programs as $program)
                <article class="bg-white shadow-sm rounded-lg border border-gray-200 p-5 flex flex-col">
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-900">
                            {{ $program->short_name }}
                        </span>
                        @if($program->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700">Aktif</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-500">Nonaktif</span>
                        @endif
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 leading-snug">{{ $program->name }}</h3>
                    <p class="mt-1 text-xs text-gray-500">{{ $program->curriculums_count }} materi &middot; {{ $program->teachers_count }} guru</p>
                    <a href="{{ route('admin.eduroute.show', $program) }}" class="mt-4 inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">
                        Kelola
                    </a>
                </article>
            @endforeach
        </div>
    @endif
@endsection
