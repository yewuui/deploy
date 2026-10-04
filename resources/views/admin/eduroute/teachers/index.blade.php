@extends('layouts.admin-eduroute', ['program' => $program, 'title' => 'Guru ' . $program->short_name, 'description' => 'Guru milik ' . $program->name . ' — lengkapi profil agar pengunjung dapat membuka detail guru di halaman publik.', 'backUrl' => route('admin.eduroute.show', $program), 'backLabel' => 'Ringkasan'])

@section('eduroute-content')
        @section('action')
<a href="{{ route('admin.eduroute.teachers.create', $program) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">
            + Tambah Guru
        </a>
    @endsection


    @if($items->isEmpty())
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-8 text-center">
            <p class="text-sm font-semibold text-gray-700">Belum ada data guru untuk {{ $program->short_name }}.</p>
            <p class="mt-1 text-sm text-gray-500">Tambahkan guru beserta bidang yang diampu, lalu lengkapi jabatan, keahlian, dan pendidikan agar profil detailnya siap dibuka pengunjung.</p>
            <a href="{{ route('admin.eduroute.teachers.create', $program) }}" class="mt-4 inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition">
                + Tambah Guru
            </a>
        </div>
    @else
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Guru</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelengkapan Profil</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($item->photo_url)
                                            <img src="{{ $item->photo_url }}" alt="Foto {{ $item->name }}" class="w-10 h-10 rounded-md object-cover bg-gray-100">
                                        @else
                                            <span class="w-10 h-10 rounded-md bg-gray-100 text-gray-400 flex items-center justify-center text-xs font-bold" aria-hidden="true">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                                        @endif
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $item->name }}</div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $item->subject }}</div>
                                            @if($item->role_title)
                                                <div class="text-xs text-blue-600 mt-0.5">{{ $item->role_title }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $profileFields = [
                                            'Jabatan' => $item->role_title,
                                            'Deskripsi' => $item->description,
                                            'Keahlian' => $item->expertise,
                                            'Pendidikan' => $item->education,
                                            'Kampus' => $item->campus,
                                        ];
                                        $filledCount = count(array_filter($profileFields, fn ($value) => filled($value)));
                                        $totalCount = count($profileFields);
                                        $isComplete = $filledCount === $totalCount;
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="h-1.5 w-20 overflow-hidden rounded-full bg-gray-100" role="img" aria-label="{{ $filledCount }} dari {{ $totalCount }} informasi profil terisi">
                                            <div class="h-full rounded-full {{ $isComplete ? 'bg-green-500' : 'bg-blue-500' }}" style="width: {{ (int) round($filledCount / $totalCount * 100) }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold {{ $isComplete ? 'text-green-700' : 'text-gray-500' }}">
                                            {{ $filledCount }}/{{ $totalCount }}
                                        </span>
                                    </div>
                                    @if(! $isComplete)
                                        <p class="mt-1 text-xs text-gray-400">Lengkapi agar detail guru tampil utuh.</p>
                                    @else
                                        <p class="mt-1 text-xs text-gray-400">Detail guru siap ditampilkan.</p>
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
                                        <a href="{{ route('admin.eduroute.teachers.edit', [$program, $item->id]) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md transition">Ubah</a>
                                        <form action="{{ route('admin.eduroute.teachers.destroy', [$program, $item->id]) }}" method="POST" onsubmit="return confirm('Hapus data guru ini? Tindakan ini tidak dapat dibatalkan.');">
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
