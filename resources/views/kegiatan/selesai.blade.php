<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" href="{{ asset('logo/logo1.png') }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AGENDA SELESAI</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:
radial-gradient(circle at 20% 20%, rgba(96,165,250,0.35), transparent 35%),
radial-gradient(circle at 80% 10%, rgba(59,130,246,0.28), transparent 35%),
linear-gradient(135deg,#e0f2fe,#f8fafc);
min-height:100vh;
padding:35px;
color:#1e293b;
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
max-width:980px;
margin:auto;
}

.header{
display:flex;
align-items:center;
gap:15px;
margin-bottom:20px;
}

.header img{
width:80px;
height:80px;
object-fit:contain;
}

.title{
font-size:34px;
font-weight:800;
background:linear-gradient(135deg,#2563eb,#38bdf8);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
line-height:1.2;
}

.subtitle{
color:#64748b;
font-size:14px;
margin-top:5px;
}

.filter-bar{
display:flex;
gap:15px;
margin:20px 0;
}

.search{
flex:1;
padding:14px 16px 14px 45px;
border-radius:16px;
border:none;
outline:none;
background:rgba(255,255,255,0.75);
backdrop-filter:blur(10px);
box-shadow:0 0 0 1px #e2e8f0;
transition:all 0.25s ease;
background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E");
background-repeat:no-repeat;
background-position:14px center;
background-size:18px;
}

select{
padding:12px 16px;
border-radius:14px;
border:none;
outline:none;
background:rgba(255,255,255,0.85);
box-shadow:0 0 0 1px #e2e8f0;
cursor:pointer;
font-weight:600;
}

.month{
margin-bottom:25px;
}

.month-title{
font-size:22px;
font-weight:800;
color:#2563eb;
margin-bottom:10px;
}

.card{
background:rgba(255,255,255,0.75);
backdrop-filter:blur(14px);
padding:18px;
border-radius:18px;
margin-bottom:12px;
display:flex;
justify-content:space-between;
align-items:center;
gap:12px;
transition:0.25s;
}

.card-title{
font-weight:800;
font-size:16px;
color:#0f172a;
}

.card-info{
font-size:13px;
color:#64748b;
margin-top:4px;
font-weight:500;
}

.status{
padding:8px 16px;
border-radius:999px;
font-size:12px;
font-weight:700;
color:white;
white-space:nowrap;
flex-shrink:0;
background:linear-gradient(135deg,#22c55e,#4ade80);
box-shadow:0 8px 18px rgba(34,197,94,0.35);
}

.back{
display:inline-flex;
justify-content:center;
align-items:center;
margin-top:25px;
padding:14px 22px;
border-radius:16px;
font-weight:700;
background:linear-gradient(135deg,#3b82f6,#60a5fa);
color:white;
text-decoration:none;
}

.no-data-box{
display:none;
text-align:center;
padding:35px 20px;
margin:30px 0;
border-radius:22px;
background:rgba(255,255,255,0.65);
}

.no-data-title{
font-size:16px;
font-weight:700;
color:#1e293b;
margin-bottom:6px;
}

.no-data-desc{
font-size:13px;
color:#64748b;
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
.header img{width:56px;height:56px;}
.title{font-size:22px;}
.subtitle{font-size:12px;}
.filter-bar{flex-direction:column;gap:10px;}
.search,select{width:100%;font-size:13px;}
.card{padding:14px;}
.card-title{font-size:14px;}
.card-info{font-size:12px;}
.status{padding:6px 12px;font-size:11px;}
.back{width:100%;}
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
<img src="{{ asset('logo/logo1.png') }}">
<div>
<div class="title">AGENDA SELESAI</div>
<div class="subtitle">Kegiatan yang telah dilaksanakan</div>
</div>
</div>

<div class="filter-bar">
<input type="text" id="searchInput" class="search" placeholder="Cari kegiatan...">
<select id="filterBulan" class="filter">
<option value="all">Semua Bulan</option>
<option value="01">January</option>
<option value="02">February</option>
<option value="03">March</option>
<option value="04">April</option>
<option value="05">May</option>
<option value="06">June</option>
<option value="07">July</option>
<option value="08">August</option>
<option value="09">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
</div>

<div id="listContainer">
@forelse($data as $bulan => $items)
<div class="month">
    <div class="month-title">{{ $bulan }}</div>
    @foreach($items as $item)
    <div class="card" data-bulan="{{ \Carbon\Carbon::parse($item->tanggal)->format('m') }}">
        <div class="card-left">
            <div>
                <div class="card-title">{{ $item->nama_kegiatan }}</div>
                <div class="card-info">
                    {{ $item->tanggal }} • {{ $item->waktu }} • {{ $item->lokasi }}
                </div>
            </div>
        </div>
        <div class="status">Selesai</div>
    </div>
    @endforeach
</div>
@empty
<p>Tidak ada data</p>
@endforelse

<div id="noData" class="no-data-box">
    <div class="no-data-title">Tidak ada agenda di bulan ini</div>
    <div class="no-data-desc">Coba pilih bulan lain atau tambahkan kegiatan baru</div>
</div>

<a href="/" class="back">← Kembali ke Dashboard</a>
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

const searchInput = document.getElementById("searchInput");
const filterBulan = document.getElementById("filterBulan");

function filterData(){
    let keyword = searchInput.value.toLowerCase();
    let bulan = filterBulan.value;
    let visibleCount = 0;

    document.querySelectorAll(".month").forEach(month=>{
        let cards = month.querySelectorAll(".card");
        let monthVisible = 0;

        cards.forEach(card=>{
            let text = card.innerText.toLowerCase();
            let cardBulan = card.dataset.bulan;
            let cocokSearch = text.includes(keyword);
            let cocokBulan = (bulan === "all" || Number(bulan) === Number(cardBulan));

            if(cocokSearch && cocokBulan){
                card.style.display = "flex";
                monthVisible++;
                visibleCount++;
            }else{
                card.style.display = "none";
            }
        });

        month.style.display = monthVisible > 0 ? "block" : "none";
    });

    let noData = document.getElementById("noData");
    if(noData){
        noData.style.display = visibleCount === 0 ? "block" : "none";
    }
}

searchInput.addEventListener("keyup", filterData);
filterBulan.addEventListener("change", filterData);
filterData();
</script>

</body>
</html>
