@extends('layouts.admin-eduroute', ['program' => $program, 'title' => 'Prospek Karier ' . $program->short_name, 'description' => 'Bidang karier milik ' . $program->name . ' — tampil di halaman prospek karier publik.', 'backUrl' => route('admin.eduroute.show', $program), 'backLabel' => 'Ringkasan'])

@section('eduroute-content')
        @section('action')
<a href="{{ route('admin.eduroute.careers.create', $program) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">
            + Tambah Karier
        </a>
    @endsection


    @if($items->isEmpty())
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-8 text-center">
            <p class="text-sm font-semibold text-gray-700">Belum ada prospek karier untuk {{ $program->short_name }}.</p>
            <p class="mt-1 text-sm text-gray-500">Tambahkan bidang karier lulusan jurusan ini.</p>
            <a href="{{ route('admin.eduroute.careers.create', $program) }}" class="mt-4 inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">
                + Tambah Karier
            </a>
        </div>
    @else
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Karier</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $item->title }}</div>
                                    @if($item->short_description)
                                        <div class="text-xs text-gray-500 mt-1">{{ $item->short_description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->is_active)
                                        <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-md bg-green-50 text-green-700">Aktif</span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-md bg-gray-100 text-gray-500">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center font-bold">
                                    {{ $item->order_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.eduroute.careers.edit', [$program, $item->id]) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md transition">Ubah</a>
                                        <form action="{{ route('admin.eduroute.careers.destroy', [$program, $item->id]) }}" method="POST" onsubmit="return confirm('Hapus prospek karier ini? Tindakan ini tidak dapat dibatalkan.');">
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
