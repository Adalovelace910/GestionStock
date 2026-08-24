<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>@yield('title','GestionStock')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
html,body{margin:0;padding:0;overflow-x:hidden;height:100%;overscroll-behavior:none}
html{background:#fff}
body{display:flex;flex-direction:column;min-height:100vh}
#app-shell{display:flex;flex-direction:column;flex:1 0 auto;width:100%;min-height:100vh}
#app-shell>.row{flex:1 0 auto;margin:0}
:root{--bs-primary:#198754;--bs-primary-rgb:25,135,84}
.bg-primary{background-color:#198754!important}
.text-primary{color:#198754!important}
.border-primary{border-color:#198754!important}
.btn-primary{background-color:#198754!important;border-color:#198754!important}
.btn-primary:hover,.btn-primary:focus,.btn-primary:active{background-color:#157347!important;border-color:#146c43!important}
.btn-outline-primary{color:#198754!important;border-color:#198754!important}
.btn-outline-primary:hover,.btn-outline-primary:focus,.btn-outline-primary:active{background-color:#198754!important;border-color:#198754!important;color:#fff!important}
.badge.bg-primary{background-color:#198754!important}
.nav-link.bg-primary{background-color:#198754!important}
</style>
</head>
<body class="bg-light" style="font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif">
<div id="app-shell" class="container-fluid px-0">
<div class="row g-0">
@include('partials.sidebar')
<div class="col-md-9 col-lg-10 p-0">
@include('partials.topbar')
<main class="p-4">
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
{{ session('success') }}
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
{{ session('error') }}
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
</div>
@endif
@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
{{ session('warning') }}
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
</div>
@endif
@if(session('info'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
{{ session('info') }}
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
</div>
@endif
@yield('content')
</main>
</div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
@stack('scripts')
</body>
</html>