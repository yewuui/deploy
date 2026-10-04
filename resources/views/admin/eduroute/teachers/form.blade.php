@extends('layouts.admin-eduroute', ['program' => $program, 'title' => ($item ? 'Ubah' : 'Tambah') . ' Guru — ' . $program->short_name, 'description' => 'Data ini tersimpan sebagai milik ' . $program->name . '.', 'backUrl' => route('admin.eduroute.teachers.index', $program), 'backLabel' => 'Guru'])

@section('eduroute-content')
    @php
        $placeholderPhoto = asset('images/guru/placeholder-guru-rpl.svg');
    @endphp

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 md:p-8">
                <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @if($method !== 'POST')
                        @method($method)
                    @else
                        <input type="hidden" name="submission_token" value="{{ $submissionToken }}">
                    @endif

                    {{-- 1. Identitas dasar: nama dan bidang yang tampil paling menonjol. --}}
                    <section>
                        <h3 class="text-sm font-bold text-gray-900">Identitas Guru</h3>
                        <p class="mt-1 text-xs text-gray-500">Nama dan bidang ini selalu tampil di kartu guru halaman publik.</p>

                        <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Guru</label>
                                <input type="text" id="name" name="name" required maxlength="255" value="{{ old('name', $item->name ?? '') }}" placeholder="Contoh: Haris Budiawan, S.Kom." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Tulis nama lengkap beserta gelar agar tampil rapi di kartu profil.</p>
                            </div>
                            <div>
                                <label for="subject" class="block text-sm font-bold text-gray-700 mb-2">Bidang / Mata Pelajaran</label>
                                <input type="text" id="subject" name="subject" required maxlength="255" value="{{ old('subject', $item->subject ?? '') }}" placeholder="Contoh: Basis Data dan AI" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Bidang keahlian yang diampu, tampil di bawah nama guru.</p>
                            </div>
                        </div>
                    </section>

                    {{-- 2. Peran struktural: tampil sebagai jabatan berwarna di detail guru. --}}
                    <section class="border-t border-gray-100 pt-6">
                        <h3 class="text-sm font-bold text-gray-900">Peran dan Keahlian</h3>
                        <p class="mt-1 text-xs text-gray-500">Melengkapi profil saat pengunjung membuka detail guru.</p>

                        <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="role_title" class="block text-sm font-bold text-gray-700 mb-2">Jabatan / Peran <span class="font-normal text-gray-400">(opsional)</span></label>
                                <input type="text" id="role_title" name="role_title" maxlength="255" value="{{ old('role_title', $item->role_title ?? '') }}" placeholder="Contoh: Ketua Program Keahlian PPLG" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Tampil tepat di bawah nama, misalnya &ldquo;Ketua Program Keahlian PPLG&rdquo;.</p>
                            </div>
                            <div>
                                <label for="expertise" class="block text-sm font-bold text-gray-700 mb-2">Bidang Keahlian <span class="font-normal text-gray-400">(opsional)</span></label>
                                <input type="text" id="expertise" name="expertise" maxlength="255" value="{{ old('expertise', $item->expertise ?? '') }}" placeholder="Contoh: Basis Data dan AI" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Keahlian utama yang ingin ditonjolkan pada panel detail.</p>
                            </div>
                        </div>
                    </section>

                    {{-- 3. Pendidikan: dua baris terpisah agar mudah dibaca di panel detail. --}}
                    <section class="border-t border-gray-100 pt-6">
                        <h3 class="text-sm font-bold text-gray-900">Pendidikan Terakhir</h3>
                        <p class="mt-1 text-xs text-gray-500">Ditampilkan sebagai blok &ldquo;Pendidikan Terakhir&rdquo; pada detail guru.</p>

                        <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="education" class="block text-sm font-bold text-gray-700 mb-2">Jenjang dan Jurusan <span class="font-normal text-gray-400">(opsional)</span></label>
                                <input type="text" id="education" name="education" maxlength="255" value="{{ old('education', $item->education ?? '') }}" placeholder="Contoh: S1 &ndash; Pendidikan Teknik Informatika" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Tulis jenjang beserta program studi.</p>
                            </div>
                            <div>
                                <label for="campus" class="block text-sm font-bold text-gray-700 mb-2">Universitas / Institusi <span class="font-normal text-gray-400">(opsional)</span></label>
                                <input type="text" id="campus" name="campus" maxlength="255" value="{{ old('campus', $item->campus ?? '') }}" placeholder="Contoh: Universitas Negeri Yogyakarta (UNY)" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Nama kampus atau institusi pendidikan terakhir.</p>
                            </div>
                        </div>
                    </section>

                    {{-- 4. Deskripsi: paragraf pengantar di panel detail. --}}
                    <section class="border-t border-gray-100 pt-6">
                        <h3 class="text-sm font-bold text-gray-900">Deskripsi Singkat</h3>
                        <div class="mt-4">
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Tentang Guru <span class="font-normal text-gray-400">(opsional)</span></label>
                            <textarea id="description" name="description" rows="3" placeholder="Contoh: Guru Produktif Rekayasa Perangkat Lunak yang memiliki keahlian di bidang Basis Data dan AI." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('description', $item->description ?? '') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Muncul sebagai paragraf pembuka saat pengunjung membuka detail guru. Kosongkan bila tidak diperlukan.</p>
                        </div>
                    </section>

                    {{-- 5. Foto: pratinjau langsung agar admin tahu hasilnya. --}}
                    <section class="border-t border-gray-100 pt-6">
                        <h3 class="text-sm font-bold text-gray-900">Foto</h3>
                        <div class="mt-4">
                            <label for="photo" class="block text-sm font-bold text-gray-700 mb-2">Foto Guru <span class="font-normal text-gray-400">(opsional)</span></label>
                            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, atau WebP. Ukuran maksimal: 2MB. Gunakan foto dengan rasio 1:1 (persegi) agar wajah terlihat jelas di kartu profil.</p>
                        </div>
                    </section>

                    {{-- 6. Pengaturan tampil: urutan dan status publikasi. --}}
                    <section class="border-t border-gray-100 pt-6">
                        <h3 class="text-sm font-bold text-gray-900">Pengaturan Tampil</h3>
                        <div class="mt-4 grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="order_number" class="block text-sm font-bold text-gray-700 mb-2">Urutan Tampil</label>
                                <input type="number" id="order_number" name="order_number" min="0" required value="{{ old('order_number', $item->order_number ?? 0) }}" placeholder="Contoh: 1" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Angka lebih kecil tampil lebih dulu. Gunakan 1, 2, 3, dst.</p>
                            </div>
                            <div class="flex items-center gap-3 pt-7">
                                <input type="checkbox" id="is_active" name="is_active" value="1" @checked((bool) old('is_active', $item->is_active ?? true)) class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="is_active" class="text-sm font-semibold text-gray-700">Tampilkan di halaman publik</label>
                            </div>
                        </div>
                    </section>

                    <div class="flex justify-end border-t border-gray-100 pt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition disabled:cursor-wait disabled:opacity-60">
                            {{ $submitLabel }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Pratinjau langsung: meniru panel detail guru di halaman publik,
            sehingga admin dapat melihat hasil isian tanpa membuka tab lain. --}}
        <aside class="lg:col-span-1">
            <div class="lg:sticky lg:top-6 space-y-3">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Pratinjau Detail Guru</h3>
                    <p class="mt-1 text-xs text-gray-500">Perkiraan tampilan saat kartu guru dibuka di halaman publik.</p>
                </div>

                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="flex items-start gap-4 p-4">
                        <div class="h-24 w-24 shrink-0 overflow-hidden rounded-lg bg-gray-100">
                            <img id="preview-photo" src="{{ $item->photo_url ?? $placeholderPhoto }}" alt="Pratinjau foto guru" class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1">
                            <p id="preview-name" class="text-base font-bold leading-snug text-gray-900">{{ $item->name ?? 'Nama Guru' }}</p>
                            <p id="preview-role" class="mt-0.5 text-sm font-semibold leading-snug text-blue-600">{{ $item->role_title ?? 'Jabatan / peran guru' }}</p>
                            <span id="preview-subject" class="mt-2 inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $item->subject ?? 'Bidang yang diampu' }}</span>
                        </div>
                    </div>

                    <div class="space-y-4 border-t border-gray-100 px-4 py-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Tentang</p>
                            <p id="preview-description" class="mt-1 text-sm leading-relaxed text-gray-600">{{ $item->description ?? 'Deskripsi singkat guru akan tampil di sini.' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Bidang Keahlian</p>
                            <p id="preview-expertise" class="mt-1 text-sm leading-relaxed text-gray-600">{{ $item->expertise ?? 'Keahlian utama guru.' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Pendidikan Terakhir</p>
                            <p id="preview-education" class="mt-1 text-sm leading-relaxed text-gray-600">{{ $item->education ?? 'Jenjang dan jurusan.' }}</p>
                            <p id="preview-campus" class="text-sm leading-relaxed text-gray-500">{{ $item->campus ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-gray-500">Kolom yang dikosongkan tidak ditampilkan pada panel detail publik.</p>
            </div>
        </aside>
    </div>

    @push('scripts')
        <script>
            (function () {
                var teacherForm = document.querySelector('form[enctype="multipart/form-data"]');
                var submitButton = teacherForm && teacherForm.querySelector('button[type="submit"]');

                if (teacherForm && submitButton) {
                    teacherForm.addEventListener('submit', function () {
                        submitButton.disabled = true;
                        submitButton.textContent = 'Menyimpan...';
                    });
                }

                var fieldBindings = [
                    ['name', 'preview-name', 'Nama Guru'],
                    ['role_title', 'preview-role', 'Jabatan / peran guru'],
                    ['subject', 'preview-subject', 'Bidang yang diampu'],
                    ['description', 'preview-description', 'Deskripsi singkat guru akan tampil di sini.'],
                    ['expertise', 'preview-expertise', 'Keahlian utama guru.'],
                    ['education', 'preview-education', 'Jenjang dan jurusan.'],
                    ['campus', 'preview-campus', '']
                ];

                fieldBindings.forEach(function (binding) {
                    var field = document.getElementById(binding[0]);
                    var target = document.getElementById(binding[1]);

                    if (!field || !target) {
                        return;
                    }

                    field.addEventListener('input', function () {
                        var value = field.value.trim();
                        target.textContent = value !== '' ? value : binding[2];
                    });
                });

                var photoField = document.getElementById('photo');
                var photoPreview = document.getElementById('preview-photo');

                if (photoField && photoPreview) {
                    photoField.addEventListener('change', function () {
                        var file = photoField.files && photoField.files[0];

                        if (!file) {
                            return;
                        }

                        var reader = new FileReader();
                        reader.onload = function (event) {
                            photoPreview.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                }
            })();
        </script>
    @endpush
@endsection
