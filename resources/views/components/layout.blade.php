<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Pakar PakarDemam' }} - Diagnosa Demam Akurat</title>
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS via CDN for instant execution & styling -->
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
                            950: '#042f2e',
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
    
    <!-- Custom Scrollbar & Print styles -->
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .print-card {
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>
    
    <!-- Vite Assets (compiled fallback) -->
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col antialiased">

    <!-- Header Navigation -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('beranda') }}" class="flex items-center space-x-2">
                        <div class="bg-gradient-to-tr from-medical-600 to-emerald-400 p-2 rounded-xl text-white shadow-md shadow-medical-200">
                            <!-- Medical Cross SVG Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 10.5h-5.5V5a1.5 1.5 0 00-3 0v5.5H5a1.5 1.5 0 000 3h5.5V19a1.5 1.5 0 003 0v-5.5H19a1.5 1.5 0 000-3z"></path>
                            </svg>
                        </div>
                        <span class="font-outfit font-bold text-2xl tracking-tight bg-gradient-to-r from-medical-800 to-medical-600 bg-clip-text text-transparent">PakarDemam</span>
                    </a>
                </div>
                
                <!-- Nav Links -->
                <nav class="flex space-x-8">
                    <a href="{{ route('beranda') }}" class="text-sm font-medium {{ request()->routeIs('beranda') ? 'text-medical-700 border-b-2 border-medical-600 py-2' : 'text-slate-500 hover:text-medical-600 py-2 transition-colors' }}">
                        Beranda
                    </a>
                    <a href="{{ route('konsultasi.index') }}" class="text-sm font-medium {{ request()->routeIs('konsultasi.index') || request()->routeIs('konsultasi.hasil') ? 'text-medical-700 border-b-2 border-medical-600 py-2' : 'text-slate-500 hover:text-medical-600 py-2 transition-colors' }}">
                        Konsultasi
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-grow flex flex-col justify-center py-8">
        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-8 border-t border-slate-800 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-2">
                    <div class="bg-medical-600/20 p-1.5 rounded-lg text-medical-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 10.5h-5.5V5a1.5 1.5 0 00-3 0v5.5H5a1.5 1.5 0 000 3h5.5V19a1.5 1.5 0 003 0v-5.5H19a1.5 1.5 0 000-3z"></path>
                        </svg>
                    </div>
                    <span class="font-outfit font-bold text-lg text-white">PakarDemam</span>
                </div>
                <div class="text-sm text-center md:text-right">
                    <p>&copy; {{ date('Y') }} PakarDemam. All rights reserved.</p>
                    <p class="text-xs text-slate-500 mt-1">Dibuat khusus untuk Prototype Medis - Klinik Amanah Riau Kepri</p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
