<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidationService
{
    /**
     * Validate class creation input
     */
    public static function validateClassCreation(array $data): array
    {
        return Validator::make($data, [
            'nama_kelas' => 'required|string|max:100|min:3',
            'deskripsi' => 'required|string|max:500|min:10',
            'max_students' => 'required|integer|min:1|max:100',
        ], [
            'nama_kelas.required' => 'Nama kelas harus diisi',
            'nama_kelas.max' => 'Nama kelas maksimal 100 karakter',
            'nama_kelas.min' => 'Nama kelas minimal 3 karakter',
            'deskripsi.required' => 'Deskripsi harus diisi',
            'deskripsi.max' => 'Deskripsi maksimal 500 karakter',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'max_students.required' => 'Jumlah siswa maksimal harus diisi',
            'max_students.min' => 'Jumlah siswa minimal 1',
            'max_students.max' => 'Jumlah siswa maksimal 100',
        ])->validate();
    }

    /**
     * Validate assignment creation input
     */
    public static function validateAssignmentCreation(array $data): array
    {
        return Validator::make($data, [
            'id_class' => 'required|exists:classes,id_class',
            'judul' => 'required|string|max:200|min:5',
            'deskripsi' => 'nullable|string|max:1000',
            'tipe' => 'required|in:pilihan_ganda,essay,praktik',
            'deadline' => 'required|date|after:now',
            'max_score' => 'required|integer|min:1|max:100',
        ], [
            'id_class.required' => 'Kelas harus dipilih',
            'id_class.exists' => 'Kelas tidak ditemukan',
            'judul.required' => 'Judul tugas harus diisi',
            'judul.max' => 'Judul tugas maksimal 200 karakter',
            'judul.min' => 'Judul tugas minimal 5 karakter',
            'tipe.required' => 'Tipe tugas harus dipilih',
            'tipe.in' => 'Tipe tugas tidak valid',
            'deadline.required' => 'Deadline harus diisi',
            'deadline.after' => 'Deadline harus lebih dari sekarang',
            'max_score.required' => 'Nilai maksimal harus diisi',
            'max_score.min' => 'Nilai maksimal minimal 1',
            'max_score.max' => 'Nilai maksimal maksimal 100',
        ])->validate();
    }

    /**
     * Validate question creation input
     */
    public static function validateQuestionCreation(array $data, string $assignmentType): array
    {
        $rules = [
            'soal' => 'required|string|max:2000|min:10',
            'poin' => 'required|integer|min:1|max:100',
        ];

        $messages = [
            'soal.required' => 'Soal harus diisi',
            'soal.max' => 'Soal maksimal 2000 karakter',
            'soal.min' => 'Soal minimal 10 karakter',
            'poin.required' => 'Poin harus diisi',
            'poin.min' => 'Poin minimal 1',
            'poin.max' => 'Poin maksimal 100',
        ];

        if ($assignmentType === 'pilihan_ganda') {
            $rules['pilihan'] = 'required|array|min:2|max:5';
            $rules['pilihan.*'] = 'required|string|max:500|min:2';
            $rules['jawaban_benar'] = 'required|integer|min:0|max:4';

            $messages['pilihan.required'] = 'Pilihan jawaban harus diisi';
            $messages['pilihan.min'] = 'Minimal 2 pilihan jawaban';
            $messages['pilihan.max'] = 'Maksimal 5 pilihan jawaban';
            $messages['jawaban_benar.required'] = 'Jawaban benar harus dipilih';
        } else {
            $rules['kunci_jawaban'] = 'nullable|string|max:2000';
        }

        return Validator::make($data, $rules, $messages)->validate();
    }

    /**
     * Validate material upload input
     */
    public static function validateMaterialUpload(array $data): array
    {
        return Validator::make($data, [
            'id_class' => 'required|exists:classes,id_class',
            'judul' => 'required|string|max:200|min:3',
            'konten' => 'nullable|string|max:5000',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240',
            'online_link' => 'nullable|url|max:500',
        ], [
            'id_class.required' => 'Kelas harus dipilih',
            'id_class.exists' => 'Kelas tidak ditemukan',
            'judul.required' => 'Judul materi harus diisi',
            'judul.max' => 'Judul materi maksimal 200 karakter',
            'judul.min' => 'Judul materi minimal 3 karakter',
            'file.mimes' => 'File harus berformat: pdf, doc, docx, ppt, pptx, xls, xlsx, zip',
            'file.max' => 'Ukuran file maksimal 10 MB',
            'online_link.url' => 'Link online harus berupa URL yang valid',
        ])->validate();
    }

    /**
     * Validate submission input
     */
    public static function validateSubmission(array $data, string $assignmentType): array
    {
        $rules = [];
        $messages = [];

        if ($assignmentType === 'praktik') {
            $rules = [
                'file' => 'required|file|max:51200|mimes:pdf,doc,docx,ppt,pptx,zip,rar,7z,tar,gz,jpg,jpeg,png,mp4,avi,mov',
                'jawaban' => 'nullable|string|max:2000',
            ];
            $messages = [
                'file.required' => 'File harus diupload',
                'file.max' => 'Ukuran file maksimal 50 MB',
                'file.mimes' => 'Format file tidak didukung',
            ];
        } else {
            $rules = [
                'answers' => 'required|array',
                'answers.*' => 'required',
            ];
            $messages = [
                'answers.required' => 'Jawaban harus diisi',
            ];
        }

        return Validator::make($data, $rules, $messages)->validate();
    }

    /**
     * Validate grading input
     */
    public static function validateGrading(array $data, int $maxScore): array
    {
        return Validator::make($data, [
            'score' => "required|numeric|min:0|max:{$maxScore}",
        ], [
            'score.required' => 'Nilai harus diisi',
            'score.numeric' => 'Nilai harus berupa angka',
            'score.min' => 'Nilai minimal 0',
            'score.max' => "Nilai maksimal {$maxScore}",
        ])->validate();
    }

    /**
     * Validate token input
     */
    public static function validateToken(array $data): array
    {
        return Validator::make($data, [
            'token' => 'required|string|size:8|regex:/^[A-Z0-9]+$/',
        ], [
            'token.required' => 'Token harus diisi',
            'token.size' => 'Token harus 8 karakter',
            'token.regex' => 'Token hanya boleh berisi huruf besar dan angka',
        ])->validate();
    }

    /**
     * Validate deadline update
     */
    public static function validateDeadlineUpdate(array $data): array
    {
        return Validator::make($data, [
            'deadline' => 'required|date|after:now',
        ], [
            'deadline.required' => 'Deadline harus diisi',
            'deadline.after' => 'Deadline harus lebih dari sekarang',
        ])->validate();
    }

    /**
     * Validate file generation input
     */
    public static function validateFileGeneration(array $data): array
    {
        return Validator::make($data, [
            'file' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
        ], [
            'file.required' => 'File harus diupload',
            'file.mimes' => 'File harus berformat: pdf, doc, docx, atau txt',
            'file.max' => 'Ukuran file maksimal 10 MB',
        ])->validate();
    }
}
