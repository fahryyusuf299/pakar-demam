<x-admin-layout>
    <x-slot:title>Riwayat Konsultasi Pasien</x-slot>

    <div class="space-y-6">
        <!-- Header & Search Bar -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-outfit font-extrabold text-slate-900">Riwayat Konsultasi Client / Pasien</h1>
                <p class="text-xs text-slate-500 font-medium mt-1">Daftar rekaman hasil diagnosa sistem pakar demam dari pengisian kuesioner pengguna.</p>
            </div>

            <form action="{{ route('admin.riwayat.index') }}" method="GET" class="w-full md:w-auto flex items-center space-x-2">
                <div class="relative w-full md:w-72">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama pasien / hasil..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:ring-2 focus:ring-medical-500 focus:border-medical-500 transition-all text-slate-800 font-medium">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                @if(!empty($search))
                    <a href="{{ route('admin.riwayat.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl text-xs font-semibold transition-all">
                        Reset
                    </a>
                @endif
                <button type="submit" class="px-4 py-2 bg-medical-600 hover:bg-medical-700 text-white rounded-2xl text-xs font-semibold transition-all">
                    Cari
                </button>
            </form>
        </div>

        <!-- Table Riwayat -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100 text-xs">
                            <th class="py-3.5 px-4">Waktu Konsultasi</th>
                            <th class="py-3.5 px-4">Nama Pasien</th>
                            <th class="py-3.5 px-4">Gejala Dipilih</th>
                            <th class="py-3.5 px-4">Hasil Diagnosa</th>
                            <th class="py-3.5 px-4 text-center">Kecocokan</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($riwayats as $item)
                            @php
                                $gejalaData = $item->gejala_dipilih ?? [];
                                $selectedGejala = is_array($gejalaData) ? ($gejalaData['selected'] ?? []) : [];
                                $score = is_array($gejalaData) ? ($gejalaData['score'] ?? null) : null;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-4 whitespace-nowrap text-xs font-medium text-slate-500">
                                    {{ $item->tanggal_konsultasi ? $item->tanggal_konsultasi->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }} WIB
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap font-bold text-slate-900 text-sm">
                                    {{ $item->nama_pasien }}
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-600 max-w-xs">
                                    @if(count($selectedGejala) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_slice($selectedGejala, 0, 3) as $g)
                                                <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md text-[11px] font-medium border border-slate-200">
                                                    {{ $g }}
                                                </span>
                                            @endforeach
                                            @if(count($selectedGejala) > 3)
                                                <span class="px-2 py-0.5 bg-teal-50 text-teal-700 rounded-md text-[11px] font-bold border border-teal-200">
                                                    +{{ count($selectedGejala) - 3 }} gejala lagi
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">Demam (G01)</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold inline-flex items-center space-x-1.5
                                        @if($item->hasil_penyakit === 'Gejala Tidak Spesifik')
                                            bg-amber-50 text-amber-700 border border-amber-200
                                        @else
                                            bg-medical-50 text-medical-800 border border-medical-200
                                        @endif">
                                        <span class="w-1.5 h-1.5 rounded-full @if($item->hasil_penyakit === 'Gejala Tidak Spesifik') bg-amber-500 @else bg-medical-600 @endif"></span>
                                        <span>{{ $item->hasil_penyakit }}</span>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-center font-bold text-xs text-slate-700">
                                    @if($score !== null)
                                        <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-800 font-mono">
                                            {{ $score }}%
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap text-center">
                                    <form action="{{ route('admin.riwayat.destroy', $item->id_diagnosa) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat konsultasi pasien {{ addslashes($item->nama_pasien) }}?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 rounded-xl transition-all border border-red-200 inline-flex items-center space-x-1 text-xs font-semibold" title="Hapus Riwayat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 font-medium">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span>Belum ada riwayat konsultasi pasien yang tercatat.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $riwayats->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
