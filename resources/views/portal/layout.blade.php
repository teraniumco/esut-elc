<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal') — ESUT Law Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/fonts/font-awesome/css/all.css')}}">
    <link rel="shortcut icon" href="{{ asset('assets/img/esut_logomark.png') }}" type="image/png">
    <script src="{{asset('assets/js/vendor/tailwindcss-3.4.17.min.js')}}"></script>
    <script defer src="{{asset('assets/js/vendor/alpine.min.js')}}"></script>
    <style>
        :root{--crimson:#711500;--crimson-dark:#4a0d00;--gold:#C9A84C;--gold-light:#e4c97e;--off-white:#f8f5f2;--border:#e8ddd8;--text:#2a1200;--text-mid:#6b4a3a;--text-light:#a08070}
        [x-cloak]{display:none!important}
        body{font-family:'DM Sans',sans-serif;color:var(--text)}

        /* Sidebar — fixed, stays put while page scrolls */
        .sidebar{width:240px;flex-shrink:0;background:var(--crimson-dark);display:flex;flex-direction:column;height:100vh;position:fixed;top:0;left:0;overflow-y:auto;z-index:40}
        /* Main content pushed right to clear the fixed sidebar */
        @media(min-width:1024px){.main-content{margin-left:240px}}

        .sidebar-logo{padding:20px 20px 16px;border-bottom:1px solid rgba(255,255,255,0.07)}
        .sidebar-logo-mark{width:32px;height:32px;border-radius:50%;background:rgba(201,168,76,0.15);border:1px solid rgba(201,168,76,0.25);display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .sidebar-logo-mark span{font-family:'DM Serif Display',serif;font-size:11px;color:var(--gold)}
        .nav-section-label{font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:rgba(255,255,255,0.25);padding:14px 16px 6px;display:block}
        .nav-item{display:flex;align-items:center;gap:10px;padding:9px 16px;border-radius:8px;margin:0 8px 2px;font-size:13px;font-weight:500;color:rgba(255,255,255,0.55);text-decoration:none;transition:background .2s,color .2s}
        .nav-item:hover{background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.85)}
        .nav-item.active{background:rgba(201,168,76,0.12);color:var(--gold-light)}
        .nav-item svg{flex-shrink:0;opacity:0.7}
        .nav-item.active svg{opacity:1}
        .nav-badge{margin-left:auto;background:var(--gold);color:var(--crimson-dark);font-size:10px;font-weight:800;padding:2px 7px;border-radius:20px;min-width:18px;text-align:center}
        .nav-badge-red{background:#ef4444;color:#fff}
        .topbar{height:56px;background:#fff;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 24px;position:sticky;top:0;z-index:10}
        .status-badge{display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px}
        .status-received{background:#eff6ff;color:#1d4ed8}
        .status-under_review{background:#fefce8;color:#854d0e}
        .status-in_progress{background:#eef2ff;color:#4338ca}
        .status-awaiting_approval{background:#fff7ed;color:#c2410c}
        .status-responded{background:#f0fdf4;color:#15803d}
        .status-closed{background:#f9fafb;color:#6b7280}
        .portal-card{background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px}
        .form-input{width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:8px;font-size:13.5px;outline:none;background:#fff;color:var(--text);transition:border-color .2s,box-shadow .2s;font-family:'DM Sans',sans-serif}
        .form-input:focus{border-color:var(--crimson);box-shadow:0 0 0 3px rgba(113,21,0,0.08)}
        .form-label{display:block;font-size:13px;font-weight:600;color:var(--text);margin-bottom:6px}
        .form-hint{font-size:12px;color:var(--text-light);margin-top:4px}
        .btn-crimson{background:var(--crimson);color:#fff;padding:9px 18px;border-radius:7px;font-size:13px;font-weight:700;border:none;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:background .2s,transform .15s}
        .btn-crimson:hover{background:#8a1a00;transform:translateY(-1px);text-decoration:none;color:#fff}
        .btn-gold{background:var(--gold);color:var(--crimson-dark);padding:9px 18px;border-radius:7px;font-size:13px;font-weight:700;border:none;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:background .2s}
        .btn-gold:hover{background:var(--gold-light);text-decoration:none;color:var(--crimson-dark)}
        .btn-ghost{background:transparent;color:var(--text-mid);padding:9px 16px;border-radius:7px;font-size:13px;font-weight:500;border:1.5px solid var(--border);cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:border-color .2s,color .2s}
        .btn-ghost:hover{border-color:var(--crimson);color:var(--crimson);text-decoration:none}
        .btn-sm{padding:6px 12px;font-size:12px}
        .btn-danger{background:#fef2f2;color:#dc2626;border:1.5px solid #fecaca;padding:7px 14px;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:5px;transition:background .2s}
        .btn-danger:hover{background:#fee2e2;text-decoration:none;color:#dc2626}
    </style>
    @stack('styles')
</head>
<body class="h-full bg-gray-50">
<div class="flex h-full" x-data="{ sidebarOpen: false }">

    <!-- ═══ SIDEBAR (fixed) ══════════════════════════════════════════════ -->
    <aside class="sidebar hidden lg:flex flex-col">
        <div class="sidebar-logo">
            <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2.5">
                <div class="sidebar-logo-mark">
                    <img src="{{ asset('assets/img/esut_logomark.png') }}" alt="ESUT Law Clinic Logo">
                </div>
                <div>
                    <span class="block text-white font-medium text-sm" style="font-family:'DM Serif Display',serif;line-height:1.2">ESUT Law Clinic</span>
                    <span class="block text-xs" style="color:rgba(255,255,255,0.3)">Member Portal</span>
                </div>
            </a>
        </div>

        <div class="px-4 py-4 border-b" style="border-color:rgba(255,255,255,0.07)">
            <div class="flex items-center gap-2.5">
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-8 h-8 rounded-full flex-shrink-0">
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs truncate" style="color:var(--gold);opacity:0.8">{{ auth()->user()->role_label }}</div>
                </div>
            </div>
        </div>

        <nav class="flex-1 py-3 overflow-y-auto">
            <span class="nav-section-label">Main</span>
            <a href="{{ route('portal.dashboard') }}" class="nav-item {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('portal.enquiries.index') }}" class="nav-item {{ request()->routeIs('portal.enquiries.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Enquiries
                @if(auth()->user()->pending_enquiries_count > 0)
                    <span class="nav-badge nav-badge-red">{{ auth()->user()->pending_enquiries_count }}</span>
                @endif
            </a>

            @if(auth()->user()->canApprove())
            <a href="{{ route('portal.enquiries.index', ['status' => 'awaiting_approval']) }}" class="nav-item {{ request()->is('portal/enquiries*') && request('status') === 'awaiting_approval' ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Review Queue
                @if(auth()->user()->pending_reviews_count > 0)
                    <span class="nav-badge">{{ auth()->user()->pending_reviews_count }}</span>
                @endif
            </a>
            @endif

            @if(auth()->user()->isAdmin())
            <span class="nav-section-label" style="margin-top:8px">Admin</span>
            <a href="{{ route('portal.admin.users.index') }}" class="nav-item {{ request()->routeIs('portal.admin.users.*') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Users
            </a>
            <a href="{{ route('portal.admin.reports') }}" class="nav-item {{ request()->routeIs('portal.admin.reports') ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Reports
            </a>
            @endif

            <span class="nav-section-label" style="margin-top:8px">Site</span>
            <a href="{{ route('home') }}" target="_blank" class="nav-item">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Public Site
            </a>
        </nav>

        <div class="p-4 border-t" style="border-color:rgba(255,255,255,0.07)">
            <form method="POST" action="{{ route('portal.logout') }}">
                @csrf
                <button type="submit" class="nav-item w-full text-left" style="background:transparent;border:none;cursor:pointer;width:100%">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- ═══ MAIN CONTENT ══════════════════════════════════════════════════ -->
    <div class="main-content flex-1 flex flex-col min-h-screen min-w-0">

        <header class="topbar py-4">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-1.5 rounded-lg" style="color:var(--text-mid)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-sm font-semibold" style="color:var(--text)">@yield('page-title', 'Portal')</h1>
                    @hasSection('page-subtitle')
                    <p class="text-xs" style="color:var(--text-light)">@yield('page-subtitle')</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('enquiry.create') }}" target="_blank" class="hidden sm:flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg" style="background:var(--off-white);color:var(--text-mid);border:1px solid var(--border)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Public Form
                </a>
                <img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full" alt="{{ auth()->user()->name }}">
            </div>
        </header>

        @if(session('success') || session('error'))
        <div class="px-6 pt-4">
            @if(session('success'))
            <div x-data="{show:true}" x-show="show" x-transition class="flex items-center justify-between gap-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-800 mb-2">
                <div class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>{{ session('success') }}</div>
                <button @click="show=false"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            @endif
            @if(session('error'))
            <div x-data="{show:true}" x-show="show" x-transition class="flex items-center justify-between gap-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-800 mb-2">
                <div class="flex items-center gap-2"><svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>{{ session('error') }}</div>
                <button @click="show=false"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            @endif
        </div>
        @endif

        <main class="flex-1 p-6">
            @yield('content')
        </main>

        <footer class="px-6 py-4 text-xs text-center border-t" style="color:var(--text-light);border-color:var(--border)">
            &copy; {{ now()->year }} ESUT Law Clinic Member Portal &mdash; <a href="{{ route('home') }}" style="color:var(--crimson)">Return to public site</a>
        </footer>
    </div>

    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false" class="fixed inset-0 bg-black/40 z-20 lg:hidden"></div>
    <aside x-show="sidebarOpen" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-30 lg:hidden flex flex-col" style="width:240px;background:var(--crimson-dark)">
        <div class="sidebar-logo">
            <div class="flex items-center justify-between">
                <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="sidebar-logo-mark"><span>EL</span></div>
                    <span class="text-white font-medium text-sm" style="font-family:'DM Serif Display',serif">ESUT Law Clinic</span>
                </a>
                <button @click="sidebarOpen=false" class="text-white/40 hover:text-white/70">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <nav class="flex-1 py-3 overflow-y-auto">
            <a href="{{ route('portal.dashboard') }}" class="nav-item">Dashboard</a>
            <a href="{{ route('portal.enquiries.index') }}" class="nav-item">Enquiries</a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('portal.admin.users.index') }}" class="nav-item">Users</a>
            <a href="{{ route('portal.admin.reports') }}" class="nav-item">Reports</a>
            @endif
            <!-- add review queue -->
            <a href="{{ route('portal.enquiries.index', ['status' => 'awaiting_approval']) }}" class="nav-item">
                Review Queue
                @if(auth()->user()->pending_reviews_count > 0)
                    <span class="nav-badge">{{ auth()->user()->pending_reviews_count }}</span>
                @endif
            </a>


            <a href="{{ route('home') }}" target="_blank" class="nav-item">Public Site</a>
        </nav>
        <div class="p-4 border-t" style="border-color:rgba(255,255,255,0.07)">
            <form method="POST" action="{{ route('portal.logout') }}">@csrf
                <button type="submit" class="nav-item w-full text-left" style="background:transparent;border:none;cursor:pointer">Sign Out</button>
            </form>
        </div>
    </aside>

</div>
@stack('scripts')
</body>
</html>
