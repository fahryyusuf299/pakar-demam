<x-admin-layout>
    <x-slot:title>Detail Aturan - {{ $penyakit->nama_penyakit }}</x-slot>

    @php
        $colorPalette = [
            'bg-blue-50 text-blue-700 border-blue-200',
            'bg-purple-50 text-purple-700 border-purple-200',
            'bg-emerald-50 text-emerald-700 border-emerald-200',
            'bg-amber-50 text-amber-700 border-amber-200',
            'bg-rose-50 text-rose-700 border-rose-200',
            'bg-teal-50 text-teal-700 border-teal-200',
            'bg-indigo-50 text-indigo-700 border-indigo-200',
            'bg-cyan-50 text-cyan-700 border-cyan-200',
            'bg-orange-50 text-orange-700 border-orange-200',
            'bg-pink-50 text-pink-700 border-pink-200',
            'bg-lime-50 text-lime-700 border-lime-200',
            'bg-sky-50 text-sky-700 border-sky-200',
            'bg-violet-50 text-violet-700 border-violet-200',
            'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200',
        ];
    @endphp

    <div class="space-y-6">
        <!-- Breadcrumb & Back Navigation -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 text-xs font-semibold text-slate-500">
                <a href="{{ route('admin.rules.index') }}" class="hover:text-medical-600 transition-colors">Basis Aturan</a>
                <span>&rarr;</span>
                <span class="text-slate-800 font-bold">[{{ $penyakit->id_penyakit }}] {{ $penyakit->nama_penyakit }}</span>
            </div>

            <a href="{{ route('admin.rules.index') }}" class="inline-flex items-center px-3.5 py-1.5 border border-slate-200 text-xs font-bold rounded-xl text-slate-600 bg-white hover:bg-slate-50 transition-colors">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Basis Aturan
            </a>
        </div>

        <!-- Header Card -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="font-mono font-bold text-sm text-medical-700 bg-medical-50 px-2.5 py-1 rounded-md">{{ $penyakit->id_penyakit }}</span>
                    <h1 class="text-2xl font-outfit font-extrabold text-slate-900">{{ $penyakit->nama_penyakit }}</h1>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-1">Kelola daftar indikator gejala yang membentuk aturan diagnosis penyakit ini.</p>
            </div>
            <button onclick="document.getElementById('add-rule-symptom-modal').classList.remove('hidden')"
                    class="px-5 py-2.5 bg-gradient-to-r from-medical-600 to-emerald-500 hover:from-medical-700 hover:to-emerald-600 text-white text-xs font-bold rounded-2xl shadow-md transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Gejala ke Aturan</span>
            </button>
        </div>

        <!-- Table Gejala Terhubung -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-outfit font-bold text-slate-800 text-sm">Daftar Indikator Gejala Terpasang</h3>
                <span class="text-xs font-bold text-medical-700 bg-medical-50 px-2.5 py-1 rounded-full">{{ $penyakit->gejala->count() }} Gejala</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100 text-xs">
                            <th class="py-3.5 px-5">Kode ID</th>
                            <th class="py-3.5 px-5">Deskripsi Gejala Klinis</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($penyakit->gejala as $g)
                            @php
                                $numId = (int) preg_replace('/[^0-9]/', '', $g->id_gejala);
                                $colorStyle = $colorPalette[$numId % count($colorPalette)];
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <span class="font-mono font-bold text-xs border px-2.5 py-1 rounded-md inline-block shadow-2xs {{ $colorStyle }}">
                                        {{ $g->id_gejala }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 font-medium text-slate-800">
                                    {{ $g->nama_gejala }}
                                </td>
                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    @php
                                        // Cari ID rule relasinya
                                        $rulePivot = \App\Models\AturanRule::where('id_penyakit', $penyakit->id_penyakit)
                                            ->where('id_gejala', $g->id_gejala)
                                            ->first();
                                    @endphp
                                    @if($rulePivot)
                                        <button type="button"
                                                onclick="openDeleteRuleDetailModal('{{ $rulePivot->id_rule }}', '{{ $g->id_gejala }}', '{{ addslashes($g->nama_gejala) }}')"
                                                class="px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 font-bold text-xs rounded-xl border border-red-200 transition-colors">
                                            Hapus dari Aturan
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-400 font-medium">Belum ada gejala yang terhubung dengan penyakit ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bottom Action Footer below table -->
            <div class="p-4 bg-slate-50/80 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-3">
                <a href="{{ route('admin.rules.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-slate-200 text-xs font-bold rounded-xl text-slate-600 bg-white hover:bg-slate-50 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Halaman Sebelumnya
                </a>

                <button onclick="document.getElementById('add-rule-symptom-modal').classList.remove('hidden')"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 bg-gradient-to-r from-medical-600 to-emerald-500 hover:from-medical-700 hover:to-emerald-600 text-white text-xs font-bold rounded-xl shadow-sm transition-all space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>+ Tambah Gejala ke Aturan Ini</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="delete-rule-detail-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Konfirmasi Hapus Gejala dari Aturan -->
    <div id="delete-rule-detail-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl max-w-md w-full mx-4 border border-slate-100 space-y-5 transform scale-95 transition-transform duration-300">
            <div class="flex items-center space-x-4">
                <div class="bg-red-50 text-red-600 p-3 rounded-2xl flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-outfit font-extrabold text-slate-900 text-lg">Hapus Gejala dari Aturan</h3>
                    <p class="text-xs text-slate-500 font-semibold">Konfirmasi pelepasan relasi</p>
                </div>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                <span id="delete-detail-gejala-id" class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md"></span>
                <p id="delete-detail-gejala-name" class="text-sm font-semibold text-slate-800 pt-1 leading-relaxed"></p>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                Apakah Anda yakin ingin melepas indikator gejala ini dari aturan penyakit <strong>{{ $penyakit->nama_penyakit }}</strong>?
            </p>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeDeleteRuleDetailModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                    Batal
                </button>
                <button type="button" onclick="confirmDeleteRuleDetailAction()" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-md shadow-red-100 transition-all">
                    Ya, Lepas Gejala
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Gejala ke Aturan -->
    <div id="add-rule-symptom-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl max-w-md w-full mx-4 border border-slate-100 space-y-5">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-outfit font-extrabold text-slate-800 text-lg">Tambah Gejala ke Aturan</h3>
                <button onclick="document.getElementById('add-rule-symptom-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('admin.rules.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm">
                @csrf
                <input type="hidden" name="id_penyakit" value="{{ $penyakit->id_penyakit }}">

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Penyakit Target</label>
                    <input type="text" readonly value="[{{ $penyakit->id_penyakit }}] {{ $penyakit->nama_penyakit }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 font-bold text-slate-700">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Gejala Indikator Baru</label>
                    <select name="id_gejala" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-medical-500 font-medium">
                        <option value="">-- Pilih Gejala --</option>
                        @foreach($availableGejalas as $g)
                            <option value="{{ $g->id_gejala }}">[{{ $g->id_gejala }}] {{ $g->nama_gejala }}</option>
                        @endforeach
                    </select>
                    @if($availableGejalas->isEmpty())
                        <p class="text-xs text-amber-600 font-medium mt-1">Semua gejala yang tersedia sudah terhubung ke penyakit ini.</p>
                    @endif
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('add-rule-symptom-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl">Batal</button>
                    <button type="submit" @if($availableGejalas->isEmpty()) disabled @endif class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white font-bold rounded-xl shadow-md disabled:opacity-50">Tambahkan Gejala</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    const deleteRuleDetailModal = document.getElementById('delete-rule-detail-modal');
    const deleteRuleDetailForm = document.getElementById('delete-rule-detail-form');
    const targetGejalaIdEl = document.getElementById('delete-detail-gejala-id');
    const targetGejalaNameEl = document.getElementById('delete-detail-gejala-name');

    function openDeleteRuleDetailModal(ruleId, gejalaId, gejalaName) {
        deleteRuleDetailForm.action = `/admin/rules/${ruleId}`;
        targetGejalaIdEl.textContent = gejalaId;
        targetGejalaNameEl.textContent = gejalaName;

        deleteRuleDetailModal.classList.remove('hidden');
        setTimeout(() => {
            deleteRuleDetailModal.classList.remove('opacity-0');
            deleteRuleDetailModal.querySelector('.transform').classList.remove('scale-95');
        }, 10);
    }

    function closeDeleteRuleDetailModal() {
        deleteRuleDetailModal.classList.add('opacity-0');
        deleteRuleDetailModal.querySelector('.transform').classList.add('scale-95');
        setTimeout(() => {
            deleteRuleDetailModal.classList.add('hidden');
        }, 300);
    }

    function confirmDeleteRuleDetailAction() {
        deleteRuleDetailForm.submit();
    }
    </script>
</x-admin-layout>
