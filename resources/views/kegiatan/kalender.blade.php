<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" href="{{ asset('logo/logo1.png') }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KALENDER KEGIATAN</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:linear-gradient(135deg,#e0f2fe,#f8fafc);
min-height:100vh;
padding:30px;
overflow-x:hidden;
}

/* MOBILE TOPBAR & HAMBURGER DRAWER */
.mobile-topbar{display:none;}
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

.container{
max-width:1100px;
margin:auto;
}

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
gap:20px;
margin-bottom:25px;
flex-wrap:wrap;
}

.header-left{
display:flex;
align-items:center;
gap:12px;
}

.logo-img{
width:80px;
height:80px;
object-fit:contain;
filter:drop-shadow(0 6px 12px rgba(37,99,235,0.3));
}

.title{
font-size:28px;
font-weight:800;
background:linear-gradient(135deg,#3b82f6,#2563eb);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

.subtitle{
color:#64748b;
font-size:14px;
margin-top:4px;
}

.nav-month{
display:flex;
align-items:center;
gap:10px;
flex-wrap:wrap;
}

.month-name{
font-weight:800;
color:#1e293b;
min-width:150px;
text-align:center;
font-size:16px;
}

.btn,
.small-btn{
position:relative;
overflow:hidden;
text-decoration:none;
cursor:pointer;
display:inline-flex;
align-items:center;
justify-content:center;
white-space:nowrap;
transition:all .28s ease;
}

.btn{
padding:12px 18px;
border-radius:14px;
background:linear-gradient(135deg,#3b82f6,#60a5fa);
color:#fff;
font-weight:700;
font-size:14px;
box-shadow:0 10px 20px rgba(59,130,246,.18);
}

.small-btn{
padding:10px 14px;
border-radius:12px;
background:#fff;
color:#2563eb;
font-weight:700;
font-size:13px;
box-shadow:0 0 0 1px #dbeafe;
}

.card{
background:rgba(255,255,255,.75);
backdrop-filter:blur(18px);
border-radius:22px;
padding:25px;
box-shadow:0 15px 35px rgba(0,0,0,.08);
}

.calendar{
display:grid;
grid-template-columns:repeat(7,1fr);
gap:12px;
}

.day-name{
text-align:center;
font-weight:700;
color:#334155;
padding:10px 0;
}

.box{
min-height:130px;
background:#fff;
border-radius:16px;
padding:10px;
box-shadow:0 0 0 1px #e5e7eb;
transition:.25s;
}

.empty{
background:transparent;
box-shadow:none;
}

.number{
font-size:15px;
font-weight:800;
color:#1e293b;
margin-bottom:8px;
}

.today{
box-shadow:0 0 0 2px rgba(59,130,246,.5);
}

.clickable{cursor:pointer;}
.clickable:hover{
transform:translateY(-4px);
box-shadow:0 15px 20px rgba(0,0,0,.08);
}

.event{
background:#dbeafe;
color:#1d4ed8;
padding:6px 8px;
border-radius:10px;
font-size:12px;
margin-bottom:6px;
font-weight:600;
}

.time{
display:block;
font-size:11px;
opacity:.8;
margin-top:3px;
}

/* MODAL */
.modal{
position:fixed;
inset:0;
background:rgba(0,0,0,.45);
display:none;
justify-content:center;
align-items:center;
z-index:1000;
padding:16px;
}

.modal-box{
width:100%;
max-width:520px;
background:#fff;
border-radius:22px;
padding:25px;
}

.modal-header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.modal-header h2{
font-size:18px;
color:#1e293b;
}

.modal-header button{
border:none;
background:#ef4444;
color:#fff;
width:36px;
height:36px;
border-radius:50%;
cursor:pointer;
}

.item{
background:#f8fafc;
padding:14px;
border-radius:14px;
margin-bottom:12px;
}

.item-title{
font-weight:700;
color:#2563eb;
margin-bottom:6px;
}

.item small{
display:block;
color:#64748b;
margin-top:4px;
}

@media(max-width:900px){
.calendar{grid-template-columns:repeat(2,1fr);}
.day-name,.empty{display:none;}
}

@media(max-width:768px){
body{padding:0 0 30px 0;}
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
.container{padding:0 16px;}
.logo-img{width:56px;height:56px;}
.title{font-size:21px;}
.subtitle{font-size:12px;}
.nav-month{
display:grid;
grid-template-columns:1fr 1fr;
width:100%;
gap:8px;
}
.month-name{
grid-column:1 / -1;
order:-1;
background:#fff;
padding:10px;
border-radius:12px;
box-shadow:0 2px 8px rgba(0,0,0,.04);
}
.nav-month .btn{grid-column:1 / -1;}
.card{padding:16px;border-radius:18px;}
.calendar{grid-template-columns:repeat(2,1fr);gap:10px;}
.box{min-height:95px;padding:10px;}
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

<div class="container">

<div class="topbar">
<div class="header-left">
<img src="{{ asset('logo/logo1.png') }}" class="logo-img">
<div>
<div class="title">KALENDER KEGIATAN</div>
<div class="subtitle">Semua kegiatan organisasi berdasarkan tanggal</div>
</div>
</div>

<div class="nav-month">
<a href="{{ url('/kalender?bulan='.$prevMonth.'&tahun='.$prevYear) }}" class="small-btn">← Sebelumnya</a>
<div class="month-name">{{ $namaBulan }} {{$tahun }}</div>
<a href="{{ url('/kalender?bulan='.$nextMonth.'&tahun='.$nextYear) }}" class="small-btn">Berikutnya →</a>
<a href="/" class="btn">Kembali Dashboard</a>
</div>
</div>

<div class="card">
<div class="calendar">

<div class="day-name">Sen</div>
<div class="day-name">Sel</div>
<div class="day-name">Rab</div>
<div class="day-name">Kam</div>
<div class="day-name">Jum</div>
<div class="day-name">Sab</div>
<div class="day-name">Min</div>

@for($i=1; $i < $startDay; $i++)
<div class="box empty"></div>
@endfor

@for($tgl=1; $tgl <= $jumlahHari; $tgl++)
@php
$fullDate = $tahun.'-'.str_pad($bulan,2,'0',STR_PAD_LEFT).'-'.str_pad($tgl,2,'0',STR_PAD_LEFT);$list = $events[$fullDate] ?? [];
$isToday =$fullDate == date('Y-m-d');
@endphp

<div class="box {{ $isToday ? 'today' : '' }} {{ count($list) ? 'clickable' : '' }}"
@if(count($list)) onclick="showEvent('{{ $fullDate }}')" @endif>

<div class="number">{{ $tgl }}</div>

@foreach($list as$item)
<div class="event">
{{ $item->nama_kegiatan }}
<span class="time">{{ $item->waktu }}</span>
</div>
@endforeach

</div>
@endfor

</div>
</div>

</div>

<!-- MODAL -->
<div class="modal" id="eventModal">
<div class="modal-box">
<div class="modal-header">
<h2>Agenda Tanggal <span id="modalDate"></span></h2>
<button onclick="closeModal()">✕</button>
</div>
<div id="modalContent"></div>
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

const agendaData = {
@foreach($events as $tanggal =>$items)
"{{ $tanggal }}":[
@foreach($items as$item)
{
nama:"{{ $item->nama_kegiatan }}",
waktu:"{{ $item->waktu }}",
lokasi:"{{ $item->lokasi }}",
status:"{{ $item->status }}"
},
@endforeach
],
@endforeach
};

function showEvent(date){
if(!agendaData[date]) return;
document.getElementById('eventModal').style.display='flex';
document.getElementById('modalDate').innerText=date;
let html='';
agendaData[date].forEach(item=>{
html += `
<div class="item">
<div class="item-title">${item.nama}</div>
<small>🕒 ${item.waktu}</small>
<small>📍 ${item.lokasi}</small>
<small>📌 ${item.status}</small>
</div>
`;
});
document.getElementById('modalContent').innerHTML=html;
}

function closeModal(){
document.getElementById('eventModal').style.display='none';
}

window.onclick=function(e){
const modal=document.getElementById('eventModal');
if(e.target===modal){
closeModal();
}
}
</script>

</body>
</html>
