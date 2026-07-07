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
.header p{
    color:rgba(255,255,255,0.6);
    font-size:13px;
    margin:6px 0 0;
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
    padding:12px 18px;
    margin:16px 0;
    font-family:monospace;
    font-size:15px;
    color:#711500;
    font-weight:700;
    text-align:center;
    letter-spacing:1px;
}
.feedback-box{
    background:#fff8e1;
    border-left:3px solid #C9A84C;
    padding:16px 20px;
    border-radius:0 8px 8px 0;
    margin:16px 0;
}
.feedback-box p{
    margin:0;
    font-size:13.5px;
    color:#2a1200;
    line-height:1.7;
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
    border-top:1px solid #e8ddd8;
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

        <h1>
            Response Returned for Revision
        </h1>

        <p>
            ESUT Law Clinic Advisor Portal
        </p>

    </div>


    {{-- Body --}}
    <div class="body">

        <p>
            Hello <strong>{{ $advisor->name }}</strong>,
        </p>

        <p>
            Your draft response for the following enquiry has been reviewed and returned with feedback. Please revise and resubmit.
        </p>


        <div class="ref">
            {{ $enquiry->reference_code }}
        </div>


        <p>
            <strong>Supervisor's feedback:</strong>
        </p>


        <div class="feedback-box">
            <p>
                {{ $response->review_notes }}
            </p>
        </div>


        <p>
            Please log in to the portal to revise your response and resubmit for approval.
        </p>


        <a href="{{ route('portal.enquiries.show', $enquiry) }}" class="btn">
            Revise Response in Portal →
        </a>

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