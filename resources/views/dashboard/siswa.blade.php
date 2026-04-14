<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/LMS.png') }}" type="image/png">
    <title>Learning Management System Berbasis AI</title>
    @vite(['resources/css/app.css', 'resources/css/siswa.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    @include('components.notifications')
    @include('components.navbar')

    <main class="flex-1">
        <section class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 text-white relative overflow-hidden">
            <!-- Animated Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
                <div class="absolute bottom-0 left-20 w-96 h-96 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
            </div>

            <!-- Floating Icons -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-20 left-10 animate-float">
                    <svg class="w-16 h-16 text-white/25" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div class="absolute top-40 right-20 animate-float-slow">
                    <svg class="w-10 h-10 text-white/25" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div class="absolute bottom-20 left-1/4 animate-float-reverse">
                    <svg class="w-10 h-10 text-white/25" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div class="absolute top-1/3 right-1/3 animate-pulse">
                    <div class="w-4 h-4 bg-white/30 rounded-full"></div>
                </div>
                <div class="absolute bottom-1/3 right-1/4 animate-pulse animation-delay-1000">
                    <div class="w-3 h-3 bg-yellow-300/40 rounded-full"></div>
                </div>
            </div>

            <div class="relative w-full px-4 sm:px-6 md:px-16 py-20 sm:py-24">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <!-- Left Content -->
                        <div class="space-y-6 text-center lg:text-left">
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-md border border-white/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm font-semibold">Learning Management System</span>
                            </div>

                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight">
                                Selamat Datang,<br>
                                <span class="bg-gradient-to-r from-yellow-300 via-yellow-200 to-yellow-300 bg-clip-text text-transparent animate-gradient">
                                    {{ Auth::user()?->nama ?? 'Admin' }}!
                                </span>
                            </h1>

                            <p class="text-lg sm:text-xl text-red-50 max-w-2xl">
                                Ikuti kelas, pelajari materi, kerjakan tugas, dan pantau progres belajarmu dengan lebih terarah dan modern.
                            </p>

                            <div class="flex flex-wrap gap-4 justify-center lg:justify-start pt-4">
                                <!-- Kelas Saya -->
                                <div class="flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-xl">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-green-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M6 2c-1.10457 0-2 .89543-2 2v4c0 .55228.44772 1 1 1s1-.44772 1-1V4h12v7h-2c-.5523 0-1 .4477-1 1v2h-1c-.5523 0-1 .4477-1 1s.4477 1 1 1h5c.5523 0 1-.4477 1-1V3.85714C20 2.98529 19.3667 2 18.268 2H6Z"/>
                                        <path d="M6 11.5C6 9.567 7.567 8 9.5 8S13 9.567 13 11.5 11.433 15 9.5 15 6 13.433 6 11.5ZM4 20c0-2.2091 1.79086-4 4-4h3c2.2091 0 4 1.7909 4 4 0 1.1046-.8954 2-2 2H6c-1.10457 0-2-.8954-2-2Z"/>
                                    </svg>
                                    <span class="text-sm font-medium">Kelas Saya</span>
                                </div>

                                <!-- Tugas & Nilai -->
                                <div class="flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-xl">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-blue-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7.171 12.906-2.153 6.411 2.672-.89 1.568 2.34 1.825-5.183m5.73-2.678 2.154 6.411-2.673-.89-1.568 2.34-1.825-5.183M9.165 4.3c.58.068 1.153-.17 1.515-.628a1.681 1.681 0 0 1 2.64 0 1.68 1.68 0 0 0 1.515.628 1.681 1.681 0 0 1 1.866 1.866c-.068.58.17 1.154.628 1.516a1.681 1.681 0 0 1 0 2.639 1.682 1.682 0 0 0-.628 1.515 1.681 1.681 0 0 1-1.866 1.866 1.681 1.681 0 0 0-1.516.628 1.681 1.681 0 0 1-2.639 0 1.681 1.681 0 0 0-1.515-.628 1.681 1.681 0 0 1-1.867-1.866 1.681 1.681 0 0 0-.627-1.515 1.681 1.681 0 0 1 0-2.64c.458-.361.696-.935.627-1.515A1.681 1.681 0 0 1 9.165 4.3ZM14 9a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
                                    </svg>
                                    <span class="text-sm font-medium">Tugas & Nilai</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Illustration -->
                        <div class="hidden lg:flex justify-center items-center">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full blur-3xl opacity-20 animate-pulse"></div>
                                <img src="/SVG/Education.svg" alt="Admin Dashboard" class="relative w-full max-w-lg h-auto drop-shadow-2xl transform hover:scale-105 transition-transform duration-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Wave -->
            <div class="absolute bottom-0 left-0 right-0">
                <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
                    <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="rgb(249 250 251)"/>
                </svg>
            </div>
        </section>

        <!-- Main Content -->
        <section class="w-full px-4 sm:px-6 md:px-16 py-12">
            <!-- Quick Actions -->
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Aktivitas Pembelajaran</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Lihat Tugas Card -->
                    <a href="#tugas-terbaru" onclick="event.preventDefault(); document.getElementById('tugas-terbaru').scrollIntoView({behavior: 'smooth'});" class="group relative p-6 bg-white rounded-2xl border-2 border-gray-100 hover:border-indigo-500 hover:shadow-2xl transition-all duration-300 overflow-hidden block">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg shadow-indigo-500/30">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">Lihat Tugas</h3>
                            <p class="text-sm text-gray-500 mb-3">Tugas yang perlu diselesaikan</p>
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-semibold">
                                <span class="w-2 h-2 bg-indigo-500 rounded-full mr-2 animate-pulse"></span>
                                {{ $assignments->where('is_completed', false)->count() }} Pending
                            </div>
                        </div>
                        <div class="absolute top-6 right-6 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </a>

                    <!-- Lihat Materi Card -->
                    <a href="{{ route('siswa.materials') }}" class="group relative p-6 bg-white rounded-2xl border-2 border-gray-100 hover:border-purple-500 hover:shadow-2xl transition-all duration-300 overflow-hidden block">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg shadow-purple-500/30">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">Lihat Materi</h3>
                            <p class="text-sm text-gray-500 mb-3">Materi pembelajaran dari guru</p>
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-purple-50 text-purple-600 text-xs font-semibold">
                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                                Tersedia
                            </div>
                        </div>
                        <div class="absolute top-6 right-6 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>

                    <!-- Progress Card -->
                    <a href="{{ route('siswa.recommendations') }}" class="group relative p-6 bg-white rounded-2xl border-2 border-gray-100 hover:border-pink-500 hover:shadow-2xl transition-all duration-300 overflow-hidden block">
                        <!-- Decorative background gradient -->
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg shadow-pink-500/30">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-pink-600 transition-colors">Rekomendasi Materi & Progres Pembelajaran</h3>
                            <p class="text-sm text-gray-500 mb-3">AI merekomendasikan materi berdasarkan perkembangan belajarmu</p>
                            <!-- Progress Bar -->
                            <div class="flex items-center gap-2 text-sm text-pink-600 font-semibold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                Dapatkan Rekomendasi
                            </div>
                        </div>

                        <!-- Arrow Icon -->
                        <div class="absolute top-6 right-6 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                            <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Kelas Saya -->
            <div x-data="{ showAll: false }" class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Kelas Saya</h2>
                    <button @click="showAll = !showAll" class="text-indigo-600 hover:text-indigo-700 font-semibold flex items-center gap-1">
                        <span x-text="showAll ? 'Lihat Sedikit' : 'Lihat Semua'"></span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!showAll" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            <path x-show="showAll" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($classes as $index => $kelas)
                    <div x-show="showAll || {{ $index }} < 3" x-cloak class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 border border-gray-100 transform hover:-translate-y-1">
                        <div class="h-36 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 relative">
                            <div class="absolute inset-0 bg-black/10"></div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">{{ $kelas->nama_kelas }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $kelas->deskripsi }}</h3>
                            <p class="text-sm text-gray-600 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $kelas->creator->nama ?? 'Guru' }}
                            </p>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    {{ $kelas->enrollments->count() }} Siswa
                                </div>
                                <a href="{{ route('siswa.classes.show', $kelas->id_class) }}" class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm flex items-center gap-1">
                                    Buka
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Kelas</h3>
                            <p class="text-gray-500 mb-4">Kamu belum bergabung dengan kelas apa pun.</p>
                            <button onclick="openJoinClassModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm rounded-xl hover:bg-indigo-700 transition font-semibold shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                Join Kelas Sekarang
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tugas Terbaru -->
            <div id="tugas-terbaru" x-data="{ showAll: false }" class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Tugas Terbaru</h2>
                    <button @click="showAll = !showAll" class="text-indigo-600 hover:text-indigo-700 font-semibold flex items-center gap-1">
                        <span x-text="showAll ? 'Lihat Sedikit' : 'Lihat Semua'"></span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!showAll" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            <path x-show="showAll" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                        </svg>
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    @forelse($assignments as $index => $assignment)
                        @php
                            $deadline = \Carbon\Carbon::parse($assignment->deadline);
                            $isLate = now()->isAfter($deadline);
                            $daysLeft = (int) now()->diffInDays($deadline, false);
                            $isCompleted = $assignment->is_completed;

                            if ($isCompleted) {
                                $badgeClass = 'bg-green-100 text-green-700';
                                $badgeIcon = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                                $badgeText = 'Selesai';
                            } elseif ($isLate) {
                                $badgeClass = 'bg-gray-100 text-gray-700';
                                $badgeIcon = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                                $badgeText = 'Terlambat';
                            } elseif ($daysLeft <= 2) {
                                $badgeClass = 'bg-red-100 text-red-700';
                                $badgeIcon = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                                $badgeText = 'Deadline: ' . $daysLeft . ' hari lagi';
                            } else {
                                $badgeClass = 'bg-amber-100 text-amber-700';
                                $badgeIcon = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                                $badgeText = 'Deadline: ' . $daysLeft . ' hari lagi';
                            }

                            $subjectColors = [
                                'blue' => 'bg-blue-100 text-blue-700',
                                'purple' => 'bg-purple-100 text-purple-700',
                                'green' => 'bg-green-100 text-green-700',
                                'orange' => 'bg-orange-100 text-orange-700',
                                'pink' => 'bg-pink-100 text-pink-700',
                            ];
                            $colorKeys = array_keys($subjectColors);
                            $subjectColor = $subjectColors[$colorKeys[$assignment->id_class % count($colorKeys)]];
                        @endphp

                        <div x-show="showAll || {{ $index }} < 3" x-cloak class="p-6 {{ !$loop->last ? 'border-b border-gray-100' : '' }} hover:bg-gray-50 transition">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-3">
                                        <span class="px-3 py-1.5 {{ $badgeClass }} text-xs font-bold rounded-full inline-flex items-center gap-1">
                                            {!! $badgeIcon !!}
                                            {{ $badgeText }}
                                        </span>
                                        <span class="px-3 py-1.5 {{ $isCompleted ? 'bg-green-100 text-green-700' : $subjectColor }} text-xs font-bold rounded-full">{{ $assignment->class->nama_kelas }}</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $assignment->judul }}</h3>
                                    <p class="text-sm text-gray-600 mb-4">{{ Str::limit($assignment->deskripsi, 100) }}</p>
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                        @if($isCompleted && $assignment->submission)
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="font-semibold text-green-600">Nilai: {{ $assignment->submission->score }}/{{ $assignment->max_score }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Submitted: {{ $assignment->submission->submitted_at->format('d M Y') }}
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $deadline->format('d M Y') }}
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                {{ ucfirst(str_replace('_', ' ', $assignment->tipe)) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if($isCompleted)
                                    <a href="{{ route('siswa.submissions.show', $assignment->submission->id_submission) }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-xl hover:bg-gray-200 transition font-semibold inline-block text-center">
                                        Lihat Detail
                                    </a>
                                @else
                                    <a href="{{ route('siswa.assignments.show', $assignment->id_assignment) }}" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm rounded-xl hover:from-indigo-700 hover:to-purple-700 transition font-semibold shadow-md inline-block text-center">
                                        Kerjakan
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Tugas</h3>
                            <p class="text-gray-600">Tugas dari guru akan muncul di sini</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Materi Pembelajaran -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Materi Pembelajaran</h2>
                </div>

                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    @forelse($materials as $material)
                    <div class="p-6 {{ !$loop->last ? 'border-b border-gray-100' : '' }} hover:bg-gray-50 transition">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="px-3 py-1.5 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">{{ $material->class->nama_kelas }}</span>
                                    <span class="px-3 py-1.5 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        Materi
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $material->judul }}</h3>
                                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($material->konten, 100) }}</p>
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $material->creator->nama ?? 'Guru' }}
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $material->created_at->format('d M Y') }}
                                    </div>
                                    @if($material->file_path)
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        {{ strtoupper(pathinfo($material->file_path, PATHINFO_EXTENSION)) }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @if($material->file_path)
                            <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm rounded-xl hover:from-indigo-700 hover:to-purple-700 transition font-semibold shadow-md inline-block text-center">
                                Download
                            </a>
                            @else
                            <button class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm rounded-xl hover:from-indigo-700 hover:to-purple-700 transition font-semibold shadow-md inline-block text-center">
                                Lihat
                            </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Materi</h3>
                        <p class="text-gray-600">Materi dari guru akan muncul di sini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Logout Modal -->
        <div id="logoutModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm animate-backdrop">
            <div class="modal-desktop modal-mobile w-full sm:max-w-md bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto custom-scrollbar">
                <div class="p-6 text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100 animate-warning">
                        <svg class="w-7 h-7 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        Logout sekarang?
                    </h3>
                    <p class="text-gray-600">
                        Kamu harus login lagi untuk mengakses dashboard.
                    </p>
                </div>

                <div class="flex flex-col-reverse sm:flex-row gap-3 px-6 pb-6">
                    <button
                        type="button"
                        onclick="closeLogoutModal()"
                        class="flex-1 rounded-xl border-2 border-gray-300 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition font-semibold">
                        Batal
                    </button>
                    <form action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button
                            type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-red-600 to-red-700 px-4 py-2.5 text-sm font-semibold text-white hover:from-red-700 hover:to-red-800 transition shadow-md">
                            Ya, Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Join Class Modal -->
        <div id="joinClassModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm animate-backdrop">
            <div class="modal-desktop modal-mobile w-full sm:max-w-md bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto custom-scrollbar">
                <!-- Close Button -->
                <button onclick="closeJoinClassModal()" class="absolute right-4 top-4 z-10 p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Header -->
                <div class="px-6 pt-8 text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Gabung Kelas
                    </h3>
                    <p class="text-gray-600">
                        Masukkan token kelas dari guru untuk mulai belajar.
                    </p>
                </div>

                <!-- Form -->
                <form action="{{ route('siswa.join') }}" method="POST" class="px-6 pt-6">
                    @csrf
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Token Kelas
                    </label>
                    <input type="text" name="token" id="classToken" placeholder="Contoh: KLS-8A23" class="w-full rounded-xl border-2 border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <p class="mt-2 text-xs text-gray-500">
                        Token bersifat unik dan hanya berlaku untuk satu kelas.
                    </p>
                    <!-- Actions -->
                    <div class="flex flex-col-reverse sm:flex-row gap-3 pt-6 pb-6">
                        <button type="button" onclick="closeJoinClassModal()" class="flex-1 rounded-xl border-2 border-gray-300 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition font-semibold">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white hover:from-indigo-700 hover:to-purple-700 transition shadow-md">
                            Join Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @include('components.firebase-notification')

    @if(session('newNotification'))
    <script>
        if ('Notification' in window && Notification.permission === 'granted') {
            showNotification('{{ session('newNotification.title') }}', '{{ session('newNotification.message') }}');
        }
    </script>
    @endif
</body>
</html>

