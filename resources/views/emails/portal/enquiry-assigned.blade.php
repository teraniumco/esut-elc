<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body{
    font-family:'Segoe UI',Arial,sans-serif;
    background:#f8f5f2;
    margin:0;
    padding:0;
}
.wrap{
    max-width:580px;
    margin:40px auto;
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    border:1px solid #e8ddd8;
}
.header{
    background:#711500;
    padding:28px 36px;
    text-align:center;
}
.header img{
    width:80px;
    height:80px;
    object-fit:cover;
    border-radius:50%;
    margin-bottom:14px;
}
.header h1{
    font-family:Georgia,serif;
    color:#fff;
    font-size:18px;
    margin:0;
    font-weight:normal;
}
.body{
    padding:32px 36px;
}
.body p{
    color:#6b4a3a;
    font-size:14px;
    line-height:1.7;
    margin:0 0 14px;
}
.ref{
    background:#fdf0ed;
    border:1px solid rgba(113,21,0,0.12);
    border-radius:8px;
    padding:14px 18px;
    margin:16px 0;
    font-family:monospace;
    font-size:16px;
    color:#711500;
    font-weight:700;
    text-align:center;
    letter-spacing:1px;
}
.info-table{
    width:100%;
    border-collapse:collapse;
    margin:16px 0;
}
.info-table td{
    padding:6px 0;
    font-size:13px;
    color:#6b4a3a;
}
.info-label{
    font-weight:600;
    color:#2a1200;
    width:100px;
}
.btn{
    display:inline-block;
    background:#711500;
    color:#fff;
    text-decoration:none;
    padding:12px 28px;
    border-radius:7px;
    font-size:13px;
    font-weight:600;
    margin:20px 0;
}
.footer{
    background:#f8f5f2;
    padding:18px 36px;
    text-align:center;
    color:#a08070;
    font-size:12px;
}
</style>
</head>

<body>
<div class="wrap">

    {{-- Header --}}
    <div class="header">

        <img src="{{ asset('assets/img/elc-logo.jpg') }}"
             alt="ESUT Law Clinic Logo"
             width="80"
             height="80">

        <h1>New Enquiry Assigned — ESUT Law Clinic</h1>

    </div>


    {{-- Body --}}
    <div class="body">

        <p>Hello <strong>{{ $advisor->name }}</strong>,</p>

        <p>
            A new legal enquiry has been assigned to you for review and response.
        </p>

        <div class="ref">
            {{ $enquiry->reference_code }}
        </div>


        <table class="info-table">
            <tr>
                <td class="info-label">Category:</td>
                <td>{{ $enquiry->category_label }}</td>
            </tr>
            <tr>
                <td class="info-label">Urgency:</td>
                <td>{{ ucfirst($enquiry->urgency) }}</td>
            </tr>
            <tr>
                <td class="info-label">Submitted:</td>
                <td>{{ $enquiry->created_at->format('d M Y') }}</td>
            </tr>
        </table>


        <p>
            Please log in to the portal to review the enquiry and draft your response.
        </p>


        <a href="{{ route('portal.enquiries.show', $enquiry) }}" class="btn">
            View in Portal →
        </a>


        <p style="font-size:12px;color:#a08070">
            Your response must be reviewed by a faculty supervisor before it is sent to the requester.
        </p>

    </div>


    {{-- Footer --}}
    <div class="footer">
        &copy; {{ now()->year }} ESUT Law Clinic · Faculty of Law · Enugu State University of Science and Technology
        <p style="color:#9ca3af; font-size:11px; margin:0;">
            Tech Partner: 
            <a href="https://teranium.co/"
               target="_blank">
                Teranium Co Limited
            </a>
        </p>
    </div>

</div>
</body>
</html>