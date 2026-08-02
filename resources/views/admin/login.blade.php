<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - PakarDemam v4.0</title>
    <!-- Favicon / Tab Web Icon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        medical: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
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
<body class="bg-slate-900 text-slate-800 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
        <!-- Header banner -->
        <div class="bg-gradient-to-br from-medical-900 via-slate-900 to-medical-950 p-8 text-center text-white relative">
            <div class="w-16 h-16 bg-medical-600 rounded-2xl mx-auto flex items-center justify-center shadow-lg shadow-medical-500/30 mb-4 border border-medical-400/30">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 10.5h-5.5V5a1.5 1.5 0 00-3 0v5.5H5a1.5 1.5 0 000 3h5.5V19a1.5 1.5 0 003 0v-5.5H19a1.5 1.5 0 000-3z"></path>
                </svg>
            </div>
            <h1 class="font-outfit text-2xl font-extrabold tracking-tight text-white">Login Administrator</h1>
            <p class="text-xs text-medical-200 mt-1.5 font-semibold bg-medical-800/60 inline-block px-3 py-1 rounded-full border border-medical-700/50">Sistem Pakar PakarDemam v4.0</p>
        </div>

        <!-- Form content -->
        <div class="p-8">
            @if(session('info'))
                <div class="mb-6 p-4 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold rounded-2xl">
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Alamat Email Admin</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           placeholder="admin@mail.com"
                           class="w-full px-4 py-3 rounded-2xl border @error('email') border-red-300 bg-red-50 @else border-slate-200 @enderror focus:outline-none focus:ring-2 focus:ring-medical-500 text-sm font-medium transition-all">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Kata Sandi / Password</label>
                    <input type="password" name="password" id="password" required
                           placeholder="••••••••"
                           class="w-full px-4 py-3 rounded-2xl border @error('password') border-red-300 bg-red-50 @else border-slate-200 @enderror focus:outline-none focus:ring-2 focus:ring-medical-500 text-sm font-medium transition-all">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-medical-600 border-slate-300 focus:ring-medical-500">
                        <span class="text-xs font-medium text-slate-600">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-medical-600 to-emerald-500 hover:from-medical-700 hover:to-emerald-600 text-white font-bold text-sm rounded-2xl shadow-lg shadow-medical-100 transition-all duration-200">
                    Masuk ke Dashboard
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <a href="{{ route('beranda') }}" class="text-xs font-semibold text-slate-500 hover:text-medical-600 transition-colors inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Halaman Pasien
                </a>
            </div>
        </div>
    </div>

</body>
</html>
