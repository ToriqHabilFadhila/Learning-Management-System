<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/LMS.png') }}" type="image/png">
    <title>Learning Management System Berbasis AI</title>
    @vite(['resources/css/app.css', 'resources/css/class-detail.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    @include('components.navbar')
    <div class="w-full px-4 sm:px-6 md:px-12 py-8">
        <div class="bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 rounded-2xl shadow-xl p-6 mb-6 text-white animate-fade-in-up overflow-hidden relative">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -mr-24 -mt-24"></div>
            <div class="absolute bottom-0 left-0 w-36 h-36 bg-white/10 rounded-full -ml-18 -mb-18"></div>
            <div class="relative z-10">
                <div class="flex items-start gap-4 mb-6">
                    <div class="flex-1">
                        <h1 class="text-2xl md:text-3xl font-bold mb-2 leading-tight">
                            {{ $kelas->nama_kelas }}
                        </h1>
                        <p class="text-base text-purple-100 mb-2">{{ $kelas->deskripsi }}</p>
                        <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                            <svg class="w-5 h-5 text-purple-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm">
                                Token: <span class="font-mono font-bold">{{ $kelas->activeToken->token_code ?? '-' }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl flex items-center gap-2 hover:bg-white/30 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                        <span class="font-semibold">{{ $kelas->enrollments->count() }}/{{ $kelas->max_students }} Siswa</span>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl flex items-center gap-2 hover:bg-white/30 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold">{{ $kelas->assignments->count() }} Tugas</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-6">
                <button onclick="openUploadMateriModal()" class="group p-4 sm:p-6 bg-white rounded-2xl border-2 border-gray-200 hover:border-blue-500 hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center gap-3 sm:gap-4">
                    <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-all duration-300 shadow-lg shadow-indigo-500/25 group-hover:shadow-indigo-600/40">
                        <svg class="w-7 sm:w-8 h-7 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <h3 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">Upload Materi</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Tambah materi</p>
                    </div>
                </button>

                <button onclick="openCreateTugasModal()" class="group p-4 sm:p-6 bg-white rounded-2xl border-2 border-gray-200 hover:border-blue-500 hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center gap-3 sm:gap-4">
                    <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-all duration-300 shadow-lg shadow-indigo-500/25 group-hover:shadow-indigo-600/40">
                        <svg class="w-7 sm:w-8 h-7 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <h3 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">Buat Tugas</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Tugas & Quiz</p>
                    </div>
                </button>

                <button onclick="openJawabanSiswaModal()" class="group p-4 sm:p-6 bg-white rounded-2xl border-2 border-gray-200 hover:border-blue-500 hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center gap-3 sm:gap-4">
                    <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-xl bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-all duration-300 shadow-lg shadow-indigo-500/25 group-hover:shadow-indigo-600/40">
                        <svg class="w-7 sm:w-8 h-7 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <h3 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">Lihat Jawaban</h3>
                        <p class="text-xs sm:text-sm text-gray-500">Review & feedback</p>
                    </div>
                </button>
            </div>

            <div class="bg-white rounded-3xl shadow-xl overflow-hidden animate-fade-in-up">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-5 border-b border-blue-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Daftar Siswa</h2>
                            <p class="text-xs text-gray-500">{{ $kelas->enrollments->count() }} terdaftar</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 max-h-[600px] overflow-y-auto">
                    @forelse($kelas->enrollments as $index => $enrollment)
                        <div class="student-card flex items-center gap-3 p-3 rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent mb-2" style="animation-delay: {{ $index * 0.05 }}s">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 text-white flex items-center justify-center text-sm font-bold shadow-md shadow-purple-500/30 flex-shrink-0">
                                {{ substr($enrollment->user->nama, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">
                                    {{ $enrollment->user->nama }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Bergabung {{ \Carbon\Carbon::parse($enrollment->created_at)->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-700 mb-1">Belum Ada Siswa</p>
                            <p class="text-xs text-gray-500 px-4">
                                Bagikan token kelas untuk mengundang siswa bergabung
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden animate-fade-in-up">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-5 border-b border-purple-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 rounded-xl bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-800">Tugas & Quiz Aktif</h2>
                                    <p class="text-xs text-gray-500">Kelola semua penugasan</p>
                                </div>
                            </div>
                            <span class="text-xs font-medium text-gray-500 bg-white px-3 py-1.5 rounded-full shadow-sm">
                                Total: {{ $kelas->assignments->count() }}
                            </span>
                        </div>
                    </div>

                    <div x-data="{ showAll: false }" class="p-6 space-y-4 max-h-[600px] overflow-y-auto">
                        @forelse($kelas->assignments as $index => $assignment)
                            <div x-show="showAll || {{ $index }} < 3" x-cloak class="assignment-card bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-2xl p-6 hover:border-purple-300" style="animation-delay: {{ $index * 0.1 }}s">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-start gap-3 mb-3">
                                            @php
                                                $bgClass = match($assignment->tipe) {
                                                    'essay' => 'bg-purple-100',
                                                    'pilihan_ganda' => 'bg-indigo-100',
                                                    default => 'bg-blue-100'
                                                };
                                            @endphp
                                            <div class="p-2 rounded-lg flex-shrink-0 {{ $bgClass }}">
                                                @if($assignment->tipe === 'essay')
                                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                @elseif($assignment->tipe === 'pilihan_ganda')
                                                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="font-bold text-lg text-gray-800 mb-1">
                                                    {{ $assignment->judul }}
                                                </h3>
                                                <p class="text-sm text-gray-600 leading-relaxed">
                                                    {{ $assignment->deskripsi }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-3 text-sm ml-11">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-gray-500">
                                                    Deadline: <span class="font-semibold text-gray-700">{{ is_string($assignment->deadline) ? $assignment->deadline : $assignment->deadline->format('d M Y, H:i') }}</span>
                                                </span>
                                                @php
                                                    $deadline = is_string($assignment->deadline)
                                                        ? \Carbon\Carbon::parse($assignment->deadline)
                                                        : $assignment->deadline;
                                                    $daysLeft = now()->startOfDay()->diffInDays($deadline->startOfDay(), false);
                                                @endphp
                                                @if($daysLeft >= 0)
                                                    @php
                                                        $badgeClass = match(true) {
                                                            $daysLeft <= 1 => 'bg-red-100 text-red-700',
                                                            $daysLeft <= 3 => 'bg-yellow-100 text-yellow-700',
                                                            default => 'bg-blue-100 text-blue-700'
                                                        };
                                                    @endphp
                                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full {{ $badgeClass }}">
                                                        {{ $daysLeft == 0 ? 'Hari ini' : ($daysLeft == 1 ? 'Besok' : $daysLeft . ' hari lagi') }}
                                                    </span>
                                                @else
                                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                                        Terlewat
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-gray-500">
                                                    <span class="font-semibold text-purple-600">{{ $assignment->questions->count() }}</span>
                                                    Soal
                                                </span>
                                            </div>
                                            @php
                                                $submissionCount = $assignment->submissions()->where('status', '!=', 'draft')->count();
                                                $totalStudents = $kelas->enrollments->count();
                                            @endphp
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-gray-500">
                                                    <span class="font-semibold text-green-600">{{ $submissionCount }}/{{ $totalStudents }}</span>
                                                    siswa mengumpulkan
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 flex gap-2">
                                        <a href="{{ route('guru.assignments.submissions', $assignment->id_assignment) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border-2 border-purple-300 text-purple-700 text-sm font-semibold rounded-xl hover:bg-purple-50 transition-all duration-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Lihat Jawaban
                                        </a>
                                        @if($assignment->tipe === 'essay')
                                            <a href="{{ route('guru.assignments.questions', $assignment->id_assignment) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 text-white text-sm font-semibold rounded-xl hover:from-slate-800 hover:via-blue-800 hover:to-indigo-700 hover:scale-105 transition-all duration-300 shadow-lg shadow-indigo-500/50 hover:shadow-indigo-600/70">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Tambah Soal
                                            </a>
                                        @elseif($assignment->tipe === 'pilihan_ganda')
                                            <a href="{{ route('guru.assignments.questions', $assignment->id_assignment) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 text-white text-sm font-semibold rounded-xl hover:from-slate-800 hover:via-blue-800 hover:to-indigo-700 hover:scale-105 transition-all duration-300 shadow-lg shadow-indigo-500/50 hover:shadow-indigo-600/70">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                Kelola Soal
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16">
                                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Tugas</h3>
                                <p class="text-gray-500 max-w-md mx-auto mb-6">
                                    Mulai buat tugas atau quiz untuk siswa Anda
                                </p>
                                <button onclick="openCreateTugasModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 text-white text-sm font-semibold rounded-xl hover:from-slate-800 hover:via-blue-800 hover:to-indigo-700 hover:scale-105 transition-all duration-300 shadow-lg shadow-indigo-500/50 hover:shadow-indigo-600/70">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Buat Tugas Baru
                                </button>
                            </div>
                        @endforelse

                        @if($kelas->assignments->count() > 3)
                            <div class="text-center pt-4">
                                <button @click="showAll = !showAll" class="text-purple-600 hover:text-purple-700 font-semibold flex items-center gap-1 mx-auto">
                                    <span x-text="showAll ? 'Lihat Sedikit' : 'Lihat Semua'"></span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path x-show="!showAll" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        <path x-show="showAll" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-xl overflow-hidden animate-fade-in-up">
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 px-6 py-5 border-b border-blue-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 rounded-xl bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-800">Materi Pembelajaran</h2>
                                    <p class="text-xs text-gray-500">Dokumen & file materi</p>
                                </div>
                            </div>
                            <span class="text-xs font-medium text-gray-500 bg-white px-3 py-1.5 rounded-full shadow-sm">
                                Total: {{ $kelas->materials->count() }}
                            </span>
                        </div>
                    </div>
                    <div x-data="{ showAll: false }" class="p-6 space-y-3 max-h-[400px] overflow-y-auto">
                        @forelse($kelas->materials as $index => $material)
                            <div x-show="showAll || {{ $index }} < 3" x-cloak class="flex items-center gap-4 p-4 border-2 border-gray-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition">
                                <div class="p-3 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 truncate">{{ $material->judul }}</h4>
                                    <p class="text-xs text-gray-500">{{ $material->created_at->format('d M Y') }}</p>
                                </div>
                                @if($material->file_path)
                                    <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="px-4 py-2 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 text-white text-sm font-semibold rounded-xl hover:from-slate-800 hover:via-blue-800 hover:to-indigo-700 hover:scale-105 transition-all duration-300 shadow-lg shadow-indigo-500/50 hover:shadow-indigo-600/70">
                                        Download
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm text-gray-500">Belum ada materi</p>
                            </div>
                        @endforelse

                        @if($kelas->materials->count() > 3)
                            <div class="text-center pt-4">
                                <button @click="showAll = !showAll" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1 mx-auto">
                                    <span x-text="showAll ? 'Lihat Sedikit' : 'Lihat Semua'"></span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path x-show="!showAll" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        <path x-show="showAll" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="uploadMateriModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm animate-backdrop">
        <div class="flex items-end sm:items-center justify-center min-h-screen w-full">
            <div class="w-full sm:max-w-md bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto custom-scrollbar">
            <button onclick="closeUploadMateriModal()" class="absolute right-4 top-4 z-10 p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="px-6 pt-8 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Upload Materi</h3>
                <p class="text-gray-600">Upload file materi pembelajaran untuk siswa</p>
            </div>

            <form method="POST" action="{{ route('guru.materials.store') }}" enctype="multipart/form-data" class="px-6 pt-6 pb-6 space-y-4">
                @csrf
                <input type="hidden" name="id_class" value="{{ $kelas->id_class }}">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Judul Materi</label>
                    <input type="text" name="judul" placeholder="Contoh: Persamaan Linear" class="w-full rounded-xl border-2 border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Konten (Opsional)</label>
                    <textarea name="konten" rows="3" placeholder="Deskripsi materi..." class="w-full rounded-xl border-2 border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Upload File</label>
                    <div id="dropzone" class="relative flex flex-col items-center justify-center w-full h-40 rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
                        <input id="fileInput" type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v6m0 0l-3-3m3 3l3-3" />
                        </svg>
                        <p class="text-sm text-gray-600 font-medium">Drag & drop file di sini</p>
                        <p class="text-xs text-gray-500">atau klik untuk memilih file</p>
                    </div>
                    <p id="fileName" class="mt-2 text-sm text-gray-700 hidden"></p>
                    <p class="text-xs text-gray-500 mt-1">PDF, DOC, PPT, XLS, ZIP (Max 10MB)</p>
                </div>

                <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2">
                    <button type="button" onclick="closeUploadMateriModal()" class="flex-1 rounded-xl border-2 border-gray-300 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 rounded-xl bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 px-4 py-2.5 text-sm font-semibold text-white hover:from-slate-800 hover:via-blue-800 hover:to-indigo-700 hover:scale-105 transition-all duration-300 shadow-lg shadow-indigo-500/50 hover:shadow-indigo-600/70">
                        Upload Materi
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <div id="createTugasModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm animate-backdrop">
        <div class="flex items-end sm:items-center justify-center min-h-screen w-full">
            <div class="w-full sm:max-w-md bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto custom-scrollbar">
            <button onclick="closeCreateTugasModal()" class="absolute right-4 top-4 z-10 p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="px-6 pt-8 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Buat Tugas / Quiz</h3>
                <p class="text-gray-600">Tambahkan tugas atau quiz baru untuk siswa</p>
            </div>

            <form method="POST" action="{{ route('guru.assignments.store') }}" class="px-6 pt-6 pb-6 space-y-4">
                @csrf
                <input type="hidden" name="id_class" value="{{ $kelas->id_class }}">
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Tipe</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="tipe" value="essay" class="peer sr-only" required>
                            <div class="p-4 text-center border-2 border-gray-300 rounded-xl transition-all peer-checked:border-green-500 peer-checked:bg-gradient-to-br peer-checked:from-green-50 peer-checked:to-emerald-50 peer-checked:shadow-md hover:border-green-400">
                                <div class="flex justify-center mb-2">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div class="text-xs font-semibold text-gray-700">Essay</div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="tipe" value="pilihan_ganda" class="peer sr-only">
                            <div class="p-4 text-center border-2 border-gray-300 rounded-xl transition-all peer-checked:border-orange-500 peer-checked:bg-gradient-to-br peer-checked:from-orange-50 peer-checked:to-amber-50 peer-checked:shadow-md hover:border-orange-400">
                                <div class="flex justify-center mb-2">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div class="text-xs font-semibold text-gray-700">Pilihan Ganda</div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="tipe" value="praktik" class="peer sr-only">
                            <div class="p-4 text-center border-2 border-gray-300 rounded-xl transition-all peer-checked:border-purple-500 peer-checked:bg-gradient-to-br peer-checked:from-purple-50 peer-checked:to-pink-50 peer-checked:shadow-md hover:border-purple-400">
                                <div class="flex justify-center mb-2">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                    </svg>
                                </div>
                                <div class="text-xs font-semibold text-gray-700">Praktik</div>
                            </div>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Judul</label>
                    <input type="text" name="judul" placeholder="Contoh: Latihan Persamaan Linear" class="w-full rounded-xl border-2 border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Jelaskan tugas/quiz ini..." class="w-full rounded-xl border-2 border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Deadline</label>
                    <input type="datetime-local" name="deadline" class="w-full rounded-xl border-2 border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Nilai Maksimal</label>
                    <input type="number" name="max_score" value="100" min="1" max="100" class="w-full rounded-xl border-2 border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition" required>
                </div>

                <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2">
                    <button type="button" onclick="closeCreateTugasModal()" class="flex-1 rounded-xl border-2 border-gray-300 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 rounded-xl bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 px-4 py-2.5 text-sm font-semibold text-white hover:from-slate-800 hover:via-blue-800 hover:to-indigo-700 hover:scale-105 transition-all duration-300 shadow-lg shadow-indigo-500/50 hover:shadow-indigo-600/70">
                        Buat
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <div id="jawabanSiswaModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm animate-backdrop">
        <div class="flex items-end sm:items-center justify-center min-h-screen w-full">
            <div class="w-full sm:max-w-2xl bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto custom-scrollbar">
                <button onclick="closeJawabanSiswaModal()" class="absolute right-4 top-4 z-10 p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="px-6 pt-8 pb-4 border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Pilih Tugas</h3>
                    <p class="text-gray-600">Pilih tugas untuk melihat jawaban siswa</p>
                </div>

                <div class="px-6 py-4">
                    @forelse($kelas->assignments as $assignment)
                        <a href="{{ route('guru.assignments.submissions', $assignment->id_assignment) }}" class="block mb-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $assignment->judul }}</h4>
                                    <p class="text-xs text-gray-500">{{ $assignment->deskripsi }}</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-gray-500">Belum ada tugas</p>
                        </div>
                    @endforelse
                </div>

                <div class="px-6 pb-6">
                    <button onclick="closeJawabanSiswaModal()" class="w-full rounded-xl border-2 border-gray-300 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition font-semibold">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="{{ asset('js/app.js') }}?v={{ time() }}"></script>
</body>
</html>
