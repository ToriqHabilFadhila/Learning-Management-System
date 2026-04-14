# 📚 DOKUMENTASI LENGKAP SISTEM LMS (Learning Management System)

## 🎯 RINGKASAN PROYEK
Sistem LMS berbasis web menggunakan Laravel untuk mengelola pembelajaran online dengan fitur:
- Manajemen kelas dan materi
- Tugas dan quiz dengan AI
- Notifikasi real-time (Email + Push Notification)
- Analisis progress siswa dengan AI
- Role-based access (Admin, Guru, Siswa)

---

## 📁 STRUKTUR FOLDER & PENJELASAN

### 1️⃣ **APP/** - Logika Aplikasi Utama

#### **A. CHANNELS/** - Custom Notification Channels
```
app/Channels/FcmChannel.php
```
**Fungsi:** Channel khusus untuk mengirim push notification ke HP menggunakan Firebase Cloud Messaging (FCM)
**Digunakan untuk:** Mengirim notifikasi real-time ke aplikasi mobile siswa/guru

---

#### **B. CONSOLE/COMMANDS/** - Perintah Artisan untuk Maintenance

##### 1. **CheckStorageCommand.php**
```bash
php artisan admin:check-storage
```
**Fungsi:** Memeriksa penggunaan storage server
**Digunakan untuk:** Monitoring kapasitas penyimpanan file (materi, tugas, submission)
**Kapan dijalankan:** Manual atau dijadwalkan setiap hari

##### 2. **CleanupEnrollments.php**
```bash
php artisan cleanup:enrollments
```
**Fungsi:** Membersihkan data enrollment yang duplikat atau bermasalah
**Digunakan untuk:** 
- Hapus enrollment kelas tidak aktif
- Hapus enrollment duplikat
- Hapus enrollment tanpa user/kelas
**Kapan dijalankan:** Berkala untuk menjaga integritas data

##### 3. **ClearLmsCache.php**
```bash
php artisan cache:clear-lms --type=all
```
**Fungsi:** Menghapus cache Redis khusus LMS
**Digunakan untuk:** Clear cache rekomendasi, materi, feedback AI
**Kapan dijalankan:** Setelah update data penting atau troubleshooting

##### 4. **SendDailyStatsCommand.php**
```bash
php artisan admin:send-daily-stats
```
**Fungsi:** Mengirim statistik harian ke admin
**Digunakan untuk:** Laporan otomatis aktivitas sistem (user baru, kelas, tugas)
**Kapan dijalankan:** Setiap hari pukul 08:00 pagi

##### 5. **VerifyDatabase.php**
```bash
php artisan db:verify
```
**Fungsi:** Verifikasi integritas database
**Digunakan untuk:** 
- Test koneksi database
- Cek semua tabel ada
- Verifikasi relationships
- Hitung jumlah data
**Kapan dijalankan:** Setelah migration atau troubleshooting

---

#### **C. HTTP/CONTROLLERS/** - Pengendali Request HTTP

##### **Auth/** - Autentikasi & Otorisasi

###### 1. **AuthServices.php**
**Fungsi:** Menangani semua proses autentikasi
**Fitur:**
- Login (dengan rate limiting 5x/15 menit)
- Register (dengan rate limiting 3x/60 menit)
- Logout
- Email verification
- Forgot password & reset password
**Digunakan untuk:** Keamanan akses sistem

###### 2. **GoogleController.php**
**Fungsi:** Login menggunakan akun Google (OAuth)
**Fitur:**
- Redirect ke Google login
- Handle callback dari Google
- Auto-create user jika belum ada
**Digunakan untuk:** Kemudahan login tanpa password

---

##### **Api/** - API Endpoints

###### **FCMTokenController.php**
**Fungsi:** Mengelola FCM token untuk push notification
**Endpoints:**
- `POST /api/fcm-token` - Simpan token device
- `DELETE /api/fcm-token` - Hapus token saat logout
**Digunakan untuk:** Registrasi device untuk terima notifikasi push

---

##### **Main Controllers**

###### 1. **AdminController.php**
**Role:** Admin
**Fungsi:** Manajemen sistem secara keseluruhan
**Fitur:**
- Kelola users (CRUD)
- Kelola semua kelas
- Monitoring sistem
- Lihat statistik
**Routes:**
- `GET /admin/users` - Daftar semua user
- `POST /admin/users` - Tambah user baru
- `PUT /admin/users/{id}` - Update user
- `DELETE /admin/users/{id}` - Hapus user
- `GET /admin/classes` - Daftar semua kelas
- `DELETE /admin/classes/{id}` - Hapus kelas
- `GET /admin/monitoring` - Dashboard monitoring

###### 2. **GuruController.php**
**Role:** Guru/Teacher
**Fungsi:** Mengelola pembelajaran
**Fitur:**
- Buat & kelola kelas
- Upload materi pembelajaran
- Buat tugas & quiz
- Generate soal dengan AI
- Nilai submission siswa
- Lihat progress siswa
- Regenerate token kelas
**Routes:**
- `POST /guru/classes` - Buat kelas baru
- `POST /guru/materials` - Upload materi
- `POST /guru/assignments` - Buat tugas
- `POST /guru/assignments/{id}/questions` - Tambah soal
- `POST /guru/assignments/{id}/questions/generate` - Generate soal AI
- `GET /guru/assignments/{id}/submissions` - Lihat submission
- `PUT /guru/submissions/{id}/grade` - Beri nilai
- `GET /guru/classes/{id}/progress` - Progress kelas

###### 3. **SiswaController.php**
**Role:** Siswa/Student
**Fungsi:** Mengikuti pembelajaran
**Fitur:**
- Join kelas dengan token
- Lihat materi & tugas
- Kerjakan quiz
- Submit tugas
- Lihat nilai & feedback
- Lihat progress belajar
**Routes:**
- `GET /siswa/kelas` - Daftar kelas yang diikuti
- `POST /siswa/join-kelas` - Join kelas baru
- `GET /siswa/classes/{id}` - Detail kelas
- `GET /siswa/assignments/{id}` - Detail tugas
- `POST /siswa/assignments/{id}/submit` - Submit jawaban
- `GET /siswa/submissions/{id}` - Lihat hasil
- `GET /siswa/progress` - Progress belajar

###### 4. **AIController.php**
**Fungsi:** Integrasi dengan AI (HuggingFace)
**Fitur:**
- Auto-grading essay dengan AI
- Analisis progress siswa
- Generate feedback otomatis
- Rekomendasi materi
**Routes:**
- `POST /guru/ai/grade` - Auto-grade dengan AI
- `GET /guru/ai/analyze/{userId}/{classId}` - Analisis progress
- `POST /siswa/ai/feedback` - Minta feedback AI
- `GET /siswa/ai/recommendations` - Rekomendasi materi

###### 5. **NotificationController.php**
**Fungsi:** Mengelola notifikasi
**Fitur:**
- Mark notification as read
- Send browser notification
**Routes:**
- `GET /notifications/{id}/read` - Tandai sudah dibaca
- `POST /notifications/browser` - Kirim notifikasi browser

###### 6. **ProfileController.php**
**Fungsi:** Manajemen profil user
**Fitur:**
- Lihat & edit profil
- Upload foto profil
- Ganti password
- Settings akun
**Routes:**
- `GET /profile` - Lihat profil
- `PUT /profile` - Update profil
- `GET /settings` - Halaman settings
- `PUT /profile/password` - Ganti password

###### 7. **PageController.php**
**Fungsi:** Render halaman statis
**Fitur:**
- Landing page
- Login page
- Register page
- Forgot password page

###### 8. **DashboardController.php**
**Fungsi:** Dashboard berdasarkan role
**Fitur:**
- Redirect ke dashboard sesuai role (admin/guru/siswa)
- Tampilkan statistik personal

---

#### **D. MIDDLEWARE/** - Filter Request

##### 1. **RoleMiddleware.php**
**Fungsi:** Memastikan user memiliki role yang sesuai
**Digunakan untuk:** Proteksi routes berdasarkan role (admin/guru/siswa)

##### 2. **CheckSessionExpiry.php**
**Fungsi:** Cek apakah session masih valid
**Digunakan untuk:** Auto-logout jika session expired

##### 3. **SecurityHeaders.php**
**Fungsi:** Menambahkan security headers ke response
**Digunakan untuk:** Proteksi XSS, clickjacking, MIME sniffing

---

#### **E. MODELS/** - Representasi Database

##### 1. **User.php**
**Tabel:** users
**Fungsi:** Data pengguna sistem
**Kolom penting:**
- id_user, nama, email, password
- role (admin/guru/siswa)
- email_verified_at
- fcm_token (untuk push notification)
- profile_picture

##### 2. **Classes.php**
**Tabel:** classes
**Fungsi:** Data kelas pembelajaran
**Kolom penting:**
- id_class, nama_class, deskripsi
- id_guru (pembuat kelas)
- status (active/inactive)

##### 3. **ClassEnrollment.php**
**Tabel:** class_enrollments
**Fungsi:** Relasi siswa dengan kelas
**Kolom penting:**
- id_enrollment, id_user, id_class
- status (active/inactive)
- enrolled_at

##### 4. **Assignment.php**
**Tabel:** assignments
**Fungsi:** Data tugas/quiz
**Kolom penting:**
- id_assignment, judul, deskripsi
- id_class, id_guru
- deadline, is_published
- tipe (essay/multiple_choice)

##### 5. **Question.php**
**Tabel:** questions
**Fungsi:** Soal dalam tugas
**Kolom penting:**
- id_question, id_assignment
- pertanyaan, kunci_jawaban
- tipe (essay/multiple_choice)

##### 6. **QuestionOption.php**
**Tabel:** question_options
**Fungsi:** Pilihan jawaban untuk soal multiple choice
**Kolom penting:**
- id_option, id_question
- option_text, is_correct

##### 7. **Submission.php**
**Tabel:** submissions
**Fungsi:** Jawaban siswa untuk tugas
**Kolom penting:**
- id_submission, id_assignment, id_user
- jawaban (JSON)
- nilai, feedback
- status (pending/graded)

##### 8. **Material.php**
**Tabel:** materials
**Fungsi:** Materi pembelajaran
**Kolom penting:**
- id_material, judul, deskripsi
- id_class, id_uploader
- file_path, online_link
- tipe (pdf/video/link)

##### 9. **Progress.php**
**Tabel:** progress
**Fungsi:** Tracking progress belajar siswa
**Kolom penting:**
- id_progress, id_user, id_class
- completed_assignments
- average_score

##### 10. **FeedbackAI.php**
**Tabel:** feedback_ai
**Fungsi:** Feedback dari AI untuk submission
**Kolom penting:**
- id_feedback, id_submission
- feedback_text, score_ai
- created_at

##### 11. **Notification.php**
**Tabel:** notifications
**Fungsi:** Notifikasi sistem
**Kolom penting:**
- id_notification, id_user
- type, title, message
- is_read, priority
- related_id (link ke assignment/class)

##### 12. **ActivityLog.php**
**Tabel:** activity_logs
**Fungsi:** Log aktivitas user
**Kolom penting:**
- id_log, id_user
- action, description
- ip_address, user_agent

##### 13. **TokenKelas.php**
**Tabel:** token_kelas
**Fungsi:** Token untuk join kelas
**Kolom penting:**
- id_token, id_class
- token (unique 6 digit)
- expired_at

---

#### **F. NOTIFICATIONS/** - Notifikasi Custom

##### **SystemNotification.php**
**Fungsi:** Template notifikasi sistem
**Channel:** Database + Mail + FCM (Push)
**Digunakan untuk:** Kirim notifikasi multi-channel

---

#### **G. POLICIES/** - Authorization Logic

##### 1. **AssignmentPolicy.php**
**Fungsi:** Aturan akses assignment
**Rules:**
- Guru hanya bisa edit assignment miliknya
- Siswa hanya bisa lihat assignment kelas yang diikuti

##### 2. **ClassPolicy.php**
**Fungsi:** Aturan akses kelas
**Rules:**
- Guru hanya bisa edit kelas miliknya
- Siswa hanya bisa akses kelas yang diikuti

##### 3. **SubmissionPolicy.php**
**Fungsi:** Aturan akses submission
**Rules:**
- Siswa hanya bisa lihat submission miliknya
- Guru bisa lihat semua submission di kelasnya

---

#### **H. RULES/** - Validation Rules

##### **StrongPassword.php**
**Fungsi:** Validasi password kuat
**Rules:**
- Minimal 8 karakter
- Harus ada huruf besar
- Harus ada huruf kecil
- Harus ada angka
- Harus ada simbol

---

#### **I. SERVICES/** - Business Logic Layer

##### 1. **ActivityLogService.php**
**Fungsi:** Mencatat semua aktivitas user
**Fitur:**
- Log login/logout
- Log create/update/delete
- Track IP & user agent
**Digunakan untuk:** Audit trail & security monitoring

##### 2. **AdminNotificationService.php**
**Fungsi:** Notifikasi khusus admin
**Fitur:**
- Alert storage penuh
- Laporan harian
- Warning sistem
**Digunakan untuk:** Monitoring sistem oleh admin

##### 3. **AIAnalysisService.php**
**Fungsi:** Analisis data dengan AI
**Fitur:**
- Analisis progress siswa
- Prediksi performa
- Identifikasi siswa butuh bantuan
**Digunakan untuk:** Insight pembelajaran

##### 4. **CacheService.php**
**Fungsi:** Manajemen cache Redis
**Fitur:**
- Cache rekomendasi materi
- Cache feedback AI
- Clear cache
**Digunakan untuk:** Optimasi performa

##### 5. **FCMService.php**
**Fungsi:** Kirim push notification via Firebase
**Fitur:**
- Send to single device
- Send to multiple devices
- Handle token invalid
**Digunakan untuk:** Real-time notification ke HP

##### 6. **FeedbackAIService.php**
**Fungsi:** Generate feedback otomatis dengan AI
**Fitur:**
- Analisis jawaban essay
- Beri skor otomatis
- Generate feedback konstruktif
**Digunakan untuk:** Auto-grading tugas essay

##### 7. **HuggingFaceService.php**
**Fungsi:** Integrasi dengan HuggingFace API
**Fitur:**
- Text generation
- Sentiment analysis
- Question generation
**Digunakan untuk:** AI features (generate soal, feedback)

##### 8. **MaterialRecommendationService.php**
**Fungsi:** Rekomendasi materi berdasarkan progress
**Fitur:**
- Analisis nilai siswa
- Cari materi relevan
- Prioritas berdasarkan kebutuhan
**Digunakan untuk:** Personalized learning

##### 9. **NotificationService.php**
**Fungsi:** Service utama notifikasi
**Fitur:**
- Send ke single/multiple user
- Multi-channel (email + push + database)
- Queue untuk performa
**Digunakan untuk:** Semua notifikasi sistem

##### 10. **ProgressService.php**
**Fungsi:** Tracking & kalkulasi progress
**Fitur:**
- Hitung completion rate
- Hitung rata-rata nilai
- Update progress otomatis
**Digunakan untuk:** Dashboard progress siswa

##### 11. **SecurityService.php**
**Fungsi:** Security utilities
**Fitur:**
- Detect suspicious activity
- Rate limiting
- IP blocking
**Digunakan untuk:** Proteksi sistem

##### 12. **ValidationService.php**
**Fungsi:** Validasi data custom
**Fitur:**
- Validasi file upload
- Validasi format data
- Sanitize input
**Digunakan untuk:** Data integrity

---

### 2️⃣ **CONFIG/** - Konfigurasi Aplikasi

#### **app.php**
**Fungsi:** Konfigurasi aplikasi utama (nama, timezone, locale)

#### **auth.php**
**Fungsi:** Konfigurasi autentikasi (guards, providers, password reset)

#### **cache.php**
**Fungsi:** Konfigurasi cache (Redis, file, database)

#### **database.php**
**Fungsi:** Konfigurasi koneksi database (MySQL, SQLite)

#### **filesystems.php**
**Fungsi:** Konfigurasi storage (local, public, S3)

#### **mail.php**
**Fungsi:** Konfigurasi email (SMTP, Mailgun, SES)

#### **queue.php**
**Fungsi:** Konfigurasi queue untuk background jobs

#### **services.php**
**Fungsi:** Konfigurasi third-party services (Google OAuth, HuggingFace, FCM)

#### **session.php**
**Fungsi:** Konfigurasi session (driver, lifetime, cookie)

---

### 3️⃣ **DATABASE/** - Database & Migrations

#### **MIGRATIONS/** - Skema Database

Semua file migration membuat tabel database:
- `users` - Data pengguna
- `classes` - Data kelas
- `class_enrollments` - Relasi siswa-kelas
- `assignments` - Data tugas
- `questions` - Soal tugas
- `question_options` - Pilihan jawaban
- `submissions` - Jawaban siswa
- `materials` - Materi pembelajaran
- `progress` - Progress belajar
- `feedback_ai` - Feedback AI
- `notifications` - Notifikasi
- `activity_logs` - Log aktivitas
- `token_kelas` - Token join kelas
- `sessions` - Session management
- `cache` - Cache storage
- `jobs` - Queue jobs
- `failed_jobs` - Failed queue jobs
- `password_resets` - Reset password tokens

#### **SEEDERS/** - Data Awal

##### **AdminSeeder.php**
**Fungsi:** Buat akun admin default
**Data:** Admin dengan email & password default

##### **DatabaseSeeder.php**
**Fungsi:** Jalankan semua seeder

---

### 4️⃣ **PUBLIC/** - File Publik

#### **images/** - Gambar statis
- Logo LMS
- Banner
- CTA images

#### **js/** - JavaScript
- `app.js` - Main JS
- `admin.js` - Admin dashboard JS
- `firebase-init.js` - Firebase initialization
- `notifications.js` - Notification handler

#### **SVG/** - Icon & ilustrasi
- Education illustrations
- Landing page graphics

#### **firebase-messaging-sw.js**
**Fungsi:** Service worker untuk push notification

#### **index.php**
**Fungsi:** Entry point aplikasi

---

### 5️⃣ **RESOURCES/** - Views & Assets

#### **css/** - Stylesheets
- `app.css` - Global styles
- `admin.css` - Admin dashboard
- `guru.css` - Guru dashboard
- `siswa.css` - Siswa dashboard
- `landing-page.css` - Homepage
- `login.css`, `register.css` - Auth pages
- `notifications.css` - Notification styles

#### **views/** - Blade Templates
- `auth/` - Login, register, forgot password
- `dashboard/` - Dashboard layouts
- `guru/` - Guru views
- `siswa/` - Siswa views
- `profile/` - Profile pages
- `emails/` - Email templates
- `landing-page.blade.php` - Homepage

---

### 6️⃣ **ROUTES/** - Routing

#### **web.php**
**Fungsi:** Definisi semua routes aplikasi
**Sections:**
- Public pages (landing, login, register)
- Auth actions (login, register, logout, OAuth)
- Email verification
- Password reset
- Dashboard (role-based)
- Profile & settings
- Admin routes (middleware: role:admin)
- Guru routes (middleware: role:guru)
- Siswa routes (middleware: role:siswa)

#### **console.php**
**Fungsi:** Definisi artisan commands

---

### 7️⃣ **STORAGE/** - File Storage

#### **app/public/** - File upload publik
- Materi pembelajaran
- Submission files
- Profile pictures

#### **app/private/** - File private
- Sensitive documents

#### **app/firebase/** - Firebase credentials

#### **logs/** - Application logs
- `laravel.log` - Error & info logs

---

## 🔐 FITUR KEAMANAN

### 1. **Rate Limiting**
- Login: 5 percobaan per 15 menit
- Register: 3 percobaan per 60 menit
- Password reset: 3 percobaan per 60 menit

### 2. **Security Headers**
- X-Frame-Options: SAMEORIGIN
- X-Content-Type-Options: nosniff
- X-XSS-Protection: 1; mode=block

### 3. **Authentication**
- Email verification required
- Strong password policy
- Session management
- Google OAuth integration

### 4. **Authorization**
- Role-based access control (RBAC)
- Policy-based permissions
- Middleware protection

### 5. **Activity Logging**
- Track semua aktivitas user
- IP address & user agent
- Audit trail

---

## 🤖 FITUR AI

### 1. **Auto-Grading**
- Nilai essay otomatis dengan AI
- Analisis jawaban siswa
- Generate feedback konstruktif

### 2. **Question Generation**
- Generate soal otomatis dari materi
- Multiple choice & essay
- Berbagai tingkat kesulitan

### 3. **Progress Analysis**
- Analisis performa siswa
- Identifikasi kelemahan
- Prediksi hasil belajar

### 4. **Material Recommendation**
- Rekomendasi materi berdasarkan progress
- Personalized learning path
- Prioritas materi yang perlu dipelajari

---

## 📱 FITUR NOTIFIKASI

### 1. **Multi-Channel**
- Email notification
- Push notification (FCM)
- In-app notification (database)

### 2. **Notification Types**
- Tugas baru
- Materi baru
- Deadline reminder
- Nilai tersedia
- Feedback AI ready
- Admin alerts

### 3. **Real-time**
- Instant push ke HP
- Browser notification
- Auto-refresh notification badge

---

## 📊 FITUR MONITORING

### 1. **Admin Dashboard**
- Total users (admin/guru/siswa)
- Total kelas aktif
- Total tugas & submission
- Storage usage
- System health

### 2. **Activity Logs**
- User login/logout
- CRUD operations
- Suspicious activities
- Error tracking

### 3. **Daily Reports**
- User baru hari ini
- Kelas baru
- Tugas baru
- Submission baru
- Storage warning

---

## 🚀 CARA MENJALANKAN

### 1. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

### 2. **Database Migration**
```bash
php artisan migrate
php artisan db:seed
```

### 3. **Install Dependencies**
```bash
composer install
npm install
npm run build
```

### 4. **Run Application**
```bash
php artisan serve
```

### 5. **Run Queue Worker** (untuk notifikasi)
```bash
php artisan queue:work
```

### 6. **Run Scheduler** (untuk cron jobs)
```bash
php artisan schedule:work
```

---

## 👥 DEFAULT ACCOUNTS

### Admin
- Email: admin@lms.com
- Password: (lihat di AdminSeeder)

---

## 🛠️ MAINTENANCE COMMANDS

### Clear Cache
```bash
php artisan cache:clear-lms --type=all
```

### Cleanup Data
```bash
php artisan cleanup:enrollments
```

### Check Storage
```bash
php artisan admin:check-storage
```

### Verify Database
```bash
php artisan db:verify
```

### Send Daily Stats
```bash
php artisan admin:send-daily-stats
```

---

## 📈 TEKNOLOGI YANG DIGUNAKAN

### Backend
- **Laravel 11** - PHP Framework
- **MySQL** - Database
- **Redis** - Cache & Queue

### Frontend
- **Blade Templates** - Templating engine
- **Tailwind CSS** - CSS Framework
- **JavaScript** - Interactivity

### Third-Party Services
- **HuggingFace API** - AI/ML features
- **Firebase Cloud Messaging** - Push notifications
- **Google OAuth** - Social login
- **SMTP** - Email delivery

### Tools
- **Composer** - PHP dependency manager
- **NPM** - JavaScript package manager
- **Artisan** - Laravel CLI

---

## 📝 CATATAN PENTING UNTUK PRESENTASI

### Keunggulan Sistem:
1. ✅ **Role-based Access** - Pemisahan akses admin/guru/siswa
2. ✅ **AI Integration** - Auto-grading & rekomendasi cerdas
3. ✅ **Real-time Notification** - Email + Push notification
4. ✅ **Security First** - Rate limiting, encryption, activity logs
5. ✅ **Scalable Architecture** - Service layer, queue, cache
6. ✅ **User Friendly** - Responsive design, intuitive UI
7. ✅ **Comprehensive Monitoring** - Admin dashboard, logs, reports
8. ✅ **Automated Maintenance** - Cleanup commands, daily reports

### Fitur Unggulan:
- 🤖 AI auto-grading untuk essay
- 📱 Push notification ke HP
- 🔐 Google OAuth login
- 📊 Progress tracking real-time
- 🎯 Personalized learning recommendations
- 📧 Multi-channel notifications
- 🛡️ Advanced security features
- 📈 Comprehensive analytics

---

**Dibuat untuk:** Presentasi Proyek LMS
**Tanggal:** 2024
**Framework:** Laravel 11
