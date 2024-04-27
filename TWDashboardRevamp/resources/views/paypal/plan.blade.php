<!doctype html>
<?php
$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$paypal_id = 'ahmadfps473@gmail.com'; //Business Email
?>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>{{config('app.name')}}</title>
    <meta name="description"
          content="Portal - Touchwon &amp; Developed by Fun Plus Studios">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.ico') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/favicon.ico') }}">
    <!-- Fonts and Styles -->
    @yield('css_before')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" id="css-main" href="{{ mix('css/dashmix.css') }}">
    <link rel="stylesheet" id="css-main" href="assets/css/custom.css">
@yield('css_after')
<!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
    </script>
</head>


<body onload="document.getElementById('osx').click();" class="bg-gd-TW">
<div id="page-loader" class="show bg-gd-TW"></div>
<div class="scroll scrollPackage">
    <form id="PayForm" name="PayForm" action="<?php echo $paypal_url; ?>" method="post" class="h-100">
        <!-- Identify your business so that you can collect the payments. -->
        <input type="hidden" name="business" value="<?php echo $paypal_id; ?>">
        <!-- Specify a Buy Now button. -->
        <input type="hidden" name="cmd" value="_xclick">
        <!-- Specify details about the item that buyers will purchase. -->
        <input type="hidden" name="item_name" value="Vendor Buy Credits">
        <input type="hidden" name="item_number" value="{{$p_data->id}}">
        <input type="hidden" name="user_id" value="{{$p_data->user_id}}">
        <input type="hidden" name="amount" value="{{$p_data->amount}}">
        <input type='hidden' name='no_shipping' value='1'>
        <input type='hidden' name='no_note' value='1'>
        <input type='hidden' name='handling' value='0'>
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="lc" value="US">
        <input type="hidden" name="cbt" value="Return to the hub">
        <input type="hidden" name="bn" value="PP-BuyNowBF">
        <input type='hidden' name='cancel_return' value='{{ route('payment_cancel') }}'>
        <input type='hidden' name='return' value='{{ route('payment_success') }}'>
        <input type='hidden' name='notify_url' value='{{ route('ipn') }}'>
        <input type="submit" id="osx" name="payNow" value="{{ __('Pay Now') }}" class="d-none btn btn-success h-100">
    </form>
    <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</div>
<br>
<script>

    var language = '{!!  app()->getLocale() !!}';

    //alert(language);
</script>

<!-- Jquery JS -->
<script src="js/lib/jquery.min.js"></script>
<!-- Laravel Original JS -->
<script src="{{ mix('/js/laravel.app.js') }}"></script>
@yield('js_after')
</body>
</html>
<script type="text/javascript">
    //document.addEventListener("DOMContentLoaded", function (event) {
    //   document.getElementById('osx').click();
    //});
</script>




