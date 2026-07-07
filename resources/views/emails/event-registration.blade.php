<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Event Registration Confirmed</title>
</head>

<body style="font-family:'Segoe UI',Arial,sans-serif;background:#f5f5f5;margin:0;padding:20px;">

<div style="max-width:560px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">

    {{-- Header --}}
    <div style="background:#711500;padding:28px 32px;text-align:center;">

        <img src="{{ asset('assets/img/elc-logo.jpg') }}"
             alt="ESUT Law Clinic Logo"
             width="90"
             height="90"
             style="width:90px;height:90px;object-fit:cover;border-radius:50%;margin-bottom:16px;">

        <p style="color:#C9A84C;font-size:11px;font-weight:700;letter-spacing:2px;text-transform:uppercase;margin:0 0 6px;">
            ESUT Law Clinic
        </p>

        <h1 style="color:#fff;font-size:20px;margin:0;font-weight:700;">
            Registration Confirmed ✓
        </h1>

    </div>


    {{-- Body --}}
    <div style="padding:28px 32px;">

        <p style="color:#374151;font-size:14px;margin:0 0 14px;">
            Dear {{ $registration->name }},
        </p>

        <p style="color:#374151;font-size:14px;margin:0 0 20px;">
            Thank you for registering. Your registration has been confirmed for the following event:
        </p>


        {{-- Event Details --}}
        <div style="background:#fdf0ed;border-left:4px solid #C9A84C;padding:16px 20px;border-radius:0 8px 8px 0;margin:0 0 24px;">

            <p style="color:#711500;font-size:16px;font-weight:700;margin:0 0 10px;">
                {{ $registration->event->title }}
            </p>

            <p style="color:#6b7280;font-size:13px;margin:0 0 6px;">
                📅 {{ $registration->event->event_date->format('l, d F Y \a\t g:i A') }}
            </p>

            @if($registration->event->location)
            <p style="color:#6b7280;font-size:13px;margin:0;">
                📍 {{ $registration->event->location }}
            </p>
            @endif

        </div>


        <p style="color:#374151;font-size:14px;line-height:1.7;margin:0 0 18px;">
            We look forward to welcoming you. If there are any updates regarding the event, they will be communicated through our official channels.
        </p>


        <p style="color:#9ca3af;font-size:12px;margin:0;">
            Please arrive a few minutes before the scheduled start time and keep this email for your records.
        </p>

    </div>


    {{-- Footer --}}
    <div style="background:#f9fafb;border-top:1px solid #f3f4f6;padding:20px 32px;text-align:center;">

        <p style="color:#9ca3af;font-size:11px;margin:0;">
            ESUT Law Clinic · Faculty of Law · Enugu State University of Science and Technology
        </p>

        <p style="color:#9ca3af;font-size:11px;margin:8px 0 0;">
            Tech Partner:
            <a href="https://teranium.co/"
               target="_blank"
               style="color:#711500;text-decoration:none;font-weight:600;">
                Teranium Co Limited
            </a>
        </p>

    </div>

</div>

</body>
</html>