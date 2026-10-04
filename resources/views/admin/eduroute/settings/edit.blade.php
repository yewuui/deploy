@extends('layouts.admin-eduroute', ['program' => $program, 'title' => 'Pengaturan ' . $program->short_name, 'description' => 'Identitas jurusan yang tampil di halaman publik. Alamat URL dikunci otomatis untuk mencegah tautan rusak.', 'backUrl' => route('admin.eduroute.show', $program), 'backLabel' => 'Ringkasan'])

@section('eduroute-content')
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 md:p-8 max-w-3xl mx-auto">
        <form action="{{ route('admin.eduroute.settings.update', $program) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Jurusan</label>
                    <input type="text" id="name" name="name" required maxlength="255" value="{{ old('name', $program->name) }}" placeholder="Contoh: Rekayasa Perangkat Lunak" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Nama lengkap jurusan seperti yang tampil di judul halaman publik.</p>
                </div>
                <div>
                    <label for="short_name" class="block text-sm font-bold text-gray-700 mb-2">Singkatan</label>
                    <input type="text" id="short_name" name="short_name" required maxlength="20" value="{{ old('short_name', $program->short_name) }}" placeholder="Contoh: RPL" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Dipakai di menu, kartu, dan judul halaman. Maksimal 20 karakter.</p>
                </div>
            </div>

            <div>
                <label for="slug" class="block text-sm font-bold text-gray-700 mb-2">Slug URL</label>
                <input type="text" id="slug" name="slug" required maxlength="50" pattern="[A-Za-z0-9-]+" readonly value="{{ old('slug', $program->slug) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-gray-50 text-gray-500 cursor-not-allowed focus:ring-0">
                <p class="text-xs text-gray-500 mt-1">Alamat URL jurusan ini dibuat otomatis dan dikunci untuk mencegah tautan rusak.</p>
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Jurusan</label>
                <textarea id="description" name="description" rows="3" placeholder="Masukkan paragraf singkat yang menjelaskan fokus jurusan ini..." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('description', $program->description) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Deskripsi singkat yang akan muncul di kartu pencarian dan halaman utama.</p>
            </div>

            <div>
                <label for="hero_title" class="block text-sm font-bold text-gray-700 mb-2">Judul Pembuka Halaman <span class="font-normal text-gray-400">(opsional)</span></label>
                <input type="text" id="hero_title" name="hero_title" maxlength="255" value="{{ old('hero_title', $program->hero_title) }}" placeholder="Contoh: Rekayasa Perangkat Lunak (RPL)" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Judul besar di bagian paling atas halaman jurusan. Kosongkan untuk memakai nama jurusan.</p>
            </div>

            <div>
                <label for="hero_text" class="block text-sm font-bold text-gray-700 mb-2">Teks Pembuka Halaman <span class="font-normal text-gray-400">(opsional)</span></label>
                <textarea id="hero_text" name="hero_text" rows="4" placeholder="Masukkan paragraf singkat yang menjelaskan fokus jurusan ini..." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('hero_text', $program->hero_text) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Paragraf pengantar di bawah judul pembuka. Cukup 2&ndash;3 kalimat.</p>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1" @checked((bool) old('is_active', $program->is_active)) class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <div>
                    <label for="is_active" class="text-sm font-semibold text-gray-700">Jurusan aktif</label>
                    <p class="text-xs text-gray-500">Jurusan nonaktif tidak dapat dibuka dari halaman publik.</p>
                </div>
            </div>

            <div class="flex justify-end border-t border-gray-100 pt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
@endsection
