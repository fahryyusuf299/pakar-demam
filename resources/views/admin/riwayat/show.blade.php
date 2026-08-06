<x-admin-layout>
    <x-slot:title>Detail Riwayat Konsultasi</x-slot>

    @php
        $selectedList = is_array($riwayat->gejala_dipilih) && isset($riwayat->gejala_dipilih['selected']) 
            ? $riwayat->gejala_dipilih['selected'] 
            : (is_array($riwayat->gejala_dipilih) ? $riwayat->gejala_dipilih : []);
            
        $matchedList = is_array($riwayat->gejala_dipilih) && isset($riwayat->gejala_dipilih['matched']) 
            ? $riwayat->gejala_dipilih['matched'] 
            : [];
            
        $score = is_array($riwayat->gejala_dipilih) && isset($riwayat->gejala_dipilih['score']) 
            ? $riwayat->gejala_dipilih['score'] 
            : 100.0;
            
        $isSpecific = $riwayat->hasil_penyakit !== 'Gejala Tidak Spesifik' && $score >= 50;
        
        $otherList = array_diff($selectedList, $matchedList);
    @endphp

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Breadcrumb & Back Navigation (Disembunyikan saat cetak) -->
        <div class="flex items-center justify-between no-print">
            <div class="flex items-center space-x-2 text-xs font-semibold text-slate-500">
                <a href="{{ route('admin.riwayat.index') }}" class="hover:text-medical-600 transition-colors">Riwayat Konsultasi</a>
                <span>&rarr;</span>
                <span class="text-slate-800 font-bold">Detail Pasien {{ $riwayat->nama_pasien }}</span>
            </div>

            <a href="{{ route('admin.riwayat.index') }}" class="inline-flex items-center px-3.5 py-1.5 border border-slate-200 text-xs font-bold rounded-xl text-slate-600 bg-white hover:bg-slate-50 transition-colors">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Riwayat Konsultasi
            </a>
        </div>

        <!-- Print Header (Hanya tampil saat pencetakan) -->
        <div class="hidden print:block text-center border-b-2 border-slate-300 pb-6 mb-8">
            <h1 class="text-3xl font-extrabold font-outfit text-slate-900">LAPORAN HASIL DIAGNOSIS MEDIS</h1>
            <p class="text-slate-500 mt-1">Klinik Amanah Riau Kepri - Sistem Pakar PakarDemam</p>
            <p class="text-xs text-slate-400 mt-1">Dokumen ini dicetak dari Admin Panel pada {{ date('d F Y, H:i') }} WIB</p>
        </div>

        <!-- Header Bar & Actions (Disembunyikan saat cetak) -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 no-print bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div>
                <h1 class="text-2xl font-outfit font-extrabold text-slate-900">Detail Konsultasi Pasien</h1>
                <p class="text-xs text-slate-500 font-medium mt-1">Laporan rincian hasil diagnosa dan gejala yang diisi oleh pasien.</p>
            </div>

            <div class="flex gap-3 w-full sm:w-auto">
                <button onclick="window.print();" class="w-1/2 sm:w-auto inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-xs sm:text-sm font-bold rounded-xl text-white bg-medical-600 hover:bg-medical-700 shadow-md shadow-medical-100 transition-colors">
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Hasil
                </button>
                <button type="button"
                        onclick="openDeleteRiwayatModal('{{ $riwayat->id_diagnosa }}', '{{ addslashes($riwayat->nama_pasien) }}', '{{ addslashes($riwayat->hasil_penyakit) }}')"
                        class="w-1/2 sm:w-auto inline-flex justify-center items-center px-4 py-2.5 border border-red-200 text-xs sm:text-sm font-bold rounded-xl text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                    <svg class="mr-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            </div>
        </div>

        <!-- Card Laporan Diagnosis (Struktur Persis Seperti Client Hasil) -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden print-card">
            <!-- Patient metadata bar -->
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Nama Pasien</span>
                    <span class="font-outfit font-bold text-slate-800 text-lg">{{ $riwayat->nama_pasien }}</span>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">ID Diagnosis</span>
                    <span class="font-mono text-sm font-semibold text-slate-700">#{{ substr($riwayat->id_diagnosa, 0, 8) }}</span>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Tanggal Pemeriksaan</span>
                    <span class="text-sm font-medium text-slate-700">
                        @if($riwayat->tanggal_konsultasi)
                            {{ $riwayat->tanggal_konsultasi->timezone('Asia/Jakarta')->format('d F Y, H:i') }} WIB
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8">
                
                @if($isSpecific)
                    <!-- Specific Match Layout -->
                    <div class="bg-medical-50/40 border border-medical-100 rounded-3xl p-6 space-y-4">
                        <div class="text-center">
                            <span class="text-xs font-bold text-medical-800 uppercase tracking-widest bg-medical-50 px-3 py-1 rounded-full">Hasil Diagnosis Utama</span>
                            <h2 class="text-2xl sm:text-3xl font-outfit font-extrabold text-medical-900 mt-3">
                                {{ $riwayat->hasil_penyakit }}
                            </h2>
                        </div>
                        
                        <!-- Confidence Score Bar -->
                        <div class="space-y-1.5 max-w-md mx-auto">
                            <div class="flex justify-between text-xs font-bold text-slate-500">
                                <span>Tingkat Kemiripan Gejala</span>
                                <span class="text-medical-600">{{ $score }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-medical-500 to-emerald-500 h-full rounded-full transition-all duration-1000" style="width: {{ $score }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Symptoms Matching Lists -->
                    <div class="space-y-5">
                        <div class="space-y-3">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-outfit border-b border-slate-100 pb-2 flex justify-between">
                                <span>Gejala Cocok dengan Basis Aturan</span>
                                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                                    {{ count($matchedList) }} Cocok
                                </span>
                            </h3>
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                @foreach($matchedList as $nama_gejala)
                                    <li class="flex items-start text-sm text-slate-700">
                                        <span class="text-emerald-500 mr-2.5 mt-0.5 flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </span>
                                        <span class="leading-tight">{{ $nama_gejala }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @if(count($otherList) > 0)
                            <div class="space-y-3">
                                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider font-outfit border-b border-slate-100 pb-2 flex justify-between">
                                    <span>Gejala Lain yang Dialami</span>
                                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                        {{ count($otherList) }} Tambahan
                                    </span>
                                </h3>
                                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                    @foreach($otherList as $nama_gejala)
                                        <li class="flex items-start text-sm text-slate-500">
                                            <span class="text-slate-400 mr-2.5 mt-1.5 flex-shrink-0 w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                            <span class="leading-tight">{{ $nama_gejala }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Treatment Solutions Section -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-outfit border-b border-slate-100 pb-2">
                            Rekomendasi Tindakan / Solusi Awal
                        </h3>
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 leading-relaxed text-sm text-slate-700 font-normal">{{ trim($riwayat->solusi) }}</div>
                    </div>
                @else
                    <!-- Non-Specific Warning Layout -->
                    <div class="bg-amber-50/50 border border-amber-200 rounded-3xl p-6 sm:p-8 space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 text-amber-500 mt-1">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.071 19.071l1.414-1.414M18.929 5.071l-1.414 1.414M1.929 12h2.828m14.486 0h2.828M5.071 4.929l1.414 1.414m12.444 12.444l-1.414-1.414M12 1.929v2.828M12 19.29v2.828"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-amber-900 font-outfit">Gejala Tidak Spesifik</h3>
                                <p class="mt-2 text-sm text-amber-700 leading-relaxed font-semibold">
                                    Gejala tidak spesifik untuk mengarah ke penyakit infeksi dalam basis pengetahuan. Silakan isi ulang kuesioner atau segera lakukan konsultasi langsung ke Klinik Amanah Riau Kepri.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Checked Symptoms List -->
                    <div class="space-y-3">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-outfit border-b border-slate-100 pb-2">
                            Gejala yang Anda Pilih
                        </h3>
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            @foreach($selectedList as $nama_gejala)
                                <li class="flex items-start text-sm text-slate-600">
                                    <span class="text-amber-500 mr-2.5 mt-0.5 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </span>
                                    <span class="leading-tight">{{ $nama_gejala }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Warning Notice -->
                <div class="bg-red-50/50 border border-red-100 rounded-2xl p-4 flex items-start space-x-3">
                    <div class="text-red-500 flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="text-xs text-red-800 leading-relaxed font-medium">
                        <strong>PERINGATAN:</strong> Rekomendasi di atas hanyalah panduan pertolongan pertama (pertolongan awal). Apabila gejala demam terus meningkat, lemas, atau tidak kunjung turun setelah 3 hari, harap segera lakukan konsultasi langsung ke dokter atau datangi <strong>Klinik Amanah Riau Kepri</strong> untuk penanganan medis profesional.
                    </div>
                </div>
            </div>

            </div>
        </div>

        <!-- Print Signature Space (Hanya tampil saat pencetakan) -->
        <div class="hidden print:grid grid-cols-2 mt-16 gap-12">
            <div class="text-center">
                <p class="text-sm font-semibold text-slate-700">Pasien/Wali</p>
                <div class="h-20 border-b border-dashed border-slate-300"></div>
                <p class="text-sm font-medium text-slate-600 mt-2">{{ $riwayat->nama_pasien }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm font-semibold text-slate-700">Petugas / Pakar Sistem</p>
                <div class="h-20 border-b border-dashed border-slate-300"></div>
                <p class="text-sm font-medium text-slate-600 mt-2">Fahry Yusuf</p>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="delete-riwayat-form" action="{{ route('admin.riwayat.destroy', $riwayat->id_diagnosa) }}" method="POST" class="hidden">
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
                <p class="text-sm font-bold text-slate-800 leading-relaxed">Pasien: {{ $riwayat->nama_pasien }}</p>
                <p class="text-xs font-semibold text-slate-500">Hasil Diagnosa: {{ $riwayat->hasil_penyakit }}</p>
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

    function openDeleteRiwayatModal() {
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
