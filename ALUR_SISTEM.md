# 🎯 ALUR KERJA SISTEM LMS

## 1️⃣ ALUR REGISTRASI & LOGIN

```
┌─────────────────────────────────────────────────────────────┐
│                    USER MENGAKSES SISTEM                     │
└─────────────────────────────────────────────────────────────┘
                            ↓
                ┌───────────────────────┐
                │   Pilih Login Method   │
                └───────────────────────┘
                    ↓              ↓
        ┌───────────────┐    ┌──────────────┐
        │ Email/Password│    │ Google OAuth │
        └───────────────┘    └──────────────┘
                    ↓              ↓
        ┌───────────────────────────────────┐
        │    Validasi Credentials           │
        │  - Rate Limiting (5x/15 menit)    │
        │  - Check Email Verified           │
        │  - Check Password Hash            │
        └───────────────────────────────────┘
                    ↓
        ┌───────────────────────────────────┐
        │    Create Session & Log Activity  │
        └───────────────────────────────────┘
                    ↓
        ┌───────────────────────────────────┐
        │   Redirect ke Dashboard by Role   │
        │   - Admin → Admin Dashboard       │
        │   - Guru → Guru Dashboard         │
        │   - Siswa → Siswa Dashboard       │
        └───────────────────────────────────┘
```

---

## 2️⃣ ALUR GURU MEMBUAT KELAS & TUGAS

```
┌─────────────────────────────────────────────────────────────┐
│                    GURU LOGIN KE SISTEM                      │
└─────────────────────────────────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │      BUAT KELAS BARU              │
        │  - Input: Nama, Deskripsi         │
        │  - Generate Token Kelas (6 digit) │
        │  - Status: Active                 │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │      UPLOAD MATERI                │
        │  - PDF / Video / Link Online      │
        │  - Simpan ke Storage              │
        │  - Notif ke Siswa: "Materi Baru"  │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │      BUAT TUGAS/QUIZ              │
        │  - Input: Judul, Deskripsi        │
        │  - Set Deadline                   │
        │  - Pilih Tipe: Essay/Multiple     │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │      TAMBAH SOAL                  │
        │  Option 1: Manual Input           │
        │  Option 2: Generate dengan AI     │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │      PUBLISH TUGAS                │
        │  - Set is_published = true        │
        │  - Notif ke Siswa: "Tugas Baru"   │
        │  - Email + Push Notification      │
        └───────────────────────────────────┘
```

---

## 3️⃣ ALUR SISWA JOIN KELAS & KERJAKAN TUGAS

```
┌─────────────────────────────────────────────────────────────┐
│                    SISWA LOGIN KE SISTEM                     │
└─────────────────────────────────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │      JOIN KELAS                   │
        │  - Input Token Kelas (6 digit)    │
        │  - Validasi Token Valid & Active  │
        │  - Create Enrollment              │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │      LIHAT MATERI & TUGAS         │
        │  - Download/View Materi           │
        │  - Lihat Daftar Tugas             │
        │  - Check Deadline                 │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │      KERJAKAN TUGAS               │
        │  - Baca Soal                      │
        │  - Input Jawaban                  │
        │  - Upload File (jika perlu)       │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │      SUBMIT JAWABAN               │
        │  - Validasi Semua Soal Terjawab   │
        │  - Simpan ke Database             │
        │  - Status: Pending                │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │      PROSES PENILAIAN             │
        │  Multiple Choice: Auto-grade      │
        │  Essay: AI Auto-grade / Manual    │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │      TERIMA HASIL & FEEDBACK      │
        │  - Notifikasi: "Nilai Tersedia"   │
        │  - Lihat Nilai & Feedback AI      │
        │  - Update Progress                │
        └───────────────────────────────────┘
```

---

## 4️⃣ ALUR AI AUTO-GRADING

```
┌─────────────────────────────────────────────────────────────┐
│              SISWA SUBMIT TUGAS ESSAY                        │
└─────────────────────────────────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Submission Masuk Queue          │
        │   Status: Pending                 │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   AI Analysis Service             │
        │   - Kirim ke HuggingFace API      │
        │   - Analisis Jawaban vs Kunci     │
        │   - Hitung Similarity Score       │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Generate Feedback               │
        │   - Poin yang benar               │
        │   - Poin yang kurang              │
        │   - Saran perbaikan               │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Simpan Hasil                    │
        │   - Update Submission.nilai       │
        │   - Create FeedbackAI record      │
        │   - Status: Graded                │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Kirim Notifikasi                │
        │   - Email: "Nilai Tersedia"       │
        │   - Push: "Lihat Hasil Tugas"     │
        │   - In-app Notification           │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Update Progress                 │
        │   - Hitung Average Score          │
        │   - Update Completion Rate        │
        │   - Generate Recommendations      │
        └───────────────────────────────────┘
```

---

## 5️⃣ ALUR NOTIFIKASI MULTI-CHANNEL

```
┌─────────────────────────────────────────────────────────────┐
│                    TRIGGER EVENT                             │
│  (Tugas Baru, Materi Baru, Nilai Tersedia, dll)            │
└─────────────────────────────────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   NotificationService.send()      │
        │   - Tentukan User Target          │
        │   - Tentukan Type & Priority      │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Dispatch ke Queue               │
        │   - Job: SendNotification         │
        │   - Async Processing              │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   CHANNEL 1: Database             │
        │   - Simpan ke tabel notifications │
        │   - Tampil di in-app notification │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   CHANNEL 2: Email                │
        │   - Render Email Template         │
        │   - Send via SMTP                 │
        │   - Include Action Button         │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   CHANNEL 3: Push (FCM)           │
        │   - Get User FCM Token            │
        │   - Send via Firebase             │
        │   - Tampil di HP User             │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Log Activity                    │
        │   - Record ke activity_logs       │
        │   - Track Delivery Status         │
        └───────────────────────────────────┘
```

---

## 6️⃣ ALUR ADMIN MONITORING

```
┌─────────────────────────────────────────────────────────────┐
│                    ADMIN LOGIN                               │
└─────────────────────────────────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Dashboard Overview              │
        │   - Total Users by Role           │
        │   - Total Kelas Aktif             │
        │   - Total Tugas & Submission      │
        │   - Storage Usage                 │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   User Management                 │
        │   - Lihat Semua User              │
        │   - Create/Edit/Delete User       │
        │   - Reset Password                │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Class Management                │
        │   - Lihat Semua Kelas             │
        │   - Delete Kelas Bermasalah       │
        │   - Monitor Enrollment            │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Activity Monitoring             │
        │   - View Activity Logs            │
        │   - Track Suspicious Activity     │
        │   - Export Reports                │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   System Health                   │
        │   - Check Storage Warning         │
        │   - View Error Logs               │
        │   - Run Maintenance Commands      │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Daily Reports                   │
        │   - Receive Email Report          │
        │   - User Baru, Kelas Baru         │
        │   - Tugas & Submission Stats      │
        └───────────────────────────────────┘
```

---

## 7️⃣ ALUR REKOMENDASI MATERI (AI)

```
┌─────────────────────────────────────────────────────────────┐
│              SISWA REQUEST REKOMENDASI                       │
└─────────────────────────────────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Check Cache                     │
        │   - Cek Redis Cache               │
        │   - TTL: 1 jam                    │
        └───────────────────────────────────┘
                ↓                    ↓
        [Cache Hit]          [Cache Miss]
                ↓                    ↓
        Return Cached    ┌───────────────────────────────────┐
        Data             │   Analyze Progress                │
                         │   - Get All Submissions           │
                         │   - Calculate Average Score       │
                         │   - Identify Weak Topics          │
                         └───────────────────────────────────┘
                                        ↓
                         ┌───────────────────────────────────┐
                         │   AI Analysis                     │
                         │   - Send Data to HuggingFace      │
                         │   - Get Topic Recommendations     │
                         │   - Prioritize by Weakness        │
                         └───────────────────────────────────┘
                                        ↓
                         ┌───────────────────────────────────┐
                         │   Find Relevant Materials         │
                         │   - Search by Topic               │
                         │   - Filter by Class               │
                         │   - Sort by Relevance             │
                         └───────────────────────────────────┘
                                        ↓
                         ┌───────────────────────────────────┐
                         │   Cache Result                    │
                         │   - Save to Redis                 │
                         │   - Set TTL: 1 hour               │
                         └───────────────────────────────────┘
                                        ↓
                         ┌───────────────────────────────────┐
                         │   Return Recommendations          │
                         │   - List Materi + Alasan          │
                         │   - Priority Level                │
                         └───────────────────────────────────┘
```

---

## 8️⃣ ALUR SECURITY & RATE LIMITING

```
┌─────────────────────────────────────────────────────────────┐
│                    USER REQUEST                              │
└─────────────────────────────────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Security Headers Middleware     │
        │   - Add X-Frame-Options           │
        │   - Add X-Content-Type-Options    │
        │   - Add X-XSS-Protection          │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   Rate Limiting Check             │
        │   - Check IP + Endpoint           │
        │   - Count Requests                │
        └───────────────────────────────────┘
                ↓                    ↓
        [Within Limit]      [Exceeded Limit]
                ↓                    ↓
        Continue         Return 429 Too Many Requests
                ↓
        ┌───────────────────────────────────┐
        │   Session Check                   │
        │   - Validate Session Token        │
        │   - Check Expiry                  │
        └───────────────────────────────────┘
                ↓                    ↓
        [Valid]              [Invalid/Expired]
                ↓                    ↓
        Continue         Redirect to Login
                ↓
        ┌───────────────────────────────────┐
        │   Role Authorization              │
        │   - Check User Role               │
        │   - Validate Permissions          │
        └───────────────────────────────────┘
                ↓                    ↓
        [Authorized]         [Unauthorized]
                ↓                    ↓
        Process Request  Return 403 Forbidden
                ↓
        ┌───────────────────────────────────┐
        │   Log Activity                    │
        │   - Record Action                 │
        │   - Save IP & User Agent          │
        │   - Timestamp                     │
        └───────────────────────────────────┘
```

---

## 9️⃣ ALUR MAINTENANCE OTOMATIS

```
┌─────────────────────────────────────────────────────────────┐
│                    CRON SCHEDULER                            │
│                  (php artisan schedule:work)                 │
└─────────────────────────────────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   DAILY 08:00 AM                  │
        │   → admin:send-daily-stats        │
        │   - Hitung User Baru              │
        │   - Hitung Kelas Baru             │
        │   - Hitung Tugas & Submission     │
        │   - Email ke Admin                │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   DAILY 02:00 AM                  │
        │   → admin:check-storage           │
        │   - Cek Disk Usage                │
        │   - Alert jika > 80%              │
        │   - Notif ke Admin                │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   WEEKLY SUNDAY 03:00 AM          │
        │   → cleanup:enrollments           │
        │   - Hapus Enrollment Duplikat     │
        │   - Hapus Orphan Records          │
        │   - Optimize Database             │
        └───────────────────────────────────┘
                            ↓
        ┌───────────────────────────────────┐
        │   HOURLY                          │
        │   → queue:work                    │
        │   - Process Notification Queue    │
        │   - Process AI Grading Queue      │
        │   - Process Email Queue           │
        └───────────────────────────────────┘
```

---

## 🔟 ALUR DATA FLOW (Arsitektur)

```
┌─────────────────────────────────────────────────────────────┐
│                         CLIENT                               │
│                    (Browser / Mobile)                        │
└─────────────────────────────────────────────────────────────┘
                            ↓
                    [HTTP Request]
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      MIDDLEWARE                              │
│  Security → Rate Limit → Session → Role Authorization       │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      CONTROLLER                              │
│  - Validate Input                                            │
│  - Call Service Layer                                        │
│  - Return Response                                           │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      SERVICE LAYER                           │
│  - Business Logic                                            │
│  - Call External APIs (HuggingFace, FCM)                    │
│  - Cache Management                                          │
│  - Queue Jobs                                                │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      MODEL LAYER                             │
│  - Database Queries (Eloquent ORM)                          │
│  - Relationships                                             │
│  - Data Validation                                           │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      DATABASE                                │
│                      (MySQL)                                 │
└─────────────────────────────────────────────────────────────┘

                    [Parallel Processes]
                            ↓
        ┌───────────────────────────────────────────┐
        │                                           │
┌───────────────┐  ┌───────────────┐  ┌───────────────┐
│  REDIS CACHE  │  │  QUEUE JOBS   │  │  FILE STORAGE │
│  - Sessions   │  │  - Notif      │  │  - Materials  │
│  - Cache Data │  │  - AI Grade   │  │  - Uploads    │
└───────────────┘  └───────────────┘  └───────────────┘
```

---

## 📊 STATISTIK SISTEM

### Database Tables: 18 tables
- Users, Classes, Enrollments
- Assignments, Questions, Submissions
- Materials, Progress, Feedback
- Notifications, Activity Logs
- Cache, Queue, Sessions

### Controllers: 8 controllers
- AdminController
- GuruController
- SiswaController
- AIController
- AuthServices
- ProfileController
- NotificationController
- PageController

### Services: 12 services
- NotificationService
- AIAnalysisService
- FeedbackAIService
- MaterialRecommendationService
- ProgressService
- ActivityLogService
- SecurityService
- CacheService
- FCMService
- HuggingFaceService
- ValidationService
- AdminNotificationService

### Models: 13 models
- User, Classes, ClassEnrollment
- Assignment, Question, QuestionOption
- Submission, Material, Progress
- FeedbackAI, Notification
- ActivityLog, TokenKelas

### Routes: 60+ routes
- Public: 5 routes
- Auth: 10 routes
- Admin: 7 routes
- Guru: 15 routes
- Siswa: 12 routes
- Profile: 6 routes
- API: 2 routes

---

**Dokumentasi ini menjelaskan alur kerja lengkap sistem LMS untuk presentasi**
