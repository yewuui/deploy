@extends('layouts.admin-eduroute', ['program' => $program, 'title' => ($item ? 'Ubah' : 'Tambah') . ' Mitra — ' . $program->short_name, 'description' => 'Data ini tersimpan sebagai milik ' . $program->name . '. Isi tautan website hanya bila alamat resminya diketahui.', 'backUrl' => route('admin.eduroute.partners.index', $program), 'backLabel' => 'Mitra'])

@section('eduroute-content')
    <div class="bg-gray-50 shadow-sm rounded-xl border border-gray-300 p-6 md:p-8 max-w-3xl mx-auto">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if($method !== 'POST')
                @method($method)
            @endif

            <div>
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Mitra</label>
                <input type="text" id="name" name="name" required maxlength="255" value="{{ old('name', $item->name ?? '') }}" placeholder="Contoh: PT Contoh Teknologi" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Nama perusahaan atau institusi mitra jurusan.</p>
            </div>

            <div>
                <label for="website_url" class="block text-sm font-bold text-gray-700 mb-2">Website Resmi <span class="font-normal text-gray-400">(opsional — kosongkan bila tidak diketahui)</span></label>
                <input type="url" id="website_url" name="website_url" maxlength="500" value="{{ old('website_url', $item->website_url ?? '') }}" placeholder="https://contoh-perusahaan.co.id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Alamat lengkap dengan awalan http:// atau https://.</p>
            </div>

            <div>
                <label for="logo" class="block text-sm font-bold text-gray-700 mb-2">Logo <span class="font-normal text-gray-400">(unggah file, opsional)</span></label>
                @if(isset($item) && $item->logo_url)
                    <div class="mb-3 flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
                        <img src="{{ $item->logo_url }}" alt="Logo {{ $item->name }}" class="h-12 w-auto max-w-[120px] object-contain rounded-md" style="background-color: {{ $item->logo_bg_color_effective }};">
                        <p class="text-xs text-gray-500">Preview dengan warna background yang tersimpan.</p>
                    </div>
                @endif
                <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp" class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, atau WebP. Ukuran maksimal: 2MB. Gunakan logo dengan latar transparan bila tersedia.</p>
            </div>

            <div class="rounded-xl border border-gray-300 bg-gray-100/70 p-4">
                <label for="logo_bg_color" class="block text-sm font-bold text-gray-700 mb-2">Warna Background Logo <span class="font-normal text-gray-400">(opsional)</span></label>
                <div class="flex flex-wrap items-center gap-3">
                    <input type="color" id="logo_bg_color_picker" aria-label="Pemilih warna background logo" class="h-10 w-10 cursor-pointer rounded-lg border border-gray-300 bg-white shadow-sm" value="{{ old('logo_bg_color') ? \App\Models\Partner::normalizeHexColor(old('logo_bg_color')) : ($item->logo_bg_color_effective ?? \App\Models\Partner::DEFAULT_LOGO_BG_COLOR) }}">
                    <input type="text" id="logo_bg_color" name="logo_bg_color" maxlength="30" autocomplete="off" spellcheck="false" value="{{ old('logo_bg_color', $item->logo_bg_color ?? '') }}" placeholder="#FFFFFF atau rgb(255,255,255)" class="min-w-[200px] flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 font-mono text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                    <button type="button" id="reset_bg_color" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-600 transition hover:bg-gray-200">Reset ke Putih</button>
                </div>
                <div class="mt-3 flex items-center gap-3">
                    <div id="bg_preview" class="flex h-12 w-20 items-center justify-center rounded-lg border border-gray-400 bg-white shadow-sm" style="background-color: {{ \App\Models\Partner::normalizeHexColor(old('logo_bg_color')) ?? ($item->logo_bg_color_effective ?? \App\Models\Partner::DEFAULT_LOGO_BG_COLOR) }};">
                        <span id="bg_preview_label" class="px-1 text-center text-[10px] font-bold" style="color: {{ \App\Models\Partner::isDarkColor(\App\Models\Partner::normalizeHexColor(old('logo_bg_color')) ?? ($item->logo_bg_color_effective ?? \App\Models\Partner::DEFAULT_LOGO_BG_COLOR)) ? '#FFFFFF' : '#111827' }};">Logo</span>
                    </div>
                    <p class="text-xs leading-relaxed text-gray-600">
                        Kotak latar di belakang logo pada halaman publik. Boleh diisi <span class="font-mono font-semibold text-gray-800">#RRGGBB</span> atau <span class="font-mono font-semibold text-gray-800">rgb(r, g, b)</span>. Kosongkan untuk memakai default putih. Warna teks pada kotak tanpa logo menyesuaikan sendiri agar tetap terbaca.
                    </p>
                </div>
                @error('logo_bg_color')
                    <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="logo_url" class="block text-sm font-bold text-gray-700 mb-2">atau URL Logo Eksternal <span class="font-normal text-gray-400">(opsional, dipakai bila tidak mengunggah file)</span></label>
                <input type="url" id="logo_url" name="logo_url" maxlength="500" value="{{ old('logo_url', (isset($item) && $item->logo && str_starts_with($item->logo, 'http')) ? $item->logo : '') }}" placeholder="https://contoh.com/logo.png" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Alternatif tanpa unggah file. Bila keduanya diisi, file yang diunggah akan dipakai.</p>
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

            <div class="flex justify-end border-t border-gray-200 pt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-bold transition shadow-sm hover:shadow-md">
                    {{ $submitLabel }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            (function () {
                var DEFAULT_COLOR = '#FFFFFF';
                var colorField = document.getElementById('logo_bg_color');
                var colorPicker = document.getElementById('logo_bg_color_picker');
                var preview = document.getElementById('bg_preview');
                var previewLabel = document.getElementById('bg_preview_label');
                var resetButton = document.getElementById('reset_bg_color');

                if (!colorField || !colorPicker || !preview) {
                    return;
                }

                // Warna teks di dalam preview memakai aturan kontras yang sama
                // dengan model (Partner::isDarkColor).
                var LUMINANCE_THRESHOLD = 140;

                function toChannels(color) {
                    var hex = color.replace('#', '');

                    if (hex.length === 3) {
                        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
                    }

                    if (!/^[0-9A-Fa-f]{6}$/.test(hex)) {
                        return null;
                    }

                    return [
                        parseInt(hex.slice(0, 2), 16),
                        parseInt(hex.slice(2, 4), 16),
                        parseInt(hex.slice(4, 6), 16)
                    ];
                }

                function applyPreview(color) {
                    var value = (color || '').trim();
                    var channels = value ? toChannels(value) : null;

                    preview.style.backgroundColor = channels ? value : DEFAULT_COLOR;

                    if (!previewLabel) {
                        return;
                    }

                    if (!channels) {
                        previewLabel.style.color = '#111827';
                        return;
                    }

                    var luminance = 0.2126 * channels[0] + 0.7152 * channels[1] + 0.0722 * channels[2];
                    previewLabel.style.color = luminance < LUMINANCE_THRESHOLD ? '#FFFFFF' : '#111827';
                }

                colorPicker.addEventListener('input', function (event) {
                    colorField.value = event.target.value.toUpperCase();
                    applyPreview(event.target.value);
                });

                colorField.addEventListener('input', function (event) {
                    var value = event.target.value.trim();
                    var channels = toChannels(value);

                    if (channels) {
                        colorPicker.value = value.length === 4
                            ? '#' + value[1] + value[1] + value[2] + value[2] + value[3] + value[3]
                            : value;
                    }

                    applyPreview(value);
                });

                if (resetButton) {
                    resetButton.addEventListener('click', function () {
                        colorField.value = '';
                        colorPicker.value = DEFAULT_COLOR;
                        applyPreview(DEFAULT_COLOR);
                    });
                }

                applyPreview(colorField.value);
            })();
        </script>
    @endpush
@endsection
