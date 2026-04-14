<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/LMS.png') }}" type="image/png">
    <title>Learning Management System Berbasis AI</title>
    @vite(['resources/css/app.css', 'resources/css/navbar.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen" data-role="{{ auth()->user()->role }}">
    @include('components.navbar')

    <div class="w-full px-4 sm:px-6 lg:px-16 py-8 sm:py-12">
        @if(session('success'))
            <div class="max-w-4xl mx-auto mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-4 sm:px-6 py-4 rounded-xl shadow-md flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 text-xl flex-shrink-0 mt-0.5"></i>
                <p class="font-semibold text-sm sm:text-base">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-4xl mx-auto mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 px-4 sm:px-6 py-4 rounded-xl shadow-md">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-semibold mb-1 text-sm sm:text-base">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside text-xs sm:text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-br from-purple-600 via-purple-700 to-pink-600 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 text-white relative overflow-hidden" style="background: var(--brand-gradient);">
                    <div class="absolute top-0 right-0 w-32 h-32 sm:w-64 sm:h-64 bg-white/10 rounded-full -mr-16 sm:-mr-32 -mt-16 sm:-mt-32"></div>
                    <div class="relative flex items-center gap-3 sm:gap-4">
                        <div class="p-2 sm:p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                            <i class="fas fa-cog text-2xl sm:text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold">Pengaturan</h1>
                            <p class="text-white/90 text-sm sm:text-base">Kelola akun dan keamanan Anda</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <i class="fas fa-lock text-red-600 text-xl sm:text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Ubah Password</h2>
                            <p class="text-xs sm:text-sm text-gray-500">Pastikan password Anda kuat dan aman</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.password') }}" class="space-y-6" id="passwordForm">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">Password Lama</label>
                            <div class="relative">
                                <input type="password" name="current_password" id="currentPassword" class="w-full px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all pr-12" style="--tw-ring-color: var(--brand-from);" required>
                                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition-colors" style="hover:color: var(--brand-from);" onclick="togglePassword('currentPassword')">
                                    <i id="eye-current-open" class="fas fa-eye text-lg"></i>
                                    <i id="eye-current-closed" class="fas fa-eye-slash text-lg hidden"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password" id="newPassword" class="w-full px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all pr-12" style="--tw-ring-color: var(--brand-from);" required minlength="6">
                                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition-colors" style="hover:color: var(--brand-from);" onclick="togglePassword('newPassword')">
                                    <i id="eye-new-open" class="fas fa-eye text-lg"></i>
                                    <i id="eye-new-closed" class="fas fa-eye-slash text-lg hidden"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                            @error('password')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="confirmPassword" class="w-full px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all pr-12" style="--tw-ring-color: var(--brand-from);" required>
                                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 transition-colors" style="hover:color: var(--brand-from);" onclick="togglePassword('confirmPassword')">
                                    <i id="eye-confirm-open" class="fas fa-eye text-lg"></i>
                                    <i id="eye-confirm-closed" class="fas fa-eye-slash text-lg hidden"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 pt-4">
                            <a href="{{ route('dashboard') }}" class="flex-1 px-6 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all font-semibold text-center text-sm sm:text-base flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="flex-1 px-6 py-2.5 sm:py-3 text-white rounded-xl transition-all font-semibold shadow-lg text-sm sm:text-base flex items-center justify-center gap-2" style="background: var(--brand-gradient);" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                <i class="fas fa-key"></i>
                                <span>Ubah Password</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const openIcon = button.querySelector('.fa-eye');
            const closedIcon = button.querySelector('.fa-eye-slash');

            if (input.type === 'password') {
                input.type = 'text';
                openIcon.classList.add('hidden');
                closedIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                openIcon.classList.remove('hidden');
                closedIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
