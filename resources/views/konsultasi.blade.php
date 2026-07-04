<x-layout>
    <x-slot:title>Konsultasi Mandiri</x-slot>

    <div class="max-w-3xl mx-auto">
        @php
            $categories = [
                'Karakteristik Demam' => ['G01', 'G02', 'G35'],
                'Gejala Pencernaan' => ['G05', 'G13', 'G14', 'G15', 'G16', 'G17', 'G18', 'G34'],
                'Gejala Pernapasan & Tenggorokan' => ['G27', 'G28', 'G29', 'G30', 'G31', 'G38', 'G39'],
                'Gejala Kulit & Tubuh' => [
                    'G03', 'G04', 'G06', 'G07', 'G08', 'G09', 'G10', 'G11', 
                    'G12', 'G19', 'G20', 'G21', 'G22', 'G23', 'G24', 'G25', 
                    'G26', 'G32', 'G33', 'G36', 'G37', 'G40'
                ]
            ];
        @endphp
        
        <!-- Header -->
        <div class="mb-8 text-center sm:text-left">
            <h1 class="text-3xl font-outfit font-extrabold text-slate-900">Formulir Pemeriksaan Gejala</h1>
            <p class="text-slate-500 mt-1">Silakan lengkapi nama pasien dan pilih gejala klinis yang dirasakan saat ini.</p>
        </div>

        <!-- Warning Alert Message from Expert Logic -->
        @if (session('warning'))
            <div class="mb-8 bg-amber-50 border-l-4 border-amber-500 p-6 rounded-2xl shadow-sm animate-fade-in-up">
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-amber-500 mt-0.5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-bold text-amber-900 font-outfit">Perhatian</h3>
                        <p class="mt-1 text-sm text-amber-700 leading-relaxed font-medium">
                            {{ session('warning') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Consultation Form -->
        <form action="{{ route('konsultasi.proses') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Patient Name Card -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100 space-y-4">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="bg-medical-50 text-medical-600 p-2 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 font-outfit">Informasi Pasien</h2>
                </div>

                <div>
                    <label for="nama_pasien" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap Pasien <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_pasien" id="nama_pasien" value="{{ old('nama_pasien') }}" placeholder="Masukkan nama pasien" 
                           class="w-full px-4 py-3 rounded-xl border @error('nama_pasien') border-red-300 bg-red-50 @else border-slate-200 @enderror focus:outline-none focus:ring-2 focus:ring-medical-500 focus:border-transparent transition-all">
                    @error('nama_pasien')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Symptoms Checklist -->
            <div class="space-y-8">
                @error('pola_demam')
                    <div class="p-4 bg-red-50 rounded-2xl text-red-600 text-sm font-semibold border border-red-100 shadow-sm">
                        {{ $message }}
                    </div>
                @enderror

                @error('id_gejala')
                    <div class="p-4 bg-red-50 rounded-2xl text-red-600 text-sm font-semibold border border-red-100 shadow-sm">
                        {{ $message }}
                    </div>
                @enderror

                @foreach($categories as $categoryName => $symptomIds)
                    @php
                        $categoryGejalas = $gejalas->whereIn('id_gejala', $symptomIds);
                    @endphp
                    
                    @if($categoryGejalas->isNotEmpty())
                        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100 space-y-4">
                            <div class="flex items-center space-x-3 mb-2 pb-3 border-b border-slate-100">
                                @if($categoryName === 'Karakteristik Demam')
                                    <div class="bg-amber-50 text-amber-600 p-2 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                @elseif($categoryName === 'Gejala Pencernaan')
                                    <div class="bg-emerald-50 text-emerald-600 p-2 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                        </svg>
                                    </div>
                                @elseif($categoryName === 'Gejala Pernapasan & Tenggorokan')
                                    <div class="bg-blue-50 text-blue-600 p-2 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="bg-rose-50 text-rose-600 p-2 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                    </div>
                                @endif
                                <h2 class="text-lg font-bold text-slate-800 font-outfit">
                                    {{ $categoryName }}
                                    @if($categoryName === 'Karakteristik Demam')
                                        <span class="text-red-500 text-sm">*</span>
                                    @endif
                                </h2>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($categoryGejalas as $gejala)
                                    @if($gejala->id_gejala === 'G01' || $gejala->id_gejala === 'G02')
                                        <label class="group flex items-start p-4 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-medical-50/40 hover:border-medical-200 transition-all duration-200 cursor-pointer">
                                            <div class="flex items-center h-5">
                                                <input type="radio" name="pola_demam" value="{{ $gejala->id_gejala }}" id="rad-{{ $gejala->id_gejala }}"
                                                       @if(old('pola_demam') === $gejala->id_gejala) checked @endif
                                                       class="w-5 h-5 text-medical-600 border-slate-300 focus:ring-medical-500 cursor-pointer">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <span class="font-bold text-slate-800 group-hover:text-slate-900 transition-colors">
                                                     {{ $gejala->nama_gejala }}
                                                </span>
                                            </div>
                                        </label>
                                    @else
                                        <label class="group flex items-start p-4 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-medical-50/40 hover:border-medical-200 transition-all duration-200 cursor-pointer">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" name="id_gejala[]" value="{{ $gejala->id_gejala }}" id="chk-{{ $gejala->id_gejala }}"
                                                       @if(is_array(old('id_gejala')) && in_array($gejala->id_gejala, old('id_gejala'))) checked @endif
                                                       class="w-5 h-5 rounded text-medical-600 border-slate-300 focus:ring-medical-500 cursor-pointer">
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <span class="font-medium text-slate-700 group-hover:text-slate-900 transition-colors">
                                                     {{ $gejala->nama_gejala }}
                                                </span>
                                            </div>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                <a href="{{ route('beranda') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3.5 border border-slate-200 text-sm font-semibold rounded-2xl text-slate-600 bg-white hover:bg-slate-50 transition-all text-center">
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
                
                <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-3">
                    <button type="reset" class="px-6 py-3.5 border border-transparent text-sm font-semibold rounded-2xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-all text-center">
                        Reset Pilihan
                    </button>
                    <button type="submit" class="inline-flex justify-center items-center px-8 py-3.5 border border-transparent text-sm font-bold rounded-2xl text-white bg-gradient-to-r from-medical-600 to-emerald-500 hover:from-medical-700 hover:to-emerald-600 hover:shadow-lg hover:shadow-medical-100 transition-all duration-300">
                        Proses Diagnosa
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Premium Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-slate-900/80 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white p-8 rounded-3xl shadow-2xl flex flex-col items-center max-w-xs text-center space-y-4 transform scale-95 transition-transform duration-300">
            <div class="relative w-20 h-20">
                <!-- Outer spinning ring -->
                <div class="absolute inset-0 border-4 border-slate-100 border-t-medical-600 rounded-full animate-spin"></div>
                <!-- Inner pulse icon -->
                <div class="absolute inset-0 flex items-center justify-center text-medical-600 animate-pulse">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
            <h3 class="font-outfit font-extrabold text-slate-800 text-lg">Menganalisis Gejala...</h3>
            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                Sistem pakar sedang mengukur tingkat kemiripan dan menerapkan penalti diagnosis. Harap tunggu sebentar.
            </p>
        </div>
    </div>

    <!-- Premium Custom Warning Modal -->
    <div id="warning-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl max-w-sm w-full mx-4 border border-slate-100 space-y-5 transform scale-95 transition-transform duration-300">
            <div class="flex items-center space-x-4">
                <div class="bg-amber-50 text-amber-500 p-3 rounded-2xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-outfit font-extrabold text-slate-800 text-lg">Batas Gejala Terpenuhi</h3>
                    <p class="text-xs text-slate-500 font-semibold">Peringatan Pengisian Kuesioner</p>
                </div>
            </div>
            
            <p class="text-sm text-slate-600 leading-relaxed font-semibold">
                Maaf, Anda hanya dapat memilih <strong>maksimal 10 gejala penyerta</strong> untuk menjaga keakuratan skoring diagnosis pada sistem pakar.
            </p>
            
            <div class="flex justify-end">
                <button type="button" id="close-warning-btn" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-2xl shadow-md transition-colors">
                    Dimengerti
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="id_gejala[]"]');
        const maxChecked = 10;
        
        // Modal & Overlay elements
        const warningModal = document.getElementById('warning-modal');
        const closeWarningBtn = document.getElementById('close-warning-btn');
        const loadingOverlay = document.getElementById('loading-overlay');
        const form = document.querySelector('form');

        function showWarningModal() {
            warningModal.classList.remove('hidden');
            setTimeout(() => {
                warningModal.classList.remove('opacity-0');
                warningModal.querySelector('.transform').classList.remove('scale-95');
            }, 10);
        }

        function hideWarningModal() {
            warningModal.classList.add('opacity-0');
            warningModal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                warningModal.classList.add('hidden');
            }, 300);
        }

        if (closeWarningBtn) {
            closeWarningBtn.addEventListener('click', hideWarningModal);
        }
        
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                const checkedCount = document.querySelectorAll('input[type="checkbox"][name="id_gejala[]"]:checked').length;
                if (checkedCount > maxChecked) {
                    this.checked = false;
                    showWarningModal();
                }
            });
        });
        
        const resetBtn = document.querySelector('button[type="reset"]');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('input[type="checkbox"]').forEach(el => el.checked = false);
                document.querySelectorAll('input[type="radio"]').forEach(el => el.checked = false);
            });
        }

        // Show loading screen transition on form submit
        if (form && loadingOverlay) {
            form.addEventListener('submit', function (e) {
                // If form is valid (e.g. required inputs completed), show loader
                const nameInput = document.getElementById('nama_pasien');
                const radioChecked = document.querySelector('input[type="radio"][name="pola_demam"]:checked');
                
                if (nameInput && nameInput.value.trim() !== '' && radioChecked) {
                    loadingOverlay.classList.remove('hidden');
                    setTimeout(() => {
                        loadingOverlay.classList.remove('opacity-0');
                        loadingOverlay.querySelector('.transform').classList.remove('scale-95');
                    }, 10);
                }
            });
        }
    });
    </script>
</x-layout>
