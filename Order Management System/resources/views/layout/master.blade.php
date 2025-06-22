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
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    {{-- BootStrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    {{-- Yajra Datatable  --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layout.navBar')
        <!-- Main Sidebar Included -->
        @include('layout.sideBar')
        <div class="content-wrapper bg-white">
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
            <!-- /Main Content -->
        </div>
    </div>
    {{-- JQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- Yajra DataTable --}}
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    @stack('scripts')
    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Parsley JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
   {{-- Custom JS --}}
    <script src="/js/rider/rider.js"></script>
    <script src="/js/customer/customer.js"></script>
    <script src="/js/order/order.js"></script>
    {{-- Toastr --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @if (session('message'))
        <script>
            toastr.options.closeButton = true;
            toastr.success("{{ session('message') }}");
        </script>
    @endif
</body>

</html>
