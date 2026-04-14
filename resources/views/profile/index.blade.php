<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/LMS.png') }}" type="image/png">
    <title>Learning Management System Berbasis AI</title>
    @vite(['resources/css/app.css', 'resources/css/navbar.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen" data-role="{{ auth()->user()->role }}">
    @include('components.navbar')

    <div class="w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
        @if(session('success'))
            <div class="max-w-4xl mx-auto mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-4 sm:px-6 py-4 rounded-xl shadow-md flex items-start gap-3">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="font-semibold text-sm sm:text-base">{{ session('success') }}</p>
            </div>
        @endif

        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12 text-white relative overflow-hidden" style="background: var(--brand-gradient);">
                    <div class="absolute top-0 right-0 w-40 h-40 sm:w-64 sm:h-64 bg-white/10 rounded-full -mr-20 sm:-mr-32 -mt-20 sm:-mt-32"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 sm:w-48 sm:h-48 bg-white/10 rounded-full -ml-16 sm:-ml-24 -mb-16 sm:-mb-24"></div>
                    <div class="relative flex flex-col sm:flex-row items-center sm:items-center gap-4 sm:gap-6">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-2xl sm:text-3xl font-bold overflow-hidden ring-4 ring-white/30 flex-shrink-0">
                            @php $avatarUrl = $user->avatar_url; @endphp
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                <div class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white text-2xl sm:text-3xl font-bold" style="display:none;">{{ strtoupper(substr($user->nama, 0, 1)) }}</div>
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white text-2xl sm:text-3xl font-bold">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="text-center sm:text-left">
                            <h1 class="text-2xl sm:text-3xl font-bold mb-2 sm:mb-1">{{ $user->nama }}</h1>
                            <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-3">
                                <span class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs sm:text-sm font-medium">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                    {{ ucfirst($user->role) }}
                                </span>
                                <span class="text-white/90 text-xs sm:text-sm flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    <span class="break-all">{{ $user->email }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 rounded-lg flex-shrink-0" style="background: linear-gradient(135deg, var(--brand-from), var(--brand-to)); opacity: 0.1;">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" style="color: var(--brand-from);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Edit Profile</h2>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-3">
                                <svg class="w-4 h-4" style="color: var(--brand-from);" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                                Foto Profile
                            </label>
                            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6">
                                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full flex items-center justify-center overflow-hidden ring-4 flex-shrink-0" style="background: linear-gradient(to bottom right, var(--brand-from), var(--brand-to)); opacity: 0.1; --tw-ring-color: var(--brand-from); --tw-ring-opacity: 0.2;">
                                    @php $avatarUrl = $user->avatar_url; @endphp
                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover" id="avatarPreview" onerror="this.style.display='none'; document.getElementById('avatarInitial').style.display='flex'">
                                        <span class="text-2xl font-bold w-full h-full flex items-center justify-center" id="avatarInitial" style="color: var(--brand-from); display:none;">{{ strtoupper(substr($user->nama, 0, 1)) }}</span>
                                    @else
                                        <span class="text-2xl font-bold" id="avatarInitial" style="color: var(--brand-from);">{{ strtoupper(substr($user->nama, 0, 1)) }}</span>
                                        <img src="" alt="Preview" class="w-full h-full object-cover hidden" id="avatarPreview">
                                    @endif
                                </div>
                                <div class="flex-1 text-center sm:text-left">
                                    <input type="file" name="avatar" accept="image/*" id="avatarInput" class="hidden">
                                    <button type="button" onclick="document.getElementById('avatarInput').click()" class="inline-flex items-center gap-2 px-4 py-2.5 text-white rounded-lg transition-all duration-200 font-semibold text-sm shadow-md hover:shadow-lg" style="background: var(--brand-gradient); opacity: 0.9;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.9'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Pilih Foto
                                    </button>
                                    <p class="text-xs text-gray-500 mt-2">JPG, PNG (Max 2MB)</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-2">
                                    <svg class="w-4 h-4" style="color: var(--brand-from);" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Nama Lengkap
                                </label>
                                <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="w-full px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition text-sm sm:text-base" style="--tw-ring-color: var(--brand-from);" required>
                                @error('nama')
                                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-2">
                                    <svg class="w-4 h-4" style="color: var(--brand-from);" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    Email
                                </label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2.5 sm:py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition text-sm sm:text-base" style="--tw-ring-color: var(--brand-from);" required>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 sm:py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 font-semibold text-sm sm:text-base order-2 sm:order-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 sm:py-3 text-white rounded-xl hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 font-semibold shadow-lg text-sm sm:text-base order-1 sm:order-2" style="background: var(--brand-gradient);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('avatarInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    const initial = document.getElementById('avatarInitial');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (initial) {
                        initial.style.display = 'none';
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
