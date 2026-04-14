@vite(['resources/css/notifications.css'])

<div id="loading-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[10000] hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-sm mx-4 text-center shadow-2xl" id="loading-content">
        <div class="w-20 h-20 mx-auto mb-4 relative">
            <div class="absolute inset-0 border-4 border-purple-100 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-transparent border-t-purple-600 rounded-full animate-spin"></div>
            <div class="absolute inset-2 border-4 border-transparent border-t-pink-400 rounded-full animate-spin" style="animation-duration: 0.8s; animation-direction: reverse;"></div>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Memproses...</h3>
        <p class="text-sm text-gray-600">Mohon tunggu sebentar</p>
        <div class="mt-4 flex justify-center gap-1">
            <div class="w-2 h-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full animate-bounce" style="animation-delay: 0s"></div>
            <div class="w-2 h-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
            <div class="w-2 h-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
        </div>
    </div>
</div>

<div id="notification-container" class="fixed top-20 right-4 z-[9999] space-y-3 max-w-sm w-full px-4 sm:px-0">
    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
            x-transition:enter="transform ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0 scale-95"
            x-transition:enter-end="translate-x-0 opacity-100 scale-100"
            x-transition:leave="transform ease-in duration-300"
            x-transition:leave-start="translate-x-0 opacity-100 scale-100"
            x-transition:leave-end="translate-x-full opacity-0 scale-95"
            class="notification-item notification-error">
            <div class="notification-icon icon-bg">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="notification-title">Terjadi Kesalahan</h4>
                <div class="notification-message">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
            <button @click="show = false" class="notification-close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('success'))
        <div x-data="{ show: true, progress: 0 }" x-show="show"
            x-init="
                setTimeout(() => show = false, 3000);
                let start = Date.now();
                let interval = setInterval(() => {
                    progress = Math.min(((Date.now() - start) / 3000) * 100, 100);
                    if (progress >= 100) clearInterval(interval);
                }, 50);
                @if (session('redirect_delay') === true)
                    setTimeout(() => window.location.href = '{{ route('dashboard') }}', 3000);
                @endif
            "
            x-transition:enter="transform ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0 scale-95"
            x-transition:enter-end="translate-x-0 opacity-100 scale-100"
            x-transition:leave="transform ease-in duration-300"
            x-transition:leave-start="translate-x-0 opacity-100 scale-100"
            x-transition:leave-end="translate-x-full opacity-0 scale-95"
            class="notification-item notification-success">
            <div class="progress-bar" :style="`width: ${progress}%`"></div>
            <div class="notification-icon icon-bg">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="notification-title">Berhasil!</h4>
                <p class="notification-message">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="notification-close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('warning'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transform ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0 scale-95"
            x-transition:enter-end="translate-x-0 opacity-100 scale-100"
            x-transition:leave="transform ease-in duration-300"
            x-transition:leave-start="translate-x-0 opacity-100 scale-100"
            x-transition:leave-end="translate-x-full opacity-0 scale-95"
            class="notification-item notification-warning">
            <div class="notification-icon icon-bg">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="notification-title">Peringatan</h4>
                <p class="notification-message">{{ session('warning') }}</p>
            </div>
            <button @click="show = false" class="notification-close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if (session('info'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transform ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0 scale-95"
            x-transition:enter-end="translate-x-0 opacity-100 scale-100"
            x-transition:leave="transform ease-in duration-300"
            x-transition:leave-start="translate-x-0 opacity-100 scale-100"
            x-transition:leave-end="translate-x-full opacity-0 scale-95"
            class="notification-item notification-info">
            <div class="notification-icon icon-bg">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="notification-title">Informasi</h4>
                <p class="notification-message">{{ session('info') }}</p>
            </div>
            <button @click="show = false" class="notification-close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
</div>

<script src="{{ asset('js/notifications.js') }}"></script>
