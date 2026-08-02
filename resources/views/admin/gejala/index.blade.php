<x-admin-layout>
    <x-slot:title>Kelola Data Gejala</x-slot>

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
        <!-- Page Header & Action Button -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-outfit font-extrabold text-slate-900">Data Master Gejala</h1>
                <p class="text-xs text-slate-500 font-medium">Kelola daftar indikator gejala klinis demam (Kode & Deskripsi Gejala).</p>
            </div>
            <button onclick="document.getElementById('add-gejala-modal').classList.remove('hidden')"
                    class="px-5 py-2.5 bg-gradient-to-r from-medical-600 to-emerald-500 hover:from-medical-700 hover:to-emerald-600 text-white text-xs font-bold rounded-2xl shadow-md transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Gejala Baru</span>
            </button>
        </div>

        <!-- Table Data Gejala -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100 text-xs">
                            <th class="py-3.5 px-5">ID Gejala</th>
                            <th class="py-3.5 px-5">Deskripsi Gejala Klinis</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($gejalas as $g)
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
                                <td class="py-4 px-5 text-right whitespace-nowrap space-x-2">
                                    <button type="button" 
                                            onclick="openEditModal('{{ $g->id_gejala }}', '{{ addslashes($g->nama_gejala) }}')"
                                            class="px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold text-xs rounded-xl border border-amber-200 transition-colors">
                                        Edit
                                    </button>
                                    <button type="button"
                                            onclick="openDeleteModal('{{ $g->id_gejala }}', '{{ addslashes($g->nama_gejala) }}')"
                                            class="px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 font-bold text-xs rounded-xl border border-red-200 transition-colors">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-400 font-medium">Belum ada data gejala.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="delete-gejala-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Konfirmasi Hapus Gejala -->
    <div id="delete-gejala-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl max-w-md w-full mx-4 border border-slate-100 space-y-5 transform scale-95 transition-transform duration-300">
            <div class="flex items-center space-x-4">
                <div class="bg-red-50 text-red-600 p-3 rounded-2xl flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-outfit font-extrabold text-slate-900 text-lg">Konfirmasi Hapus Gejala</h3>
                    <p class="text-xs text-slate-500 font-semibold">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                <span id="delete-target-id" class="text-xs font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md"></span>
                <p id="delete-target-name" class="text-sm font-semibold text-slate-800 pt-1 leading-relaxed"></p>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                Apakah Anda yakin ingin menghapus data gejala ini dari basis pengetahuan sistem pakar? Pemetaan aturan rule terkait juga akan disesuaikan.
            </p>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                    Batal
                </button>
                <button type="button" onclick="confirmDeleteAction()" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-md shadow-red-100 transition-all">
                    Ya, Hapus Gejala
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Gejala -->
    <div id="add-gejala-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl max-w-md w-full mx-4 border border-slate-100 space-y-5">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-outfit font-extrabold text-slate-800 text-lg">Tambah Gejala Baru</h3>
                <button onclick="document.getElementById('add-gejala-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('admin.gejala.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kode ID Gejala (misal: G41)</label>
                    <input type="text" name="id_gejala" required placeholder="G41" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-medical-500 font-mono">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi / Nama Gejala</label>
                    <textarea name="nama_gejala" rows="3" required placeholder="Deskripsi gejala klinis..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-medical-500"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('add-gejala-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white font-bold rounded-xl shadow-md">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Gejala -->
    <div id="edit-gejala-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl max-w-md w-full mx-4 border border-slate-100 space-y-5">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-outfit font-extrabold text-slate-800 text-lg">Edit Data Gejala</h3>
                <button onclick="document.getElementById('edit-gejala-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form id="edit-gejala-form" method="POST" class="space-y-4 text-xs sm:text-sm">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-slate-700 mb-1">ID Gejala</label>
                    <input type="text" id="edit-id-gejala" readonly class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 font-mono font-bold text-slate-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Deskripsi / Nama Gejala</label>
                    <textarea name="nama_gejala" id="edit-nama-gejala" rows="3" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-medical-500"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('edit-gejala-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white font-bold rounded-xl shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    const deleteModal = document.getElementById('delete-gejala-modal');
    const deleteForm = document.getElementById('delete-gejala-form');
    const targetIdEl = document.getElementById('delete-target-id');
    const targetNameEl = document.getElementById('delete-target-name');

    function openEditModal(id, name) {
        document.getElementById('edit-id-gejala').value = id;
        document.getElementById('edit-nama-gejala').value = name;
        
        const form = document.getElementById('edit-gejala-form');
        form.action = `/admin/gejala/${id}`;

        document.getElementById('edit-gejala-modal').classList.remove('hidden');
    }

    function openDeleteModal(id, name) {
        deleteForm.action = `/admin/gejala/${id}`;
        targetIdEl.textContent = id;
        targetNameEl.textContent = name;

        deleteModal.classList.remove('hidden');
        setTimeout(() => {
            deleteModal.classList.remove('opacity-0');
            deleteModal.querySelector('.transform').classList.remove('scale-95');
        }, 10);
    }

    function closeDeleteModal() {
        deleteModal.classList.add('opacity-0');
        deleteModal.querySelector('.transform').classList.add('scale-95');
        setTimeout(() => {
            deleteModal.classList.add('hidden');
        }, 300);
    }

    function confirmDeleteAction() {
        deleteForm.submit();
    }
    </script>
</x-admin-layout>
