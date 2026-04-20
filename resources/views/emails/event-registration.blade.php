<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Event Registration Confirmed</title></head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background:#f5f5f5; margin:0; padding:20px;">
<div style="max-width:560px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; border:1px solid #e5e7eb;">
    <div style="background:#0F2557; padding:28px 32px; text-align:center;">
        <p style="color:#C9A84C; font-size:11px; letter-spacing:2px; text-transform:uppercase; margin:0 0 6px;">ESUT Law Clinic</p>
        <h1 style="color:#fff; font-size:20px; margin:0; font-weight:700;">Registration Confirmed ✓</h1>
    </div>
    <div style="padding:28px 32px;">
        <p style="color:#374151; font-size:14px; margin:0 0 14px;">Dear {{ $registration->name }},</p>
        <p style="color:#374151; font-size:14px; margin:0 0 20px;">
            You are registered for the following event:
        </p>
        <div style="background:#f0f4ff; border-left:4px solid #0F2557; padding:16px 20px; border-radius:0 8px 8px 0; margin:0 0 24px;">
            <p style="color:#0F2557; font-size:16px; font-weight:700; margin:0 0 8px;">{{ $registration->event->title }}</p>
            <p style="color:#6b7280; font-size:13px; margin:0 0 4px;">📅 {{ $registration->event->event_date->format('l, d F Y \a\t g:i A') }}</p>
            @if($registration->event->location)
            <p style="color:#6b7280; font-size:13px; margin:0;">📍 {{ $registration->event->location }}</p>
            @endif
        </div>
        <p style="color:#9ca3af; font-size:12px; margin:0;">We look forward to seeing you. Please check back on our website for any updates.</p>
    </div>
    <div style="background:#f9fafb; border-top:1px solid #f3f4f6; padding:16px 32px; text-align:center;">
        <p style="color:#9ca3af; font-size:11px; margin:0;">ESUT Law Clinic · Faculty of Law · ESUT, Agbani, Enugu State</p>
    </div>
</div>
</body></html>
