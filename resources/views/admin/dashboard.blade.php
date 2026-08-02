<x-admin-layout>
    <x-slot:title>Dashboard Admin</x-slot>

    <div class="space-y-8">
        <!-- Dashboard Header -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-xs font-bold text-medical-600 bg-medical-50 px-3 py-1 rounded-full uppercase tracking-wider">Overview Panel</span>
                <h1 class="text-2xl sm:text-3xl font-outfit font-extrabold text-slate-900 mt-2">Selamat Datang, {{ auth('admin')->user()->name }}</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola basis pengetahuan (Penyakit, Gejala, Rules) dan pantau log aktivitas admin.</p>
            </div>
            <div class="flex items-center space-x-2 bg-slate-50 px-4 py-2.5 rounded-2xl border border-slate-200 text-xs font-semibold text-slate-700">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Supabase PostgreSQL Active</span>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Penyakit Stat -->
            <a href="{{ route('admin.penyakit.index') }}" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Penyakit</span>
                    <div class="p-2.5 bg-teal-50 text-teal-600 rounded-2xl group-hover:bg-teal-600 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-outfit font-extrabold text-slate-900">{{ $totalPenyakit }}</div>
                <p class="text-xs text-slate-500 mt-1 font-medium">Master Data Penyakit & Solusi</p>
            </a>

            <!-- Gejala Stat -->
            <a href="{{ route('admin.gejala.index') }}" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Gejala</span>
                    <div class="p-2.5 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-outfit font-extrabold text-slate-900">{{ $totalGejala }}</div>
                <p class="text-xs text-slate-500 mt-1 font-medium">Checklist Indikator Gejala</p>
            </a>

            <!-- Rules Stat -->
            <a href="{{ route('admin.rules.index') }}" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pemetaan Rules</span>
                    <div class="p-2.5 bg-amber-50 text-amber-600 rounded-2xl group-hover:bg-amber-600 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-outfit font-extrabold text-slate-900">{{ $totalRule }}</div>
                <p class="text-xs text-slate-500 mt-1 font-medium">Relasi Aturan Penyakit <-> Gejala</p>
            </a>

            <!-- Diagnosa Stat -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Konsultasi</span>
                    <div class="p-2.5 bg-purple-50 text-purple-600 rounded-2xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-outfit font-extrabold text-slate-900">{{ $totalRiwayat }}</div>
                <p class="text-xs text-slate-500 mt-1 font-medium">Riwayat Diagnosa Pasien</p>
            </div>
        </div>

        <!-- Recent Activity Logs Section -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">
            <div class="flex justify-between items-center pb-4 border-b border-slate-100 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 font-outfit">Log Aktivitas Admin Terbaru</h2>
                    <p class="text-xs text-slate-500 font-medium">Catatan riwayat perubahan data dan login oleh admin.</p>
                </div>
                <a href="{{ route('admin.logs') }}" class="text-xs font-bold text-medical-600 hover:text-medical-700 transition-colors">
                    Lihat Semua Log &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 uppercase tracking-wider font-semibold border-b border-slate-100">
                            <th class="pb-3 px-2">Waktu</th>
                            <th class="pb-3 px-2">Aksi</th>
                            <th class="pb-3 px-2">Deskripsi Aktivitas</th>
                            <th class="pb-3 px-2">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($recentLogs as $log)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-2 whitespace-nowrap font-medium text-slate-500">
                                    {{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i:s') }}
                                </td>
                                <td class="py-3 px-2 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                                        @if(str_contains($log->action, 'TAMBAH')) bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @elseif(str_contains($log->action, 'EDIT')) bg-amber-50 text-amber-700 border border-amber-200
                                        @elseif(str_contains($log->action, 'HAPUS')) bg-red-50 text-red-700 border border-red-200
                                        @else bg-slate-100 text-slate-700 border border-slate-200
                                        @endif">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 font-medium leading-relaxed">
                                    {{ $log->description }}
                                </td>
                                <td class="py-3 px-2 whitespace-nowrap font-mono text-slate-400">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 font-medium">Belum ada catatan aktivitas admin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
