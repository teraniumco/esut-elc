<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>New Contact Message</title></head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background:#f5f5f5; margin:0; padding:20px;">
<div style="max-width:560px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; border:1px solid #e5e7eb;">
    <div style="background:#0F2557; padding:24px 32px;">
        <p style="color:#C9A84C; font-size:11px; letter-spacing:2px; text-transform:uppercase; margin:0 0 4px;">ESUT Law Clinic</p>
        <h1 style="color:#fff; font-size:18px; margin:0; font-weight:700;">New Contact Message</h1>
    </div>
    <div style="padding:24px 32px;">
        <table style="width:100%; border-collapse:collapse; font-size:13px; margin-bottom:20px;">
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:8px 0; color:#9ca3af; width:80px;">From</td>
                <td style="padding:8px 0; color:#111827; font-weight:600;">{{ $contactMessage->name }}</td>
            </tr>
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:8px 0; color:#9ca3af;">Email</td>
                <td style="padding:8px 0; color:#111827;">{{ $contactMessage->email }}</td>
            </tr>
            <tr>
                <td style="padding:8px 0; color:#9ca3af;">Subject</td>
                <td style="padding:8px 0; color:#111827;">{{ $contactMessage->subject ?: '(No subject)' }}</td>
            </tr>
        </table>
        <div style="background:#f9fafb; border-radius:8px; padding:16px 20px;">
            <p style="color:#374151; font-size:13px; line-height:1.8; margin:0; white-space:pre-wrap;">{{ $contactMessage->message }}</p>
        </div>
        <p style="color:#9ca3af; font-size:11px; margin:16px 0 0;">Received: {{ $contactMessage->created_at->format('d M Y, g:i A') }}</p>
    </div>
</div>
</body></html>
