@php
    $isGrade12 = (int) old('grade', $item->grade ?? $activeGrade) === 12;
@endphp

@extends('layouts.admin-eduroute', ['program' => $program, 'title' => 'Kelas ' . $activeGrade . ' — ' . ($item ? 'Ubah' : 'Tambah') . ($isGrade12 ? ' Program / Kegiatan' : ' Materi'), 'description' => $isGrade12 ? 'Konten ini tersimpan sebagai milik ' . $program->name . ' dan tampil pada halaman publik Kelas 12 (Proyek & Kesiapan Kerja).' : 'Materi ini tersimpan sebagai milik ' . $program->name . ' dan tampil di halaman roadmap publik jurusan ini.', 'backUrl' => route('admin.eduroute.curriculum.index', ['program' => $program, 'kelas' => $activeGrade]), 'backLabel' => 'Kurikulum Kelas ' . $activeGrade])

@section('eduroute-content')
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 md:p-8 max-w-3xl mx-auto">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if($method !== 'POST')
                @method($method)
            @endif

            {{-- Semester tidak lagi ditampilkan di UI publik (tetap dikirim untuk kompatibilitas data) --}}
            <input type="hidden" name="semester" value="{{ old('semester', $item->semester ?? 1) }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="grade" class="block text-sm font-bold text-gray-700 mb-2">Tingkat Kelas</label>
                    <select id="grade" name="grade" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        @foreach([10, 11, 12] as $grade)
                            <option value="{{ $grade }}" @selected((int) old('grade', $item->grade ?? $activeGrade) === $grade)>Kelas {{ $grade }} {{ $grade === 12 ? '(Proyek & Kesiapan Kerja)' : '(Materi Pembelajaran)' }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Kelas tempat konten ini dikelola.</p>
                </div>

                <div>
                    <label for="order_number" class="block text-sm font-bold text-gray-700 mb-2">Urutan Tampil</label>
                    <input type="number" id="order_number" name="order_number" min="0" required value="{{ old('order_number', $item->order_number ?? 0) }}" placeholder="Contoh: 1" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Menentukan urutan kemunculan di halaman publik. Angka kecil tampil lebih awal.</p>
                </div>
            </div>

            {{-- FIELD KHUSUS KELAS 12: Kategori / Tipe --}}
            <div id="grade-12-type-field" class="{{ $isGrade12 ? '' : 'hidden' }}">
                <label for="type" class="block text-sm font-bold text-gray-700 mb-2">Kategori Program Kelas 12 <span class="text-red-500">*</span></label>
                <select id="type" name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <option value="pkl" @selected(old('type', $item->type ?? 'pkl') === 'pkl')>Praktik Kerja Lapangan (PKL)</option>
                    <option value="proyek" @selected(old('type', $item->type ?? '') === 'proyek')>Proyek / Capstone Project</option>
                    <option value="ukk" @selected(old('type', $item->type ?? '') === 'ukk')>Uji Kompetensi Keahlian (UKK) / Sertifikasi</option>
                    <option value="kesiapan_kerja" @selected(old('type', $item->type ?? '') === 'kesiapan_kerja')>Kesiapan Kerja / Penyaluran Karir</option>
                    <option value="dokumentasi" @selected(old('type', $item->type ?? '') === 'dokumentasi')>Dokumentasi Kegiatan Siswa</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Pilih kategori yang sesuai untuk menyusun struktur informasi di halaman Kelas 12.</p>
            </div>

            <div>
                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">
                    <span id="title-label">{{ $isGrade12 ? 'Judul Program / Kegiatan' : 'Judul Materi' }}</span>
                    <span class="text-red-500">*</span>
                </label>
                <input type="text" id="title" name="title" required maxlength="255" value="{{ old('title', $item->title ?? '') }}" placeholder="{{ $isGrade12 ? 'Contoh: Praktik Kerja Lapangan (PKL) Industri' : 'Contoh: Pemrograman Web Dasar' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <p id="title-hint" class="text-xs text-gray-500 mt-1">
                    {{ $isGrade12 ? 'Nama program atau kegiatan yang tampil di halaman Kelas 12.' : 'Judul tahap yang tampil di kartu roadmap publik.' }}
                </p>
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi / Ringkasan <span class="font-normal text-gray-400">(opsional)</span></label>
                <textarea id="description" name="description" rows="3" placeholder="{{ $isGrade12 ? 'Penjelasan ringkas mengenai pelaksanaan, tujuan, atau manfaat kegiatan...' : 'Satu-dua kalimat pengantar tahap ini di halaman publik.' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('description', $item->description ?? '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Deskripsi kegiatan yang informatif dan mudah dipahami siswa.</p>
            </div>

            <div>
                <label for="topics" class="block text-sm font-bold text-gray-700 mb-2">
                    <span id="topics-label">{{ $isGrade12 ? 'Poin-Poin / Cakupan Kegiatan (pisahkan dengan koma)' : 'Daftar Topik (pisahkan dengan koma)' }}</span>
                    <span id="topics-required" class="text-red-500 {{ $isGrade12 ? 'hidden' : '' }}">*</span>
                </label>
                <textarea id="topics" name="topics" rows="3" placeholder="{{ $isGrade12 ? 'Contoh: Magang Industri 6 Bulan, DUDI Mitra, Mentoring Praktisi, Laporan & Sidang' : 'Contoh: Instalasi, Routing Dasar, Controller' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('topics', isset($item) && $item->topics ? implode(', ', (array) $item->topics) : '') }}</textarea>
                <p id="topics-hint" class="text-xs text-gray-500 mt-1">
                    {{ $isGrade12 ? 'Poin penting yang tercakup dalam kegiatan ini. Pisahkan antar poin dengan tanda koma.' : 'Daftar materi yang dipelajari. Pisahkan dengan koma.' }}
                </p>
            </div>

            {{-- FIELD KHUSUS KELAS 12: Foto Dokumentasi --}}
            <div id="grade-12-image-field" class="{{ $isGrade12 ? '' : 'hidden' }}">
                <label for="image" class="block text-sm font-bold text-gray-700 mb-2">Foto Dokumentasi / Media <span class="font-normal text-gray-400">(opsional)</span></label>
                @if($item && $item->image_url)
                    <div class="mb-3 flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <img src="{{ $item->image_url }}" alt="Pratinjau" class="w-16 h-16 object-cover rounded-md border border-gray-300">
                        <div class="text-xs text-gray-600">
                            <p class="font-semibold">Foto saat ini terpasang</p>
                            <p class="text-gray-400 mt-0.5">Unggah berkas baru untuk mengganti foto.</p>
                        </div>
                    </div>
                @endif
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, atau WebP. Maksimal 4 MB. Cocok untuk dokumentasi foto kegiatan PKL, UKK, atau Proyek.</p>
            </div>

            <div class="flex justify-end border-t border-gray-100 pt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition">
                    {{ $submitLabel }}
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const gradeSelect = document.getElementById('grade');
            const typeField = document.getElementById('grade-12-type-field');
            const imageField = document.getElementById('grade-12-image-field');
            const titleLabel = document.getElementById('title-label');
            const titleHint = document.getElementById('title-hint');
            const topicsLabel = document.getElementById('topics-label');
            const topicsRequired = document.getElementById('topics-required');
            const topicsHint = document.getElementById('topics-hint');

            function updateFormForGrade(grade) {
                const isGrade12 = String(grade) === '12';

                if (typeField) typeField.classList.toggle('hidden', !isGrade12);
                if (imageField) imageField.classList.toggle('hidden', !isGrade12);
                if (titleLabel) titleLabel.textContent = isGrade12 ? 'Judul Program / Kegiatan' : 'Judul Materi';
                if (titleHint) titleHint.textContent = isGrade12 ? 'Nama program atau kegiatan yang tampil di halaman Kelas 12.' : 'Judul tahap yang tampil di kartu roadmap publik.';

                if (topicsLabel) topicsLabel.textContent = isGrade12 ? 'Poin-Poin / Cakupan Kegiatan (pisahkan dengan koma)' : 'Daftar Topik (pisahkan dengan koma)';
                if (topicsRequired) topicsRequired.classList.toggle('hidden', isGrade12);
                if (topicsHint) topicsHint.textContent = isGrade12 ? 'Poin penting yang tercakup dalam kegiatan ini. Pisahkan antar poin dengan tanda koma.' : 'Daftar materi yang dipelajari. Pisahkan dengan koma.';
            }

            if (gradeSelect) {
                gradeSelect.addEventListener('change', function () {
                    updateFormForGrade(this.value);
                });
            }
        });
    </script>
@endsection
