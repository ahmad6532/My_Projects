@extends('layouts.backend')

@section('content')

    <div class="row dashBoardContentMargin no-gutters flex-md-10-auto bg-black">


        <div class="col-md-8 col-lg-8 col-xl-8 order-md-0">


            <!-- Main Container -->
            <div id="main-container">


                <div class="row g-0 justify-content-center mx-lg-2 bg-black-75">

                    <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                        <div class="block-content block-content-full px-lg-5 px-xl-6 py-2 py-md-5 py-lg-1
                                bg-body-extra-light other-page-border content-Btn "
                             style="background-image: url('assets/images/bulkcredits_bg.png');
                                     -webkit-background-size: cover; -moz-background-size: cover;
                                     -o-background-size: cover;background-size: cover;">
                            <div class="mt-3 text-center">
                                <img src="assets/images/buycredittitle.png" class="img-fluid h-25 w-75">
                            </div>


                            <form name="stateForm" id="stateForm" method="GET" action="" class="mt-2">


                                <div class="w-100 h-100">


                                    <div class="h-100 w-100 bulk-credits">


                                        <div class="block-header content-Btn">

                                            <div class="h-50 w-50">
                                                <img src="assets/images/mostpopular-01.png"
                                                     class="img-fluid h-100 w-100 platinumgoldicon3 blink">
                                            </div>

                                            <div class="mt-3 text-center"><img
                                                    src="assets/images/platinumtext.png"
                                                    class="img-fluid h-100 w-100"></div>

                                            <div class="h-50 w-50">
                                                <img src="assets/images/30bounsadded.png"
                                                     class="img-fluid h-100 w-100 platinumgoldicon2 blink">
                                            </div>

                                        </div>
                                        <div class="block-header">

                                            <div class="w-25">
                                                <img src="assets/images/platinumgoldicon.png"
                                                     class="img-fluid  h-100 w-100 platinumgoldicon1">
                                            </div>
                                            <div class="block-content-full text-white"><label
                                                    class="h-25 w-25 platinumtext1">303625 {{ strtoupper(__('Credits')) }}</label></div>
                                            <div class="w-25">
                                                <a href="{{ route('payment',"c_id=7") }}">

                                                    <img src="assets/images/1799dollorbutton.png"
                                                         class="img-fluid  h-100 w-100 platinumgoldicon">
                                                </a>

                                            </div>

                                        </div>


                                    </div>


                                    <div class="h-100 w-100 bulk-credits mt-3">


                                        <div class="block-header content-Btn">

                                            <div class="h-50 w-50">

                                            </div>

                                            <div class="mt-3 text-center"><img src="assets/images/goldtext.png"
                                                                               class="img-fluid h-100 w-100">
                                            </div>

                                            <div class="h-50 w-50">
                                                <img src="assets/images/kickback20.png"
                                                     class="img-fluid h-100 w-100 platinumgoldicon2 blink">
                                            </div>

                                        </div>
                                        <div class="block-header">

                                            <div class="w-25">
                                                <img src="assets/images/goldiconimg.png"
                                                     class="img-fluid  h-100 w-100 platinumgoldicon1">
                                            </div>
                                            <div class="block-content-full text-white"><label
                                                    class="h-25 w-25 platinumtext1">303625 {{ strtoupper(__('Credits')) }}</label></div>
                                            <div class="w-25">
                                                <a href="{{ route('payment',"c_id=3") }}">

                                                    <img src="assets/images/1228dollorbutton.png"
                                                         class="img-fluid  h-100 w-100 platinumgoldicon">

                                                </a>

                                            </div>

                                        </div>


                                    </div>


                                    <div class="h-100 w-100 bulk-credits mt-3">


                                        <div class="block-header content-Btn">

                                            <div class="h-50 w-50">

                                            </div>

                                            <div class="mt-3 text-center"><img
                                                    src="assets/images/silvertext.png"
                                                    class="img-fluid h-75 w-75"></div>

                                            <div class="h-50 w-50">
                                                <img src="assets/images/BeginnerPackage.png"
                                                     class="img-fluid h-100 w-100 platinumgoldicon2 blink">
                                            </div>

                                        </div>
                                        <div class="block-header">

                                            <div class="w-25">
                                                <img src="assets/images/silvericonimg.png"
                                                     class="img-fluid  h-100 w-100 platinumgoldicon1">
                                            </div>
                                            <div class="block-content-full text-white"><label
                                                    class="h-25 w-25 platinumtext1">303625 {{ strtoupper(__('Credits')) }}</label></div>
                                            <div class="w-25">

                                                <a href="{{ route('payment',"c_id=2") }}">

                                                    <img src="assets/images/599dollorbutton.png"
                                                         class="img-fluid  h-100 w-100 platinumgoldicon">

                                                </a>
                                            </div>

                                        </div>


                                    </div>


                                </div>
                            </form>
                            <div class="text-white mb-2 mt-3" style="text-align: center"><strong
                                    class="drawer-heading">{{ strtoupper(__('Package purchases')) }}</strong></div>


                        </div>
                    </div><!--end of border1-->
                </div>

            </div>
        </div>

        <!-- Right-Hand-Bar(rhb) -->
        @include('rhb')
    </div>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @if(session()->has('alert') && !is_array (session()->get('alert')))
        <script>

            swal("Alert!", "{!! session()->get('alert')!!}", "error");
        </script>
    @endif

    <!-- END Page Content -->
@endsection
