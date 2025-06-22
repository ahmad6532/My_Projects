<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="{{ asset('v2/fonts/LitteraText/stylesheet.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('v2/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('v2/css/my-profile.css') }}">
    <link rel="stylesheet" href="{{ asset('v2/css/colors.css') }}">
    <link href="{{ asset('admin_assets/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin_assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/loader.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"> --}}
    <link href="{{ asset('/css/dataTable-custom.css') }}" rel="stylesheet">
    <link href="{{asset("admin_assets/css/intlTelInput.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{asset("mdboostrap/css/mdb.min.css")}}" rel="stylesheet" type="text/css" />
    
    
    
    <title>@yield('title') :: {{ env('APP_NAME') }}</title>
    {{-- @livewireStyles() --}}
    {{-- @yield('styles') --}}
    <style>
        body{
            display: grid;
            place-items: center;
        }
        .grid{
            display: grid;
            grid-template-columns: auto auto auto;
            /* gap: 1rem; */
        }
        .grid-item {
            border: 1px solid #bab7b7; 
            padding:3rem; 
            border-radius: 4px;
        }
        /* Custom input styles */
        .form-outline .form-control:focus~.form-notch .form-notch-middle, .form-outline .form-control.active~.form-notch .form-notch-middle{
            border-top: 1px solid #bdbdbd;
        }
        .form-outline .form-control:focus~.form-notch .form-notch-middle{
            box-shadow:  0 -1px 0 0 var(--mdb-input-focus-border-color), /* Top shadow */
            0 1px 0 0 var(--mdb-input-focus-border-color);
            border-top: 1px solid #3b71ca; 
            transition-delay: 0.05s;
        }
        .form-outline .form-control:focus~.form-label, .form-outline .form-control.active~.form-label{
            transform: translateY(-1.7rem) translateY(0.1rem) scale(0.8) translateX(-14px);
        }

        #search-addon{
            margin-right: -40px;
            z-index: 3;
        }
        .form-control::placeholder{
            color: #999 !important;
            padding-left: 1.5rem;
            transition: 0.2s ease-in-out;
        }
        .form-control:focus::placeholder{
            padding-left: 0;
            transition: 0.2s ease-in-out;
        }
    </style>

</head>

<body>

    <div class="grid">
        
        <div class="grid-item">
            <div class="form-outline" data-mdb-input-init>
                <input type="text" id="form12" class="form-control" />
                <label class="form-label" for="form12">Example label</label>
            </div>
        </div>
        <div class="grid-item">
            <div class="input-group rounded">
                <span class="input-group-text border-0" id="search-addon">
                    <i class="fas fa-search"></i>
                </span>
                <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
            </div>
        </div>
        <div class="grid-item">
            <div class="form-outline" data-mdb-input-init>
                <textarea spellcheck="true"  class="form-control" data-mdb-showcounter="true" maxlength="200" id="textAreaExample" rows="4"></textarea>
                <label class="form-label" for="textAreaExample">Message</label>
                <div class="form-helper"></div>
              </div>
        </div>
    </div>







    <!-- Font awesome -->
    {{-- <script src="https://kit.fontawesome.com/350d033a5a.js" crossorigin="anonymous"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        @livewireScripts

        @stack('scripts')
        <script src="{{asset("mdboostrap/js/mdb.umd.min.js")}}"  ></script>
        <script src="{{ asset('v2/js/main.js') }}"></script>


        <script src="{{ asset('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>
        <script src="{{ asset('js/alertify.min.js') }}"></script>


        <script src="{{ asset('admin_assets/js/select2.min.js') }}"></script>

        <script src="{{ asset('admin_assets/head-office-script.js') }}"></script>

        <script src="{{ asset('admin_assets/js/view_case.js') }}"></script>
        <script src="{{asset('admin_assets/js/intlTelInput-jquery.min.js')}}" type="text/javascript"></script>
        

        <script>
            $('.select_group').select2();
        </script>
        @yield('scripts')
</body>

</html>
