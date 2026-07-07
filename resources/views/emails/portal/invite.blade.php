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
    color:rgba(255,255,255,0.7);
    font-size:13px;
    margin:6px 0 0;
}
.body{
    padding:36px 40px;
}
.body h2{
    color:#2a1200;
    font-size:18px;
    margin:0 0 12px;
    font-family:Georgia,serif;
    font-weight:normal;
}
.body p{
    color:#6b4a3a;
    font-size:14px;
    line-height:1.7;
    margin:0 0 16px;
}
.role-chip{
    display:inline-block;
    background:#fdf0ed;
    color:#711500;
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    border:1px solid rgba(113,21,0,0.15);
    margin-bottom:18px;
}
.btn{
    display:inline-block;
    background:#711500;
    color:#ffffff !important;
    text-decoration:none;
    padding:14px 32px;
    border-radius:7px;
    font-size:14px;
    font-weight:600;
    margin:24px 0;
}
.divider{
    height:1px;
    background:#e8ddd8;
    margin:24px 0;
}
.footer{
    background:#f8f5f2;
    padding:20px 40px;
    text-align:center;
    color:#a08070;
    font-size:12px;
    border-top:1px solid #e8ddd8;
}
.footer a{
    color:#711500;
    text-decoration:none;
    font-weight:600;
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

        <h1>ESUT Law Clinic</h1>

        <p>Faculty of Law, Enugu State University of Science and Technology</p>

    </div>


    {{-- Body --}}
    <div class="body">

        <h2>You've Been Invited to the Portal</h2>

        <p>
            Hello <strong>{{ $user->name }}</strong>,
        </p>

        <p>
            You have been invited to join the ESUT Law Clinic Member Portal as:
        </p>

        <div class="role-chip">
            {{ $user->role_label }}
        </div>

        <p>
            Click the button below to set your password and activate your account. This invitation link will expire in <strong>7 days</strong>.
        </p>

        <div style="text-align:center;">
            <a href="{{ route('portal.invite.show', $token) }}" class="btn">
                Accept Invitation &amp; Set Password
            </a>
        </div>

        <div class="divider"></div>

        <p style="font-size:12px;color:#a08070;">
            If you did not expect this invitation, you can safely ignore this email. If you have any concerns, please contact the clinic administrator.
        </p>

        <p style="font-size:12px;color:#a08070;word-break:break-all;">
            Invitation Link:<br>
            {{ route('portal.invite.show', $token) }}
        </p>

    </div>


    {{-- Footer --}}
    <div class="footer">

        <p style="margin:0;">
            &copy; {{ now()->year }} ESUT Law Clinic · Faculty of Law · Enugu State University of Science and Technology
        </p>

        <p style="margin:8px 0 0;font-size:11px;color:#9ca3af;">
            Tech Partner:
            <a href="https://teranium.co/" target="_blank">
                Teranium Co Limited
            </a>
        </p>

    </div>

</div>

</body>
</html>