<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Agenda Organisasi</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
min-height:100vh;
display:flex;
justify-content:center;
align-items:center;
background:linear-gradient(135deg,#e0f2fe,#f8fafc);
overflow:hidden;
}

/* BACKGROUND FLOATING */
.bg{
position:absolute;
width:100%;
height:100%;
overflow:hidden;
}

.bubble{
position:absolute;
border-radius:50%;
background:rgba(59,130,246,.15);
filter:blur(40px);
animation:float 10s infinite ease-in-out;
}

.bubble:nth-child(1){width:220px;height:220px;top:10%;left:10%;}
.bubble:nth-child(2){width:260px;height:260px;bottom:10%;right:10%;animation-delay:2s;}
.bubble:nth-child(3){width:160px;height:160px;top:60%;left:45%;animation-delay:4s;}

@keyframes float{
0%,100%{transform:translateY(0);}
50%{transform:translateY(-30px);}
}

/* CONTAINER */
.container{
width:950px;
display:grid;
grid-template-columns:1fr 1fr;
border-radius:28px;
overflow:hidden;
background:rgba(255,255,255,.65);
backdrop-filter:blur(20px);
box-shadow:0 30px 60px rgba(0,0,0,.1);
z-index:2;
}

/* LEFT */
.left{
background:linear-gradient(135deg,#dbeafe,#eff6ff);
padding:40px;
display:flex;
flex-direction:column;
justify-content:center;
align-items:center;
text-align:center;
}

.left img{
width:130px;
margin-bottom:20px;
animation:fadeUp .8s ease;
}

.left h2{
font-size:20px;
font-weight:700;
color:#1e3a8a;
}

.left p{
font-size:13px;
color:#64748b;
margin-top:8px;
}

/* RIGHT */
.right{
padding:40px;
display:flex;
flex-direction:column;
justify-content:center;
animation:fadeUp .8s ease;
}

@keyframes fadeUp{
from{opacity:0;transform:translateY(25px);}
to{opacity:1;transform:translateY(0);}
}

/* 🔥 TITLE SUPER FIX */
.title{
position:relative;
font-size:30px;
font-weight:800;
margin-bottom:6px;

display:flex;
align-items:center;
gap:10px;

letter-spacing:0.5px;

/* gradient */
background:linear-gradient(135deg,#1d4ed8,#3b82f6,#60a5fa);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

/* glow layer */
.title::after{
content:attr(data-text);
position:absolute;
left:0;
top:0;
z-index:-1;

background:linear-gradient(135deg,#1d4ed8,#3b82f6,#60a5fa);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;

filter:blur(8px);
opacity:0.5;
}

/* ICON */
.title i{
color:#2563eb;
font-size:22px;
filter:drop-shadow(0 4px 12px rgba(59,130,246,0.6));
}

/* hover */
.title:hover{
transform:translateY(-2px) scale(1.02);
transition:0.3s;
}

/* SUBTITLE */
.subtitle{
font-size:13px;
color:#64748b;
margin-bottom:25px;
}

/* INPUT */
.group{margin-bottom:16px;}

label{
font-size:13px;
font-weight:600;
margin-bottom:6px;
display:block;
}

.input-box{
position:relative;
}

.input-box input{
width:100%;
padding:12px 14px;
padding-left:42px;
border-radius:12px;
border:1px solid #dbeafe;
outline:none;
transition:.25s;
background:#fff;
}

.input-box input:focus{
border-color:#3b82f6;
box-shadow:0 0 0 3px rgba(59,130,246,.15);
transform:translateY(-1px);
}

.input-box svg{
position:absolute;
left:12px;
top:50%;
transform:translateY(-50%);
width:18px;
height:18px;
stroke:#94a3b8;
}

/* BUTTON */
.btn{
position:relative;
overflow:hidden;
width:100%;
padding:14px;
border:none;
border-radius:14px;
background:linear-gradient(135deg,#3b82f6,#2563eb);
color:white;
font-weight:700;
cursor:pointer;
transition:.25s;
box-shadow:0 15px 25px rgba(59,130,246,.3);
}

.btn:hover{
transform:translateY(-2px);
}

.btn::after{
content:'';
position:absolute;
top:0;
left:-100%;
width:100%;
height:100%;
background:linear-gradient(120deg,transparent,rgba(255,255,255,.4),transparent);
transition:.6s;
}

.btn:hover::after{
left:100%;
}

/* ERROR */
.error{
background:#fee2e2;
color:#b91c1c;
padding:10px;
border-radius:10px;
font-size:13px;
margin-bottom:15px;
text-align:center;
}

/* INFO */
.info{
margin-top:15px;
font-size:12px;
color:#64748b;
text-align:center;
}

/* RESPONSIVE */
@media(max-width:768px){
.container{
grid-template-columns:1fr;
}
.left{
display:none;
}
}
</style>
</head>

<body>

<div class="bg">
<div class="bubble"></div>
<div class="bubble"></div>
<div class="bubble"></div>
</div>

<div class="container">

<div class="left">
<img src="{{ asset('logo/logo1.png') }}">
<h2>HIMEDIAJKT</h2>
<p>Himpunan Mahasiswa Teknologi Rekayasa Multimedia</p>
</div>

<div class="right">

<!-- 🔥 PERUBAHAN DI SINI -->
<div class="title" data-text="SELAMAT DATANG!">
<i class="fa-solid fa-hand-wave"></i>
SELAMAT DATANG!
</div>

<div class="subtitle">Silakan login untuk melanjutkan ke sistem</div>

@if(session('error'))
<div class="error">{{ session('error') }}</div>
@endif

<form action="/login" method="POST">
@csrf

<div class="group">
<label>Nama</label>
<div class="input-box">
<svg viewBox="0 0 24 24" fill="none" stroke-width="2">
<circle cx="12" cy="8" r="4"/>
<path d="M4 20c2-4 6-6 8-6s6 2 8 6"/>
</svg>
<input type="text" name="nama" placeholder="Masukkan nama" required>
</div>
</div>

<div class="group">
<label>Password</label>
<div class="input-box">
<svg viewBox="0 0 24 24" fill="none" stroke-width="2">
<rect x="5" y="11" width="14" height="10" rx="2"/>
<path d="M8 11V7a4 4 0 018 0v4"/>
</svg>
<input type="password" name="password" placeholder="Masukkan password" required>
</div>
</div>

<button type="submit" class="btn">Login →</button>

</form>

<div class="info">
Gunakan nama yang sudah didaftarkan<br>
Password sesuai jabatan
</div>

</div>
</div>

</body>
</html>