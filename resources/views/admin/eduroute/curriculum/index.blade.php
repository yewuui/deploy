@extends('layouts.admin-eduroute', ['program' => $program, 'title' => 'Kurikulum ' . $program->short_name, 'description' => $activeGrade === 12 ? 'Program PKL, Proyek, UKK, Kesiapan Kerja, dan Dokumentasi milik ' . $program->name . ' — tampil di halaman publik Kelas 12.' : 'Materi milik ' . $program->name . ' — tampil sebagai tahap pembelajaran di halaman roadmap publik.', 'backUrl' => route('admin.eduroute.show', $program), 'backLabel' => 'Ringkasan'])

@section('eduroute-content')
        @section('action')
<a href="{{ route('admin.eduroute.curriculum.create', ['program' => $program, 'kelas' => $activeGrade]) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">
            {{ $activeGrade === 12 ? '+ Tambah Program / Kegiatan' : '+ Tambah Materi' }}
        </a>
    @endsection


    {{-- Pemilih kelas: satu kelas ditampilkan pada satu waktu, state disimpan di query string (?kelas=). --}}
    <div class="mb-4 overflow-x-auto">
        <nav class="inline-flex gap-1 rounded-lg border border-gray-200 bg-white p-1" aria-label="Pilih kelas kurikulum">
            @foreach($gradeTabs as $tab)
                <a href="{{ route('admin.eduroute.curriculum.index', ['program' => $program, 'kelas' => $tab['grade']]) }}"
                   @if($tab['is_active']) aria-current="page" @endif
                   aria-label="Kelas {{ $tab['grade'] }}, {{ $tab['count'] }} {{ $tab['grade'] === 12 ? 'kegiatan' : 'materi' }}"
                   @class([
                       'inline-flex items-center gap-2 whitespace-nowrap rounded-md px-3.5 py-2 text-sm transition focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2',
                       'bg-blue-600 font-semibold text-white' => $tab['is_active'],
                       'font-medium text-gray-600 hover:bg-gray-50 hover:text-gray-900' => ! $tab['is_active'],
                   ])>
                    Kelas {{ $tab['grade'] }}
                    <span @class([
                        'inline-flex min-w-[1.5rem] justify-center rounded px-1.5 py-0.5 text-xs font-semibold tabular-nums',
                        'bg-white text-blue-700' => $tab['is_active'],
                        'bg-gray-100 text-gray-600' => ! $tab['is_active'],
                    ])>{{ $tab['count'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <div class="mb-3 flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1">
        <div>
            <h3 class="text-base font-bold text-gray-900">
                Kelas {{ $activeGrade }}
                @if($activeGrade === 12)
                    <span class="text-sm font-normal text-gray-500">— Proyek & Kesiapan Kerja (PKL, Proyek, UKK, Karir, Dokumentasi)</span>
                @endif
            </h3>
        </div>
        <p class="text-xs text-gray-500">{{ $items->count() }} {{ $activeGrade === 12 ? 'program / kegiatan' : 'materi' }}</p>
    </div>

    @if($items->isEmpty())
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-8 text-center">
            <p class="text-sm font-semibold text-gray-700">Belum ada {{ $activeGrade === 12 ? 'program / kegiatan' : 'materi' }} untuk Kelas {{ $activeGrade }}.</p>
            <p class="mt-1 text-sm text-gray-500">
                {{ $activeGrade === 12 ? 'Tambahkan program PKL, Proyek, UKK, Kesiapan Kerja, atau Dokumentasi kegiatan pertama untuk kelas ini.' : 'Tambahkan materi pertama untuk kelas ini, atau pilih kelas lain di atas.' }}
            </p>
            <a href="{{ route('admin.eduroute.curriculum.create', ['program' => $program, 'kelas' => $activeGrade]) }}" class="mt-4 inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">
                {{ $activeGrade === 12 ? '+ Tambah Program / Kegiatan' : '+ Tambah Materi' }}
            </a>
        </div>
    @else
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">{{ $activeGrade === 12 ? 'Program / Kegiatan' : 'Materi' }}</th>
                            @if($activeGrade === 12)
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">Foto</th>
                            @else
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Kelas</th>
                            @endif
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @foreach($items as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->title }}</div>
                                    @if($item->description)
                                        <div class="text-xs text-gray-500 mt-1 max-w-xl line-clamp-2">{{ $item->description }}</div>
                                    @endif
                                    @if(!empty($item->topics))
                                        <div class="text-xs text-blue-600 font-medium mt-1.5 flex flex-wrap gap-1">
                                            @foreach((array) $item->topics as $topic)
                                                <span class="inline-block bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[11px]">{{ $topic }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                @if($activeGrade === 12)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $typeBadgeClass = match($item->type) {
                                                'pkl' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'proyek' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                'ukk' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'kesiapan_kerja' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                'dokumentasi' => 'bg-sky-50 text-sky-700 border-sky-200',
                                                default => 'bg-gray-100 text-gray-700 border-gray-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border {{ $typeBadgeClass }}">
                                            {{ $item->type_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($item->image_url)
                                            <div class="inline-block w-12 h-12 rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                                <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Kelas {{ $item->grade }}
                                        </span>
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-bold">
                                    {{ $item->order_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.eduroute.curriculum.edit', [$program, $item->id, 'kelas' => $activeGrade]) }}" class="rounded-md px-3 py-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 transition-colors">Ubah</a>
                                        <form action="{{ route('admin.eduroute.curriculum.destroy', [$program, $item->id, 'kelas' => $activeGrade]) }}" method="POST" onsubmit="return confirm('Hapus item ini? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 transition-colors">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
