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
    max-width:600px;
    margin:40px auto;
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    border:1px solid #e8ddd8;
}
.header{
    background:#711500;
    padding:32px 40px;
    text-align:center;
}
.header img{
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:50%;
    margin-bottom:16px;
}
.header h1{
    font-family:Georgia,serif;
    color:#fff;
    font-size:22px;
    margin:0;
    font-weight:normal;
}
.header p{
    color:rgba(255,255,255,0.6);
    font-size:13px;
    margin:6px 0 0;
}
.body{
    padding:36px 40px;
}
.body h2{
    color:#2a1200;
    font-size:17px;
    margin:0 0 14px;
    font-family:Georgia,serif;
    font-weight:normal;
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
.response-box{
    background:#f8f5f2;
    border-left:3px solid #C9A84C;
    padding:20px 24px;
    border-radius:0 8px 8px 0;
    margin:20px 0;
}
.response-box p{
    margin:0;
    font-size:14px;
    color:#2a1200;
    line-height:1.8;
    white-space:pre-line;
}
.disclaimer{
    font-size:12px;
    color:#a08070;
    background:#fdf0ed;
    border-radius:8px;
    padding:14px 18px;
    margin-top:20px;
    line-height:1.6;
}
.footer{
    background:#f8f5f2;
    padding:20px 40px;
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
             width="90"
             height="90">

        <h1>
            ESUT Law Clinic
        </h1>

        <p>
            Your legal enquiry has been responded to
        </p>

    </div>


    {{-- Body --}}
    <div class="body">

        <h2>
            Response to Your Enquiry
        </h2>

        <p>
            Dear {{ $enquiry->display_name }},
        </p>

        <p>
            Thank you for reaching out to the ESUT Law Clinic. Our student advisors, under faculty supervision, have reviewed your matter and prepared the following response:
        </p>


        <div class="ref">
            {{ $enquiry->reference_code }}
        </div>


        <div class="response-box">
            <p>{{ $response->content }}</p>
        </div>


        <div class="disclaimer">
            <strong>⚠️ Disclaimer:</strong>
            This response is for general legal information purposes only. It does not constitute formal legal advice and should not be relied upon as such. For matters involving significant legal consequences, you are advised to consult a qualified legal practitioner. This response was reviewed and approved by a faculty supervisor of the ESUT Faculty of Law.
        </div>

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