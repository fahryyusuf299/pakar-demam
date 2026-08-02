<x-admin-layout>
    <x-slot:title>Basis Aturan (Rules)</x-slot>

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
        <!-- Page Header -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <h1 class="text-2xl font-outfit font-extrabold text-slate-900">Basis Aturan Diagnosis (Rules)</h1>
            <p class="text-xs text-slate-500 font-medium">Ringkasan daftar penyakit dan indikator kode gejala yang terhubung. Setiap kode gejala memiliki warna unik konsisten.</p>
        </div>

        <!-- Table Penyakit & Kode Gejala -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100 text-xs">
                            <th class="py-3.5 px-5">Penyakit</th>
                            <th class="py-3.5 px-5">Kode Indikator Gejala Terkait</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($penyakits as $p)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-mono font-bold text-medical-700 bg-medical-50 px-2 py-0.5 rounded-md">{{ $p->id_penyakit }}</span>
                                        <span class="font-bold text-slate-800">{{ $p->nama_penyakit }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    <div class="flex flex-wrap gap-1.5 items-center">
                                        @forelse($p->gejala as $g)
                                            @php
                                                // Generate index warna konsisten dari angka ID gejala (misal G02 -> 2)
                                                $numId = (int) preg_replace('/[^0-9]/', '', $g->id_gejala);
                                                $colorStyle = $colorPalette[$numId % count($colorPalette)];
                                            @endphp
                                            <span class="font-mono font-bold text-xs border px-2 py-0.5 rounded-md shadow-2xs {{ $colorStyle }}" title="{{ $g->nama_gejala }}">
                                                {{ $g->id_gejala }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-400 italic font-medium">Belum ada gejala terhubung</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.rules.show', $p->id_penyakit) }}" 
                                       class="inline-flex items-center px-3.5 py-1.5 bg-medical-50 text-medical-700 hover:bg-medical-100 font-bold text-xs rounded-xl border border-medical-200 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Kelola Gejala ({{ $p->gejala->count() }})
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-400 font-medium">Belum ada data penyakit.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
