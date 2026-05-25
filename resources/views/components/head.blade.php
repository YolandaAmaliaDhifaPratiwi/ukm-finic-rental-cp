{{-- resources/views/components/head.blade.php --}}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

{{-- Try asset() first, fallback to root-relative path --}}
<link rel="stylesheet" href="{{ asset('css/app.css') }}" onerror="this.onerror=null;this.href='/css/app.css'">

<style>
/* CRITICAL INLINE CSS — fallback jika asset() gagal load */
:root{--orange:#16a34a;--orange-dark:#EA580C;--orange-light:#FED7AA;--blue:#064e3b;--blue-light:#DBEAFE;--dark:#111827;--gray-900:#111827;--gray-700:#374151;--gray-500:#6B7280;--gray-300:#D1D5DB;--gray-100:#F3F4F6;--white:#fff;--sidebar-w:220px;--radius:10px;--shadow:0 1px 4px rgba(0,0,0,.08);--shadow-md:0 4px 16px rgba(0,0,0,.12)}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',-apple-system,sans-serif;background:var(--gray-100);color:var(--gray-900);font-size:14px}
a{text-decoration:none;color:inherit}
h1{font-size:28px;font-weight:700}h2{font-size:22px;font-weight:700}h3{font-size:18px;font-weight:600}h4{font-size:15px;font-weight:600}
/* Buttons */
.btn{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;border:none;transition:all .2s;text-decoration:none}
.btn-primary{background:var(--orange);color:#fff}.btn-primary:hover{background:var(--orange-dark)}
.btn-outline{background:transparent;color:var(--gray-700);border:1.5px solid var(--gray-300)}.btn-outline:hover{border-color:var(--orange);color:var(--orange)}
.btn-blue{background:var(--blue);color:#fff}.btn-danger{background:#EF4444;color:#fff}
.btn-sm{padding:6px 14px;font-size:13px}.btn-full{width:100%;justify-content:center}
/* Form */
.form-group{margin-bottom:18px}
.form-label{display:block;font-size:13px;font-weight:500;margin-bottom:6px;color:var(--gray-700)}
.form-control{width:100%;padding:10px 14px;border:1.5px solid var(--gray-300);border-radius:8px;font-size:14px;background:#fff;transition:border-color .2s;outline:none}
.form-control:focus{border-color:var(--orange)}.form-control.with-icon{padding-left:40px}
.input-wrap{position:relative}.input-wrap .icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-500)}
.form-hint{color:var(--gray-500);font-size:11px;margin-top:4px}
/* Card */
.card{background:#fff;border-radius:var(--radius);padding:24px;box-shadow:var(--shadow)}.card-sm{padding:16px}
/* Badge */
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:12px;font-weight:600}
.badge-available{background:#D1FAE5;color:#065F46}.badge-borrowed{background:#FEE2E2;color:#991B1B}
.badge-maintenance{background:#FEF3C7;color:#92400E}.badge-pending{background:#FEF9C3;color:#854D0E}
.badge-approved{background:#D1FAE5;color:#065F46}.badge-rejected{background:#FEE2E2;color:#991B1B}
.badge-returned{background:#DBEAFE;color:#1E40AF}.badge-overdue{background:#FEE2E2;color:#991B1B}
.badge-camera{background:#EDE9FE;color:#5B21B6}.badge-lens{background:#FCE7F3;color:#9D174D}
.badge-tripod{background:#D1FAE5;color:#065F46}.badge-lighting{background:#FEF3C7;color:#92400E}
.badge-accessory{background:#F3F4F6;color:#374151}
/* Alert */
.alert{padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px;display:flex;align-items:flex-start;gap:10px}
.alert-success{background:#D1FAE5;color:#065F46;border-left:4px solid #10B981}
.alert-error{background:#FEE2E2;color:#991B1B;border-left:4px solid #EF4444}
.alert-warning{background:#FEF3C7;color:#92400E;border-left:4px solid #F59E0B}
/* Topnav */
.topnav{position:sticky;top:0;z-index:100;background:#fff;border-bottom:1px solid var(--gray-300);height:56px;display:flex;align-items:center;padding:0 24px;gap:24px}
.topnav-brand{display:flex;align-items:center;gap:8px;font-weight:700;font-size:16px;color:var(--blue)}
.topnav-links{display:flex;align-items:center;gap:4px;flex:1}
.topnav-link{padding:6px 14px;border-radius:6px;font-size:14px;color:var(--gray-500);font-weight:500;display:flex;align-items:center;gap:6px;transition:all .15s}
.topnav-link:hover,.topnav-link.active{background:var(--gray-100);color:var(--orange)}
.topnav-right{display:flex;align-items:center;gap:12px;margin-left:auto}
.topnav-user{display:flex;align-items:center;gap:8px}
.topnav-user .name{font-weight:600;font-size:13px}.topnav-user .role{font-size:11px;color:var(--gray-500)}
.avatar{width:36px;height:36px;border-radius:50%;background:var(--orange-light);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--orange-dark);font-size:14px;flex-shrink:0}
.bell-btn{position:relative;background:none;border:none;cursor:pointer;padding:6px;color:var(--gray-500)}
.bell-badge{position:absolute;top:2px;right:2px;background:#EF4444;color:#fff;border-radius:99px;font-size:10px;width:16px;height:16px;display:flex;align-items:center;justify-content:center;font-weight:700}
.logout-btn{background:none;border:none;cursor:pointer;padding:6px;color:var(--gray-500);display:flex;align-items:center}
.logout-btn:hover{color:#EF4444}
/* Sidebar */
.layout-sidebar{display:flex;min-height:calc(100vh - 56px)}
.sidebar{width:var(--sidebar-w);flex-shrink:0;background:#fff;border-right:1px solid var(--gray-300);padding:16px 12px;display:flex;flex-direction:column;position:sticky;top:56px;height:calc(100vh - 56px);overflow-y:auto}
.sidebar-link{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:8px;font-size:13px;color:var(--gray-700);font-weight:500;transition:all .15s;margin-bottom:2px;border:none;cursor:pointer;width:100%;text-align:left;background:none}
.sidebar-link:hover{background:var(--gray-100);color:var(--orange)}.sidebar-link.active{background:#FFF7ED;color:var(--orange);font-weight:600}
.sidebar-bottom{margin-top:auto;border-top:1px solid var(--gray-300);padding-top:12px}
.page-content{flex:1;padding:28px;min-width:0}
/* Stats */
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px}
.stat-card{background:#fff;border-radius:var(--radius);padding:20px;box-shadow:var(--shadow);display:flex;align-items:flex-start;justify-content:space-between}
.stat-card .label{font-size:13px;color:var(--gray-500);margin-bottom:6px}
.stat-card .value{font-size:30px;font-weight:700}
.stat-card .sub{font-size:11px;color:var(--gray-500);margin-top:4px}
.stat-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center}
.stat-icon-orange{background:#FFF7ED;color:var(--orange)}.stat-icon-blue{background:#DBEAFE;color:var(--blue)}
.stat-icon-green{background:#D1FAE5;color:#059669}.stat-icon-red{background:#FEE2E2;color:#EF4444}
/* Table */
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{text-align:left;font-size:12px;font-weight:600;color:var(--gray-500);text-transform:uppercase;letter-spacing:.5px;padding:10px 14px;border-bottom:1px solid var(--gray-300)}
td{padding:12px 14px;border-bottom:1px solid var(--gray-100);vertical-align:middle}
tr:last-child td{border-bottom:none}tr:hover td{background:#FAFAFA}
/* Equipment grid */
.equip-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px}
.equip-card{background:#fff;border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow);transition:transform .2s,box-shadow .2s}
.equip-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-md)}
.equip-img{width:100%;height:180px;background:var(--gray-100);position:relative}
.equip-img img{width:100%;height:100%;object-fit:contain;padding:16px}
.equip-status-badge{position:absolute;top:10px;right:10px}
.equip-body{padding:16px}
.equip-category{font-size:11px;font-weight:700;color:var(--orange);text-transform:uppercase;letter-spacing:.5px}
.equip-name{font-size:15px;font-weight:700;margin:4px 0}
.equip-condition{font-size:12px;color:var(--gray-500);margin-bottom:14px}
/* Welcome banner */
.welcome-banner{background:linear-gradient(135deg,#378a1e 0%,#064e3b 100%);border-radius:14px;padding:28px 32px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;color:#fff;position:relative;overflow:hidden}
.welcome-banner h2{font-size:22px;margin-bottom:6px}
.welcome-banner p{font-size:14px;opacity:.85;margin-bottom:16px}
.banner-actions{display:flex;gap:10px}
/* Modal */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:200;align-items:center;justify-content:center}
.modal-overlay.open{display:flex}
.modal{background:#fff;border-radius:14px;padding:28px;width:500px;max-width:95vw;max-height:90vh;overflow-y:auto}
.modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
.modal-close{background:none;border:none;font-size:22px;cursor:pointer;color:var(--gray-500)}
.modal-footer{display:flex;justify-content:flex-end;gap:10px;margin-top:24px}
/* Tabs */
.tabs{display:flex;gap:4px;background:var(--gray-100);border-radius:8px;padding:4px;margin-bottom:20px}
.tab-btn{padding:7px 16px;border-radius:6px;font-size:13px;font-weight:500;cursor:pointer;border:none;background:none;color:var(--gray-500);transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
.tab-btn.active{background:#fff;color:var(--orange);font-weight:600;box-shadow:var(--shadow)}
/* Search */
.search-bar{position:relative}
.search-bar input{padding-left:38px}
.search-bar svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--gray-500)}
/* Pagination */
.pagination{display:flex;align-items:center;gap:6px;margin-top:20px;justify-content:flex-end}
.page-btn{width:34px;height:34px;border-radius:6px;border:1.5px solid var(--gray-300);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:13px;font-weight:600;color:var(--gray-700);text-decoration:none}
.page-btn.active{background:var(--orange);color:#fff;border-color:var(--orange)}
/* Auth */
.auth-page{min-height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;background:#1a1a2e;background-size:cover;background-position:center;position:relative}
.auth-page::before{content:'';position:absolute;inset:0;background:rgba(0,0,0,.55)}
.auth-card{position:relative;z-index:1;background:#fff;border-radius:16px;padding:36px 40px;width:440px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.3)}
.auth-logo{text-align:center;margin-bottom:24px}
.auth-logo .brand{display:inline-flex;align-items:center;gap:10px;font-size:22px;font-weight:700;color:var(--orange)}
.auth-title{text-align:center;margin-bottom:6px}
.auth-sub{text-align:center;color:var(--gray-500);font-size:13px;margin-bottom:24px}
.auth-divider{display:flex;align-items:center;gap:12px;margin:16px 0;color:var(--gray-500);font-size:11px;letter-spacing:1px}
.auth-divider::before,.auth-divider::after{content:'';flex:1;height:1px;background:var(--gray-300)}
.auth-footer-links{display:flex;justify-content:center;gap:16px;margin-top:20px;font-size:12px;color:var(--gray-500)}
.auth-bottom{position:relative;z-index:1;text-align:center;margin-top:20px}
.auth-bottom a{color:#fff;font-size:13px}
.copyright-bar{background:#1a1a2e;padding:12px;text-align:center;font-size:12px;color:#9CA3AF}
/* Profile card */
.profile-card{background:linear-gradient(135deg,#378a1e,#064e3b);border-radius:12px;padding:20px;color:#fff;text-align:center}
.profile-stats{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:14px}
.profile-stat .val{font-size:18px;font-weight:700}.profile-stat .lbl{font-size:11px;opacity:.75}
/* Borrow */
.borrow-page{max-width:640px;margin:0 auto}
.equip-preview{display:flex;gap:16px;align-items:center;padding:16px;border:1.5px solid var(--gray-300);border-radius:10px;margin-bottom:24px}
.rental-summary{background:#FFF7ED;border:1.5px solid var(--orange-light);border-radius:8px;padding:12px 16px;font-size:13px;margin-top:12px;color:var(--orange-dark)}
.terms-notice{background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:12px 16px;font-size:12px;color:#7F1D1D;display:flex;gap:10px;align-items:flex-start}
.info-perks{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:24px}
.perk{text-align:center;font-size:12px;color:var(--gray-500)}
.perk h5{font-size:13px;font-weight:600;color:var(--gray-700);margin:6px 0 4px}
/* Analytics */
.chart-card{background:#fff;border-radius:var(--radius);padding:24px;box-shadow:var(--shadow)}
.chart-legend{display:flex;gap:16px;align-items:center}
.legend-dot{width:10px;height:10px;border-radius:50%}
.legend-dot-blue{background:var(--blue)}.legend-dot-orange{background:var(--orange)}
.donut-list{margin-top:16px}
.donut-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);font-size:13px}
.donut-row:last-child{border-bottom:none}
.donut-dot{display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:8px}
.activity-item{display:flex;gap:12px;align-items:flex-start;padding:10px 0;border-bottom:1px solid var(--gray-100)}
.activity-item:last-child{border-bottom:none}
.activity-badge{font-size:10px;font-weight:700;padding:2px 8px;border-radius:99px;text-transform:uppercase}
.activity-borrow{background:#DBEAFE;color:var(--blue)}.activity-return{background:#D1FAE5;color:#059669}
.activity-request{background:#FEF3C7;color:#92400E}.activity-overdue{background:#FEE2E2;color:#991B1B}
.activity-pending{background:#FEF9C3;color:#854D0E}.activity-approved{background:#D1FAE5;color:#059669}
.trending-item{display:flex;gap:12px;align-items:center;padding:10px 0;border-bottom:1px solid var(--gray-100)}
.trending-item:last-child{border-bottom:none}
.trending-img{width:44px;height:44px;border-radius:8px;background:var(--gray-100);overflow:hidden;flex-shrink:0}
.trending-item .count{margin-left:auto;text-align:right}
.trending-item .count .num{font-size:16px;font-weight:700}
.trending-item .count .lbl{font-size:10px;color:var(--gray-500);text-transform:uppercase}
/* Notif */
.notif-panel{position:absolute;top:52px;right:0;width:340px;background:#fff;border-radius:12px;box-shadow:var(--shadow-md);border:1px solid var(--gray-300);z-index:200;display:none}
.notif-panel.open{display:block}
.notif-header{padding:16px 16px 10px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--gray-100)}
.notif-item{padding:12px 16px;border-bottom:1px solid var(--gray-100);font-size:13px;cursor:pointer}
.notif-item:hover{background:var(--gray-100)}.notif-item:last-child{border-bottom:none}
.notif-item .title{font-weight:600}.notif-item .sub{color:var(--gray-500);font-size:12px;margin-top:2px}
.notif-item .time{font-size:11px;color:var(--gray-500);margin-top:4px}
.notif-action{color:var(--orange);font-size:12px;font-weight:600}
.notif-footer{padding:10px 16px;text-align:center}
/* History stats */
.history-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:24px}
.history-stat{background:#fff;border-radius:var(--radius);padding:18px;box-shadow:var(--shadow);display:flex;align-items:center;gap:14px}
.history-stat .icon{width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.history-stat .val{font-size:20px;font-weight:700}.history-stat .lbl{font-size:12px;color:var(--gray-500);text-transform:uppercase;letter-spacing:.5px}
/* Hero / Landing */
.hero{min-height:100vh;background:#0a0a0a;position:relative;display:flex;flex-direction:column}
.hero-nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;justify-content:space-between;padding:14px 48px;background:rgba(0,0,0,.4);backdrop-filter:blur(8px)}
.hero-body{flex:1;display:flex;align-items:center;justify-content:center;text-align:center;padding:100px 24px 60px;background:#0a0a1a;min-height:80vh;position:relative}
.hero-body::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(29,78,216,.3),rgba(0,0,0,.6))}
.hero-content{position:relative;z-index:1;color:#fff;max-width:700px}
.hero-tag{display:inline-block;background:var(--orange);color:#fff;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;padding:4px 12px;border-radius:99px;margin-bottom:16px}
.hero-title{font-size:52px;font-weight:800;line-height:1.15;margin-bottom:16px}
.hero-title span{color:var(--orange)}.hero-sub{font-size:18px;opacity:.85;margin-bottom:32px}
.hero-stats{display:flex;justify-content:center;gap:48px;padding:28px 48px;background:#fff}
.hero-stat .num{font-size:24px;font-weight:800;color:var(--orange)}
.hero-stat .lbl{font-size:12px;color:var(--gray-500);margin-top:2px}
.features{padding:64px 48px;background:#fff}
.features-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:32px;margin-top:40px}
.feature-item{text-align:center}
.feature-icon{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px}
.catalog-section{padding:64px 48px;background:var(--gray-100)}
.catalog-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:32px}
.catalog-cat{border-radius:12px;overflow:hidden;position:relative;height:160px;cursor:pointer;display:flex;align-items:center;justify-content:center}
.catalog-cat-label{position:absolute;bottom:0;left:0;right:0;padding:12px;background:linear-gradient(to top,rgba(0,0,0,.8),transparent);color:#fff;font-weight:700;font-size:14px}
.steps-section{padding:64px 48px;background:#fff}
.steps-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:32px;margin-top:40px;text-align:center}
.step-num{width:48px;height:48px;border-radius:50%;border:2px solid var(--gray-300);display:flex;align-items:center;justify-content:center;font-size:20px;margin:0 auto 14px}
.cta-section{background:var(--blue);color:#fff;padding:64px;text-align:center}
.cta-section h2{font-size:30px;margin-bottom:10px}.cta-section p{opacity:.85;margin-bottom:28px}
.cta-btns{display:flex;gap:12px;justify-content:center}
.landing-footer{background:var(--gray-900);color:#fff;padding:48px}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;margin-bottom:32px}
.footer-brand p{font-size:13px;color:#9CA3AF;margin-top:10px}
.footer-col h4{font-size:14px;font-weight:700;margin-bottom:14px}
.footer-col a{display:block;color:#9CA3AF;font-size:13px;margin-bottom:8px}
.footer-col a:hover{color:#fff}
.footer-bottom{border-top:1px solid #374151;padding-top:24px;display:flex;justify-content:space-between;color:#6B7280;font-size:12px}
/* Utility */
.flex{display:flex}.flex-center{display:flex;align-items:center}.flex-between{display:flex;align-items:center;justify-content:space-between}
.gap-8{gap:8px}.gap-12{gap:12px}.gap-16{gap:16px}.gap-24{gap:24px}
.mb-4{margin-bottom:4px}.mb-8{margin-bottom:8px}.mb-16{margin-bottom:16px}.mb-24{margin-bottom:24px}.mt-16{margin-top:16px}.mt-4{margin-top:4px}
.text-sm{font-size:12px}.text-gray{color:var(--gray-500)}.text-orange{color:var(--orange)}.text-blue{color:var(--blue)}.text-red{color:#EF4444}
.font-bold{font-weight:700}.text-right{text-align:right}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:16px}.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
.section-title{font-size:16px;font-weight:700;display:flex;align-items:center;gap:8px}
.new-badge{background:#EF4444;color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:99px}
.analytics-header{margin-bottom:24px}
.analytics-header p{color:var(--gray-500);margin-top:4px}
@media(max-width:768px){.sidebar{display:none}.stats-grid{grid-template-columns:1fr 1fr}.equip-grid{grid-template-columns:1fr 1fr}.hero-title{font-size:32px}.catalog-grid,.footer-grid{grid-template-columns:1fr 1fr}.steps-grid{grid-template-columns:1fr}.page-content{padding:16px}}
</style>
