<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/LMS.png') }}" type="image/png">
    <title>Learning Management System Berbasis AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('components.navbar')

    <div class="w-full px-4 sm:px-6 md:px-16 py-12">
        <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl shadow-2xl p-8 mb-8 text-white animate-fade-in-up overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Materi Pembelajaran</h1>
            <p class="text-lg text-purple-100">Akses semua materi dari guru</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($materials as $material)
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
                    <div class="h-32 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 relative">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute bottom-4 left-4">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-bold rounded-full">
                                {{ $material->class->nama_kelas }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $material->judul }}</h3>
                        @if($material->konten)
                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($material->konten, 100) }}</p>
                        @endif
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $material->uploader->nama }}
                        </div>
                        <div class="flex gap-2">
                            @if($material->file_path)
                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white rounded-xl hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 transition font-semibold text-center">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download
                                </a>
                            @endif
                            @if($material->online_link)
                                <a href="{{ $material->online_link }}" target="_blank" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-600 text-white rounded-xl hover:from-blue-700 hover:via-cyan-700 hover:to-teal-700 transition font-semibold text-center">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Buka Online
                                </a>
                            @endif
                            @if(!$material->file_path && !$material->online_link)
                                <button class="w-full px-4 py-2.5 bg-gray-100 text-gray-500 rounded-xl cursor-not-allowed font-semibold text-center">
                                    Tidak Ada Materi
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Materi</h3>
                    <p class="text-gray-600">Materi dari guru akan muncul di sini</p>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
