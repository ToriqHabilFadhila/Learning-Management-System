<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/LMS.png') }}" type="image/png">
    <title>Learning Management System Berbasis AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/register.css', 'resources/js/app.js'])
</head>
<body>
    @include('components.notifications')

    <div class="register-card">
        <div class="left-panel">
            <img src="/images/LMS.png" alt="LMS Logo" class="left-logo">
            <h1 class="left-title">Bergabung<br>Sekarang!</h1>
            <p class="left-sub">Platform pembelajaran berbasis AI yang membantu Anda belajar lebih efektif dan efisien.</p>
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <div class="feature-title">Materi Terstruktur</div>
                        <div class="feature-desc">Akses ratusan materi pembelajaran</div>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="feature-title">AI-Powered</div>
                        <div class="feature-desc">Evaluasi otomatis dengan AI</div>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="feature-title">Sertifikat Digital</div>
                        <div class="feature-desc">Dapatkan sertifikat setelah selesai</div>
                    </div>
                </div>
            </div>

            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-num">1K+</div>
                    <div class="stat-lbl">Pengguna</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">120+</div>
                    <div class="stat-lbl">Materi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">95%</div>
                    <div class="stat-lbl">Kepuasan</div>
                </div>
            </div>
        </div>

        <div class="right-panel">
            <div class="form-inner">
                <div class="mobile-logo-wrap">
                    <img src="/images/LMS.png" alt="LMS Logo" class="mobile-logo">
                    <span class="mobile-app-name">Learning Management System</span>
                </div>

                <div class="form-heading">
                    <h2 class="form-title">Registrasi Sekarang</h2>
                    <p class="form-desc">Buat akun baru untuk mulai menggunakan platform pembelajaran.</p>
                </div>
                <!-- Form -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="field">
                        <label>Nama Lengkap</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM6 20c0-3.31 2.69-6 6-6s6 2.69 6 6"/>
                                </svg>
                            </span>
                            <input type="text" name="nama" class="form-input {{ $errors->has('nama') ? 'is-error' : '' }}" placeholder="Nama lengkap Anda" value="{{ old('nama') }}" required autocomplete="name">
                        </div>
                        @error('nama')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="email">Alamat Email</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </span>
                            <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'is-error' : '' }}" placeholder="nama@email.com" value="{{ old('email') }}" required autocomplete="username">
                        </div>
                        @error('email')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input type="password" id="password" name="password" class="form-input {{ $errors->has('password') ? 'is-error' : '' }}" placeholder="Min. 8 karakter" required autocomplete="new-password" oninput="checkStrength(this.value)" style="padding-right:2.5rem;">
                            <button type="button" class="toggle-password" onclick="togglePassword('password','eyeOpen-password','eyeClosed-password')" aria-label="Toggle password">
                                <svg id="eyeOpen-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                <svg id="eyeClosed-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>
                        <div class="strength-bar" id="strengthBar">
                            <div class="strength-segment" id="seg1"></div>
                            <div class="strength-segment" id="seg2"></div>
                            <div class="strength-segment" id="seg3"></div>
                            <div class="strength-segment" id="seg4"></div>
                        </div>
                        <div class="strength-label" id="strengthLabel"></div>
                        @error('password')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </span>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Ulangi password Anda" required autocomplete="new-password" style="padding-right:2.5rem;">
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation','eyeOpen-confirmation','eyeClosed-confirmation')" aria-label="Toggle konfirmasi password">
                                <svg id="eyeOpen-confirmation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                <svg id="eyeClosed-confirmation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-brand">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                        </svg>
                        Buat Akun Sekarang
                    </button>
                </form>

                <div class="divider"><span>atau daftar dengan</span></div>

                <a href="{{ route('google.auth') }}" class="btn-google" onclick="handleGoogleRegister(this)">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Daftar dengan Google
                </a>

                <div class="login-row">
                    Sudah punya akun?
                    <a href="/login" class="login-link">Login sekarang</a>
                </div>

            </div>
        </div>

    </div><!-- /.register-card -->

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        /* Toggle show/hide password */
        function togglePassword(inputId, eyeOpenId, eyeClosedId) {
            const input     = document.getElementById(inputId);
            const eyeOpen   = document.getElementById(eyeOpenId);
            const eyeClosed = document.getElementById(eyeClosedId);
            const isHidden  = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isHidden);
            eyeClosed.classList.toggle('hidden', !isHidden);
        }

        /* Password strength checker */
        function checkStrength(val) {
            const segs  = [
                document.getElementById('seg1'),
                document.getElementById('seg2'),
                document.getElementById('seg3'),
                document.getElementById('seg4'),
            ];
            const label = document.getElementById('strengthLabel');

            // Reset
            segs.forEach(s => { s.className = 'strength-segment'; });

            if (!val) { label.textContent = ''; return; }

            let score = 0;
            if (val.length >= 8)             score++;
            if (/[A-Z]/.test(val))           score++;
            if (/[0-9]/.test(val))           score++;
            if (/[^A-Za-z0-9]/.test(val))   score++;

            const classMap  = ['weak', 'weak', 'medium', 'strong', 'strong'];
            const labelMap  = ['', 'Lemah', 'Lemah', 'Sedang', 'Kuat'];
            const cls       = classMap[score] || 'weak';

            for (let i = 0; i < score; i++) {
                segs[i].classList.add(cls);
            }

            label.textContent  = labelMap[score] || '';
            label.style.color  = cls === 'strong' ? '#10b981' : cls === 'medium' ? '#f59e0b' : '#ef4444';
        }

        /* Google loading state */
        function handleGoogleRegister(el) {
            el.style.pointerEvents = 'none';
            el.style.opacity = '0.65';
            el.innerHTML = `
                <svg style="width:18px;height:18px;animation:spin 0.8s linear infinite;" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="#9ca3af" stroke-width="4" fill="none" opacity="0.25"/>
                    <path d="M22 12a10 10 0 0 1-10 10" stroke="#7c3aed" stroke-width="4" fill="none"/>
                </svg>
                <span style="font-size:0.875rem;font-weight:600;color:#374151;">Memproses...</span>
            `;
        }
    </script>

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</body>
</html>
