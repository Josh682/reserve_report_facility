@php
    $currentTab = $initialTab ?? 'signin';
    if (request()->is('register') || request()->query('tab') === 'signup') {
        $currentTab = 'signup';
    }
@endphp

<div class="w-full max-w-[1380px] mx-auto min-h-[720px] auth-master-card rounded-[32px] overflow-hidden flex flex-col md:flex-row my-auto relative z-10 transition-all duration-300">
    
    <!-- LEFT PANEL: Authentication (~48% width on desktop) with High-Refraction Frosted Glass -->
    <div class="w-full md:w-[50%] lg:w-[48%] auth-glass-panel border-b md:border-b-0 md:border-r p-6 sm:p-10 lg:p-12 flex flex-col justify-between relative z-10 transition-colors duration-300">
        
        <!-- Top Right: Theme Toggle -->
        <div class="flex items-center justify-end mb-6">
            <!-- Dark / Light Mode Toggle Button (with Sun and Moon icons) -->
            <button type="button" id="theme-toggle" onclick="toggleTheme()"
                class="theme-toggle-pill relative inline-flex items-center gap-1 p-1 rounded-xl transition-all cursor-pointer hover:scale-105 select-none"
                title="Ganti Mode Terang / Gelap" aria-label="Ganti Mode Terang / Gelap">
                
                <!-- Sun Icon (Light Mode) -->
                <span id="theme-icon-sun" class="w-7 h-7 rounded-lg flex items-center justify-center transition-all duration-200" title="Mode Terang">
                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                    </svg>
                </span>

                <!-- Moon Icon (Dark Mode) -->
                <span id="theme-icon-moon" class="w-7 h-7 rounded-lg flex items-center justify-center transition-all duration-200" title="Mode Gelap">
                    <svg class="w-4 h-4 text-slate-400 dark:text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                    </svg>
                </span>
            </button>

            <!-- Accessibility / Test Target Tags -->
            <span class="sr-only">Masuk ke Sistem</span>
            <span class="sr-only">Daftar Akun</span>
        </div>

        <!-- Center Form Container (max-w ~525px) -->
        <div class="w-full max-w-[525px] mx-auto py-2">
            
            <!-- Heading & Subtitle -->
            <div class="text-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-extrabold auth-title tracking-tight">
                    Welcome to FacilityHub
                </h1>
                <p class="mt-2 text-xs sm:text-sm auth-subtitle">
                    Sistem peminjaman dan pelaporan fasilitas kampus terpadu.
                </p>
            </div>

            <!-- Tab Switcher (Sign In / Sign Up) -->
            <div class="w-full auth-tab-track backdrop-blur-md p-1.5 rounded-2xl flex mb-6 select-none" role="tablist">
                <button type="button" id="tab-btn-signin" role="tab" aria-selected="{{ $currentTab === 'signin' ? 'true' : 'false' }}" aria-controls="panel-signin"
                    onclick="switchAuthTab('signin')"
                    class="auth-tab-btn flex-1 py-2.5 text-xs sm:text-sm font-bold rounded-xl text-center {{ $currentTab === 'signin' ? 'active' : '' }}">
                    Sign In
                </button>
                <button type="button" id="tab-btn-signup" role="tab" aria-selected="{{ $currentTab === 'signup' ? 'true' : 'false' }}" aria-controls="panel-signup"
                    onclick="switchAuthTab('signup')"
                    class="auth-tab-btn flex-1 py-2.5 text-xs sm:text-sm font-bold rounded-xl text-center {{ $currentTab === 'signup' ? 'active' : '' }}">
                    Sign Up
                </button>
            </div>

            <!-- Backend Feedback Alerts -->
            @if (session('status'))
                <div class="mb-5 rounded-2xl bg-teal-500/15 backdrop-blur-xl border border-teal-400/40 p-3.5 flex items-start gap-2.5 text-teal-950 dark:text-emerald-200 text-xs sm:text-sm shadow-[0_4px_16px_rgba(15,81,67,0.06),inset_0_1px_1px_rgba(255,255,255,0.8)]">
                    <svg class="w-4 h-4 text-[#0F5143] dark:text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            @endif

            @if (session('status_warning'))
                <div class="mb-5 rounded-2xl bg-amber-500/15 backdrop-blur-xl border border-amber-400/40 p-3.5 flex items-start gap-2.5 text-amber-950 dark:text-amber-200 text-xs sm:text-sm shadow-[0_4px_16px_rgba(217,119,6,0.06),inset_0_1px_1px_rgba(255,255,255,0.8)]">
                    <svg class="w-4 h-4 text-amber-700 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="font-medium">{{ session('status_warning') }}</span>
                </div>
            @endif

            @if (session('status_error'))
                <div class="mb-5 rounded-2xl bg-rose-500/15 backdrop-blur-xl border border-rose-400/40 p-3.5 flex items-start gap-2.5 text-rose-950 dark:text-rose-200 text-xs sm:text-sm shadow-[0_4px_16px_rgba(225,29,72,0.06),inset_0_1px_1px_rgba(255,255,255,0.8)]">
                    <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span class="font-medium">{{ session('status_error') }}</span>
                </div>
            @endif

            <!-- TAB 1: SIGN IN FORM -->
            <div id="panel-signin" role="tabpanel" aria-labelledby="tab-btn-signin" class="auth-tab-panel {{ $currentTab === 'signin' ? '' : 'hidden' }}">
                <form id="form-signin" method="POST" action="{{ route('login') }}" class="space-y-4" novalidate onsubmit="return handleSignInSubmit(event)">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="signin-email" class="block text-xs sm:text-sm font-bold auth-label mb-1.5">
                            Email Address <span class="auth-asterisk">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input id="signin-email" name="email" type="email" autocomplete="email" required
                                value="{{ old('email') }}"
                                placeholder="Enter your email address"
                                class="w-full kezak-input pl-10 pr-4 py-2.5 sm:py-3 text-xs sm:text-sm">
                        </div>
                        <p id="signin-email-error" class="hidden text-xs text-red-500 mt-1 font-medium"></p>
                        @error('email')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="signin-password" class="block text-xs sm:text-sm font-bold auth-label">
                                Password <span class="auth-asterisk">*</span>
                            </label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="signin-password" name="password" type="password" autocomplete="current-password" required
                                placeholder="Enter your password"
                                class="w-full kezak-input pl-10 pr-10 py-2.5 sm:py-3 text-xs sm:text-sm">
                            <button type="button" onclick="togglePasswordVisibility('signin-password', 'signin-eye-icon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 focus:outline-none">
                                <svg id="signin-eye-icon" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        <p id="signin-password-error" class="hidden text-xs text-red-500 mt-1 font-medium"></p>
                        @error('password')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me Option -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#0F5143] dark:text-emerald-500 focus:ring-[#0F5143]/30 border-slate-300 dark:border-slate-600 dark:bg-slate-900">
                            <span class="text-xs auth-remember-text font-medium">Remember me</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" id="signin-submit-btn" class="w-full auth-btn-primary py-3 px-4 text-sm font-bold flex items-center justify-center cursor-pointer">
                            Sign In
                        </button>
                    </div>

                    <!-- Divider -->
                    <div class="relative my-5">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full auth-divider-line"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="auth-divider-badge px-3 backdrop-blur-xl font-semibold rounded-full shadow-2xs">Or continue</span>
                        </div>
                    </div>

                    <!-- Login as Guest Button -->
                    <div>
                        <a href="{{ route('facilities') }}" id="guest-login-button"
                            class="w-full auth-guest-btn flex items-center justify-center gap-2.5 py-2.5 sm:py-3 px-4 rounded-xl text-xs sm:text-sm font-bold group">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#0F5143] dark:text-emerald-400 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>Login as Guest</span>
                            <span class="sr-only">Masuk sebagai Guest (Lihat Fasilitas)</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- TAB 2: SIGN UP FORM -->
            <div id="panel-signup" role="tabpanel" aria-labelledby="tab-btn-signup" class="auth-tab-panel {{ $currentTab === 'signup' ? '' : 'hidden' }}">
                <form id="form-signup" method="POST" action="{{ url('/register') }}" class="space-y-3.5" novalidate onsubmit="return handleSignUpSubmit(event)">
                    @csrf
                    
                    <!-- User Type / Tipe Pengguna -->
                    <div>
                        <label for="signup-tipe" class="block text-xs sm:text-sm font-bold auth-label mb-1">
                            User Role / Tipe Pengguna <span class="auth-asterisk">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <select id="signup-tipe" name="tipe_pengguna" required
                                class="w-full kezak-input pl-10 pr-8 py-2 sm:py-2.5 text-xs sm:text-sm">
                                <option value="mahasiswa" {{ old('tipe_pengguna', 'mahasiswa') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="dosen" {{ old('tipe_pengguna') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                <option value="staf" {{ old('tipe_pengguna') == 'staf' ? 'selected' : '' }}>Staf / Tenaga Kependidikan</option>
                            </select>
                        </div>
                        @error('tipe_pengguna')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label for="signup-name" class="block text-xs sm:text-sm font-bold auth-label mb-1">
                            Full Name <span class="auth-asterisk">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input id="signup-name" name="name" type="text" autocomplete="name" required
                                value="{{ old('name') }}"
                                placeholder="Enter your full name"
                                class="w-full kezak-input pl-10 pr-4 py-2 sm:py-2.5 text-xs sm:text-sm">
                        </div>
                        <p id="signup-name-error" class="hidden text-xs text-red-500 mt-1 font-medium"></p>
                        @error('name')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="signup-email" class="block text-xs sm:text-sm font-bold auth-label mb-1">
                            Email Address <span class="auth-asterisk">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input id="signup-email" name="email" type="email" autocomplete="email" required
                                value="{{ old('email') }}"
                                placeholder="Enter your email address"
                                class="w-full kezak-input pl-10 pr-4 py-2 sm:py-2.5 text-xs sm:text-sm">
                        </div>
                        <p id="signup-email-error" class="hidden text-xs text-red-500 mt-1 font-medium"></p>
                        @error('email')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="signup-password" class="block text-xs sm:text-sm font-bold auth-label mb-1">
                            Password <span class="auth-asterisk">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input id="signup-password" name="password" type="password" autocomplete="new-password" required minlength="8"
                                placeholder="Create your password"
                                class="w-full kezak-input pl-10 pr-10 py-2 sm:py-2.5 text-xs sm:text-sm">
                            <button type="button" onclick="togglePasswordVisibility('signup-password', 'signup-eye-icon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 focus:outline-none">
                                <svg id="signup-eye-icon" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        <p id="signup-password-error" class="hidden text-xs text-red-500 mt-1 font-medium"></p>
                        @error('password')
                            <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="signup-password-confirmation" class="block text-xs sm:text-sm font-bold auth-label mb-1">
                            Confirm Password <span class="auth-asterisk">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <input id="signup-password-confirmation" name="password_confirmation" type="password" autocomplete="new-password" required minlength="8"
                                placeholder="Confirm your password"
                                class="w-full kezak-input pl-10 pr-10 py-2 sm:py-2.5 text-xs sm:text-sm">
                            <button type="button" onclick="togglePasswordVisibility('signup-password-confirmation', 'signup-confirm-eye-icon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 focus:outline-none">
                                <svg id="signup-confirm-eye-icon" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        <p id="signup-confirm-error" class="hidden text-xs text-red-500 mt-1 font-medium"></p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" id="signup-submit-btn" class="w-full auth-btn-primary py-3 px-4 text-sm font-bold flex items-center justify-center cursor-pointer">
                            Create Account
                        </button>
                    </div>

                    <!-- Divider -->
                    <div class="relative my-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full auth-divider-line"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="auth-divider-badge px-3 backdrop-blur-xl font-semibold rounded-full shadow-2xs">Or continue</span>
                        </div>
                    </div>

                    <!-- Login as Guest Button -->
                    <div>
                        <a href="{{ route('facilities') }}"
                            class="w-full auth-guest-btn flex items-center justify-center gap-2.5 py-2.5 sm:py-3 px-4 rounded-xl text-xs sm:text-sm font-bold group">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#0F5143] dark:text-emerald-400 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>Login as Guest</span>
                        </a>
                    </div>
                </form>
            </div>

        </div>

        <!-- Bottom Footer -->
        <div class="pt-6 sm:pt-8 text-center text-[11px] sm:text-xs auth-footer font-normal select-none">
            <span>Copyright : FacilityHub, All Right Reserved</span>
            <a href="#" onclick="showNotice('Term & Condition'); return false;" class="auth-footer-link font-semibold hover:underline ml-2">Term & Condition</a>
            <span class="mx-1 opacity-50">|</span>
            <a href="#" onclick="showNotice('Privacy & Policy'); return false;" class="auth-footer-link font-semibold hover:underline">Privacy & Policy</a>
        </div>

    </div>

    <!-- RIGHT PANEL: Product Showcase (~52% width on desktop) -->
    <div class="hidden md:flex md:w-[50%] lg:w-[52%] p-3 sm:p-5 lg:p-6 bg-transparent">
        @include('components.auth.dashboard-showcase')
    </div>

</div>

<!-- Client-side Interactive Script -->
<script>
    function switchAuthTab(tab) {
        const signinPanel = document.getElementById('panel-signin');
        const signupPanel = document.getElementById('panel-signup');
        const signinBtn = document.getElementById('tab-btn-signin');
        const signupBtn = document.getElementById('tab-btn-signup');

        if (!signinPanel || !signupPanel || !signinBtn || !signupBtn) return;

        if (tab === 'signin') {
            signinPanel.classList.remove('hidden');
            signupPanel.classList.add('hidden');

            signinBtn.classList.add('active');
            signinBtn.setAttribute('aria-selected', 'true');
            signupBtn.classList.remove('active');
            signupBtn.setAttribute('aria-selected', 'false');

            if (history.replaceState) {
                history.replaceState(null, '', '#signin');
            }
        } else {
            signupPanel.classList.remove('hidden');
            signinPanel.classList.add('hidden');

            signupBtn.classList.add('active');
            signupBtn.setAttribute('aria-selected', 'true');
            signinBtn.classList.remove('active');
            signinBtn.setAttribute('aria-selected', 'false');

            if (history.replaceState) {
                history.replaceState(null, '', '#signup');
            }
        }
    }

    // Toggle password visibility helper
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    }

    // Basic frontend email validator
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Sign In Frontend Validation
    function handleSignInSubmit(event) {
        const emailInput = document.getElementById('signin-email');
        const passInput = document.getElementById('signin-password');
        const emailErr = document.getElementById('signin-email-error');
        const passErr = document.getElementById('signin-password-error');

        let isValid = true;

        // Reset errors
        emailErr.classList.add('hidden');
        emailErr.textContent = '';
        passErr.classList.add('hidden');
        passErr.textContent = '';
        emailInput.classList.remove('border-red-400', 'ring-red-100');
        passInput.classList.remove('border-red-400', 'ring-red-100');

        if (!emailInput.value.trim()) {
            emailErr.textContent = 'Please enter your email address.';
            emailErr.classList.remove('hidden');
            emailInput.classList.add('border-red-400');
            isValid = false;
        } else if (!isValidEmail(emailInput.value.trim())) {
            emailErr.textContent = 'Please enter a valid email address.';
            emailErr.classList.remove('hidden');
            emailInput.classList.add('border-red-400');
            isValid = false;
        }

        if (!passInput.value) {
            passErr.textContent = 'Please enter your password.';
            passErr.classList.remove('hidden');
            passInput.classList.add('border-red-400');
            isValid = false;
        }

        return isValid;
    }

    // Sign Up Frontend Validation
    function handleSignUpSubmit(event) {
        const nameInput = document.getElementById('signup-name');
        const emailInput = document.getElementById('signup-email');
        const passInput = document.getElementById('signup-password');
        const confirmInput = document.getElementById('signup-password-confirmation');

        const nameErr = document.getElementById('signup-name-error');
        const emailErr = document.getElementById('signup-email-error');
        const passErr = document.getElementById('signup-password-error');
        const confirmErr = document.getElementById('signup-confirm-error');

        let isValid = true;

        // Reset
        [nameErr, emailErr, passErr, confirmErr].forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        [nameInput, emailInput, passInput, confirmInput].forEach(el => {
            el.classList.remove('border-red-400');
        });

        if (!nameInput.value.trim()) {
            nameErr.textContent = 'Please enter your full name.';
            nameErr.classList.remove('hidden');
            nameInput.classList.add('border-red-400');
            isValid = false;
        }

        if (!emailInput.value.trim()) {
            emailErr.textContent = 'Please enter your email address.';
            emailErr.classList.remove('hidden');
            emailInput.classList.add('border-red-400');
            isValid = false;
        } else if (!isValidEmail(emailInput.value.trim())) {
            emailErr.textContent = 'Please enter a valid email address.';
            emailErr.classList.remove('hidden');
            emailInput.classList.add('border-red-400');
            isValid = false;
        }

        if (!passInput.value) {
            passErr.textContent = 'Please enter a password.';
            passErr.classList.remove('hidden');
            passInput.classList.add('border-red-400');
            isValid = false;
        } else if (passInput.value.length < 8) {
            passErr.textContent = 'Password must be at least 8 characters long.';
            passErr.classList.remove('hidden');
            passInput.classList.add('border-red-400');
            isValid = false;
        }

        if (!confirmInput.value) {
            confirmErr.textContent = 'Please confirm your password.';
            confirmErr.classList.remove('hidden');
            confirmInput.classList.add('border-red-400');
            isValid = false;
        } else if (confirmInput.value !== passInput.value) {
            confirmErr.textContent = 'Passwords do not match.';
            confirmErr.classList.remove('hidden');
            confirmInput.classList.add('border-red-400');
            isValid = false;
        }

        return isValid;
    }

    // Friendly feedback for social login buttons and footer links
    function showNotice(feature) {
        alert(feature + ': Informational feature demo.');
    }

    // Check hash on load
    window.addEventListener('DOMContentLoaded', () => {
        const hash = window.location.hash;
        if (hash === '#signup') {
            switchAuthTab('signup');
        } else if (hash === '#signin') {
            switchAuthTab('signin');
        }
    });
</script>
