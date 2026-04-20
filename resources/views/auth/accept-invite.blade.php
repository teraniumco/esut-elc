<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation — ESUT Law Clinic</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body{font-family:'DM Sans',sans-serif}
        .input-field{width:100%;padding:11px 14px;border:1.5px solid #e8ddd8;border-radius:8px;font-size:14px;outline:none;background:#fff;color:#2a1200;transition:border-color .2s,box-shadow .2s}
        .input-field:focus{border-color:#711500;box-shadow:0 0 0 3px rgba(113,21,0,0.08)}
        .btn-primary{width:100%;background:#711500;color:#fff;padding:12px;border-radius:8px;font-size:14px;font-weight:700;border:none;cursor:pointer;transition:background .2s}
        .btn-primary:hover{background:#8a1a00}
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 bg-gray-50">
<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <div class="w-12 h-12 rounded-full mx-auto flex items-center justify-center mb-4" style="background:#711500">
            <span style="font-family:'DM Serif Display',serif;color:#C9A84C;font-size:14px">EL</span>
        </div>
        <h1 class="text-2xl" style="font-family:'DM Serif Display',serif;color:#2a1200">Welcome to ESUT Law Clinic</h1>
        <p class="text-sm text-gray-400 mt-1">Set your password to activate your account</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-8" style="border-color:#e8ddd8">
        <div class="mb-6 p-4 rounded-xl" style="background:#fdf0ed;border:1px solid rgba(113,21,0,0.12)">
            <div class="text-xs font-bold uppercase tracking-widest mb-1" style="color:#C9A84C">Your account</div>
            <div class="font-semibold" style="color:#2a1200">{{ $user->name }}</div>
            <div class="text-sm text-gray-500">{{ $user->email }}</div>
            <div class="inline-block mt-2 text-xs px-3 py-1 rounded-full font-semibold" style="background:rgba(113,21,0,0.08);color:#711500">{{ $user->role_label }}</div>
        </div>

        <form method="POST" action="{{ route('portal.invite.accept', $token) }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#2a1200">New Password</label>
                <input type="password" name="password" required minlength="8" class="input-field @error('password') border-red-400 @enderror">
                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Minimum 8 characters</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#2a1200">Confirm Password</label>
                <input type="password" name="password_confirmation" required class="input-field">
            </div>

            <button type="submit" class="btn-primary">Activate Account & Sign In</button>
        </form>
    </div>
</div>
</body>
</html>
