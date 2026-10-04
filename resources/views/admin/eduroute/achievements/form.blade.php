@extends('layouts.admin-eduroute', ['program' => $program, 'title' => ($item ? 'Ubah' : 'Tambah') . ' Prestasi — ' . $program->short_name, 'description' => 'Data ini tersimpan sebagai milik ' . $program->name . '.', 'backUrl' => route('admin.eduroute.achievements.index', $program), 'backLabel' => 'Prestasi'])

@section('eduroute-content')
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 md:p-8 max-w-3xl mx-auto">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if($method !== 'POST')
                @method($method)
            @endif

            <div>
                <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Nama Prestasi</label>
                <input type="text" id="title" name="title" required maxlength="255" value="{{ old('title', $item->title ?? '') }}" placeholder="Contoh: Juara 1 LKS Web Technology Tingkat Provinsi" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Tulis nama lomba atau penghargaan secara lengkap.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Peraih <span class="font-normal text-gray-400">(siswa/tim)</span></label>
                    <input type="text" id="name" name="name" maxlength="255" value="{{ old('name', $item->name ?? '') }}" placeholder="Contoh: Abid Muhammad Rasyid" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Nama siswa atau nama tim yang meraih prestasi.</p>
                </div>
                <div>
                    <label for="date_label" class="block text-sm font-bold text-gray-700 mb-2">Waktu <span class="font-normal text-gray-400">(mis. Agu 2026)</span></label>
                    <input type="text" id="date_label" name="date_label" maxlength="100" value="{{ old('date_label', $item->date_label ?? '') }}" placeholder="Contoh: Agu 2026" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Teks bebas, misalnya bulan dan tahun tanpa tanggal pasti.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category" class="block text-sm font-bold text-gray-700 mb-2">Kategori <span class="font-normal text-gray-400">(mis. Kompetisi, Akademik)</span></label>
                    <input type="text" id="category" name="category" maxlength="100" value="{{ old('category', $item->category ?? '') }}" placeholder="Contoh: Kompetisi" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="level" class="block text-sm font-bold text-gray-700 mb-2">Tingkat <span class="font-normal text-gray-400">(mis. Sekolah, Nasional)</span></label>
                    <input type="text" id="level" name="level" maxlength="100" value="{{ old('level', $item->level ?? '') }}" placeholder="Contoh: Provinsi" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-4">
                <div class="flex items-start gap-3">
                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <div class="min-w-0 flex-1">
                        <label for="description" class="block text-sm font-bold text-gray-800 mb-2">Deskripsi / Cerita Prestasi <span class="font-normal text-gray-400">(opsional, tapi paling disarankan)</span></label>
                        <textarea id="description" name="description" rows="6" placeholder="Ceritakan jalannya kompetisi, persiapan tim, dan hal yang membuat pencapaian ini menjadi luar biasa.&#10;&#10;Contoh:&#10;Tim menghabiskan tiga bulan prepping dengan rutin melakukan simulasi.&#10;&#10;Pada hari final, tim berhasil mengatasi tekanan dan meraih juara pertama." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm leading-relaxed focus:border-blue-500 focus:ring-2 focus:ring-blue-500 placeholder:text-gray-400">{{ old('description', $item->description ?? '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-2">Tampil saat pengunjung <span class="font-semibold text-gray-700">membuka kartu prestasi</span> di halaman publik. Ini isi utama dari modal detail, jadi ceritakan selengkap mungkin.</p>
                        <p class="text-xs text-gray-500 mt-1">Pisahkan antar paragraf dengan satu baris kosong agar tampilannya rapi di modal.</p>
                    </div>
                </div>
            </div>

            <div>
                <label for="image" class="block text-sm font-bold text-gray-700 mb-2">Foto Dokumentasi <span class="font-normal text-gray-400">(opsional)</span></label>
                @if(isset($item) && $item->image_url)
                    <img src="{{ $item->image_url }}" alt="Dokumentasi {{ $item->title }}" class="h-24 w-auto rounded-lg object-cover bg-gray-100 mb-3">
                @endif
                <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp" class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, atau WebP. Ukuran maksimal: 4MB. Gunakan foto lanskap agar tampil penuh di kartu prestasi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

            <div class="flex justify-end border-t border-gray-100 pt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition">
                    {{ $submitLabel }}
                </button>
            </div>
        </form>
    </div>
@endsection
