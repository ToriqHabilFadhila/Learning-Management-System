@vite(['resources/css/navbar.css'])

@php
    $accountPage = request()->routeIs('profile', 'settings');
    $isDashboard = request()->routeIs('dashboard');
    $authUser = auth()->user();
    
    // Tentukan URL kembali berdasarkan halaman saat ini
    $backUrl = route('dashboard');
    $backLabel = 'Kembali';
    
    if (request()->routeIs('guru.classes.show')) {
        $backUrl = route('dashboard');
        $backLabel = 'Dashboard';
    } elseif (request()->routeIs('guru.assignments.questions')) {
        $assignmentId = request()->route('id');
        $assignment = \App\Models\Assignment::find($assignmentId);
        if ($assignment && $assignment->id_kelas) {
            $backUrl = route('guru.classes.show', $assignment->id_kelas);
            $backLabel = 'Kelas';
        }
    } elseif (request()->routeIs('guru.assignments.submissions')) {
        $assignmentId = request()->route('id');
        $assignment = \App\Models\Assignment::find($assignmentId);
        if ($assignment && $assignment->id_kelas) {
            $backUrl = route('guru.classes.show', $assignment->id_kelas);
            $backLabel = 'Kelas';
        }
    } elseif (request()->routeIs('siswa.classes.show')) {
        $backUrl = route('dashboard');
        $backLabel = 'Dashboard';
    } elseif (request()->routeIs('siswa.assignments.show')) {
        $assignmentId = request()->route('id');
        $assignment = \App\Models\Assignment::find($assignmentId);
        if ($assignment && $assignment->id_kelas) {
            $backUrl = route('siswa.classes.show', $assignment->id_kelas);
            $backLabel = 'Kelas';
        }
    } elseif (request()->routeIs('siswa.submissions.show')) {
        $submissionId = request()->route('id');
        $submission = \App\Models\Submission::with('assignment')->find($submissionId);
        if ($submission && $submission->assignment && $submission->assignment->id_kelas) {
            $backUrl = route('siswa.classes.show', $submission->assignment->id_kelas);
            $backLabel = 'Kelas';
        }
    } elseif (request()->routeIs('siswa.recommendations')) {
        $backUrl = route('dashboard');
        $backLabel = 'Dashboard';
    }
@endphp

<nav class="w-full bg-white border-b border-gray-100 sticky top-0 z-40 transition-all duration-300" data-role="{{ auth()->check() ? auth()->user()->role : '' }}">
    <div class="w-full px-4 sm:px-6 md:px-16 py-2.5">
        <div class="flex justify-between items-center gap-4">
            <a href="{{ auth()->check() ? route('dashboard') : '/' }}" class="flex items-center gap-2.5 flex-shrink-0" style="text-decoration:none;">
                <img src="{{ asset('images/LMS.png') }}" alt="LMS Logo" class="w-10 h-10 rounded-xl object-cover">
                <span class="hidden sm:block font-bold text-gray-900 text-base tracking-tight leading-tight">
                    Learning<br>
                    <span style="background:var(--brand-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-size:0.78rem;font-weight:700;letter-spacing:0.01em;">Management System</span>
                </span>
            </a>

            <div class="flex items-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="nav-btn-ghost">Login</a>
                    <a href="{{ route('register') }}" class="nav-btn-brand">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:15px;height:15px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                        </svg>
                        <span class="hidden sm:inline">Daftar Gratis</span>
                        <span class="sm:hidden">Daftar</span>
                    </a>

                @else
                    @php
                        $unreadCount = \App\Models\Notification::where('id_user', $authUser->id_user)
                            ->where('is_read', false)->count();
                        $avatarUrl = $authUser->avatar_url;
                        $role = $authUser->role;
                    @endphp

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="notif-btn" aria-label="Notifikasi">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($unreadCount > 0)
                                <span class="notif-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="notif-panel" style="display:none;">
                            <div class="notif-header">
                                <h3>Notifikasi</h3>
                                @if($unreadCount > 0)
                                    <span style="font-size:0.72rem;font-weight:600;padding:2px 8px;border-radius:99px;background:linear-gradient(135deg,rgba(124,58,237,0.1),rgba(219,39,119,0.1));color:#7c3aed;">
                                        {{ $unreadCount }} baru
                                    </span>
                                @endif
                            </div>

                            @php
                                $notifications = \App\Models\Notification::where('id_user', $authUser->id_user)
                                    ->orderBy('created_at', 'desc')->limit(10)->get();
                            @endphp

                            @forelse($notifications as $notif)
                                <a href="{{ route('notifications.read', $notif->id_notification) }}"
                                    class="notif-item {{ !$notif->is_read ? 'unread' : '' }}">
                                    <div class="notif-dot {{ $notif->type === 'deadline' ? 'bg-red-100' : ($notif->type === 'grade' ? 'bg-green-100' : 'bg-purple-100') }}">
                                        @if($notif->type === 'deadline')
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @elseif($notif->type === 'grade')
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" style="color:#7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <p style="font-size:0.82rem;font-weight:600;color:#111827;line-height:1.3;">{{ $notif->title }}</p>
                                        <p style="font-size:0.75rem;color:#6b7280;margin-top:2px;line-height:1.4;">{{ $notif->message }}</p>
                                        <p style="font-size:0.7rem;color:#9ca3af;margin-top:3px;">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(!$notif->is_read)
                                        <div style="width:7px;height:7px;border-radius:50%;background:var(--brand-from);flex-shrink:0;margin-top:4px;"></div>
                                    @endif
                                </a>
                            @empty
                                <div style="padding:2.5rem 1rem;text-align:center;color:#9ca3af;">
                                    <svg class="w-10 h-10 mx-auto mb-2" style="color:#d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <p style="font-size:0.82rem;">Tidak ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if($isDashboard)
                        <div class="relative" style="position:relative;">
                            <button onclick="toggleDropdown()" class="flex items-center gap-2 hover:bg-gray-50 rounded-xl px-2 py-1.5 transition border border-transparent hover:border-gray-200">
                                <div class="avatar-ring">
                                    <div class="avatar-inner">
                                        @if($avatarUrl)
                                            <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                            <span style="display:none;">{{ strtoupper(substr($authUser->nama, 0, 1)) }}</span>
                                        @else
                                            {{ strtoupper(substr($authUser->nama, 0, 1)) }}
                                        @endif
                                    </div>
                                </div>
                                <span class="hidden sm:inline text-gray-700 font-semibold text-sm">{{ $authUser->nama }}</span>
                                <svg class="hidden sm:block w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="userDropdown" class="user-dropdown hidden">
                                <div style="padding:0.875rem 1rem 0.75rem;border-bottom:1px solid #f3f4f6;">
                                    <p style="font-size:0.875rem;font-weight:700;color:#111827;">{{ $authUser->nama }}</p>
                                    <p style="font-size:0.75rem;color:#9ca3af;margin-top:1px;">{{ $authUser->email }}</p>
                                </div>

                                <a href="{{ route('profile') }}" class="dropdown-item">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profil Saya
                                </a>
                                <a href="{{ route('settings') }}" class="dropdown-item">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Pengaturan
                                </a>
                                <div class="dropdown-divider"></div>
                                <button onclick="openLogoutModal()" class="dropdown-item danger">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </div>
                        </div>

                    @else
                        <div class="relative" style="position:relative;">
                            <button onclick="toggleDropdown()" class="flex items-center gap-2 hover:bg-gray-50 rounded-xl px-2 py-1.5 transition border border-transparent hover:border-gray-200">
                                <div class="avatar-ring">
                                    <div class="avatar-inner">
                                        @if($avatarUrl)
                                            <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                            <span style="display:none;">{{ strtoupper(substr($authUser->nama, 0, 1)) }}</span>
                                        @else
                                            {{ strtoupper(substr($authUser->nama, 0, 1)) }}
                                        @endif
                                    </div>
                                </div>
                                <span class="hidden sm:inline text-gray-700 font-semibold text-sm">{{ $authUser->nama }}</span>
                                <svg class="hidden sm:block w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="userDropdown" class="user-dropdown hidden">
                                <div style="padding:0.875rem 1rem 0.75rem;border-bottom:1px solid #f3f4f6;">
                                    <p style="font-size:0.875rem;font-weight:700;color:#111827;">{{ $authUser->nama }}</p>
                                    <p style="font-size:0.75rem;color:#9ca3af;margin-top:1px;">{{ $authUser->email }}</p>
                                </div>

                                <a href="{{ route('profile') }}" class="dropdown-item">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profil Saya
                                </a>
                                <a href="{{ route('settings') }}" class="dropdown-item">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Pengaturan
                                </a>
                                <div class="dropdown-divider"></div>
                                <button onclick="openLogoutModal()" class="dropdown-item danger">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($isDashboard)
                        @if($role === 'siswa')
                            <button onclick="openJoinClassModal()" class="nav-btn-brand">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                                </svg>
                                <span class="hidden sm:inline">Join Kelas</span>
                                <span class="sm:hidden">Join</span>
                            </button>
                        @elseif($role === 'guru')
                            <button onclick="openCreateClassModal()" class="nav-btn-brand">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                                </svg>
                                <span class="hidden sm:inline">Buat Kelas</span>
                                <span class="sm:hidden">Buat</span>
                            </button>
                        @elseif($role === 'admin')
                            <button onclick="openManageClassesModal()" class="nav-btn-brand">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                                </svg>
                                <span class="hidden sm:inline">Kelola Kelas</span>
                                <span class="sm:hidden">Kelola</span>
                            </button>
                        @endif
                    @else
                        <a href="{{ $backUrl }}" class="nav-btn-brand">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                            </svg>
                            <span class="hidden sm:inline">{{ $backLabel }}</span>
                        </a>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</nav>

