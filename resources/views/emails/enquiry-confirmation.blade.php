<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Enquiry Received</title></head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background:#f5f5f5; margin:0; padding:20px;">
<div style="max-width:560px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; border:1px solid #e5e7eb;">

    {{-- Header --}}
    <div style="background:#711500; padding:32px 32px 24px; text-align:center;">
        <img src="{{ asset('assets/img/elc-logo.jpg') }}"
            alt="ESUT Law Clinic Logo"
            style="width: 100px; object-fit: cover; border-radius: 50%; margin-bottom:16px;">
        <p style="color:#C9A84C; font-size:11px; font-weight:700; letter-spacing:2px; text-transform:uppercase; margin:0 0 8px;">
            ESUT Law Clinic
        </p>
        <h1 style="color:#fff; font-size:22px; margin:0; font-weight:700;">
            Enquiry Received
        </h1>
    </div>

    {{-- Body --}}
    <div style="padding:32px;">
        <p style="color:#374151; font-size:14px; margin:0 0 16px;">
            @if($enquiry->is_anonymous)
            Hello,
            @else
            Dear {{ $enquiry->full_name }},
            @endif
        </p>
        <p style="color:#374151; font-size:14px; margin:0 0 20px;">
            Thank you for reaching out to the ESUT Law Clinic. We have received your legal enquiry and will assign it to one of our student advisors shortly.
        </p>

        {{-- Reference code spotlight --}}
        <div style="background:#f0f4ff; border:2px solid #711500; border-radius:10px; padding:20px; text-align:center; margin:0 0 24px;">
            <p style="color:#6b7280; font-size:11px; margin:0 0 6px; text-transform:uppercase; letter-spacing:1px;">Your Reference Code</p>
            <p style="color:#711500; font-size:26px; font-weight:800; letter-spacing:4px; margin:0; font-family:monospace;">{{ $enquiry->reference_code }}</p>
            <p style="color:#9ca3af; font-size:11px; margin:6px 0 0;">Save this code to track your enquiry</p>
        </div>

        {{-- Details --}}
        <table style="width:100%; border-collapse:collapse; margin:0 0 24px; font-size:13px;">
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:8px 0; color:#9ca3af;">Category</td>
                <td style="padding:8px 0; color:#111827; font-weight:500; text-align:right;">{{ $enquiry->category_label }}</td>
            </tr>
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:8px 0; color:#9ca3af;">Urgency</td>
                <td style="padding:8px 0; color:#111827; font-weight:500; text-align:right;">{{ ucfirst($enquiry->urgency) }}</td>
            </tr>
            <tr>
                <td style="padding:8px 0; color:#9ca3af;">Submitted</td>
                <td style="padding:8px 0; color:#111827; font-weight:500; text-align:right;">{{ $enquiry->created_at->format('d M Y, g:i A') }}</td>
            </tr>
        </table>

        {{-- CTA Button --}}
        <div style="text-align:center; margin:0 0 24px;">
            <a href="{{ route('enquiry.track') }}"
               style="display:inline-block; background:#711500; color:#fff; text-decoration:none; padding:12px 28px; border-radius:8px; font-weight:700; font-size:13px;">
                Track My Enquiry
            </a>
        </div>

        <p style="color:#9ca3af; font-size:12px; line-height:1.7; margin:0;">
            Response time is typically 2–3 business days (24 hours for urgent matters). All advice provided by the clinic is general legal information and not formal legal representation. If your matter requires formal legal representation, our team will advise you accordingly.
        </p>
    </div>

    {{-- Footer --}}
    <div style="background:#f9fafb; border-top:1px solid #f3f4f6; padding:20px 32px; text-align:center;">
        <p style="color:#9ca3af; font-size:11px; margin:0;">ESUT Law Clinic · Faculty of Law · Enugu State University of Science and Technology</p>
        <p style="color:#9ca3af; font-size:11px; margin:0;">
            Tech Partner: 
            <a href="https://teranium.co/"
               target="_blank">
                Teranium Co Limited
            </a>
        </p>
    </div>
</div>
</body></html>
