<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/LMS.png') }}" type="image/png">
    <title>{{ $assignment->judul }} - Learning Management System Berbasis AI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('components.notifications')
    @include('components.navbar')

    <div class="w-full px-4 sm:px-6 md:px-12 py-8">
        <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-2xl shadow-xl p-6 mb-6 text-white animate-fade-in-up overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
            <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ $assignment->judul }}</h1>
            <p class="text-base text-purple-100 mb-3">{{ $assignment->deskripsi }}</p>
            <div class="flex flex-wrap gap-3">
                <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                    <span class="font-semibold">Kelas: {{ $assignment->class->nama_kelas }}</span>
                </div>
                @php
                    $existingSubmission = \App\Models\Submission::where('id_assignment', $assignment->id_assignment)
                        ->where('id_user', auth()->id())
                        ->where('status', '!=', 'draft')
                        ->first();
                @endphp
                @if(!$existingSubmission)
                    <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                        <span class="font-semibold">Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}</span>
                    </div>
                @endif
                <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl">
                    <span class="font-semibold">Nilai Maks: {{ $assignment->max_score }}</span>
                </div>
                @if($existingSubmission)
                    <div class="bg-green-500/30 backdrop-blur-sm px-4 py-2 rounded-xl border-2 border-green-300 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-bold">Sudah Dikumpulkan</span>
                    </div>
                @endif
            </div>
        </div>

        @if($existingSubmission)
            <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Tugas Sudah Dikumpulkan</h2>
                <p class="text-gray-600 mb-6">Kamu sudah mengumpulkan tugas ini pada {{ $existingSubmission->submitted_at->format('d M Y, H:i') }}</p>
                @if($existingSubmission->status === 'graded')
                    <div class="inline-block bg-green-50 border-2 border-green-200 rounded-xl p-6 mb-6">
                        <p class="text-sm text-gray-600 mb-2">Nilai Kamu:</p>
                        <p class="text-4xl font-bold text-green-600">{{ $existingSubmission->score }}/{{ $assignment->max_score }}</p>
                    </div>
                @else
                    <div class="inline-block bg-amber-50 border-2 border-amber-200 rounded-xl p-6 mb-6">
                        <p class="text-amber-800 font-semibold flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Menunggu Penilaian dari Guru
                        </p>
                    </div>
                @endif
                <div class="flex justify-center gap-4">
                    <a href="{{ route('siswa.submissions.show', $existingSubmission->id_submission) }}" class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white text-sm rounded-xl hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 transition font-semibold shadow-md">
                        Lihat Detail Jawaban
                    </a>
                    <a href="{{ route('dashboard') }}" class="px-4 py-2.5 border-2 border-gray-300 text-gray-700 text-sm rounded-xl hover:bg-gray-50 transition font-semibold">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        @else
        <div class="bg-white rounded-3xl shadow-xl p-8">
            @if($assignment->tipe === 'pilihan_ganda')
                <form method="POST" action="{{ route('siswa.assignments.submit', $assignment->id_assignment) }}">
                    @csrf
                    @foreach($assignment->questions->sortBy('urutan') as $index => $question)
                        <div class="mb-8 pb-8 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                {{ $index + 1 }}. {{ $question->soal }}
                                <span class="text-sm text-gray-500">({{ $question->poin }} poin)</span>
                            </h3>
                            <div class="space-y-3">
                                @foreach($question->options as $optIndex => $option)
                                    <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 cursor-pointer transition">
                                        <input type="radio" name="answers[{{ $question->id_question }}]" value="{{ $option->id_option }}" class="w-5 h-5 text-indigo-600" required>
                                        <span class="text-gray-800">{{ chr(65 + $optIndex) }}. {{ $option->pilihan }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="flex justify-end gap-4 mt-8">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 border-2 border-gray-300 text-gray-700 text-sm rounded-xl hover:bg-gray-50 transition font-semibold">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white text-sm rounded-xl hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 transition font-semibold shadow-md">
                            Submit Jawaban
                        </button>
                    </div>
                </form>
            @elseif($assignment->tipe === 'essay')
                <form method="POST" action="{{ route('siswa.assignments.submit', $assignment->id_assignment) }}">
                    @csrf
                    @foreach($assignment->questions as $index => $question)
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                {{ $index + 1 }}. {{ $question->soal }}
                                <span class="text-sm text-gray-500">({{ $question->poin }} poin)</span>
                            </h3>
                            <textarea name="answers[{{ $question->id_question }}]" rows="5" class="w-full rounded-xl border-2 border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tulis jawabanmu di sini..." required></textarea>
                        </div>
                    @endforeach

                    <div class="flex justify-end gap-4 mt-8">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 border-2 border-gray-300 text-gray-700 text-sm rounded-xl hover:bg-gray-50 transition font-semibold">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white text-sm rounded-xl hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 transition font-semibold shadow-md">
                            Submit Jawaban
                        </button>
                    </div>
                </form>
            @else
                <form method="POST" action="{{ route('siswa.assignments.submit', $assignment->id_assignment) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="max-w-2xl mx-auto">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Upload File Tugas Praktik</h3>
                            <p class="text-gray-600">Upload file hasil pekerjaanmu (dokumen, presentasi, kode, video, dll)</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-900 mb-3">File Tugas</label>
                            <div id="dropzonePraktik" class="relative flex flex-col items-center justify-center w-full h-48 rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 cursor-pointer hover:bg-gray-100 transition">
                                <input id="fileInputPraktik" type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar,.7z,.tar,.gz,.jpg,.jpeg,.png,.mp4,.avi,.mov" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required />
                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v6m0 0l-3-3m3 3l3-3"/>
                                </svg>
                                <p class="text-sm text-gray-600 font-medium mb-1">Drag & drop file di sini</p>
                                <p class="text-xs text-gray-500">atau klik untuk memilih file</p>
                            </div>
                            <p id="fileNamePraktik" class="mt-3 text-sm text-gray-700 hidden"></p>
                            <p class="text-xs text-gray-500 mt-2">Format: PDF, DOC, DOCX, PPT, PPTX, ZIP, RAR, 7Z, JPG, PNG, MP4 (Max 50MB)</p>
                            <p class="text-xs text-amber-600 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Jika ingin upload folder project, compress dulu menjadi ZIP/RAR
                            </p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-900 mb-3">Catatan Tambahan (Opsional)</label>
                            <textarea name="jawaban" rows="3" class="w-full rounded-xl border-2 border-gray-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Tambahkan catatan atau penjelasan tentang tugas yang kamu kerjakan..."></textarea>
                        </div>

                        <div class="flex justify-center gap-4">
                            <a href="{{ route('dashboard') }}" class="px-4 py-2.5 border-2 border-gray-300 text-gray-700 text-sm rounded-xl hover:bg-gray-50 transition font-semibold">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white text-sm rounded-xl hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 transition font-semibold shadow-md">
                                Submit Tugas
                            </button>
                        </div>
                    </div>
                </form>

                <script>
                    const fileInputPraktik = document.getElementById('fileInputPraktik');
                    const fileNamePraktik = document.getElementById('fileNamePraktik');
                    const dropzonePraktik = document.getElementById('dropzonePraktik');

                    fileInputPraktik.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            fileNamePraktik.textContent = '📎 File terpilih: ' + this.files[0].name;
                            fileNamePraktik.classList.remove('hidden');
                        }
                    });

                    dropzonePraktik.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        this.classList.add('border-indigo-500', 'bg-indigo-50');
                    });

                    dropzonePraktik.addEventListener('dragleave', function(e) {
                        e.preventDefault();
                        this.classList.remove('border-indigo-500', 'bg-indigo-50');
                    });

                    dropzonePraktik.addEventListener('drop', function(e) {
                        e.preventDefault();
                        this.classList.remove('border-indigo-500', 'bg-indigo-50');
                        if (e.dataTransfer.files.length) {
                            fileInputPraktik.files = e.dataTransfer.files;
                            fileNamePraktik.textContent = '📎 File terpilih: ' + e.dataTransfer.files[0].name;
                            fileNamePraktik.classList.remove('hidden');
                        }
                    });
                </script>
            @endif
        </div>
        @endif
    </div>

    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>
