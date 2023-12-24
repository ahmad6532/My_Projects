<?php
$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$paypal_id = 'matthew-facilitator@edgewatergroupinc.com'; //Business Email
?>


@extends('layouts.backend')

@section('content')
    <div class="row dashBoardContentMargin no-gutters flex-md-10-auto bg-black">
        <div class="col-md-8 col-lg-8 col-xl-8 order-md-0">
            <!-- Main Content -->
            <div class="content bg-black">
                <!-- Quick Overview -->
                <!-- Main Container -->
                <div class="d-flex align-items-center p-2 px-sm-0 mt">
                    <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                        <a class="SetContentCenterLogo1 img-fluid"> <img src="assets/images/buyz1.png" alt=""> </a>
                        <div
                            class="block-content block-content-full row px-lg-5 px-xl-6 py-2 py-md-5 py-lg-4 bg-body-extra-light bg-dark-green player-edit-pageBorder content-Btn row g-0 justify-content-center align-items-center">
                            <a href="players" class="ribbon">
                                <img src="assets/images/x-button.png" width="40" height="40">
                            </a>

                            <div class="block-content">
                                <div class="scroll scrollPackage">
                                    <table class="table table-vcenter text-white tab-bord1 dataTablePackages" id="dataTables">
                                        <thead class="tabel-color">
                                        <tr>
                                            <th class="border-1">{{ __('Credits') }}</th>
                                            <th class="border-1">{{ __('Amount') }}</th>
                                            <th class="border-1"></th>
                                        </tr>
                                        </thead>
                                        <tbody id="geeks">


                                        <tr class="tab-bord">
                                            <td align="center">{{$p_data->credits_value_count}}</td>
                                            <td align="center">&#x24;{{$p_data->amount}}</td>
                                            <td align="center">
                                                <form id="PayForm" name="PayForm" action="{{ route('plan') }}" method="post" class="h-100">
                                                    <!-- Identify your business so that you can collect the payments. -->
                                                    @csrf
                                                    <input type='hidden' name='business' value="{{$p_data->c_id}}"> <!-- found on top -->
                                                    <input type='hidden' name='cmd' value="_xclick">
                                                    <input type="hidden" name="rm" value="2" /> <!--1-get 0-get 2-POST -->
                                                    <input type='hidden' class="name" name='item_name' value='Vendor Credits'>
                                                    <input type='hidden' name='item_number' value="{{$p_data->c_id}}">
                                                    <input type='hidden' class="price" name='amount' value="{{$p_data->amount}}">
                                                    <input type='hidden' class="price" name='user_id' value="{{$p_data->user_id}}">
                                                    <input type='hidden' name='no_shipping' value='1'>
                                                    <input type='hidden' name='no_note' value='1'>
                                                    <input type='hidden' name='handling' value='0'>
                                                    <input type="hidden" name="currency_code" value="USD">
                                                    <input type="hidden" name="lc" value="US">
                                                    <input type="hidden" name="cbt" value="Return to the hub">
                                                    <input type="hidden" name="bn" value="PP-BuyNowBF">
                                                    <input type='hidden' name='cancel_return' value='https://portal.touchwon.com/admin/paypal/cancel.php'>
                                                    <input type='hidden' name='return' value='https://portal.touchwon.com/admin/profile.php'>
                                                    <input type="submit" id="payNow" name="payNow" value="{{ __('Pay Now') }}" class="btn btn-success h-100">
                                                    <input type="hidden" name="c_id" value="{{$p_data->c_id}}">
                                                </form>
                                            </td>
                                        </tr>
                                        <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
                                        </tbody>
                                    </table>
                                </div>
                                <br>


                            </div>

                        </div>
                    </div><!--end of border1-->
                </div>
            </div>
        </div>
        <!-- Right-Hand-Bar(rhb) -->
        @include('rhb')
    </div>
    <script src="js/lib/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $('#payNow').click(function () {
            Dashmix.loader('show', 'bg-gd-TW');
        });
    </script>
    @if(session()->has('message'))
        <script>
            swal("Success!", "{!! session()->get('message')!!}", "success");
        </script>
    @endif
@endsection


