<x-admin-layout>
    <x-slot:title>Kelola Data Penyakit</x-slot>

    <div class="space-y-6">
        <!-- Page Header & Action Button -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-outfit font-extrabold text-slate-900">Data Master Penyakit</h1>
                <p class="text-xs text-slate-500 font-medium">Kelola daftar penyakit (Kode, Nama, dan Solusi Rekomendasi).</p>
            </div>
            <button onclick="document.getElementById('add-penyakit-modal').classList.remove('hidden')"
                    class="px-5 py-2.5 bg-gradient-to-r from-medical-600 to-emerald-500 hover:from-medical-700 hover:to-emerald-600 text-white text-xs font-bold rounded-2xl shadow-md transition-all flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Penyakit Baru</span>
            </button>
        </div>

        <!-- Table Data Penyakit -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100 text-xs">
                            <th class="py-3.5 px-5">ID Penyakit</th>
                            <th class="py-3.5 px-5">Nama Penyakit</th>
                            <th class="py-3.5 px-5">Rekomendasi Solusi / Penanganan</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($penyakits as $p)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-5 font-mono font-bold text-medical-700 whitespace-nowrap">
                                    {{ $p->id_penyakit }}
                                </td>
                                <td class="py-4 px-5 font-bold text-slate-800 whitespace-nowrap">
                                    {{ $p->nama_penyakit }}
                                </td>
                                <td class="py-4 px-5 text-xs text-slate-600 leading-relaxed max-w-md">
                                    {{ Str::limit($p->solusi, 120) }}
                                </td>
                                <td class="py-4 px-5 text-right whitespace-nowrap space-x-2">
                                    <button type="button" 
                                            onclick="openEditModal('{{ $p->id_penyakit }}', '{{ addslashes($p->nama_penyakit) }}', '{{ addslashes($p->solusi) }}')"
                                            class="px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold text-xs rounded-xl border border-amber-200 transition-colors">
                                        Edit
                                    </button>
                                    <button type="button"
                                            onclick="openDeletePenyakitModal('{{ $p->id_penyakit }}', '{{ addslashes($p->nama_penyakit) }}')"
                                            class="px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 font-bold text-xs rounded-xl border border-red-200 transition-colors">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 font-medium">Belum ada data penyakit.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="delete-penyakit-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Konfirmasi Hapus Penyakit -->
    <div id="delete-penyakit-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl max-w-md w-full mx-4 border border-slate-100 space-y-5 transform scale-95 transition-transform duration-300">
            <div class="flex items-center space-x-4">
                <div class="bg-red-50 text-red-600 p-3 rounded-2xl flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-outfit font-extrabold text-slate-900 text-lg">Konfirmasi Hapus Penyakit</h3>
                    <p class="text-xs text-slate-500 font-semibold">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                <span id="delete-penyakit-id" class="text-xs font-mono font-bold text-medical-700 bg-medical-50 px-2 py-0.5 rounded-md"></span>
                <p id="delete-penyakit-name" class="text-sm font-bold text-slate-800 pt-1 leading-relaxed"></p>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                Apakah Anda yakin ingin menghapus data penyakit ini dari basis pengetahuan sistem pakar? Aturan rule terkait penyakit ini juga akan disesuaikan.
            </p>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeDeletePenyakitModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                    Batal
                </button>
                <button type="button" onclick="confirmDeletePenyakitAction()" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-md shadow-red-100 transition-all">
                    Ya, Hapus Penyakit
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Penyakit -->
    <div id="add-penyakit-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl max-w-lg w-full mx-4 border border-slate-100 space-y-5">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-outfit font-extrabold text-slate-800 text-lg">Tambah Penyakit Baru</h3>
                <button onclick="document.getElementById('add-penyakit-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form action="{{ route('admin.penyakit.store') }}" method="POST" class="space-y-4 text-xs sm:text-sm">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kode ID Penyakit (misal: P13)</label>
                    <input type="text" name="id_penyakit" required placeholder="P13" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-medical-500 font-mono">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Penyakit</label>
                    <input type="text" name="nama_penyakit" required placeholder="Nama penyakit..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-medical-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Rekomendasi Solusi & Penanganan</label>
                    <textarea name="solusi" rows="4" required placeholder="Tuliskan rekomendasi tindakan medis awal..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-medical-500"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('add-penyakit-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white font-bold rounded-xl shadow-md">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Penyakit -->
    <div id="edit-penyakit-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl max-w-lg w-full mx-4 border border-slate-100 space-y-5">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-outfit font-extrabold text-slate-800 text-lg">Edit Data Penyakit</h3>
                <button onclick="document.getElementById('edit-penyakit-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <form id="edit-form" method="POST" class="space-y-4 text-xs sm:text-sm">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-slate-700 mb-1">ID Penyakit</label>
                    <input type="text" id="edit-id-penyakit" readonly class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 font-mono font-bold text-slate-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Penyakit</label>
                    <input type="text" name="nama_penyakit" id="edit-nama-penyakit" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-medical-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Rekomendasi Solusi & Penanganan</label>
                    <textarea name="solusi" id="edit-solusi" rows="4" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-medical-500"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('edit-penyakit-modal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-medical-600 hover:bg-medical-700 text-white font-bold rounded-xl shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    const deletePenyakitModal = document.getElementById('delete-penyakit-modal');
    const deletePenyakitForm = document.getElementById('delete-penyakit-form');
    const targetPenyakitIdEl = document.getElementById('delete-penyakit-id');
    const targetPenyakitNameEl = document.getElementById('delete-penyakit-name');

    function openEditModal(id, name, solusi) {
        document.getElementById('edit-id-penyakit').value = id;
        document.getElementById('edit-nama-penyakit').value = name;
        document.getElementById('edit-solusi').value = solusi;
        
        const form = document.getElementById('edit-form');
        form.action = `/admin/penyakit/${id}`;

        document.getElementById('edit-penyakit-modal').classList.remove('hidden');
    }

    function openDeletePenyakitModal(id, name) {
        deletePenyakitForm.action = `/admin/penyakit/${id}`;
        targetPenyakitIdEl.textContent = id;
        targetPenyakitNameEl.textContent = name;

        deletePenyakitModal.classList.remove('hidden');
        setTimeout(() => {
            deletePenyakitModal.classList.remove('opacity-0');
            deletePenyakitModal.querySelector('.transform').classList.remove('scale-95');
        }, 10);
    }

    function closeDeletePenyakitModal() {
        deletePenyakitModal.classList.add('opacity-0');
        deletePenyakitModal.querySelector('.transform').classList.add('scale-95');
        setTimeout(() => {
            deletePenyakitModal.classList.add('hidden');
        }, 300);
    }

    function confirmDeletePenyakitAction() {
        deletePenyakitForm.submit();
    }
    </script>
</x-admin-layout>
