@extends('layouts.admin-eduroute', ['program' => $program, 'title' => 'Mitra Industri ' . $program->short_name, 'description' => 'Mitra milik ' . $program->name . ' — atur warna background kotak logo agar logo selalu terbaca.', 'backUrl' => route('admin.eduroute.show', $program), 'backLabel' => 'Ringkasan'])

@section('eduroute-content')
        @section('action')
<a href="{{ route('admin.eduroute.partners.create', $program) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 text-sm font-bold text-white hover:bg-blue-700 shadow-sm transition hover:shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Mitra
        </a>
    @endsection


    @if($items->isEmpty())
        <div class="bg-gray-50 shadow-xs rounded-2xl border border-gray-300 p-12 text-center">
            <div class="w-16 h-16 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7l7-4 7 4v14M9 9h1m4 0h1M9 13h1m4 0h1M9 17h1m4 0h1"/></svg>
            </div>
            <p class="text-base font-bold text-gray-800">Belum ada mitra untuk {{ $program->short_name }}.</p>
            <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">Tambahkan perusahaan atau institusi mitra, lalu atur warna background logonya.</p>
            <a href="{{ route('admin.eduroute.partners.create', $program) }}" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 text-sm font-bold text-white hover:bg-blue-700 transition shadow-sm">
                + Tambah Mitra Baru
            </a>
        </div>
    @else
        <div class="bg-gray-50 shadow-sm rounded-2xl border border-gray-300 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-200/60">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Mitra</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Warna Background</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Website</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-50 divide-y divide-gray-200">
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-100/70 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        @if($item->logo_url)
                                            <img src="{{ $item->logo_url }}" alt="Logo {{ $item->name }}" class="h-10 w-auto max-w-24 object-contain rounded-md border border-gray-300 shadow-sm" style="background-color: {{ $item->logo_bg_color_effective }};">
                                        @else
                                            <span class="h-10 w-10 rounded-lg border border-gray-300 shadow-sm flex items-center justify-center text-xs font-bold" style="background-color: {{ $item->logo_bg_color_effective }}; color: {{ $item->logo_bg_text_color }};" aria-hidden="true">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                                        @endif
                                        <div class="text-sm font-bold text-gray-900">{{ $item->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-5 w-5 rounded border border-gray-400 shadow-sm" style="background-color: {{ $item->logo_bg_color_effective }};" aria-hidden="true"></span>
                                        <span class="font-mono font-semibold text-gray-700">{{ $item->logo_bg_color_effective }}</span>
                                        @unless($item->logo_bg_color)
                                            <span class="text-gray-400">(default)</span>
                                        @endunless
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
                                    {{ $item->website_url ?? 'Tidak ada tautan' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-bold">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-gray-200 text-gray-700 text-xs">{{ $item->order_number }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.eduroute.partners.edit', [$program, $item->id]) }}" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg font-semibold transition text-xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Ubah
                                        </a>
                                        <form action="{{ route('admin.eduroute.partners.destroy', [$program, $item->id]) }}" method="POST" onsubmit="return confirm('Hapus mitra ini? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg font-semibold transition text-xs">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
