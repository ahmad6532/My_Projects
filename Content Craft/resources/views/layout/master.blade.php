<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    {{-- AdminLte --}}
    <link rel="stylesheet" href="/theme/adminlte.min.css">
    {{-- Stripe Payment --}}
    {{-- <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" /> --}}
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    {{-- BootStrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    {{-- Yajra Datatable  --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    {{-- Custome CSS  --}}
    <link rel="stylesheet" href="/css/style.css">

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layout.navBar')
        <!-- Main Sidebar Included -->
        @include('layout.sideBar')
        <!-- Article Modal Included -->
        @include('article.modal.addModal')
        @include('article.modal.deleteModal')
        @include('article.modal.editModal')
        @include('article.modal.viewModal')
        <!-- User Modal Included -->
        @include('user.modal.deleteUser')
        <div class="content-wrapper bg-white">
            <!-- Article Modals -->
            @include('article.modal.addModal')
            @include('article.modal.viewModal')
            @include('article.modal.deleteModal')
            @include('article.modal.editModal')
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Content will be appear here -->
                            @yield('content')
                        </div>
                    </div>
                </div>
            </section>
            <!-- User Modal -->
            @include('user.modal.deleteUser')
            <!-- Main Content End -->
        </div>
    </div>
    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- Stripe JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://js.stripe.com/v2/"></script>
    {{-- Firebase --}}
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
    {{-- Yajra DataTable --}}
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    @stack('scripts')
    {{-- Chart JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Parsley JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    {{-- Toastr --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- Custome JS --}}
    <script src="/js/script.js"></script>

    @if (session('message'))
        <script>
            toastr.options.closeButton = true;
            toastr.success("{{ session('message') }}");
        </script>
    @endif


</body>

</html>
