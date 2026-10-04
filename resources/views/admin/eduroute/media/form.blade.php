@extends('layouts.admin-eduroute', ['program' => $program, 'title' => ($item ? 'Ubah' : 'Tambah') . ' Media — ' . $program->short_name, 'description' => 'Data ini tersimpan sebagai milik ' . $program->name . '.', 'backUrl' => route('admin.eduroute.media.index', $program), 'backLabel' => 'Media'])

@section('eduroute-content')
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 md:p-8 max-w-3xl mx-auto">
        <form action="{{ $action }}" method="POST" class="space-y-6">
            @csrf
            @if($method !== 'POST')
                @method($method)
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="platform" class="block text-sm font-bold text-gray-700 mb-2">Platform</label>
                    <input type="text" id="platform" name="platform" required maxlength="100" value="{{ old('platform', $item->platform ?? '') }}" placeholder="Contoh: Instagram, Website, YouTube" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Nama layanan media sosial atau kanal resmi.</p>
                </div>
                <div>
                    <label for="handle" class="block text-sm font-bold text-gray-700 mb-2">Nama/Akun <span class="font-normal text-gray-400">(opsional)</span></label>
                    <input type="text" id="handle" name="handle" maxlength="255" value="{{ old('handle', $item->handle ?? '') }}" placeholder="Contoh: @skansaba.dev" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Nama pengguna atau akun yang tampil di halaman publik.</p>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi <span class="font-normal text-gray-400">(opsional)</span></label>
                <textarea id="description" name="description" rows="2" placeholder="Contoh: Kanal resmi kegiatan dan karya siswa RPL." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('description', $item->description ?? '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Satu kalimat pengantar tentang kanal ini.</p>
            </div>

            <div>
                <label for="url" class="block text-sm font-bold text-gray-700 mb-2">Tautan <span class="font-normal text-gray-400">(opsional, harus URL lengkap http:// atau https://)</span></label>
                <input type="url" id="url" name="url" maxlength="500" value="{{ old('url', $item->url ?? '') }}" placeholder="https://instagram.com/skansaba.dev" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Alamat lengkap kanal, diawali http:// atau https://.</p>
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
