<x-layout>
    <x-slot:title>Hasil Diagnosa</x-slot>

    <div class="max-w-3xl mx-auto animate-fade-in-up">
        <!-- Print Header (Only visible when printing) -->
        <div class="hidden print:block text-center border-b-2 border-slate-300 pb-6 mb-8">
            <h1 class="text-3xl font-extrabold font-outfit text-slate-900">LAPORAN HASIL DIAGNOSIS MEDIS</h1>
            <p class="text-slate-500 mt-1">Klinik Amanah Riau Kepri - Sistem Pakar PakarDemam</p>
            <p class="text-xs text-slate-400 mt-1">Dokumen ini dicetak otomatis pada {{ date('d F Y, H:i') }} WIB</p>
        </div>

        <!-- Breadcrumb / Actions (Hidden when printing) -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4 no-print">
            <div>
                <h1 class="text-2xl font-outfit font-extrabold text-slate-900">Diagnosis Selesai</h1>
                <p class="text-slate-500 mt-0.5">Berikut adalah hasil pencocokan aturan klinis sistem pakar.</p>
            </div>
            
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('konsultasi.index') }}" class="w-1/2 sm:w-auto inline-flex justify-center items-center px-4 py-2.5 border border-slate-200 text-sm font-semibold rounded-xl text-slate-600 bg-white hover:bg-slate-50 transition-colors">
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                    </svg>
                    Konsultasi Baru
                </a>
                <button onclick="window.print();" class="w-1/2 sm:w-auto inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-xl text-white bg-medical-600 hover:bg-medical-700 shadow-md shadow-medical-100 transition-colors">
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Hasil
                </button>
            </div>
        </div>

        <!-- Main Diagnosis Report Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden print-card">
            <!-- Patient metadata bar -->
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Nama Pasien</span>
                    <span class="font-outfit font-bold text-slate-800 text-lg">{{ $riwayat->nama_pasien }}</span>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">ID Diagnosis</span>
                    <span class="font-mono text-sm font-semibold text-slate-700">#{{ str_pad($riwayat->id_diagnosa, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Tanggal Pemeriksaan</span>
                    <span class="text-sm font-medium text-slate-700">{{ $riwayat->tanggal_konsultasi->timezone('Asia/Jakarta')->format('d F Y, H:i') }} WIB</span>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8">
                <!-- Result / Diagnosis Title -->
                <div class="text-center bg-medical-50/50 border border-medical-100 rounded-3xl p-6">
                    <span class="text-sm font-semibold text-medical-800 uppercase tracking-wide">Hasil Diagnosis Utama</span>
                    <h2 class="text-2xl sm:text-3xl font-outfit font-extrabold text-medical-900 mt-2">
                        {{ $riwayat->hasil_penyakit }}
                    </h2>
                    <p class="text-sm text-slate-500 mt-2">
                        Sistem mendeteksi kecocokan gejala klinis dengan penyakit di atas.
                    </p>
                </div>

                <!-- Checked Symptoms List -->
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-outfit border-b border-slate-100 pb-2 flex justify-between">
                        <span>Gejala yang Dialami</span>
                        <span class="text-xs font-semibold text-medical-600 bg-medical-50 px-2 py-0.5 rounded-md">
                            Total: {{ is_array($riwayat->gejala_dipilih) ? count($riwayat->gejala_dipilih) : 0 }} Gejala
                        </span>
                    </h3>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        @if(is_array($riwayat->gejala_dipilih))
                            @foreach($riwayat->gejala_dipilih as $nama_gejala)
                                <li class="flex items-start text-sm text-slate-700">
                                    <span class="text-emerald-500 mr-2.5 mt-0.5 flex-shrink-0">
                                        <!-- Checked Checklist Icon -->
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </span>
                                    <span class="leading-tight">{{ $nama_gejala }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="text-slate-500 text-sm">Tidak ada gejala terpilih</li>
                        @endif
                    </ul>
                </div>

                <!-- Treatment Solutions Section -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider font-outfit border-b border-slate-100 pb-2">
                        Rekomendasi Tindakan / Solusi Awal
                    </h3>
                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 leading-relaxed text-sm text-slate-700 whitespace-pre-line">
                        {{ $riwayat->solusi }}
                    </div>
                </div>

                <!-- Warning Notice -->
                <div class="bg-red-50/50 border border-red-100 rounded-2xl p-4 flex items-start space-x-3">
                    <div class="text-red-500 flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="text-xs text-red-800 leading-relaxed font-medium">
                        <strong>PERINGATAN:</strong> Rekomendasi di atas hanyalah panduan pertolongan pertama (pertolongan awal). Apabila gejala demam terus meningkat, lemas, atau tidak kunjung turun setelah 3 hari, harap segera lakukan konsultasi langsung ke dokter atau datangi <strong>Klinik Amanah Riau Kepri</strong> untuk penanganan medis profesional.
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Signature Space (Only visible when printing) -->
        <div class="hidden print:grid grid-cols-2 mt-16 gap-12">
            <div class="text-center">
                <p class="text-sm font-semibold text-slate-700">Pasien/Wali</p>
                <div class="h-20 border-b border-dashed border-slate-300"></div>
                <p class="text-sm font-medium text-slate-600 mt-2">{{ $riwayat->nama_pasien }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm font-semibold text-slate-700">Petugas / Pakar Sistem</p>
                <div class="h-20 border-b border-dashed border-slate-300"></div>
                <p class="text-sm font-medium text-slate-600 mt-2">PakarDemam AI</p>
            </div>
        </div>

        <!-- Bottom Actions (Hidden when printing) -->
        <div class="mt-8 flex justify-center no-print">
            <a href="{{ route('beranda') }}" class="inline-flex items-center px-6 py-3 border border-slate-200 text-sm font-semibold rounded-2xl text-slate-600 bg-white hover:bg-slate-50 transition-colors">
                <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</x-layout>
