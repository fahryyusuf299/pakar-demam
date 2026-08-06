<x-admin-layout>
    <x-slot:title>Riwayat Konsultasi Pasien</x-slot>

    <div class="space-y-6">
        <!-- Header & Search Bar -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-outfit font-extrabold text-slate-900">Riwayat Konsultasi Pasien</h1>
                <p class="text-xs text-slate-500 font-medium mt-1">Daftar rekaman hasil diagnosa sistem pakar demam dari kuesioner pengguna.</p>
            </div>

            <form action="{{ route('admin.riwayat.index') }}" method="GET" class="w-full sm:w-auto flex items-center space-x-2">
                <div class="relative w-full sm:w-72">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama pasien / hasil..." class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-medical-500 transition-all text-slate-800 font-medium">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                @if(!empty($search))
                    <a href="{{ route('admin.riwayat.index') }}" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all">
                        Reset
                    </a>
                @endif
                <button type="submit" class="px-4 py-2.5 bg-medical-600 hover:bg-medical-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                    Cari
                </button>
            </form>
        </div>

        <!-- Table Riwayat -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100 text-xs">
                            <th class="py-3.5 px-5">Waktu Konsultasi</th>
                            <th class="py-3.5 px-5">Nama Pasien</th>
                            <th class="py-3.5 px-5">Gejala Dipilih</th>
                            <th class="py-3.5 px-5">Hasil Diagnosa</th>
                            <th class="py-3.5 px-5 text-center">Kecocokan</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
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
                                <td class="py-4 px-5 whitespace-nowrap text-xs font-medium text-slate-500">
                                    {{ $item->tanggal_konsultasi ? $item->tanggal_konsultasi->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }} WIB
                                </td>
                                <td class="py-4 px-5 whitespace-nowrap font-bold text-slate-800">
                                    {{ $item->nama_pasien }}
                                </td>
                                <td class="py-4 px-5 text-xs text-slate-600 max-w-xs">
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
                                <td class="py-4 px-5 whitespace-nowrap">
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
                                <td class="py-4 px-5 whitespace-nowrap text-center font-bold text-xs text-slate-700">
                                    @if($score !== null)
                                        <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-800 font-mono">
                                            {{ $score }}%
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-5 text-right whitespace-nowrap space-x-2">
                                    <a href="{{ route('admin.riwayat.show', $item->id_diagnosa) }}"
                                       class="px-3 py-1.5 bg-teal-50 text-teal-700 hover:bg-teal-100 font-bold text-xs rounded-xl border border-teal-200 transition-colors inline-block">
                                        Detail
                                    </a>
                                    <button type="button"
                                            onclick="openDeleteRiwayatModal('{{ $item->id_diagnosa }}', '{{ addslashes($item->nama_pasien) }}', '{{ addslashes($item->hasil_penyakit) }}')"
                                            class="px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 font-bold text-xs rounded-xl border border-red-200 transition-colors">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 font-medium">
                                    Belum ada data riwayat konsultasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100">
                {{ $riwayats->links() }}
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="delete-riwayat-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Konfirmasi Hapus Riwayat -->
    <div id="delete-riwayat-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl max-w-md w-full mx-4 border border-slate-100 space-y-5 transform scale-95 transition-transform duration-300">
            <div class="flex items-center space-x-4">
                <div class="bg-red-50 text-red-600 p-3 rounded-2xl flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-outfit font-extrabold text-slate-900 text-lg">Konfirmasi Hapus Riwayat</h3>
                    <p class="text-xs text-slate-500 font-semibold">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                <p id="delete-riwayat-pasien" class="text-sm font-bold text-slate-800 leading-relaxed"></p>
                <p id="delete-riwayat-hasil" class="text-xs font-semibold text-slate-500"></p>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                Apakah Anda yakin ingin menghapus data riwayat konsultasi pasien ini dari sistem?
            </p>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeDeleteRiwayatModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                    Batal
                </button>
                <button type="button" onclick="confirmDeleteRiwayatAction()" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-md shadow-red-100 transition-all">
                    Ya, Hapus Riwayat
                </button>
            </div>
        </div>
    </div>

    <script>
    const deleteRiwayatModal = document.getElementById('delete-riwayat-modal');
    const deleteRiwayatForm = document.getElementById('delete-riwayat-form');
    const deletePasienEl = document.getElementById('delete-riwayat-pasien');
    const deleteHasilEl = document.getElementById('delete-riwayat-hasil');

    function openDeleteRiwayatModal(id, pasien, hasil) {
        deleteRiwayatForm.action = `/admin/riwayat/${id}`;
        deletePasienEl.textContent = 'Pasien: ' + pasien;
        deleteHasilEl.textContent = 'Hasil Diagnosa: ' + hasil;

        deleteRiwayatModal.classList.remove('hidden');
        setTimeout(() => {
            deleteRiwayatModal.classList.remove('opacity-0');
            deleteRiwayatModal.querySelector('.transform').classList.remove('scale-95');
        }, 10);
    }

    function closeDeleteRiwayatModal() {
        deleteRiwayatModal.classList.add('opacity-0');
        deleteRiwayatModal.querySelector('.transform').classList.add('scale-95');
        setTimeout(() => {
            deleteRiwayatModal.classList.add('hidden');
        }, 300);
    }

    function confirmDeleteRiwayatAction() {
        deleteRiwayatForm.submit();
    }
    </script>
</x-admin-layout>
