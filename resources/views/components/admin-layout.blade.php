<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Admin' }} - PakarDemam Admin Panel</title>
    <!-- Favicon / Tab Web Icon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.svg') }}">
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        medical: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col antialiased">

    <!-- Admin Top Header -->
    <header class="bg-slate-900 text-white sticky top-0 z-40 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5">
                        <div class="bg-medical-600 p-2 rounded-xl text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 10.5h-5.5V5a1.5 1.5 0 00-3 0v5.5H5a1.5 1.5 0 000 3h5.5V19a1.5 1.5 0 003 0v-5.5H19a1.5 1.5 0 000-3z"></path>
                            </svg>
                        </div>
                        <span class="font-outfit font-bold text-xl tracking-tight text-white">PakarDemam <span class="text-xs bg-medical-500 text-white px-2 py-0.5 rounded-md ml-1 font-mono">ADMIN</span></span>
                    </a>
                </div>

                <!-- Admin Profile & Logout Button -->
                <div class="flex items-center space-x-4">
                    <div class="hidden sm:flex items-center space-x-2 text-sm text-slate-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="font-medium">{{ auth('admin')->user()->name }}</span>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3.5 py-1.5 border border-slate-700 hover:border-slate-600 text-xs font-semibold rounded-xl text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Admin Navigation Bar -->
    <nav class="bg-white border-b border-slate-200 shadow-sm sticky top-16 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-1 sm:space-x-4 overflow-x-auto py-2">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2 whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'bg-medical-50 text-medical-700 border border-medical-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.penyakit.index') }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2 whitespace-nowrap {{ request()->routeIs('admin.penyakit.*') ? 'bg-medical-50 text-medical-700 border border-medical-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    <span>Data Penyakit</span>
                </a>

                <a href="{{ route('admin.gejala.index') }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2 whitespace-nowrap {{ request()->routeIs('admin.gejala.*') ? 'bg-medical-50 text-medical-700 border border-medical-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span>Data Gejala</span>
                </a>

                <a href="{{ route('admin.rules.index') }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2 whitespace-nowrap {{ request()->routeIs('admin.rules.*') ? 'bg-medical-50 text-medical-700 border border-medical-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span>Basis Aturan</span>
                </a>

                <a href="{{ route('admin.logs') }}" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2 whitespace-nowrap {{ request()->routeIs('admin.logs') ? 'bg-medical-50 text-medical-700 border border-medical-100 shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Log Aktivitas</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Global Alert Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold rounded-2xl flex items-center space-x-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-red-50 border border-red-200 text-red-800 text-sm font-semibold rounded-2xl flex items-center space-x-3 shadow-sm">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Admin Body -->
    <main class="flex-grow py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500 font-medium">
        <p>&copy; {{ date('Y') }} PakarDemam Admin Panel v4.0 - Klinik Amanah Riau Kepri</p>
    </footer>

</body>
</html>
