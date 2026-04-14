<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/LMS.png') }}" type="image/png">
    <title>Learning Management System Berbasis AI - {{ $assignment->judul }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                max-height: 2000px;
                transform: translateY(0);
            }
        }
        .answer-detail {
            animation: slideDown 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    @include('components.navbar')

    <div class="w-full px-4 sm:px-6 md:px-16 py-12">
        <div class="bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 rounded-3xl shadow-2xl p-6 sm:p-8 mb-8 text-white overflow-hidden relative animate-fade-in-up">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -mr-24 -mt-24"></div>
            <div class="absolute bottom-0 left-0 w-36 h-36 bg-white/10 rounded-full -ml-18 -mb-18"></div>
            <div class="relative z-10">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-2">Jawaban Siswa</h1>
                <p class="text-base sm:text-lg text-purple-100 mb-4">{{ $assignment->judul }} - {{ $assignment->class->nama_kelas }}</p>
                <div class="flex flex-wrap gap-2 sm:gap-3">
                    <div class="bg-white/20 backdrop-blur-sm px-3 sm:px-4 py-2 rounded-xl">
                        <span class="text-sm sm:text-base font-semibold">Total Siswa: {{ $assignment->class->enrollments->count() }}</span>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-3 sm:px-4 py-2 rounded-xl">
                        <span class="text-sm sm:text-base font-semibold">Sudah Submit: {{ $assignment->submissions->count() }}</span>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-3 sm:px-4 py-2 rounded-xl">
                        <span class="text-sm sm:text-base font-semibold">Belum Submit: {{ $assignment->class->enrollments->count() - $assignment->submissions->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-4 sm:px-6 py-5 border-b border-purple-100">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Daftar Jawaban</h2>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($assignment->submissions as $submission)
                    <div class="p-4 sm:p-6 hover:bg-gray-50 transition-all duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 flex items-center justify-center text-white font-bold text-base sm:text-lg flex-shrink-0 shadow-md shadow-purple-500/30">
                                    {{ strtoupper(substr($submission->user->nama, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-bold text-gray-900 text-sm sm:text-base truncate">{{ $submission->user->nama }}</h3>
                                    <p class="text-xs sm:text-sm text-gray-500 truncate">{{ $submission->user->email }}</p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right flex-shrink-0">
                                @if($submission->status === 'graded')
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Sudah dinilai
                                    </span>
                                    <p class="text-sm font-bold text-green-600 mt-1">Nilai: {{ $submission->score }}/{{ $assignment->max_score }}</p>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Perlu dinilai
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-xs sm:text-sm text-gray-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="truncate">Dikumpulkan: {{ $submission->submitted_at->format('d M Y, H:i') }}</span>
                            </p>
                            <button onclick="toggleAnswer({{ $submission->id_submission }})" class="inline-flex items-center gap-1 text-purple-600 hover:text-purple-700 font-semibold text-sm hover:bg-purple-50 px-3 py-1.5 rounded-lg transition-all">
                                <span>Lihat Detail</span>
                                <svg class="w-4 h-4 transition-transform duration-200" id="arrow-{{ $submission->id_submission }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>

                        <div id="answer-{{ $submission->id_submission }}" class="hidden mt-4 p-3 sm:p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-gray-200 answer-detail">
                            <h4 class="font-semibold text-gray-900 mb-3">Jawaban:</h4>
                            @php
                                $answers = json_decode($submission->jawaban, true);
                            @endphp
                            @if(is_array($answers))
                                <div class="space-y-3">
                                    @foreach($answers as $questionId => $answer)
                                        @php
                                            $question = $assignment->questions->where('id_question', $questionId)->first();
                                        @endphp
                                        @if($question)
                                            <div class="bg-white p-3 rounded-lg">
                                                <p class="font-semibold text-gray-800 mb-2">{{ $question->soal }}</p>
                                                @if($assignment->tipe === 'pilihan_ganda')
                                                    @php
                                                        $selectedOption = $question->options->where('id_option', $answer)->first();
                                                    @endphp
                                                    <p class="text-gray-700">
                                                        Jawaban: <span class="font-semibold {{ $selectedOption && $selectedOption->is_correct ? 'text-green-600' : 'text-red-600' }}">
                                                            {{ $selectedOption ? $selectedOption->pilihan : 'N/A' }}
                                                            @if($selectedOption && $selectedOption->is_correct)
                                                                <svg class="w-4 h-4 inline ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                            @else
                                                                <svg class="w-4 h-4 inline ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                                </svg>
                                                            @endif
                                                        </span>
                                                    </p>
                                                @else
                                                    <p class="text-gray-700 mb-2">{{ $answer }}</p>
                                                    @if($question->kunci_jawaban)
                                                        <div class="mt-2 p-2 bg-blue-50 rounded border border-blue-200">
                                                            <p class="text-xs font-semibold text-blue-700 mb-1 flex items-center gap-1">
                                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Kunci Jawaban:
                                                            </p>
                                                            <p class="text-xs text-blue-600">{{ $question->kunci_jawaban }}</p>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-600">{{ $submission->jawaban }}</p>
                            @endif

                            @if($submission->file_path)
                                <div class="mt-4 p-4 bg-blue-50 border-2 border-blue-200 rounded-xl">
                                    <h5 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                        </svg>
                                        File Pengumpulan
                                    </h5>
                                    <div class="flex items-center justify-between bg-white p-3 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-blue-100 rounded-lg">
                                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 text-sm">{{ basename($submission->file_path) }}</p>
                                                <p class="text-xs text-gray-500">Dikumpulkan: {{ $submission->submitted_at->format('d M Y, H:i') }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($submission->status !== 'graded')
                                @if(($assignment->tipe === 'essay' || $assignment->tipe === 'praktik') && $assignment->questions->where('kunci_jawaban', '!=', null)->count() > 0)
                                    <div class="mt-4 mb-3 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                                        <p class="text-xs text-blue-700 mb-2">
                                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            <strong>AI Grading:</strong> Klik tombol di bawah untuk koreksi otomatis dengan AI.
                                        </p>
                                        <button onclick="autoGrade({{ $submission->id_submission }})" id="ai-btn-{{ $submission->id_submission }}" class="w-full px-4 py-3 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 text-white rounded-xl hover:from-slate-800 hover:via-blue-800 hover:to-indigo-700 hover:scale-105 transition-all duration-300 font-semibold flex items-center justify-center gap-2 shadow-lg shadow-indigo-500/50 hover:shadow-indigo-600/70">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 7H7v6h6V7z"/>
                                                <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-2v1a1 1 0 11-2 0v-1H9v1a1 1 0 11-2 0v-1H5a2 2 0 01-2-2v-2H2a1 1 0 110-2h1V9H2a1 1 0 010-2h1V5a2 2 0 012-2h2V2zM5 5h10v10H5V5z" clip-rule="evenodd"/>
                                            </svg>
                                            Koreksi dengan AI
                                        </button>
                                    </div>
                                @endif

                                <div id="ai-result-{{ $submission->id_submission }}" class="hidden mt-3 p-3 rounded-xl">
                                    <p class="text-xs font-semibold mb-1 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Hasil AI Grading:
                                    </p>
                                    <p id="ai-feedback-{{ $submission->id_submission }}" class="text-sm"></p>
                                </div>

                                <p class="text-xs text-gray-500 mt-2 flex items-start gap-1">
                                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                                    </svg>
                                    <span><strong>Penilaian Manual:</strong> Atau isi nilai manual di kolom di bawah.</span>
                                </p>

                                <form method="POST" action="{{ route('guru.submissions.grade', $submission->id_submission) }}" id="grade-form-{{ $submission->id_submission }}" class="flex flex-col sm:flex-row gap-3 mt-3">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="score" id="score-{{ $submission->id_submission }}" min="0" max="{{ $assignment->max_score }}" placeholder="Nilai (0-{{ $assignment->max_score }})" class="flex-1 rounded-xl border-2 border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all" required>
                                    <button type="submit" class="px-4 sm:px-6 py-2.5 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-800 text-white text-sm rounded-xl hover:from-slate-800 hover:via-blue-800 hover:to-indigo-700 hover:scale-105 transition-all duration-300 font-semibold shadow-lg shadow-indigo-500/50 hover:shadow-indigo-600/70">
                                        Beri Nilai
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Jawaban</h3>
                        <p class="text-gray-600">Belum ada siswa yang mengumpulkan tugas ini</p>
                    </div>
                @endforelse
            </div>
        </div>

        @php
            $submittedUserIds = $assignment->submissions->pluck('id_user');
            $notSubmitted = $assignment->class->enrollments->whereNotIn('id_user', $submittedUserIds);
        @endphp

        @if($notSubmitted->count() > 0)
            <div class="bg-red-50 rounded-3xl shadow-xl p-6 mt-8 border border-red-200">
                <h3 class="font-bold text-red-800 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Belum Mengumpulkan ({{ $notSubmitted->count() }} siswa)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($notSubmitted as $enrollment)
                        <div class="bg-white p-3 rounded-xl flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">
                                {{ strtoupper(substr($enrollment->user->nama, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $enrollment->user->nama }}</p>
                                <p class="text-xs text-gray-500">{{ $enrollment->user->email }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function toggleAnswer(id) {
            const element = document.getElementById('answer-' + id);
            const arrow = document.getElementById('arrow-' + id);

            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                element.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        async function autoGrade(submissionId) {
            const btn = document.getElementById(`ai-btn-${submissionId}`);
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sedang mengoreksi...';

            try {
                const response = await fetch('/guru/ai/grade', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ submission_id: submissionId })
                });

                const data = await response.json();

                if (data.success) {
                    // Auto-fill score
                    document.getElementById(`score-${submissionId}`).value = data.score;

                    // Show AI feedback
                    const resultDiv = document.getElementById(`ai-result-${submissionId}`);
                    const feedbackP = document.getElementById(`ai-feedback-${submissionId}`);

                    // Check if there's an error in feedback
                    if (data.feedback.includes('Error:')) {
                        resultDiv.className = 'mt-3 p-3 bg-yellow-50 border border-yellow-300 rounded-xl';
                        feedbackP.className = 'text-sm text-yellow-800';
                        feedbackP.innerHTML = '<span class="font-semibold text-yellow-700 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> AI Tidak Tersedia</span><br>' +
                            '<span class="text-xs">API key tidak valid atau koneksi bermasalah. Silakan nilai manual dengan mengisi kolom di bawah.</span><br>' +
                            '<span class="text-xs text-gray-600 mt-1 block">Detail: ' + data.feedback.replace('Error: ', '') + '</span>';
                    } else {
                        resultDiv.className = 'mt-3 p-3 bg-green-50 border border-green-200 rounded-xl';
                        feedbackP.className = 'text-sm text-green-800';
                        feedbackP.textContent = data.feedback;
                    }

                    resultDiv.classList.remove('hidden');
                    btn.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg> Selesai! Nilai: ' + data.score;
                } else {
                    const resultDiv = document.getElementById(`ai-result-${submissionId}`);
                    const feedbackP = document.getElementById(`ai-feedback-${submissionId}`);
                    resultDiv.className = 'mt-3 p-3 bg-yellow-50 border border-yellow-300 rounded-xl';
                    feedbackP.className = 'text-sm text-yellow-800';
                    feedbackP.innerHTML = '<span class="font-semibold text-yellow-700 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> AI Tidak Tersedia</span><br>' +
                        '<span class="text-xs">Silakan nilai manual dengan mengisi kolom di bawah.</span><br>' +
                        '<span class="text-xs text-gray-600 mt-1 block">Detail: ' + (data.message || 'Gagal mengoreksi') + '</span>';
                    resultDiv.classList.remove('hidden');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                const resultDiv = document.getElementById(`ai-result-${submissionId}`);
                const feedbackP = document.getElementById(`ai-feedback-${submissionId}`);
                resultDiv.className = 'mt-3 p-3 bg-yellow-50 border border-yellow-300 rounded-xl';
                feedbackP.className = 'text-sm text-yellow-800';
                feedbackP.innerHTML = '<span class="font-semibold text-yellow-700 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> Koneksi Bermasalah</span><br>' +
                    '<span class="text-xs">Tidak dapat terhubung ke server AI. Silakan nilai manual.</span>';
                resultDiv.classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    </script>
</body>
</html>
