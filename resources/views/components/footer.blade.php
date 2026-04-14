@vite(['resources/css/footer.css'])

<footer>
    <div class="footer-container">
        <!-- Footer CTA Section -->
        @auth
        <div class="footer-cta">
            <h3>Bergabunglah dengan Komunitas Kami</h3>
            <p>Dapatkan akses ke ribuan materi pembelajaran dan tingkatkan skill Anda bersama kami.</p>
            <a href="{{ route('dashboard') }}" class="footer-cta-button">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Mulai Belajar
            </a>
        </div>
        @endauth

        <!-- Footer Grid -->
        <div class="footer-grid">
            <!-- Brand Section -->
            <div>
                <div class="footer-brand">
                    <img src="{{ asset('images/LMS.png') }}" alt="LMS Logo">
                    <div class="footer-brand-text">
                        <h4>Learning<br>Management System</h4>
                        <p>Berbasis AI</p>
                    </div>
                </div>
                <p class="footer-description">
                    Platform pembelajaran modern yang mengintegrasikan teknologi AI untuk memberikan pengalaman belajar yang personal dan efektif.
                </p>
                <div class="footer-socials">
                    <a href="#" class="footer-social-link" title="Facebook">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="footer-social-link" title="Twitter">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="footer-social-link" title="Instagram">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0m5.521 17.05c-1.224 2.45-3.963 4.05-7.021 4.05-3.059 0-5.796-1.6-7.02-4.05-.499-1.001-.777-2.066-.777-3.15 0-4.418 3.582-8 8-8s8 3.582 8 8c0 1.084-.278 2.149-.777 3.15m1.452-9.106c-.704 0-1.275-.571-1.275-1.275s.571-1.275 1.275-1.275 1.275.571 1.275 1.275-.571 1.275-1.275 1.275"/>
                        </svg>
                    </a>
                    <a href="#" class="footer-social-link" title="LinkedIn">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Product Section -->
            <div class="footer-section">
                <h3>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Produk
                </h3>
                <ul>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="#">Fitur Pembelajaran</a></li>
                    <li><a href="#">AI Assistant</a></li>
                    <li><a href="#">Analytics</a></li>
                    <li><a href="#">Integrasi</a></li>
                </ul>
            </div>

            <!-- Company Section -->
            <div class="footer-section">
                <h3>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Perusahaan
                </h3>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Karir</a></li>
                    <li><a href="#">Press</a></li>
                    <li><a href="#">Hubungi Kami</a></li>
                </ul>
            </div>

            <!-- Resources Section -->
            <div class="footer-section">
                <h3>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Sumber Daya
                </h3>
                <ul>
                    <li><a href="#">Dokumentasi</a></li>
                    <li><a href="#">Tutorial</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Komunitas</a></li>
                    <li><a href="#">Status</a></li>
                </ul>
            </div>

            <!-- Legal Section -->
            <div class="footer-section">
                <h3>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Legal
                </h3>
                <ul>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                    <li><a href="#">Lisensi</a></li>
                    <li><a href="#">Keamanan</a></li>
                    <li><a href="#">Cookie</a></li>
                </ul>
            </div>
        </div>

        <!-- Footer Divider -->
        <div class="footer-divider"></div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-bottom-links">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat Layanan</a>
                <a href="#">Pengaturan Cookie</a>
                <a href="#">Hubungi Kami</a>
            </div>
            <p class="footer-copyright">
                &copy; {{ date('Y') }} Learning Management System. Semua hak dilindungi.
            </p>
        </div>
    </div>
</footer>
