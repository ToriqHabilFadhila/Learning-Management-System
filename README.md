<p align="center"><img src="https://raw.githubusercontent.com/ToriqHabilFadhila/Learning-Management-System/master/public/images/LMS.png" width="400" alt="LMS Logo"></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Learning Management System (LMS)

> **Platform pembelajaran online yang komprehensif dengan teknologi AI untuk pengalaman belajar yang optimal.**

## 🎯 Latar Belakang

Sistem Learning Management System (LMS) ini dikembangkan untuk memfasilitasi proses pembelajaran online yang efektif dan efisien. Dengan meningkatnya kebutuhan akan pendidikan digital, LMS ini hadir sebagai solusi komprehensif untuk mengelola kursus, siswa, dan materi pembelajaran dalam satu platform terpadu.

## 📖 Deskripsi Proyek

LMS ini adalah platform pembelajaran online yang memungkinkan institusi pendidikan, perusahaan, atau individu untuk membuat, mengelola, dan menyampaikan konten pembelajaran secara digital. Sistem ini dirancang dengan antarmuka yang user-friendly dan fitur-fitur canggih untuk mendukung pengalaman belajar yang optimal.

## 👥 Role Pengguna

<table>
<tr>
<td width="33%">

### 🔧 Administrator
- Mengelola seluruh sistem
- Mengatur pengguna dan hak akses
- Monitoring aktivitas platform
- Konfigurasi sistem

</td>
<td width="33%">

### 👨‍🏫 Instruktur/Guru
- Membuat dan mengelola kursus
- Upload materi pembelajaran
- Membuat quiz dan tugas
- Menilai dan memberikan feedback
- Monitoring progress siswa

</td>
<td width="33%">

### 🎓 Siswa/Peserta
- Mengakses kursus yang tersedia
- Mengerjakan tugas dan quiz
- Melihat progress pembelajaran
- Berinteraksi dengan instruktur dan sesama siswa

</td>
</tr>
</table>

## ✨ Fitur Utama

<div align="center">

| 📚 **Manajemen Kursus** | 👥 **Manajemen Pengguna** | 📝 **Sistem Penilaian** |
|:---:|:---:|:---:|
| Pembuatan kursus dengan struktur modul | Sistem registrasi dan autentikasi | Quiz interaktif dengan berbagai tipe soal |
| Upload berbagai format materi | Profil pengguna yang dapat disesuaikan | Tugas dengan upload file |
| Pengaturan jadwal dan deadline | Manajemen role dan permission | Sistem grading otomatis dan manual |
| Kategorisasi kursus | Sistem notifikasi | Laporan progress dan nilai |

| 💬 **Komunikasi & Kolaborasi** | 📊 **Analytics & Reporting** |
|:---:|:---:|
| Forum diskusi per kursus | Dashboard analytics untuk instruktur |
| Chat real-time | Laporan progress siswa |
| Sistem komentar pada materi | Statistik engagement |
| Pengumuman dan notifikasi | Export data dalam berbagai format |

</div>

## 🛠️ Teknologi yang Digunakan

<table>
<tr>
<td width="33%">

### 🔧 Backend
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)
![Redis](https://img.shields.io/badge/Redis-DC382D?style=for-the-badge&logo=redis&logoColor=white)

- **Laravel 10** - PHP Framework
- **PostgreSQL** - Database Management System
- **Redis** - Caching dan Session Storage
- **Laravel Sanctum** - API Authentication

</td>
<td width="33%">

### 🎨 Frontend
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![jQuery](https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)

- **Blade Templates** - Server-side Rendering
- **Bootstrap 5** - CSS Framework
- **jQuery** - JavaScript Library
- **Chart.js** - Data Visualization

</td>
<td width="33%">

### ⚙️ Tools & Services
![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)
![NPM](https://img.shields.io/badge/NPM-CB3837?style=for-the-badge&logo=npm&logoColor=white)

- **Composer** - PHP Dependency Manager
- **NPM** - Node Package Manager
- **Laravel Mix** - Asset Compilation
- **Pusher** - Real-time Communication

</td>
</tr>
</table>

## 🤖 Fitur AI (Artificial Intelligence)

> **Powered by Advanced Machine Learning & Natural Language Processing**

<div align="center">

### 🧠 AI-Powered Features

</div>

<table>
<tr>
<td width="50%">

#### 🎯 **Rekomendasi Pembelajaran**
- Algoritma machine learning untuk merekomendasikan kursus
- Personalisasi jalur pembelajaran berdasarkan progress
- Analisis pola belajar siswa

#### 📝 **Auto-Grading Cerdas**
- Penilaian otomatis untuk essay dan jawaban terbuka
- Natural Language Processing untuk analisis teks
- Deteksi plagiarisme menggunakan AI

#### 💬 **Chatbot Pembelajaran**
- AI Assistant untuk membantu siswa
- Jawaban otomatis untuk pertanyaan umum
- Panduan navigasi platform

</td>
<td width="50%">

#### 📊 **Analisis Prediktif**
- Prediksi risiko dropout siswa
- Identifikasi siswa yang membutuhkan bantuan tambahan
- Optimasi konten berdasarkan engagement

#### ✨ **Content Generation**
- AI untuk membuat soal quiz otomatis
- Ringkasan materi pembelajaran
- Subtitle otomatis untuk video

<br>

> 🚀 **Coming Soon**: Voice Recognition, Adaptive Learning Paths, Smart Content Curation

</td>
</tr>
</table>

## 🚀 Instalasi

### Persyaratan Sistem
- PHP >= 8.1
- Composer
- Node.js & NPM
- PostgreSQL >= 12

### Quick Start

```bash
# 1. Clone repository
git clone [repository-url]
cd LMS

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Database setup
php artisan migrate
php artisan db:seed

# 5. Compile assets
npm run dev

# 6. Start server
php artisan serve
```

### 🔧 Konfigurasi Tambahan

```bash
# Setup Redis (Optional)
sudo apt-get install redis-server

# Setup Pusher untuk real-time features
# Tambahkan kredensial Pusher di .env

# Setup AI Features (Optional)
# Konfigurasi API keys untuk OpenAI/Google AI
```

## 🤝 Kontribusi

Kami menyambut kontribusi dari developer untuk meningkatkan sistem LMS ini!

### Cara Berkontribusi
1. Fork repository ini
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

### 📋 Development Guidelines
- Ikuti PSR-12 coding standards
- Tulis unit tests untuk fitur baru
- Update dokumentasi jika diperlukan
- Pastikan semua tests passing

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## 📞 Kontak & Support

<div align="center">

[![GitHub Issues](https://img.shields.io/github/issues/username/lms)](https://github.com/username/lms/issues)
[![GitHub Discussions](https://img.shields.io/github/discussions/username/lms)](https://github.com/username/lms/discussions)

**Tim Development**

📧 Email: toriqqhabilfadhila21@gmail.com

</div>

---

<div align="center">

**Made with ❤️ by LMS Development Team**

⭐ Jika project ini membantu, jangan lupa berikan star!

</div>
