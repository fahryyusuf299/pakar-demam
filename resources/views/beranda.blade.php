<x-layout>
    <x-slot:title>Beranda</x-slot>

    <div class="max-w-4xl mx-auto py-6 sm:py-12">
        <!-- Hero Section -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 overflow-hidden border border-slate-100 mb-8 sm:mb-12 transform hover:scale-[1.01] transition-transform duration-300">
            <div class="p-6 sm:p-12 md:flex md:items-center md:space-x-8">
                <!-- Left: Illustration / Icon -->
                <div class="md:w-1/3 flex justify-center mb-6 md:mb-0">
                    <div class="relative w-32 h-32 sm:w-40 sm:h-40 rounded-full bg-medical-50 flex items-center justify-center border-4 border-medical-100 animate-pulse">
                        <div class="w-20 h-20 sm:w-28 sm:h-28 rounded-full bg-medical-600 flex items-center justify-center text-white shadow-lg shadow-medical-200">
                            <!-- Premium Pulse SVG -->
                            <svg class="w-10 h-10 sm:w-16 sm:h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <!-- Decorative Orbs -->
                        <span class="absolute top-2 right-2 w-3.5 h-3.5 sm:w-4 sm:h-4 bg-emerald-400 rounded-full"></span>
                        <span class="absolute bottom-2 left-6 w-2.5 h-2.5 sm:w-3 sm:h-3 bg-teal-300 rounded-full"></span>
                    </div>
                </div>

                <!-- Right: Copywriting and CTA -->
                <div class="md:w-2/3 space-y-4 sm:space-y-6 text-center md:text-left">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-medical-100 text-medical-800 animate-bounce">
                            Prototype Medis v4.0
                        </span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-outfit font-extrabold text-slate-900 tracking-tight leading-tight">
                        Diagnosa Dini Gejala Demam Anda dengan Akurat & Cepat
                    </h1>
                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl font-normal">
                        Selamat datang di <strong>PakarDemam v4.0</strong>. Sistem pakar diagnosis demam berbasis <em>Symptom-Driven Checklist & Scoring System</em> dengan penalti gejala berlebih untuk hasil diagnosis presisi, akurat, dan terpercaya.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('konsultasi.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 sm:py-4 border border-transparent text-sm sm:text-base font-bold rounded-2xl text-white bg-gradient-to-r from-medical-600 to-emerald-500 hover:from-medical-700 hover:to-emerald-600 hover:shadow-lg hover:shadow-medical-100 transition-all duration-300 transform hover:-translate-y-0.5">
                            Mulai Konsultasi
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Cards / Features Section -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <!-- Card 1 -->
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-medical-50 text-medical-600 flex items-center justify-center mb-3 font-bold">
                    1
                </div>
                <h3 class="font-outfit font-bold text-slate-900 text-base sm:text-lg mb-1.5">Checklist Gejala</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Pilih gejala-gejala yang Anda rasakan secara bebas lewat daftar kuesioner interaktif.
                </p>
            </div>
            
            <!-- Card 2 -->
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-medical-50 text-medical-600 flex items-center justify-center mb-3 font-bold">
                    2
                </div>
                <h3 class="font-outfit font-bold text-slate-900 text-base sm:text-lg mb-1.5">Scoring & Penalti</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Sistem mengukur tingkat kemiripan rule (R01-R20) dan menerapkan kalkulasi penalti secara otomatis.
                </p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-medical-50 text-medical-600 flex items-center justify-center mb-3 font-bold">
                    3
                </div>
                <h3 class="font-outfit font-bold text-slate-900 text-base sm:text-lg mb-1.5">Hasil Diagnosa</h3>
                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                    Dapatkan rekomendasi penanganan, persentase kemiripan, opsi cetak hasil, serta panduan medis.
                </p>
            </div>
        </div>
    </div>
</x-layout>
