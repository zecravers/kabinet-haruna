<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" href="{{ asset('logo/logo1.png') }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TAMBAH KEGIATAN</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

html{
    min-height:100%;
    background:
        radial-gradient(circle at 20% 30%, rgba(59,130,246,0.25), transparent 40%),
        radial-gradient(circle at 80% 70%, rgba(37,99,235,0.2), transparent 40%),
        linear-gradient(135deg,#eef5ff,#f8fbff);
}

body{
    min-height:100vh;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    padding:40px 20px;
}

/* MOBILE TOPBAR & HAMBURGER DRAWER */
.mobile-topbar{display:none;width:100%;}
.sidebar-overlay{
display:none;position:fixed;inset:0;background:rgba(15,23,42,.45);
backdrop-filter:blur(4px);z-index:998;
}
.sidebar-overlay.active{display:block;}
.mobile-drawer{
position:fixed;top:0;left:0;bottom:0;width:270px;max-width:82vw;
background:rgba(255,255,255,.96);backdrop-filter:blur(24px);
box-shadow:20px 0 50px rgba(15,23,42,.18);transform:translateX(-105%);
transition:transform .3s cubic-bezier(.4,0,.2,1);z-index:999;padding:22px 20px;overflow-y:auto;
}
.mobile-drawer.open{transform:translateX(0);}
.drawer-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;}
.drawer-header img{width:140px;}
.close-drawer-btn{
width:36px;height:36px;border-radius:10px;border:none;background:#eff6ff;
color:#1e3a8a;font-size:18px;font-weight:700;cursor:pointer;
}
.drawer-menu a{
display:flex;align-items:center;gap:12px;padding:13px 14px;margin:8px 0;
border-radius:14px;text-decoration:none;color:#1e3a8a;background:rgba(239,246,255,.7);
font-weight:600;transition:.25s;
}
.drawer-menu a:hover{background:linear-gradient(135deg,#3b82f6,#60a5fa);color:#fff;}
.side-icon{width:18px;height:18px;stroke:currentColor;stroke-width:2.2;flex-shrink:0;}

/* WRAPPER */
.wrapper{
    display:grid;
    grid-template-columns: 1fr 1.2fr;
    gap:30px;
    width:100%;
    max-width:1050px;
}

/* LEFT PANEL */
.left{
    position:relative;
    background:rgba(255,255,255,0.18);
    backdrop-filter:blur(30px);
    -webkit-backdrop-filter:blur(30px);
    border-radius:28px;
    padding:40px 30px;
    border:1px solid rgba(255,255,255,0.35);
    box-shadow:0 25px 60px rgba(0,0,0,0.12),inset 0 1px 1px rgba(255,255,255,0.6);
    overflow:hidden;
    transition:0.35s ease;
}

.left-icon{
    width:70px;
    height:70px;
    border-radius:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
    color:white;
    background:linear-gradient(135deg,#2563eb,#3b82f6);
    box-shadow:0 12px 30px rgba(37,99,235,0.45);
    margin-bottom:18px;
}

.left h2{
    font-size:26px;
    font-weight:800;
    color:#1e3a8a;
}

.left p{
    font-size:14px;
    color:#475569;
    margin-bottom:30px;
}

.feature{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:18px;
}

.feature i{
    width:36px;
    height:36px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#2563eb,#3b82f6);
    color:white;
    border-radius:12px;
    font-size:14px;
    box-shadow:0 6px 15px rgba(37,99,235,0.35);
    flex-shrink:0;
}

/* RIGHT CARD */
.card{
    background:rgba(255,255,255,0.72);
    backdrop-filter:blur(18px);
    border-radius:24px;
    padding:30px;
    box-shadow:0 20px 50px rgba(0,0,0,0.08);
}

.header{
    display:flex;
    align-items:center;
    gap:15px;
    margin-bottom:20px;
}

.header-logo img{
    width:70px;
    height:70px;
    object-fit:contain;
}

.title{
    font-size:22px;
    font-weight:800;
    color:#1e3a8a;
}

.subtitle{
    font-size:13px;
    color:#64748b;
}

.group{margin-bottom:16px;}

label{
    font-size:13px;
    font-weight:600;
    color:#334155;
    display:block;
    margin-bottom:6px;
}

.input-wrap{
    position:relative;
    transition:all 0.25s ease;
}

.input-wrap i{
    position:absolute;
    left:14px;
    top:50%;
    transform:translateY(-50%);
    color:#2563eb;
    font-size:16px;
    z-index:5;
}

input, select{
    width:100%;
    padding:13px 14px 13px 42px;
    border-radius:16px;
    border:none;
    outline:none;
    background:rgba(255,255,255,0.85);
    font-size:14px;
    box-shadow:0 2px 6px rgba(0,0,0,0.05),0 0 0 1px rgba(226,232,240,0.8);
    transition:all 0.25s ease;
}

.input-wrap:focus-within input,
.input-wrap:focus-within select{
    background:white;
    box-shadow:0 10px 30px rgba(59,130,246,0.2),0 0 0 2px rgba(59,130,246,0.35);
}

.btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:14px;
    background:linear-gradient(135deg,#2563eb,#3b82f6);
    color:white;
    font-weight:700;
    cursor:pointer;
    transition:0.25s;
    font-size:14px;
}

.btn:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(37,99,235,0.4);
}

.btn-back{
    display:flex;
    justify-content:center;
    align-items:center;
    gap:8px;
    margin-top:12px;
    padding:12px;
    border-radius:14px;
    text-decoration:none;
    font-weight:600;
    color:#2563eb;
    background:rgba(255,255,255,0.7);
    border:1px solid rgba(255,255,255,0.6);
    font-size:14px;
}

@media(max-width:900px){
    .wrapper{grid-template-columns:1fr;}
}

@media(max-width:768px){
    body{padding:0 0 28px 0;justify-content:flex-start;}
    .mobile-topbar{
        display:flex;align-items:center;justify-content:space-between;padding:14px 18px;
        background:rgba(255,255,255,.85);backdrop-filter:blur(20px);position:sticky;top:0;z-index:900;
        box-shadow:0 4px 20px rgba(15,23,42,.06);margin-bottom:18px;
    }
    .hamburger-btn{
        width:42px;height:42px;border-radius:12px;border:1px solid #dbeafe;background:#fff;
        display:flex;align-items:center;justify-content:center;cursor:pointer;
    }
    .hamburger-btn svg{width:22px;height:22px;stroke:#1e3a8a;stroke-width:2.4;}
    .mobile-topbar-logo{height:34px;width:auto;}
    .wrapper{padding:0 16px;gap:18px;}
    .left{display:none;}
    .card{padding:22px 18px;border-radius:20px;}
    .header-logo img{width:54px;height:54px;}
    .title{font-size:18px;}
    .subtitle{font-size:12px;}
}
</style>
</head>

<body>

<!-- TOPBAR & DRAWER MOBILE -->
<div class="mobile-topbar">
    <button class="hamburger-btn" onclick="toggleDrawer()" aria-label="Buka Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round">
            <line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/>
        </svg>
    </button>
    <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="mobile-topbar-logo">
</div>
<div id="sidebarOverlay" class="sidebar-overlay" onclick="closeDrawer()"></div>
<div id="mobileDrawer" class="mobile-drawer">
    <div class="drawer-header">
        <img src="{{ asset('logo/logo.png') }}" alt="Logo">
        <button class="close-drawer-btn" onclick="closeDrawer()">✕</button>
    </div>
    <div class="drawer-menu">
        <a href="/"><svg class="side-icon" viewBox="0 0 24 24" fill="none"><path d="M3 10.5L12 3l9 7.5"/><path d="M5 9.5V20h14V9.5"/></svg>Dashboard</a>
        <a href="/tambah"><svg class="side-icon" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>Tambah Agenda</a>
        <a href="{{ route('kegiatan.kalender') }}"><svg class="side-icon" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="16" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Kalender</a>
        <a href="{{ route('kegiatan.akan') }}"><svg class="side-icon" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>Akan Datang</a>
        <a href="{{ route('kegiatan.selesai') }}"><svg class="side-icon" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.2 2.2L16 9"/></svg>Selesai</a>
    </div>
</div>

<div class="wrapper">

<div class="left">
    <div class="left-icon">
        <i class="fa-solid fa-clipboard-list"></i>
    </div>
    <h2>TAMBAH KEGIATAN</h2>
    <p>Isi data kegiatan organisasi dengan lengkap untuk mencatat kegiatan baru.</p>
    <div class="feature">
        <i class="fa-solid fa-calendar"></i>
        <div>Catat kegiatan dengan mudah</div>
    </div>
    <div class="feature">
        <i class="fa-solid fa-location-dot"></i>
        <div>Kelola lokasi kegiatan</div>
    </div>
    <div class="feature">
        <i class="fa-solid fa-clock"></i>
        <div>Pantau jadwal kegiatan</div>
    </div>
</div>

<div class="card">

<div class="header">
    <div class="header-logo">
        <img src="{{ asset('logo/logo1.png') }}" alt="Logo">
    </div>
    <div>
        <div class="title">TAMBAH KEGIATAN</div>
        <div class="subtitle">Isi data kegiatan organisasi dengan lengkap</div>
    </div>
</div>

<form action="{{ route('kegiatan.store') }}" method="POST">
    @csrf

<div class="group">
<label>Nama Kegiatan</label>
<div class="input-wrap">
<i class="fa-solid fa-pen"></i>
<input type="text" name="nama_kegiatan" placeholder="Masukkan nama kegiatan" required>
</div>
</div>

<div class="group">
<label>Tanggal</label>
<div class="input-wrap">
<i class="fa-solid fa-calendar"></i>
<input type="date" name="tanggal" required>
</div>
</div>

<div class="group">
<label>Waktu</label>
<div class="input-wrap">
<i class="fa-solid fa-clock"></i>
<input type="time" name="waktu" required>
</div>
</div>

<div class="group">
<label>Lokasi</label>
<div class="input-wrap">
<i class="fa-solid fa-location-dot"></i>
<input type="text" name="lokasi" placeholder="Masukkan lokasi kegiatan" required>
</div>
</div>

<div class="group">
<label>Status</label>
<div class="input-wrap">
<i class="fa-solid fa-flag"></i>
<select name="status" required>
<option value="akan datang">Akan Datang</option>
<option value="selesai">Selesai</option>
</select>
</div>
</div>

<button type="submit" class="btn">
<i class="fa-solid fa-floppy-disk"></i>
Simpan Kegiatan
</button>

<a href="/" class="btn-back">
<i class="fa-solid fa-arrow-left"></i>
Kembali ke Dashboard
</a>

</form>

</div>

</div>

<script>
function toggleDrawer(){
    document.getElementById('mobileDrawer').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('active');
}
function closeDrawer(){
    document.getElementById('mobileDrawer').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('active');
}
</script>

</body>
</html>
