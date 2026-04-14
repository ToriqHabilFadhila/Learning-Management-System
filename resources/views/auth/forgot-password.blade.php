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
    @vite(['resources/css/app.css', 'resources/css/forgot.css', 'resources/js/app.js'])
    </style>
</head>
<body>
    @include('components.notifications')

    <div class="auth-card">
        <div class="left-panel">
            <img src="/images/LMS.png" alt="LMS Logo" class="left-logo">
            <h1 class="left-title">Selamat<br>Datang!</h1>
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

                {{-- MODE: REQUEST — Kirim link reset password --}}
                @if ($mode === 'request')
                    <div class="mode-icon-wrap">
                        <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="iconGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#7c3aed"/>
                                    <stop offset="100%" stop-color="#db2777"/>
                                </linearGradient>
                            </defs>
                            <path stroke="url(#iconGrad)" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                    </div>

                    <div class="form-heading">
                        <h2 class="form-title">Lupa Password?</h2>
                        <p class="form-desc">Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.</p>
                    </div>

                    <div class="info-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Link reset akan dikirim ke email Anda dan berlaku selama <strong>60 menit</strong>.</p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="field">
                            <label for="email">Alamat Email</label>
                            <div class="input-wrap">
                                <span class="input-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                </span>
                                <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'is-error' : '' }}" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus autocomplete="email">
                            </div>
                            @error('email')
                                <div class="error-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-brand">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                            </svg>
                            Kirim Link Reset
                        </button>
                    </form>
                @endif

                {{-- MODE: RESET — Set password baru --}}
                @if ($mode === 'reset')
                    <div class="mode-icon-wrap">
                        <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="iconGrad2" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#7c3aed"/>
                                    <stop offset="100%" stop-color="#db2777"/>
                                </linearGradient>
                            </defs>
                            <path stroke="url(#iconGrad2)" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                        </svg>
                    </div>

                    <div class="step-indicator" style="margin-bottom:1.5rem;">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div class="step done">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:13px;height:13px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                            </div>
                            <span class="step-label">Email</span>
                        </div>
                        <div class="step-line"></div>
                        <div style="display:flex;flex-direction:column;align-items:center;gap:4px;">
                            <div class="step active">2</div>
                            <span class="step-label">Password Baru</span>
                        </div>
                    </div>

                    <div class="form-heading">
                        <h2 class="form-title">Reset Password</h2>
                        <p class="form-desc">Buat password baru yang kuat untuk akun Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ request('token') }}">

                        <div class="field">
                            <label for="email">Alamat Email</label>
                            <div class="input-wrap">
                                <span class="input-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                </span>
                                <input type="email" id="email" name="email" class="form-input" value="{{ request('email') }}" readonly>
                            </div>
                        </div>

                        <div class="field">
                            <label for="password">Password Baru</label>
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
                            <div style="display:flex;gap:4px;margin-top:6px;">
                                <div class="strength-segment" id="seg1" style="flex:1;height:3px;border-radius:99px;background:var(--border);transition:background 0.3s;"></div>
                                <div class="strength-segment" id="seg2" style="flex:1;height:3px;border-radius:99px;background:var(--border);transition:background 0.3s;"></div>
                                <div class="strength-segment" id="seg3" style="flex:1;height:3px;border-radius:99px;background:var(--border);transition:background 0.3s;"></div>
                                <div class="strength-segment" id="seg4" style="flex:1;height:3px;border-radius:99px;background:var(--border);transition:background 0.3s;"></div>
                            </div>
                            <div id="strengthLabel" style="font-size:0.72rem;margin-top:3px;color:var(--text-secondary);"></div>
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
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input {{ $errors->has('password_confirmation') ? 'is-error' : '' }}" placeholder="Ulangi password baru" required autocomplete="new-password" style="padding-right:2.5rem;">
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
                            @error('password_confirmation')
                                <div class="error-msg">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-brand">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                            Simpan Password Baru
                        </button>
                    </form>
                @endif

                <div class="back-row">
                    Ingat password Anda?
                    <a href="/login" class="back-link">
                        Kembali ke login
                    </a>
                </div>
            </div>
        </div>
    </div>

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
            const ids  = ['seg1','seg2','seg3','seg4'];
            const segs = ids.map(id => document.getElementById(id));
            const label = document.getElementById('strengthLabel');

            if (!segs[0] || !label) return;

            segs.forEach(s => { s.style.background = 'var(--border)'; });

            if (!val) { label.textContent = ''; return; }

            let score = 0;
            if (val.length >= 8)            score++;
            if (/[A-Z]/.test(val))          score++;
            if (/[0-9]/.test(val))          score++;
            if (/[^A-Za-z0-9]/.test(val))  score++;

            const colorMap = { 1: '#ef4444', 2: '#ef4444', 3: '#f59e0b', 4: '#10b981' };
            const labelMap = { 1: 'Lemah', 2: 'Lemah', 3: 'Sedang', 4: 'Kuat' };

            const color = colorMap[score] || '#e5e7eb';
            for (let i = 0; i < score; i++) segs[i].style.background = color;

            label.textContent = labelMap[score] || '';
            label.style.color = color;
        }
    </script>
</body>
</html>
