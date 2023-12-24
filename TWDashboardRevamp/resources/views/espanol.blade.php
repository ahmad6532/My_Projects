@extends('layouts.backend')

@section('content')


    <!-- Page Content -->
    <div class="row dashBoardContentMargin no-gutters flex-md-10-auto bg-black">


        <div class="col-md-8 col-lg-8 col-xl-8 order-md-0">

            <!-- Main Content -->

            <div class="content bg-black">


                <!-- Quick Overview -->


                <!-- Main Container -->

                <!-- Table -->
                <div class="block block-rounded mt-4 player-edit-pageBorder bg-dark-green text-white">

                    <div class="row align-items-baseline m-5 mb">

                        <div class="">
                            <div class="text-center mb-7">
                                <br><br><br>
                                <h1 class="text-white">{{ __('Page is Under Processing') }}</h1>

                            </div>
                        </div>
                    </div>



                </div>
                <!-- END Table -->

            </div>

            <!-- END Main Content -->

        </div>

        <!-- Right-Hand-Bar(rhb) -->
        @include('rhb')
    </div>
    <!-- END Page Content -->
@endsection
