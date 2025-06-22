@extends('layouts.backend')

@section('content')



    <div class="row dashBoardContentMargin no-gutters flex-md-10-auto bg-black">


        <div class="col-md-8 col-lg-8 col-xl-8 order-md-0">

            <!-- Main Content -->

            <div class="content bg-black">


                <!-- Quick Overview -->


                <!-- Main Container -->


                <div class="d-flex align-items-center p-2 px-sm-0">

                    <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                        <a class="SetContentCenterLogo" href="players"> <img src="assets/images/addz1.png" alt="">
                        </a>
                        <div
                            class="block-content block-content-full px-xl-6 py-2 bg-body-extra-light bg-dark-green player-edit-pageBorder content-Btn SetContentMargin">


                            <a href="players" class="ribbon">
                                <img src="assets/images/x-button.png" width="40" height="40"></a>


                            <br><br>
                            <form class="text-center mb-4" id="register_form" name="register_form" method="POST"
                                  action="{{route('updatebtn')}}" novalidate="novalidate">
                                @csrf

                                <input type="hidden" name="player_id" value="{{$tdata->player_id}}" class="form-control Custom-Field"
                                       id="player_id"
                                       placeholder="First Name">

                                <div class=" text-white block-header inputfileds">
                                    <div class="w-25"><label>{{ __('First Name') }}:<span class="errors">*</span></label></div>
                                    <div class="w-100">
                                        <input class="form-control placeholder-color text-white Custom-Field fontSize" type="text"
                                               name="first_name" value="{{$tdata->first_name}}"
                                               placeholder="{{ __('First Name') }}">
                                        <div class="errors">
                                            @error('first_name')
                                            {{$message}}
                                            @enderror</div>
                                    </div>
                                    <div>

                                    </div>
                                </div>
                                <div class=" text-white block-header inputfileds">
                                    <div class="w-25"><label>{{ __('Last Name') }}:<span class="errors">*</span></label></div>
                                    <div class="w-100">
                                        <input class="form-control placeholder-color text-white Custom-Field fontSize" type="text"
                                               name="last_name" value="{{$tdata->last_name}}" id="last_name"
                                               placeholder="{{ __('Last Name') }}">
                                        <div class="errors">
                                            @error('last_name')
                                            {{$message}}
                                            @enderror</div>
                                    </div>
                                    <div></div>
                                </div>
                                <div class=" text-white block-header inputfileds">
                                    <div class="w-25"><label> {{ __('Email') }}:</label></div>
                                    <div class="w-100">
                                        <input class="form-control placeholder-color text-white Custom-Field fontSize" type="text" name="email_id"
                                               value="{{$tdata->email}}"
                                               placeholder="{{ __('Email') }}">


                                        <div class="errors">
                                            @error('email_id')
                                            {{$message}}
                                            @enderror</div>
                                    </div>

                                        <!--<label style="margin-top:5px;">ankushaggarwal2055@gmail.com</label>-->

                                    <div></div>
                                </div>

                                <div class="text-white block-header inputfileds">
                                    <div class="w-25"><label>{{ __('Credits') }}:</label></div>
                                    <div class="w-100 textAlign">
                                        <label style="margin-top:5px; margin-left: 10px;">${{$tdata->credits}}</label>
                                    </div>
                                    <div></div>
                                </div>

                                <div class=" text-white block-header inputfileds">
                                    <div class="w-25"><label>{{ __('Contact Number') }}:<span class="errors">*</span></label></div>
                                    <div class="w-100">
                                        <input class="form-control placeholder-color text-white Custom-Field fontSize" type="text"
                                               name="contact_no" value="{{$tdata->phone_number}}" id="contact_no"
                                               placeholder="{{ __('Contact Number') }}">
                                        <div class="errors">
                                            @error('contact_no')
                                            {{$message}}
                                            @enderror</div>
                                    </div>
                                    <div></div>
                                </div>
                                <div class="text-white block-header inputfileds">
                                    <div class="w-25"><label>{{ __('Address') }}:</label></div>
                                    <div class="w-100 ">
                            <input class="form-control placeholder-color text-white Custom-Field addressHeight fontSize" style="resize: none;"
                                      type="text" name="address" id="address" value="{{$tdata->street_name}}"
                                      placeholder="{{ __('Address') }}">
                                    </div>
                                    <div></div>
                                </div>
                                <div class="text-white block-header inputfileds">
                                    <div class="w-25"><label>{{ __('State') }}:</label></div>
                                    <div class="w-100">
                                        <input class="form-control placeholder-color text-white Custom-Field fontSize" type="text" name="state"
                                               value="{{$tdata->state}}" id="state" placeholder="{{ __('State') }}">
                                    </div>
                                    <div></div>
                                </div>
                                <div class="text-white block-header inputfileds">
                                    <div class="w-25"><label>{{ __('Country') }}:</label></div>
                                    <div class="w-100">
                                        <input class="form-control placeholder-color text-white Custom-Field fontSize" type="text" name="country"
                                               value="{{$tdata->country}}" id="country" placeholder="{{ __('Country') }}">
                                    </div>
                                    <div></div>
                                </div>
                                <div class="text-white block-header inputfileds">
                                    <div class="w-25"><label>{{ __('Zip Code') }}:</label></div>
                                    <div class="w-100">
                                        <input class="form-control placeholder-color text-white Custom-Field fontSize" type="text" name="zipcode"
                                               value="{{$tdata->zip_code}}" id="zipcode" placeholder="{{ __('Zip Code') }}">
                                    </div>
                                    <div></div>
                                </div>


                                <div class="text-white block-header inputfileds">
                                    <div class="w-25"><label>{{ __('Promo Code') }}:<span class="errors">*</span></label></div>
                                    <div class="w-100 textAlign">
                                        <!--<input type="text" name="promocode" maxlength="10" value="Win336" class="form-control" id="promocode" placeholder="Promocode"  disabled>-->
                                        <label style="margin-top:5px;margin-left: 10px;">{{$tdata->vendor_promocode}}</label>
                                        <span id="promocode_error" class="errors" for="promocode"></span>
                                    </div>
                                    <div></div>
                                </div>


                                <div>

                                    <div>
                                        <div>
                                            <input type="hidden" name="allowRegister" id="allowRegister">

                                            <input class="btn btn-hero btn-primary btnGreen w-50" type="submit"
                                                   name="profile" value="{{ __('Save') }}">


                                        </div>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div><!--end of border1-->
                </div>

            </div>

            <!-- END Main Content -->

        </div>


        @include('rhb')

    </div>


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @if(session()->has('message'))
        <script>
            swal("Success !", "{!! session()->get('message')!!}", "success");

        </script>
    @endif
    <!-- END Page Content -->
@endsection
