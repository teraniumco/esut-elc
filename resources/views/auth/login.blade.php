<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Login — ESUT Law Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/fonts/font-awesome/css/all.css')}}">
    <link rel="shortcut icon" href="{{ asset('assets/img/elc-logo.jpg') }}" type="image/png">

    <script src="{{asset('assets/js/vendor/tailwindcss-3.4.17.min.js')}}"></script>
    <style>
        :root { --crimson:#711500; --gold:#C9A84C; }
        body { font-family:'DM Sans',sans-serif; }
        .login-bg { background:linear-gradient(135deg,#4a0d00 0%,#711500 55%,#8a1a00 100%); }
        .grid-lines {
            position:absolute;inset:0;
            background-image:linear-gradient(rgba(255,255,255,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.04) 1px,transparent 1px);
            background-size:60px 60px;
        }
        .input-field {
            width:100%;padding:11px 14px;border:1.5px solid #e8ddd8;border-radius:8px;font-size:14px;
            transition:border-color 0.2s,box-shadow 0.2s;outline:none;background:#fff;color:#2a1200;
        }
        .input-field:focus { border-color:var(--crimson);box-shadow:0 0 0 3px rgba(113,21,0,0.08); }
        .btn-login {
            width:100%;background:var(--crimson);color:#fff;padding:12px;border-radius:8px;
            font-size:14px;font-weight:700;border:none;cursor:pointer;
            transition:background 0.2s,transform 0.15s;
        }
        .btn-login:hover { background:#8a1a00;transform:translateY(-1px); }
        .ring { position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,0.06); }
    </style>
</head>
<body class="min-h-screen flex">

    {{-- Left: Brand panel --}}
    <div class="login-bg hidden lg:flex flex-col justify-between w-5/12 p-12 relative overflow-hidden">
        <div class="grid-lines"></div>
        <div class="ring" style="width:500px;height:500px;top:-200px;right:-200px"></div>
        <div class="ring" style="width:300px;height:300px;bottom:-100px;left:-80px;border-color:rgba(201,168,76,0.1)"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-12">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background:rgba(201,168,76,0.15);border:1px solid rgba(201,168,76,0.3)">
                    <img src="{{ asset('assets/img/elc-logo.jpg') }}" alt="ESUT Law Clinic Logo">
                </div>
                <div>
                    <span class="block font-serif text-white text-base" style="font-family:'DM Serif Display',serif">ESUT Law Clinic</span>
                    <span class="block text-xs" style="color:rgba(255,255,255,0.4)">Member Portal</span>
                </div>
            </div>

            <h1 class="text-4xl font-normal text-white leading-tight mb-4" style="font-family:'DM Serif Display',serif;letter-spacing:-1px">
                Justice requires<br><em style="color:#C9A84C">careful hands.</em>
            </h1>
            <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.45);max-width:280px">
                The ESUT Law Clinic portal — where student advisors and faculty supervisors manage legal enquiries together.
            </p>
        </div>
    </div>

    {{-- Right: Login form --}}
    <div class="flex-1 flex items-center justify-center p-6 bg-gray-50">
        <div class="w-full max-w-md">

            {{-- Mobile logo --}}
            <div class="lg:hidden flex items-center gap-3 mb-8 justify-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background:#711500">
                    <img src="{{ asset('assets/img/elc-logo.jpg') }}" alt="ESUT Law Clinic Logo">
                </div>
                <div>
                    <span class="block font-serif" style="color:#711500;font-family:'DM Serif Display',serif">ESUT Law Clinic</span>
                    <span class="block text-xs text-gray-400">Member Portal</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border p-8" style="border-color:#e8ddd8">
                <h2 class="text-2xl mb-1" style="font-family:'DM Serif Display',serif;color:#2a1200">Sign in to Portal</h2>
                <p class="text-sm text-gray-400 mb-8">Enter your credentials to access the clinic portal.</p>

                {{-- Flash errors --}}
                @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-6 text-sm text-red-700">
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('portal.login.post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#2a1200">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required
                               class="input-field @error('email') border-red-400 @enderror">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#2a1200">Password</label>
                        <input type="password" name="password" autocomplete="current-password" required
                               class="input-field @error('password') border-red-400 @enderror">
                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-500 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded">
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="btn-login">Sign In</button>
                </form>
            </div>

            <p class="text-center text-xs text-gray-400 mt-6">
                Don't have an account? <a href="{{ route('home') }}" style="color:#711500">Return to public site</a>
            </p>
            <p class="text-center text-xs text-gray-400 mt-1">
                Check your email for an invite link, or contact your clinic administrator.
            </p>
        </div>
    </div>
</body>
</html>
