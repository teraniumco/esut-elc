<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Invalid Invite — ESUT Law Clinic</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:opsz,wght@9..40,400;9..40,500&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50 p-6" style="font-family:'DM Sans',sans-serif">
<div class="text-center max-w-sm">
    <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-6" style="background:#fdf0ed">
        <svg class="w-8 h-8" style="color:#711500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </div>
    <h1 class="text-2xl mb-2" style="font-family:'DM Serif Display',serif;color:#2a1200">Link Expired or Invalid</h1>
    <p class="text-sm text-gray-500 mb-6">This invitation link has expired or is no longer valid. Invitation links expire after 7 days.</p>
    <p class="text-sm text-gray-500 mb-6">Please contact your clinic administrator to request a new invitation.</p>
    <a href="{{ route('portal.login') }}" class="inline-block px-6 py-2.5 rounded-lg text-sm font-semibold text-white" style="background:#711500">Return to Login</a>
</div>
</body>
</html>
