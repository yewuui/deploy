@extends('layouts.admin-eduroute', ['program' => $program, 'title' => 'Media ' . $program->short_name, 'description' => 'Kanal media milik ' . $program->name . ' — tautan eksternal selalu divalidasi sebelum disimpan.', 'backUrl' => route('admin.eduroute.show', $program), 'backLabel' => 'Ringkasan'])

@section('eduroute-content')
        @section('action')
<a href="{{ route('admin.eduroute.media.create', $program) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">
            + Tambah Media
        </a>
    @endsection


    @if($items->isEmpty())
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-8 text-center">
            <p class="text-sm font-semibold text-gray-700">Belum ada kanal media untuk {{ $program->short_name }}.</p>
            <p class="mt-1 text-sm text-gray-500">Tambahkan media sosial atau dokumentasi kegiatan.</p>
            <a href="{{ route('admin.eduroute.media.create', $program) }}" class="mt-4 inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">
                + Tambah Media
            </a>
        </div>
    @else
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kanal</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tautan</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->platform }}</div>
                                    @if($item->handle)
                                        <div class="text-xs text-gray-500 mt-1">{{ $item->handle }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
                                    {{ $item->url ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-bold">
                                    {{ $item->order_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.eduroute.media.edit', [$program, $item->id]) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md transition">Ubah</a>
                                        <form action="{{ route('admin.eduroute.media.destroy', [$program, $item->id]) }}" method="POST" onsubmit="return confirm('Hapus kanal media ini? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md transition">Hapus</button>
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
