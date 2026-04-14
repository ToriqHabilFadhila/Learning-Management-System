<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/LMS.png') }}" type="image/png">
    <title>Learning Management System Berbasis AI - {{ $submission->assignment->judul }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('components.navbar')

    <div class="w-full px-4 sm:px-6 md:px-16 py-12">
        <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl shadow-2xl p-8 mb-8 text-white animate-fade-in-up overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Detail Jawaban</h1>
            <p class="text-lg text-purple-100 mb-4">{{ $submission->assignment->judul }}</p>
            <div class="flex flex-wrap gap-3">
                <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                    <span class="font-semibold">Kelas: {{ $submission->assignment->class->nama_kelas }}</span>
                </div>
                <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                    <span class="font-semibold">Dikumpulkan: {{ $submission->submitted_at->format('d M Y, H:i') }}</span>
                </div>
                @if($submission->status === 'graded')
                    <div class="bg-green-500/30 backdrop-blur-sm px-4 py-2 rounded-xl border-2 border-green-300">
                        <span class="font-bold">Nilai: {{ $submission->score }}/{{ $submission->assignment->max_score }}</span>
                    </div>
                @else
                    <div class="bg-amber-500/30 backdrop-blur-sm px-4 py-2 rounded-xl border-2 border-amber-300">
                        <span class="font-semibold">Status: Menunggu Penilaian</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl p-8">
            @php
                $answers = json_decode($submission->jawaban, true);
            @endphp

            @if($submission->assignment->tipe === 'praktik' && $submission->file_path)
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">File yang Dikumpulkan</h3>
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border-2 border-blue-200">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-blue-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 mb-1">{{ basename($submission->file_path) }}</p>
                                <p class="text-sm text-gray-600">Diupload: {{ $submission->submitted_at->format('d M Y, H:i') }}</p>
                            </div>
                            <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition font-semibold shadow-md flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if(is_array($answers))
                @foreach($answers as $questionId => $answer)
                    @php
                        $question = $submission->assignment->questions->where('id_question', $questionId)->first();
                    @endphp
                    @if($question)
                        <div class="mb-8 pb-8 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                {{ $loop->iteration }}. {{ $question->soal }}
                                <span class="text-sm text-gray-500">({{ $question->poin }} poin)</span>
                            </h3>

                            @if($submission->assignment->tipe === 'pilihan_ganda')
                                <div class="space-y-3">
                                    @foreach($question->options as $optIndex => $option)
                                        @php
                                            $borderClass = match(true) {
                                                $option->id_option == $answer && $option->is_correct => 'border-green-500 bg-green-50',
                                                $option->id_option == $answer && !$option->is_correct => 'border-red-500 bg-red-50',
                                                $option->is_correct => 'border-green-300 bg-green-50/50',
                                                default => 'border-gray-200'
                                            };
                                        @endphp
                                        <div class="flex items-center gap-3 p-4 border-2 rounded-xl {{ $borderClass }}">
                                            @php
                                                $radioClass = match(true) {
                                                    $option->id_option == $answer && $option->is_correct => 'border-green-600 bg-green-600',
                                                    $option->id_option == $answer && !$option->is_correct => 'border-red-600 bg-red-600',
                                                    $option->is_correct => 'border-green-600',
                                                    default => 'border-gray-300'
                                                };
                                            @endphp
                                            <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center {{ $radioClass }}">
                                                @if($option->id_option == $answer)
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <circle cx="10" cy="10" r="4"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <span class="flex-1 {{ $option->id_option == $answer ? 'font-semibold' : '' }}">
                                                {{ chr(65 + $optIndex) }}. {{ $option->pilihan }}
                                            </span>
                                            @if($option->id_option == $answer && $option->is_correct)
                                                <span class="text-green-600 font-bold flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Benar
                                                </span>
                                            @elseif($option->id_option == $answer && !$option->is_correct)
                                                <span class="text-red-600 font-bold flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Salah
                                                </span>
                                            @elseif($option->is_correct)
                                                <span class="text-green-600 text-sm">Jawaban Benar</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-gray-50 p-4 rounded-xl">
                                    <p class="text-gray-800 whitespace-pre-wrap">{{ $answer }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            @else
                <div class="bg-gray-50 p-6 rounded-xl">
                    <p class="text-gray-800">{{ $submission->jawaban }}</p>
                </div>
            @endif

            @if($submission->status === 'graded' && $submission->graded_at)
                <div class="mt-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border-2 border-green-200">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">Tugas Sudah Dinilai</h4>
                            <p class="text-sm text-gray-600">Dinilai pada: {{ $submission->graded_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-gray-700">Nilai Akhir:</p>
                        <p class="text-3xl font-bold text-green-600">{{ $submission->score }}/{{ $submission->assignment->max_score }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
