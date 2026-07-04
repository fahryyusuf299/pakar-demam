<x-layout>
    <x-slot:title>Konsultasi Mandiri</x-slot>

    <div class="max-w-3xl mx-auto">
        @php
            $feverIds = ['G01', 'G02'];
            $sharedIds = ['G06', 'G12', 'G13', 'G15', 'G20', 'G40'];
            
            $fevers = $gejalas->whereIn('id_gejala', $feverIds);
            $shareds = $gejalas->whereIn('id_gejala', $sharedIds);
            $g01Uniques = $gejalas->whereNotIn('id_gejala', array_merge($feverIds, $sharedIds));
            
            $g02UniqueIds = ['G05', 'G17', 'G18'];
            $g02Uniques = $g01Uniques->whereIn('id_gejala', $g02UniqueIds);
            $g01Uniques = $g01Uniques->whereNotIn('id_gejala', $g02UniqueIds);
        @endphp
        <!-- Breadcrumb / Header -->
        <div class="mb-8 text-center sm:text-left">
            <h1 class="text-3xl font-outfit font-extrabold text-slate-900">Formulir Pemeriksaan Gejala</h1>
            <p class="text-slate-500 mt-1">Silakan lengkapi nama pasien dan pilih gejala klinis yang Anda rasakan saat ini.</p>
        </div>

        <!-- Warning Alert Message from Expert Logic -->
        @if (session('warning'))
            <div class="mb-8 bg-amber-50 border-l-4 border-amber-500 p-6 rounded-2xl shadow-sm animate-fade-in-up">
                <div class="flex items-start">
                    <div class="flex-shrink-0 text-amber-500 mt-0.5">
                        <!-- Warning Exclamation Icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-bold text-amber-900 font-outfit">Pencocokan Diagnosa Gagal</h3>
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
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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

            <!-- Symptoms Checklist Card -->
            <div class="space-y-6">
                @error('id_gejala')
                    <div class="p-4 bg-red-50 rounded-2xl text-red-600 text-sm font-semibold border border-red-100 shadow-sm">
                        {{ $message }}
                    </div>
                @enderror

                <!-- SECTION 1: Pola Demam Utama -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100 space-y-4">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="bg-amber-50 text-amber-600 p-2 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800 font-outfit">Langkah 1: Pola Demam Utama <span class="text-red-500">*</span></h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($fevers as $gejala)
                            <label class="group flex items-start p-4 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-medical-50/40 hover:border-medical-200 transition-all duration-200 cursor-pointer">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="id_gejala[]" value="{{ $gejala->id_gejala }}" id="chk-{{ $gejala->id_gejala }}"
                                           @if(is_array(old('id_gejala')) && in_array($gejala->id_gejala, old('id_gejala'))) checked @endif
                                           class="fever-checkbox w-5 h-5 rounded text-medical-600 border-slate-300 focus:ring-medical-500 cursor-pointer">
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="font-bold text-slate-800 group-hover:text-slate-900 transition-colors">
                                         {{ $gejala->nama_gejala }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- SECTION 2: Gejala Umum (Selalu Terbuka) -->
                <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100 space-y-4">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="bg-blue-50 text-blue-600 p-2 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800 font-outfit">Langkah 2: Gejala Umum (Dapat Terjadi pada Semua Demam)</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($shareds as $gejala)
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
                        @endforeach
                    </div>
                </div>

                <!-- SECTION 3A: Gejala Khusus G01 -->
                <div id="section-g01" class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100 space-y-4 transition-all duration-300 opacity-50 pointer-events-none">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-red-50 text-red-600 p-2 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.071 19.071l1.414-1.414M18.929 5.071l-1.414 1.414M1.929 12h2.828m14.486 0h2.828M5.071 4.929l1.414 1.414m12.444 12.444l-1.414-1.414M12 1.929v2.828M12 19.29v2.828"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-slate-800 font-outfit">Langkah 3A: Gejala Khusus Demam Tinggi Mendadak (G01)</h2>
                        </div>
                        <span class="text-xs font-semibold text-red-600 bg-red-50 px-2.5 py-1 rounded-lg lock-status">Terkunci</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($g01Uniques as $gejala)
                            <label class="group flex items-start p-4 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-medical-50/40 hover:border-medical-200 transition-all duration-200 cursor-pointer">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="id_gejala[]" value="{{ $gejala->id_gejala }}" id="chk-{{ $gejala->id_gejala }}"
                                           @if(is_array(old('id_gejala')) && in_array($gejala->id_gejala, old('id_gejala'))) checked @endif
                                           class="g01-symptom w-5 h-5 rounded text-medical-600 border-slate-300 focus:ring-medical-500 cursor-pointer">
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="font-medium text-slate-700 group-hover:text-slate-900 transition-colors">
                                         {{ $gejala->nama_gejala }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- SECTION 3B: Gejala Khusus G02 -->
                <div id="section-g02" class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100 space-y-4 transition-all duration-300 opacity-50 pointer-events-none">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center space-x-3">
                            <div class="bg-teal-50 text-teal-600 p-2 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-slate-800 font-outfit">Langkah 3B: Gejala Khusus Demam Bertahap (G02)</h2>
                        </div>
                        <span class="text-xs font-semibold text-teal-600 bg-teal-50 px-2.5 py-1 rounded-lg lock-status">Terkunci</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($g02Uniques as $gejala)
                            <label class="group flex items-start p-4 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-medical-50/40 hover:border-medical-200 transition-all duration-200 cursor-pointer">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="id_gejala[]" value="{{ $gejala->id_gejala }}" id="chk-{{ $gejala->id_gejala }}"
                                           @if(is_array(old('id_gejala')) && in_array($gejala->id_gejala, old('id_gejala'))) checked @endif
                                           class="g02-symptom w-5 h-5 rounded text-medical-600 border-slate-300 focus:ring-medical-500 cursor-pointer">
                                </div>
                                <div class="ml-3 text-sm">
                                    <span class="font-medium text-slate-700 group-hover:text-slate-900 transition-colors">
                                         {{ $gejala->nama_gejala }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                <a href="{{ route('beranda') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3.5 border border-slate-200 text-sm font-semibold rounded-2xl text-slate-600 bg-white hover:bg-slate-50 transition-all text-center">
                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
                
                <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-3">
                    <button type="reset" onclick="document.querySelectorAll('input[type=checkbox]').forEach(el => el.checked = false);" class="px-6 py-3.5 border border-transparent text-sm font-semibold rounded-2xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-all text-center">
                        Reset Pilihan
                    </button>
                    <button type="submit" class="inline-flex justify-center items-center px-8 py-3.5 border border-transparent text-sm font-bold rounded-2xl text-white bg-gradient-to-r from-medical-600 to-emerald-500 hover:from-medical-700 hover:to-emerald-600 hover:shadow-lg hover:shadow-medical-100 transition-all duration-300">
                        Proses Diagnosa
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const chkG01 = document.getElementById('chk-G01');
        const chkG02 = document.getElementById('chk-G02');
        
        const sectionG01 = document.getElementById('section-g01');
        const sectionG02 = document.getElementById('section-g02');
        
        const g01Symptoms = document.querySelectorAll('.g01-symptom');
        const g02Symptoms = document.querySelectorAll('.g02-symptom');

        function updateFormState() {
            if (chkG01 && chkG01.checked) {
                // Lock G02
                if (chkG02) {
                    chkG02.checked = false;
                    chkG02.disabled = true;
                    chkG02.closest('label').classList.add('opacity-40', 'pointer-events-none');
                }
                
                // Unlock G01 section
                if (sectionG01) {
                    sectionG01.classList.remove('opacity-50', 'pointer-events-none');
                    const lockG01 = sectionG01.querySelector('.lock-status');
                    if (lockG01) {
                        lockG01.textContent = 'Terbuka';
                        lockG01.className = 'text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg lock-status';
                    }
                }
                g01Symptoms.forEach(el => el.disabled = false);
                
                // Lock G02 section and clear inputs
                if (sectionG02) {
                    sectionG02.classList.add('opacity-50', 'pointer-events-none');
                    const lockG02 = sectionG02.querySelector('.lock-status');
                    if (lockG02) {
                        lockG02.textContent = 'Terkunci';
                        lockG02.className = 'text-xs font-semibold text-teal-600 bg-teal-50 px-2.5 py-1 rounded-lg lock-status';
                    }
                }
                g02Symptoms.forEach(el => {
                    el.checked = false;
                    el.disabled = true;
                });
            } else if (chkG02 && chkG02.checked) {
                // Lock G01
                if (chkG01) {
                    chkG01.checked = false;
                    chkG01.disabled = true;
                    chkG01.closest('label').classList.add('opacity-40', 'pointer-events-none');
                }
                
                // Unlock G02 section
                if (sectionG02) {
                    sectionG02.classList.remove('opacity-50', 'pointer-events-none');
                    const lockG02 = sectionG02.querySelector('.lock-status');
                    if (lockG02) {
                        lockG02.textContent = 'Terbuka';
                        lockG02.className = 'text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg lock-status';
                    }
                }
                g02Symptoms.forEach(el => el.disabled = false);
                
                // Lock G01 section and clear inputs
                if (sectionG01) {
                    sectionG01.classList.add('opacity-50', 'pointer-events-none');
                    const lockG01 = sectionG01.querySelector('.lock-status');
                    if (lockG01) {
                        lockG01.textContent = 'Terkunci';
                        lockG01.className = 'text-xs font-semibold text-red-600 bg-red-50 px-2.5 py-1 rounded-lg lock-status';
                    }
                }
                g01Symptoms.forEach(el => {
                    el.checked = false;
                    el.disabled = true;
                });
            } else {
                // Both unchecked: reset states
                if (chkG01) {
                    chkG01.disabled = false;
                    chkG01.closest('label').classList.remove('opacity-40', 'pointer-events-none');
                }
                if (chkG02) {
                    chkG02.disabled = false;
                    chkG02.closest('label').classList.remove('opacity-40', 'pointer-events-none');
                }
                
                // Lock both sections
                if (sectionG01) {
                    sectionG01.classList.add('opacity-50', 'pointer-events-none');
                    const lockG01 = sectionG01.querySelector('.lock-status');
                    if (lockG01) {
                        lockG01.textContent = 'Terkunci';
                        lockG01.className = 'text-xs font-semibold text-red-600 bg-red-50 px-2.5 py-1 rounded-lg lock-status';
                    }
                }
                g01Symptoms.forEach(el => {
                    el.checked = false;
                    el.disabled = true;
                });
                
                if (sectionG02) {
                    sectionG02.classList.add('opacity-50', 'pointer-events-none');
                    const lockG02 = sectionG02.querySelector('.lock-status');
                    if (lockG02) {
                        lockG02.textContent = 'Terkunci';
                        lockG02.className = 'text-xs font-semibold text-teal-600 bg-teal-50 px-2.5 py-1 rounded-lg lock-status';
                    }
                }
                g02Symptoms.forEach(el => {
                    el.checked = false;
                    el.disabled = true;
                });
            }
        }

        if (chkG01) chkG01.addEventListener('change', updateFormState);
        if (chkG02) chkG02.addEventListener('change', updateFormState);
        
        // Initial call to handle old input state
        updateFormState();

        // Reset button logic
        const resetBtn = document.querySelector('button[type="reset"]');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('input[type=checkbox]').forEach(el => {
                    el.checked = false;
                    el.disabled = false;
                    const label = el.closest('label');
                    if (label) label.classList.remove('opacity-40', 'pointer-events-none');
                });
                updateFormState();
            });
        }
    });
    </script>
</x-layout>
