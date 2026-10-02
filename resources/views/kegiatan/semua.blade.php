<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" href="{{ asset('logo/logo1.png') }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DAFTAR SEMUA</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}

body{
background:
radial-gradient(circle at 20% 20%, rgba(96,165,250,0.35), transparent 35%),
radial-gradient(circle at 80% 10%, rgba(59,130,246,0.28), transparent 35%),
linear-gradient(135deg,#e0f2fe,#f8fafc);
padding:40px;
color:#1e293b;
min-height:100vh;
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

.container{max-width:1100px;margin:auto;}

.header{
display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:20px;flex-wrap:wrap;
}
.header-left{display:flex;align-items:center;gap:16px;}

.logo-img{
width:80px;height:80px;object-fit:contain;
filter:drop-shadow(0 6px 12px rgba(37,99,235,0.3));
}

.title{
font-size:30px;font-weight:800;
background:linear-gradient(135deg,#1d4ed8,#38bdf8);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
line-height:1.2;
}

.subtitle{font-size:14px;color:#64748b;}

.add-btn{
background:linear-gradient(135deg,#2563eb,#3b82f6);
color:#fff;
padding:14px 24px;
border-radius:18px;
font-weight:700;
text-decoration:none;
box-shadow:0 12px 30px rgba(37,99,235,0.35);
transition:all 0.3s ease;
display:inline-flex;
align-items:center;
justify-content:center;
}

.search{
width:100%;
padding:14px 18px 14px 45px;
border-radius:18px;
border:none;
outline:none;
background:rgba(255,255,255,0.75);
backdrop-filter:blur(12px);
box-shadow:0 0 0 1px #e2e8f0,0 8px 25px rgba(0,0,0,0.05);
background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E");
background-repeat:no-repeat;
background-position:14px center;
background-size:18px;
}

.month-title{
font-size:20px;font-weight:800;
margin:25px 0 10px;color:#2563eb;
}

.card{
display:flex;justify-content:space-between;align-items:center;gap:14px;
padding:20px;border-radius:20px;
background:rgba(255,255,255,0.75);
backdrop-filter:blur(16px);
box-shadow:0 12px 25px rgba(0,0,0,0.08);
margin-bottom:12px;
transition:.3s;
}

.card-title{font-weight:800;}
.card-info{font-size:13px;color:#64748b;margin-top:4px;}

.right{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.actions{display:flex;gap:6px;}

.btn{
padding:8px 14px;border-radius:12px;border:none;
cursor:pointer;font-weight:600;
box-shadow:0 6px 15px rgba(0,0,0,0.1);
transition:.25s;
}

.edit{background:linear-gradient(135deg,#3b82f6,#60a5fa);color:white;}
.delete{background:linear-gradient(135deg,#ef4444,#f87171);color:white;}

.badge{
display:flex;align-items:center;gap:6px;
padding:8px 14px;border-radius:12px;
font-size:12px;font-weight:700;color:white;
}

.selesai{background:linear-gradient(135deg,#22c55e,#4ade80);}
.akan{background:linear-gradient(135deg,#f59e0b,#fbbf24);}

.pagination{display:flex;justify-content:center;gap:8px;margin-top:20px;flex-wrap:wrap;}
.page-btn,.nav-btn{
padding:8px 12px;border-radius:10px;background:white;
cursor:pointer;box-shadow:0 5px 12px rgba(0,0,0,0.1);
}
.page-btn.active{background:#3b82f6;color:white;}

.popup{
position:fixed;inset:0;
background:rgba(15,23,42,0.45);
backdrop-filter:blur(6px);
display:flex;justify-content:center;align-items:center;
opacity:0;pointer-events:none;transition:.3s;
z-index:1000;padding:16px;
}

.popup.active{opacity:1;pointer-events:auto;}

.popup-box{
background:rgba(255,255,255,0.92);
backdrop-filter:blur(18px);
padding:24px;border-radius:20px;width:100%;max-width:360px;
box-shadow:0 20px 50px rgba(0,0,0,0.2);
}

.popup-title{font-weight:800;margin-bottom:10px;}

.popup-box input{
width:100%;padding:12px;margin-top:10px;
border-radius:12px;border:none;background:#f1f5f9;
}

.popup-actions{
display:flex;justify-content:flex-end;gap:10px;margin-top:15px;
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
.add-btn{width:100%;padding:12px;}
.card{flex-direction:column;align-items:flex-start;padding:15px;}
.right{width:100%;justify-content:space-between;padding-top:8px;border-top:1px solid rgba(226,232,240,.8);}
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

<div class="header">
<div class="header-left">
<img src="{{ asset('logo/logo1.png') }}" class="logo-img">
<div>
<div class="title">DAFTAR SEMUA KEGIATAN</div>
<div class="subtitle">Kelola kegiatan organisasi</div>
</div>
</div>

<a href="{{ route('kegiatan.create') }}" class="add-btn">+ Tambah Kegiatan</a>
</div>

<input type="text" id="searchInput" class="search" placeholder="Cari kegiatan...">

@php
$grouped = collect($data)->filter(fn($items)=>$items->count()>0)->values();
@endphp

<div id="monthContainer">
@foreach($grouped as$items)
<div class="month-group">
<div class="month-title">
{{ \Carbon\Carbon::parse($items[0]->tanggal)->translatedFormat('F Y') }}
</div>

@foreach($items as$item)
<div class="card data-item">
<div>
<div class="card-title">{{ $item->nama_kegiatan }}</div>
<div class="card-info">
{{ $item->tanggal }} • {{ $item->waktu }} • {{$item->lokasi }}
</div>
</div>

<div class="right">
<div class="badge {{ $item->status=='selesai'?'selesai':'akan' }}">
{{ ucfirst($item->status) }}
</div>

<div class="actions">
<button class="btn edit editBtn"
data-id="{{ $item->id }}"
data-nama="{{ $item->nama_kegiatan }}"
data-tanggal="{{ $item->tanggal }}"
data-waktu="{{ $item->waktu }}"
data-lokasi="{{ $item->lokasi }}">
Edit
</button>

<form action="{{ route('kegiatan.destroy', $item->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="button" class="btn delete deleteBtn">Delete</button>
</form>
</div>
</div>
</div>
@endforeach

</div>
@endforeach
</div>

<div class="pagination" id="pagination"></div>

</div>

<!-- EDIT POPUP -->
<div class="popup" id="popupEdit">
<div class="popup-box">
<div class="popup-title">Edit Kegiatan</div>
<form id="editForm" method="POST">
@csrf
@method('PUT')
<input type="text" name="nama_kegiatan" id="editNama">
<input type="date" name="tanggal" id="editTanggal">
<input type="time" name="waktu" id="editWaktu">
<input type="text" name="lokasi" id="editLokasi">
<div class="popup-actions">
<button type="button" onclick="closeEdit()" class="btn">Batal</button>
<button type="submit" class="btn edit">Simpan</button>
</div>
</form>
</div>
</div>

<!-- DELETE POPUP -->
<div class="popup" id="popupDelete">
<div class="popup-box">
<div class="popup-title">⚠️ Hapus Kegiatan</div>
<p id="deleteText"></p>
<div class="popup-actions">
<button onclick="closeDelete()" class="btn">Batal</button>
<button id="confirmDelete" class="btn delete">Hapus</button>
</div>
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

searchInput.onkeyup=function(){
let keyword=this.value.toLowerCase();
document.querySelectorAll('.data-item').forEach(card=>{
card.style.display=card.innerText.toLowerCase().includes(keyword)?'flex':'none';
});
};

const months=document.querySelectorAll('.month-group');
let currentPage=1;
const perPage=2;

function showPage(page){
currentPage=page;
months.forEach((m,i)=>{
m.style.display=(i>= (page-1)*perPage && i<page*perPage)?'block':'none';
});
renderPagination();
}

function renderPagination(){
pagination.innerHTML="";
let totalPages=Math.ceil(months.length/perPage);

let prev=document.createElement('div');
prev.innerHTML="‹";
prev.className="nav-btn";
prev.onclick=()=>currentPage>1&&showPage(currentPage-1);
pagination.appendChild(prev);

for(let i=Math.max(1,currentPage-1);i<=Math.min(totalPages,currentPage+1);i++){
let btn=document.createElement('div');
btn.innerText=i;
btn.className="page-btn";
if(i===currentPage)btn.classList.add('active');
btn.onclick=()=>showPage(i);
pagination.appendChild(btn);
}

let next=document.createElement('div');
next.innerHTML="›";
next.className="nav-btn";
next.onclick=()=>currentPage<totalPages&&showPage(currentPage+1);
pagination.appendChild(next);
}

showPage(1);

document.querySelectorAll('.editBtn').forEach(btn=>{
btn.onclick=function(){
editNama.value=this.dataset.nama;
editTanggal.value=this.dataset.tanggal;
editWaktu.value=this.dataset.waktu;
editLokasi.value=this.dataset.lokasi;
editForm.action="/update/"+this.dataset.id;
popupEdit.classList.add('active');
}
});

function closeEdit(){
popupEdit.classList.remove('active');
}

let selectedForm;
document.querySelectorAll('.deleteBtn').forEach(btn=>{
btn.onclick=function(){
selectedForm=this.closest('form');
let nama=this.closest('.card').querySelector('.card-title').innerText;
deleteText.innerText=`Kegiatan "${nama}" akan dihapus.`;
popupDelete.classList.add('active');
}
});

confirmDelete.onclick=()=>selectedForm.submit();

function closeDelete(){
popupDelete.classList.remove('active');
}
</script>

</body>
</html>
