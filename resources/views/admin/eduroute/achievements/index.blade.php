@extends('layouts.admin-eduroute', ['program' => $program, 'title' => 'Prestasi ' . $program->short_name, 'description' => 'Pencapaian milik ' . $program->name . ' — tampil di halaman jejak prestasi publik.', 'backUrl' => route('admin.eduroute.show', $program), 'backLabel' => 'Ringkasan'])

@section('eduroute-content')
        @section('action')
<a href="{{ route('admin.eduroute.achievements.create', $program) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 text-sm font-bold text-white hover:bg-blue-700 shadow-sm transition hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Prestasi
        </a>
    @endsection


    @if($items->isEmpty())
        <div class="bg-white shadow-xs rounded-2xl border border-gray-200/80 p-12 text-center">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            </div>
            <p class="text-base font-bold text-gray-800">Belum ada prestasi untuk {{ $program->short_name }}.</p>
            <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">Tambahkan pencapaian siswa atau jurusan beserta deskripsi narasi lengkap untuk ditampilkan di portal publik.</p>
            <a href="{{ route('admin.eduroute.achievements.create', $program) }}" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-sm font-bold text-white hover:bg-blue-700 transition shadow-sm">
                + Tambah Prestasi Baru
            </a>
        </div>
    @else
        <div class="bg-white shadow-xs rounded-2xl border border-gray-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/75">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Foto & Prestasi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori & Tingkat</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($items as $item)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        @if($item->image_url)
                                            <img src="{{ $item->image_url }}" alt="" class="w-14 h-10 rounded-lg object-cover bg-gray-100 border border-gray-200 shrink-0">
                                        @else
                                            <div class="w-14 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 shrink-0">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-bold text-gray-900 line-clamp-1">{{ $item->title }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-2">
                                                @if($item->name)
                                                    <span class="font-semibold text-gray-700">{{ $item->name }}</span>
                                                @endif
                                                @if($item->date_label)
                                                    <span>·</span>
                                                    <span>{{ $item->date_label }}</span>
                                                @endif
                                                @if($item->description)
                                                    <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-1.5 py-0.5 rounded">Ada Deskripsi</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                    <div class="font-semibold text-gray-800">{{ $item->category ?: '-' }}</div>
                                    @if($item->level)
                                        <div class="text-gray-400 mt-0.5">Tingkat: {{ $item->level }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-bold">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gray-100 text-gray-700 text-xs">{{ $item->order_number }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.eduroute.achievements.edit', [$program, $item->id]) }}" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg font-semibold transition text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Ubah
                                        </a>
                                        <form action="{{ route('admin.eduroute.achievements.destroy', [$program, $item->id]) }}" method="POST" onsubmit="return confirm('Hapus prestasi ini? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg font-semibold transition text-xs">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
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
