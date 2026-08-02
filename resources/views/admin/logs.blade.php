<x-admin-layout>
    <x-slot:title>Log Aktivitas Admin</x-slot>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <h1 class="text-2xl font-outfit font-extrabold text-slate-900">Log Audit Aktivitas Administrator</h1>
            <p class="text-xs text-slate-500 font-medium">Catatan lengkap riwayat aksi (Login, Logout, Tambah, Edit, Hapus) yang dilakukan admin.</p>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100 text-xs">
                            <th class="py-3.5 px-4">Waktu (WIB)</th>
                            <th class="py-3.5 px-4">Admin</th>
                            <th class="py-3.5 px-4">Aksi</th>
                            <th class="py-3.5 px-4">Deskripsi Aktivitas</th>
                            <th class="py-3.5 px-4">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-4 whitespace-nowrap font-medium text-slate-500 text-xs">
                                    {{ $log->created_at->timezone('Asia/Jakarta')->format('d F Y, H:i:s') }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap font-bold text-slate-800 text-xs">
                                    {{ $log->admin->name ?? 'System Admin' }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                                        @if(str_contains($log->action, 'TAMBAH')) bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @elseif(str_contains($log->action, 'EDIT')) bg-amber-50 text-amber-700 border border-amber-200
                                        @elseif(str_contains($log->action, 'HAPUS')) bg-red-50 text-red-700 border border-red-200
                                        @elseif(str_contains($log->action, 'LOGIN')) bg-blue-50 text-blue-700 border border-blue-200
                                        @else bg-slate-100 text-slate-700 border border-slate-200
                                        @endif">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-slate-700 leading-relaxed text-xs">
                                    {{ $log->description }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap font-mono text-xs text-slate-400">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 font-medium">Belum ada catatan aktivitas admin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
