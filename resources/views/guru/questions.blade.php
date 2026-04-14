<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/LMS.png') }}" type="image/png">
    <title>Learning Management System Berbasis AI</title>
    @vite(['resources/css/app.css', 'resources/css/questions.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    @include('components.navbar')

    <div class="w-full px-4 sm:px-6 md:px-16 py-12">
        <div class="bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 rounded-3xl shadow-2xl p-8 mb-8 text-white animate-fade-in-up overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
            <div class="relative z-10">
                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <h1 class="text-3xl md:text-4xl font-bold mb-2 leading-tight">
                            {{ $assignment->judul }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-3 text-sm">
                            <span class="bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full font-medium">
                                {{ $assignment->class->nama_kelas }}
                            </span>
                            <span class="bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-full font-medium flex items-center gap-2">
                                @if ($assignment->tipe === 'pilihan_ganda')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Pilihan Ganda
                                @elseif($assignment->tipe === 'essay')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                    Essay
                                @else
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                                    </svg>
                                    Praktik
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="animate-fade-in-up bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-xl mb-8 shadow-md flex items-start gap-3">
                <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-semibold">Berhasil!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="flex gap-4 mb-8">
            <button onclick="openAIGenerateModal()" class="flex-1 bg-gradient-to-r from-blue-600 to-cyan-600 text-white py-4 px-6 rounded-2xl font-bold text-lg hover:from-blue-700 hover:to-cyan-700 transition-all duration-300 hover:shadow-xl flex items-center justify-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                Generate Soal dengan AI
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8 animate-fade-in-up">
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-8 py-6 border-b border-purple-100">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Tambah Soal Manual</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('guru.questions.store', $assignment->id_assignment) }}" id="questionForm" class="p-8">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-3">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            @if($assignment->tipe === 'essay')
                                Pertanyaan Essay
                            @elseif($assignment->tipe === 'praktik')
                                Instruksi Tugas Praktik
                            @else
                                Pertanyaan Soal
                            @endif
                        </label>
                        <textarea name="soal" rows="3" required class="w-full px-3 py-2.5 text-sm border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none" placeholder="@if($assignment->tipe === 'essay')Contoh: Jelaskan pengertian fotosintesis dan sebutkan faktor-faktor yang mempengaruhinya!@elseif($assignment->tipe === 'praktik')Contoh: Buatlah program sederhana menggunakan Python untuk menghitung luas lingkaran. Upload file .py hasil pekerjaan Anda.@elseTuliskan pertanyaan soal di sini...@endif"></textarea>
                        @if($assignment->tipe === 'essay')
                            <p class="mt-2 text-xs text-gray-500 flex items-start gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span>Tips: Buat pertanyaan yang mendorong siswa untuk menjelaskan, menganalisis, atau memberikan pendapat.</span>
                            </p>
                        @elseif($assignment->tipe === 'praktik')
                            <p class="mt-2 text-xs text-gray-500 flex items-start gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span>Tips: Berikan instruksi yang jelas tentang apa yang harus dikerjakan dan format file yang harus diupload.</span>
                            </p>
                        @endif
                    </div>

                    @if($assignment->tipe === 'essay' || $assignment->tipe === 'praktik')
                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-3">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd" />
                            </svg>
                            Kunci Jawaban (untuk AI Grading)
                        </label>
                        <textarea name="kunci_jawaban" rows="2" class="w-full px-3 py-2.5 text-sm border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none" placeholder="@if($assignment->tipe === 'essay')Tuliskan jawaban ideal/kunci jawaban yang diharapkan. AI akan menggunakan ini untuk mengoreksi jawaban siswa.@elseTuliskan kriteria penilaian atau poin-poin penting yang harus ada dalam tugas praktik.@endif"></textarea>
                        <p class="mt-2 text-xs text-purple-600 flex items-start gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <span>Kunci jawaban ini akan digunakan oleh AI untuk mengoreksi jawaban siswa secara otomatis</span>
                        </p>
                    </div>
                    @endif

                    <div>
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-3">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            Poin
                        </label>
                        <input type="number" name="poin" value="10" min="1" required class="w-full px-3 py-2.5 text-sm border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>

                    @if ($assignment->tipe === 'pilihan_ganda')
                        <div id="pilihanContainer">
                            <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-3">
                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                                Pilihan Jawaban
                            </label>
                            <div class="space-y-3 bg-gray-50 p-4 rounded-xl" id="optionsList">
                                <div class="option-item flex gap-3 items-center bg-white p-3 rounded-lg border-2 border-gray-200 hover:border-purple-300 transition-all">
                                    <input type="radio" name="jawaban_benar" value="0" required class="w-5 h-5 text-purple-600 focus:ring-purple-500">
                                    <input type="text" name="pilihan[]" placeholder="Pilihan A" required class="flex-1 px-3 py-2 text-sm bg-transparent focus:outline-none">>
                                    <span class="text-sm font-semibold text-gray-400">A</span>
                                </div>
                                <div class="option-item flex gap-3 items-center bg-white p-3 rounded-lg border-2 border-gray-200 hover:border-purple-300 transition-all">
                                    <input type="radio" name="jawaban_benar" value="1" required class="w-5 h-5 text-purple-600 focus:ring-purple-500">
                                    <input type="text" name="pilihan[]" placeholder="Pilihan B" required class="flex-1 px-3 py-2 text-sm bg-transparent focus:outline-none">>
                                    <span class="text-sm font-semibold text-gray-400">B</span>
                                </div>
                            </div>
                            <button type="button" onclick="addOption()" class="mt-3 inline-flex items-center gap-2 text-sm text-purple-600 hover:text-purple-700 font-semibold hover:bg-purple-50 px-4 py-2 rounded-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Pilihan
                            </button>
                        </div>
                    @endif

                    <button type="submit" class="w-full bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 text-white py-3 text-sm rounded-xl font-semibold hover:from-slate-800 hover:via-blue-800 hover:to-indigo-700 hover:scale-105 transition-all duration-300 shadow-lg shadow-indigo-500/50 hover:shadow-indigo-600/70 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Soal
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-8 py-6 border-b border-indigo-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($assignment->questions->count() > 0)
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll()" class="w-5 h-5 text-purple-600 focus:ring-purple-500 rounded cursor-pointer">
                        @endif
                        <div class="p-3 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Daftar Soal</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <button id="deleteSelectedBtn" onclick="deleteSelected()" class="hidden items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span id="selectedCount">Hapus (0)</span>
                        </button>
                        <span class="text-sm font-medium text-gray-500 bg-white px-4 py-2 rounded-full shadow-sm">
                            Total: {{ $assignment->questions->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-4">
                @if($assignment->questions->count() > 0)
                    <div class="mb-6 flex justify-end">
                        <form method="POST" action="{{ route('guru.assignments.publish', $assignment->id_assignment) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition font-semibold shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan & Publikasikan
                            </button>
                        </form>
                    </div>
                @endif
                <div id="questionsContainer">
                    @forelse($assignment->questions as $question)
                        <div class="question-card border-2 border-gray-200 rounded-2xl p-6 hover:border-purple-300 {{ $loop->index >= 3 ? 'hidden-question' : '' }}" x-data="{ edit: false }" style="{{ $loop->index >= 3 ? 'display: none;' : '' }}">
                            <div x-show="!edit">
                                <div class="flex justify-between items-start gap-4">
                                    <input type="checkbox" class="question-checkbox mt-2 w-5 h-5 text-purple-600 focus:ring-purple-500 rounded cursor-pointer" data-id="{{ $question->id_question }}" onclick="updateDeleteButton()">
                                    <div class="flex-1">
                                        <div class="flex items-start gap-3 mb-3">
                                            <span class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-lg flex items-center justify-center font-bold text-sm">
                                                {{ $loop->iteration }}
                                            </span>
                                        <div class="flex-1">
                                            <div class="mb-3">
                                                <p class="text-sm font-bold text-gray-700 mb-1">Soal:</p>
                                                <p class="font-medium text-gray-800 text-base leading-relaxed">
                                                    {{ $question->soal }}
                                                </p>
                                            </div>

                                            @if(($assignment->tipe === 'essay' || $assignment->tipe === 'praktik') && $question->kunci_jawaban)
                                                <div class="mb-3 p-3 bg-green-50 border-l-4 border-green-500 rounded-lg">
                                                    <p class="text-sm font-bold text-green-800 mb-1">Kunci Jawaban:</p>
                                                    <p class="text-sm text-green-700 leading-relaxed">{{ $question->kunci_jawaban }}</p>
                                                </div>
                                            @endif

                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-bold text-gray-700">Poin:</span>
                                                <span class="inline-flex items-center gap-1 px-3 py-1 text-sm font-bold text-purple-700 bg-purple-100 rounded-lg">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                    {{ $question->poin }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($assignment->tipe === 'pilihan_ganda')
                                        <div class="ml-11 space-y-2 mt-3">
                                            @foreach ($question->options as $opt)
                                                <div class="flex items-start gap-3 p-3 rounded-lg {{ $opt->is_correct ? 'bg-green-50 border-2 border-green-300' : 'bg-gray-50' }}">
                                                    <span class="flex-shrink-0 w-7 h-7 rounded-full {{ $opt->is_correct ? 'bg-green-600 text-white' : 'bg-gray-300 text-gray-600' }} flex items-center justify-center text-sm font-bold">
                                                        {{ chr(65 + $loop->index) }}
                                                    </span>
                                                    <span class="flex-1 text-sm {{ $opt->is_correct ? 'text-green-800 font-semibold' : 'text-gray-700' }}">
                                                        {{ $opt->pilihan }}
                                                    </span>
                                                    @if ($opt->is_correct)
                                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-col gap-2">
                                    <button @click="edit=true" class="inline-flex items-center gap-1 px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('guru.questions.delete', $question->id_question) }}" onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex items-center gap-1 px-4 py-2 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <form x-show="edit" method="POST" action="{{ route('guru.questions.update', $question->id_question) }}" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Soal</label>
                                <textarea name="soal" rows="2" class="w-full border-2 border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none" required>{{ $question->soal }}</textarea>
                            </div>

                            @if($assignment->tipe === 'essay' || $assignment->tipe === 'praktik')
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kunci Jawaban</label>
                                <textarea name="kunci_jawaban" rows="2" class="w-full border-2 border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none">{{ $question->kunci_jawaban }}</textarea>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Poin</label>
                                <input type="number" name="poin" value="{{ $question->poin }}" class="w-full border-2 border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>

                            @if ($assignment->tipe === 'pilihan_ganda')
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Pilihan Jawaban</label>
                                    <div class="space-y-2">
                                        @foreach ($question->options as $i => $opt)
                                            <div class="flex gap-3 items-center bg-gray-50 p-3 rounded-lg">
                                                <input type="radio" name="jawaban_benar" value="{{ $i }}" {{ $opt->is_correct ? 'checked' : '' }} class="w-5 h-5 text-purple-600 focus:ring-purple-500">
                                                <input type="text" name="pilihan[]" value="{{ $opt->pilihan }}" class="flex-1 border-2 border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                                <span class="text-sm font-semibold text-gray-400">{{ chr(65 + $i) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="flex gap-3 pt-4">
                                <button type="submit" class="flex-1 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 text-white text-sm px-4 py-2.5 rounded-xl font-semibold hover:from-slate-800 hover:via-blue-800 hover:to-indigo-700 hover:scale-105 transition-all duration-300 shadow-lg shadow-indigo-500/50 hover:shadow-indigo-600/70">
                                    Simpan Perubahan
                                </button>
                                <button type="button" @click="edit=false" class="px-4 py-2.5 border-2 border-gray-300 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-all">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Soal</h3>
                        <p class="text-gray-500">
                            Gunakan form di atas untuk menambahkan soal pertama Anda
                        </p>
                    </div>
                @endforelse
                </div>

                @if($assignment->questions->count() > 3)
                    <div class="text-center mt-6">
                        <button id="showAllBtn" onclick="toggleShowAll()" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-800 text-white rounded-xl hover:from-slate-900 hover:via-blue-900 hover:to-indigo-800 transition font-semibold shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                            <span id="showAllText">Lihat Semua ({{ $assignment->questions->count() }} soal)</span>
                        </button>
                    </div>
                @endif
        </div>
    </div>

    <div id="aiGenerateModal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm animate-backdrop">
        <div class="flex items-end sm:items-center justify-center min-h-screen w-full">
        <div class="w-full sm:max-w-2xl bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto custom-scrollbar">
            <button onclick="closeAIGenerateModal()" class="absolute right-4 top-4 z-10 p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="px-8 pt-8 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-2">Generate Soal dengan AI</h3>
                <p class="text-gray-600 mb-6">Upload file PDF/DOC berisi soal dan kunci jawaban, AI akan otomatis menginputkannya</p>
            </div>

            <form method="POST" action="{{ route('guru.questions.generate', $assignment->id_assignment) }}" enctype="multipart/form-data" class="px-8 pb-8 space-y-6" id="aiGenerateForm">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">Upload File Soal</label>
                    <div class="relative flex flex-col items-center justify-center w-full h-48 rounded-2xl border-3 border-dashed border-blue-300 bg-blue-50 cursor-pointer hover:bg-blue-100 transition">
                        <input id="aiFileInput" type="file" name="file" accept=".pdf,.doc,.docx,.txt" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="updateFileName(this)" />
                        <svg class="w-12 h-12 text-blue-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-base text-blue-700 font-semibold mb-1">Drag & drop file di sini</p>
                        <p class="text-sm text-blue-600">atau klik untuk memilih file</p>
                        <p id="aiFileName" class="mt-3 text-sm text-gray-700 font-medium hidden"></p>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span>Format: PDF, DOC, DOCX, TXT (Max 10MB)</span>
                    </p>
                </div>

                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500 p-4 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span>Tips untuk hasil terbaik:</span>
                            </p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Pastikan format soal jelas dan terstruktur</li>
                                <li>Untuk pilihan ganda: sertakan opsi A, B, C, D</li>
                                <li><strong>Tandai jawaban benar: Jawaban: A atau Kunci: B atau Benar: C</strong></li>
                                <li>Untuk essay: tambahkan kunci jawaban setelah soal</li>
                                <li><strong>Format kunci essay: Kunci Jawaban: ... atau Jawaban: ...</strong></li>
                                <li>Gunakan numbering yang konsisten (1, 2, 3...)</li>
                                <li><strong>Format poin: (Poin: 10) atau (10 poin) atau [10]</strong></li>
                                <li>Jika tidak ada poin/kunci, sistem akan gunakan default</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="aiLoadingState" class="hidden">
                    <div class="flex items-center justify-center gap-3 py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <p class="text-gray-700 font-semibold">AI sedang memproses file Anda...</p>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row gap-3">
                    <button type="button" onclick="closeAIGenerateModal()" class="flex-1 rounded-xl border-2 border-gray-300 px-6 py-3 text-gray-700 hover:bg-gray-50 transition font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 rounded-xl bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 px-6 py-3 font-bold text-white hover:from-slate-800 hover:via-blue-800 hover:to-indigo-700 hover:scale-105 transition-all duration-300 shadow-lg shadow-indigo-500/50 hover:shadow-indigo-600/70 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Generate Soal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        let optionCount = 2;
        function addOption() {
            const container = document.getElementById('optionsList');
            const div = document.createElement('div');
            div.className =
                'option-item flex gap-3 items-center bg-white p-3 rounded-lg border-2 border-gray-200 hover:border-purple-300 transition-all';
            div.innerHTML = `
                <input type="radio" name="jawaban_benar" value="${optionCount}" required class="w-5 h-5 text-purple-600 focus:ring-purple-500">
                <input type="text" name="pilihan[]" placeholder="Pilihan ${String.fromCharCode(65 + optionCount)}" required class="flex-1 px-4 py-2 bg-transparent focus:outline-none">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <span class="text-sm font-semibold text-gray-400">${String.fromCharCode(65 + optionCount)}</span>
            `;
            container.appendChild(div);
            optionCount++;
        }

        function openAIGenerateModal() {
            document.getElementById('aiGenerateModal').classList.remove('hidden');
        }

        function closeAIGenerateModal() {
            document.getElementById('aiGenerateModal').classList.add('hidden');
            document.getElementById('aiGenerateForm').reset();
            document.getElementById('aiFileName').classList.add('hidden');
        }

        function updateFileName(input) {
            const fileNameDisplay = document.getElementById('aiFileName');
            if (input.files && input.files[0]) {
                fileNameDisplay.textContent = input.files[0].name;
                fileNameDisplay.innerHTML = '<svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>' + input.files[0].name;
                fileNameDisplay.classList.remove('hidden');
            }
        }

        document.getElementById('aiGenerateForm')?.addEventListener('submit', function() {
            document.getElementById('aiLoadingState').classList.remove('hidden');
        });

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.question-checkbox');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateDeleteButton();
        }

        function updateDeleteButton() {
            const checkboxes = document.querySelectorAll('.question-checkbox:checked');
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            const selectedCount = document.getElementById('selectedCount');
            const selectAll = document.getElementById('selectAll');

            if (checkboxes.length > 0) {
                deleteBtn.classList.remove('hidden');
                deleteBtn.classList.add('flex');
                selectedCount.textContent = `Hapus (${checkboxes.length})`;
            } else {
                deleteBtn.classList.add('hidden');
                deleteBtn.classList.remove('flex');
            }

            const allCheckboxes = document.querySelectorAll('.question-checkbox');
            if (selectAll) {
                selectAll.checked = allCheckboxes.length > 0 && checkboxes.length === allCheckboxes.length;
            }
        }

        function deleteSelected() {
            const checkboxes = document.querySelectorAll('.question-checkbox:checked');
            if (checkboxes.length === 0) return;

            if (!confirm(`Yakin ingin menghapus ${checkboxes.length} soal yang dipilih?`)) return;

            const ids = Array.from(checkboxes).map(cb => cb.dataset.id);
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('guru.questions.bulkDelete', $assignment->id_assignment) }}';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            const idsInput = document.createElement('input');
            idsInput.type = 'hidden';
            idsInput.name = 'ids';
            idsInput.value = JSON.stringify(ids);
            form.appendChild(idsInput);

            document.body.appendChild(form);
            form.submit();
        }

        let showingAll = false;
        function toggleShowAll() {
            const hiddenQuestions = document.querySelectorAll('.hidden-question');
            const btn = document.getElementById('showAllBtn');
            const btnText = document.getElementById('showAllText');
            const btnIcon = btn.querySelector('svg');

            showingAll = !showingAll;

            hiddenQuestions.forEach(q => {
                q.style.display = showingAll ? 'block' : 'none';
            });

            if (showingAll) {
                btnText.textContent = 'Lihat Lebih Sedikit';
                btnIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>';
            } else {
                btnText.textContent = 'Lihat Semua ({{ $assignment->questions->count() }} soal)';
                btnIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>';
            }
        }
    </script>
</body>
</html>
