@extends('layouts.admin-eduroute', ['program' => $program, 'title' => 'Ringkasan Jurusan', 'description' => 'Kelola konten ' . $program->name . '. Semua perubahan di halaman ini hanya berlaku untuk jurusan ini.'])

@section('eduroute-content')
    @php
        $modules = [
            ['name' => 'Kurikulum', 'description' => 'Roadmap pembelajaran per kelas dan semester.', 'count' => $program->curriculums_count . ' materi', 'url' => route('admin.eduroute.curriculum.index', $program), 'action' => 'Kelola'],
            ['name' => 'Guru', 'description' => 'Guru, bidang, dan foto pengampu jurusan.', 'count' => $program->teachers_count . ' guru', 'url' => route('admin.eduroute.teachers.index', $program), 'action' => 'Kelola'],
            ['name' => 'Prospek Karier', 'description' => 'Bidang karier lulusan beserta detailnya.', 'count' => $program->careers_count . ' karier', 'url' => route('admin.eduroute.careers.index', $program), 'action' => 'Kelola'],
            ['name' => 'Prestasi', 'description' => 'Pencapaian siswa dan jurusan.', 'count' => $program->achievements_count . ' prestasi', 'url' => route('admin.eduroute.achievements.index', $program), 'action' => 'Kelola'],
            ['name' => 'Media', 'description' => 'Kanal media sosial dan dokumentasi.', 'count' => $program->media_count . ' kanal', 'url' => route('admin.eduroute.media.index', $program), 'action' => 'Kelola'],
            ['name' => 'Mitra Industri', 'description' => 'Perusahaan dan institusi mitra.', 'count' => $program->partners_count . ' mitra', 'url' => route('admin.eduroute.partners.index', $program), 'action' => 'Kelola'],
            ['name' => 'Pengaturan Jurusan', 'description' => 'Nama, deskripsi, logo, dan status jurusan.', 'count' => $program->is_active ? 'Aktif' : 'Nonaktif', 'url' => route('admin.eduroute.settings.edit', $program), 'action' => 'Ubah'],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($modules as $module)
            <article class="bg-white shadow-sm rounded-lg border border-gray-200 p-5 flex flex-col">
                <h3 class="text-sm font-bold text-gray-900">{{ $module['name'] }}</h3>
                <p class="mt-1 text-sm text-gray-500 flex-1">{{ $module['description'] }}</p>
                <p class="mt-3 text-xs font-semibold text-gray-500">{{ $module['count'] }}</p>
                <a href="{{ $module['url'] }}" class="mt-3 inline-flex items-center justify-center px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                    {{ $module['action'] }}
                </a>
            </article>
        @endforeach
    </div>
@endsection
