@extends('layouts.admin-eduroute', ['program' => $program, 'title' => ($item ? 'Ubah' : 'Tambah') . ' Karier — ' . $program->short_name, 'description' => 'Data ini tersimpan sebagai milik ' . $program->name . '.', 'backUrl' => route('admin.eduroute.careers.index', $program), 'backLabel' => 'Karier'])

@section('eduroute-content')
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6 md:p-8 max-w-3xl mx-auto">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if($method !== 'POST')
                @method($method)
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-bold text-gray-700 mb-2">Judul Karier</label>
                    <input type="text" id="title" name="title" required maxlength="255" value="{{ old('title', $item->title ?? '') }}" placeholder="Contoh: Web Developer" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Nama profesi atau posisi yang ditampilkan di kartu karier.</p>
                </div>
                <div>
                    <label for="icon" class="block text-sm font-bold text-gray-700 mb-2">Ikon <span class="font-normal text-gray-400">(opsional)</span></label>
                    @if(isset($item) && $item->icon_url)
                        <img src="{{ $item->icon_url }}" alt="Ikon {{ $item->title }}" class="h-10 w-10 object-contain rounded-lg bg-gray-100 mb-2">
                    @endif
                    <input type="file" id="icon" name="icon" accept="image/jpeg,image/png,image/webp" class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, atau WebP. Ukuran maksimal: 2MB. Ikon persegi berukuran kecil tampil paling rapi.</p>
                </div>
            </div>

            <div>
                <label for="short_description" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat <span class="font-normal text-gray-400">(tampil di kartu)</span></label>
                <input type="text" id="short_description" name="short_description" maxlength="255" value="{{ old('short_description', $item->short_description ?? '') }}" placeholder="Contoh: Membangun dan memelihara website modern" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Satu kalimat singkat, idealnya di bawah 120 karakter.</p>
            </div>

            <div>
                <label for="details" class="block text-sm font-bold text-gray-700 mb-2">Penjelasan Pekerjaan <span class="font-normal text-gray-400">(tampil saat detail dibuka)</span></label>
                <textarea id="details" name="details" rows="4" placeholder="Jelaskan gambaran umum pekerjaan ini: apa yang dikerjakan, di mana, dan mengapa penting..." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('details', $item->details ?? '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Muncul di modal detail saat pengunjung menekan kartu karier.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tasks" class="block text-sm font-bold text-gray-700 mb-2">Yang Dikerjakan <span class="font-normal text-gray-400">(pisahkan koma)</span></label>
                    <textarea id="tasks" name="tasks" rows="3" placeholder="Contoh: Menulis kode, Membuat API, Meninjau kode rekan" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('tasks', isset($item) && $item->tasks ? implode(', ', $item->tasks) : '') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Tulis 3&ndash;5 poin, dipisahkan koma.</p>
                </div>
                <div>
                    <label for="tools" class="block text-sm font-bold text-gray-700 mb-2">Skill dan Tools <span class="font-normal text-gray-400">(pisahkan koma)</span></label>
                    <textarea id="tools" name="tools" rows="3" placeholder="Contoh: PHP, Laravel, Git, MySQL" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">{{ old('tools', isset($item) && $item->tools ? implode(', ', $item->tools) : '') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Keterampilan dan perangkat yang dipakai, dipisahkan koma.</p>
                </div>
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
