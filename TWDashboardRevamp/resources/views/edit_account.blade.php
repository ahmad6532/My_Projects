@extends('layouts.backend')

@section('content')


    <div class="row dashBoardContentMargin no-gutters flex-md-10-auto bg-black" xmlns="http://www.w3.org/1999/html"
         xmlns="http://www.w3.org/1999/html">
        <div class="col-md-8 col-lg-8 col-xl-8 order-md-0">

            <!-- Main Content -->
            <div class="content bg-black">
                <!-- Quick Overview -->
                <!-- Main Container -->
                <div class="d-flex align-items-center p-2 px-sm-0 mt">

                    <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                        <a class="SetContentCenterLogo1 img-fluid"> <img src="assets/images/addz1.png" alt=""> </a>
                        <div
                            class="block-content block-content-full row px-lg-5 px-xl-6 py-2 py-md-5 py-lg-4 bg-body-extra-light bg-dark-green player-edit-pageBorder content-Btn row g-0 justify-content-center align-items-center">
                            <a href="players" class="ribbon">
                                <img src="assets/images/x-button.png" width="40" height="40"></a>
                            <div class="col-xl-8 content">

                            <form class="text-center add-player" id="register_form" name="register_form" method="POST"
                                  action="{{route('vendorbtn')}}">
                                @csrf
                                <div>
                                    <input type="hidden" name="vendor_id" value="{{$vendor_data->vendor_id}}">
                                    <div>
                                        <input type="text" class="form-control mt-5 text-white Custom-Field fontSize"
                                               title="First Name"
                                               name="first_name" value="{{$vendor_data->first_name}} " id="first_name"
                                               placeholder="{{ strtoupper(__('First Name')) }}">
                                        <span id="firstname"></span>
                                    </div>
                                    <div></div>
                                </div>
                                <div>

                                    <div>
                                        <input type="text" class="form-control  mt-3 text-white Custom-Field fontSize"
                                               title="Last Name"
                                               name="last_name" value="{{$vendor_data->last_name}}" id="last_name"
                                               placeholder="{{ strtoupper(__('Last Name')) }}">
                                        <span id="lastname"></span>
                                    </div>

                                </div>
                                <div>
                                    <div></div>
                                    <div class="text-white  mt-3 ">
                                        <label style="margin-top:5px;">{{$vendor_data->email}}</label>
                                    </div>

                                </div>

                                <div>
                                    <div></div>
                                    <div class="text-white  mt-3 ">
                                        <label style="margin-top:5px;">{{ strtoupper(__('Credits')) }}
                                            : {{$vendor_data->credits}}</label>
                                    </div>
                                    <div></div>
                                </div>

                                <div>

                                    <div>
                                        <input type="text" class="form-control mt-3 text-white Custom-Field fontSize"
                                               title="Contact No"
                                               name="contact_no" value="{{$vendor_data->phone_number}}" id="contact_no"
                                               placeholder="{{ strtoupper(__('Contact Nubmer')) }}">
                                        <span id="contact"></span>
                                    </div>
                                    <div></div>
                                </div>
                                <div>

                                    <div>
                                        <input style="resize: none;"
                                               class="form-control mt-3 text-white Custom-Field fontSize" type="text"
                                               title="Address"
                                               name="address" value="{{$vendor_data->address}}" id="address"
                                               placeholder="{{ strtoupper(__('Address')) }}">
                                    </div>
                                    <div></div>
                                </div>


                                <div>

                                    <div class="text-white mt-3">
                                        <label style="margin-top:5px;">{{ strtoupper(__('Promo Code')) }}
                                            : {{$vendor_data->vendor_promocode}}</label>

                                    </div>
                                    <div></div>
                                </div>


                                <div>
                                    <label>&nbsp; &nbsp; &nbsp;</label>
                                    <div>

                                        <input type="submit" title="{{ strtoupper(__('Update Account')) }}"
                                               class="update-btn m-1" value="Update">


                                        <a type="button" id="bAddPlayer1" class="delete-account-btn m-1"
                                           title="{{ strtoupper(__('Delete Account')) }}"
                                           onclick="return confirm('{{ strtoupper(__('Are you sure you want to delete your Account?')) }}')"
                                           href="{{route('deletebtn')}}">
                                            <span class="">{{ strtoupper(__('Delete Account')) }}</span>
                                        </a>
                                    </div>
                                    <div></div>
                                </div>
                                <br>
                            </form>

                        </div>
                    </div><!--end of border1-->
                </div>
            </div>
        </div>
        </div>
        @include('rhb')

    </div>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @if(session()->has('message'))
        <script>
            swal("Success!", "{!! session()->get('message')!!}", "success");

        </script>
    @endif
    <!-- END Page Content -->
@endsection
