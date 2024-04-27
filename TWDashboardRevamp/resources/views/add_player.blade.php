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
                        <a class="SetContentCenterLogo1 img-fluid"> <img src="assets/images/addz1.png" alt=""> </a>
                        <div
                            class="block-content block-content-full row px-lg-5 px-xl-6 py-2 py-md-5 py-lg-4 bg-body-extra-light bg-dark-green player-edit-pageBorder content-Btn row g-0 justify-content-center align-items-center">
                            <a href="players" class="ribbon">
                                <img src="assets/images/x-button.png" width="40" height="40">
                            </a>
                            <div class="col-xl-8 content">

                                <form class="text-center mb-2 top-margin" id="register_form" name="register_form"
                                      method="POST"
                                      action="{{route('addplayer')}}">
                                    @csrf

                                    <div class="row mt-5">
                                        <div class="col-md-6">


                                            <div class="add-player">
                                                <input class="form-control Custom-Field placeholder-color fontSize"
                                                       type="text"
                                                       name="first_name" id="first_name"
                                                       placeholder="{{ strtoupper(__('First Name')) }}"
                                                       value="{{old('first_name')}}">
                                                <span id="firstname"></span>
                                            </div>
                                            <span class="errors">
                                               @error('first_name')
                                                {{$message}}
                                                @enderror
                                            </span>

                                        </div>
                                        <div class="col-md-6">


                                            <div class="add-player">
                                                <input class="form-control Custom-Field placeholder-color fontSize"
                                                       type="text"
                                                       name="last_name" id="last_name"
                                                       placeholder="{{ strtoupper(__('Last Name')) }}"
                                                       value="{{old('last_name')}}">
                                                <span id="lastname"></span>
                                            </div>
                                            <span class="errors">
                                               @error('last_name')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="add-player">
                                            <input class="form-control Custom-Field placeholder-color fontSize"
                                                   type="text"
                                                   name="email_id" id="email_id"
                                                   placeholder="{{ strtoupper(__('Email')) }}"
                                                   value="{{old('email_id')}}">
                                            <span id="email_id_error"></span>
                                        </div>
                                        <span class="errors">
                                               @error('email_id')
                                            {{$message}}
                                            @enderror
                                            </span>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-md-6">


                                            <div class="add-player">
                                                <select name="code" id="select_code"
                                                        class="country-code w-100 how country-code-padding"
                                                        value="{{old('code')}}">
                                                    <option value="">{{ __('Select Country Code') }}</option>
                                                    <option value="92">(+92)Pakistan</option>
                                                    <option value="1">(+1)United States</option>
                                                    <option value="1684">(+1684)American Samoa</option>
                                                    <option value="1671">(+1671)Guam</option>
                                                    <option value="1670">(+1670)Northern Mariana Islands</option>
                                                    <option value="1264">(+1264)Anguilla</option>
                                                    <option value="1268">(+1268)Antigua and Barbuda</option>
                                                    <option value="1242">(+1242)Bahamas</option>
                                                    <option value="1246">(+1246)Barbados</option>
                                                    <option value="1441">(+1441)Bermuda</option>
                                                    <option value="1">(+1)Canada</option>
                                                    <option value="1345">(+1345)Cayman Islands</option>
                                                    <option value="1767">(+1767)Dominica</option>
                                                    <option value="1809">(+1809)Dominican Republic</option>
                                                    <option value="1809201">(+1809201)Dominican Republic</option>
                                                    <option value="1473">(+1473)Grenada</option>
                                                    <option value="1664">(+1664)Montserrat</option>
                                                    <option value="1670">(+1670)Northern Mariana Islands</option>
                                                    <option value="1787">(+1787)Puerto Rico</option>
                                                    <option value="1758">(+1758)St Lucia</option>
                                                    <option value="1784">(+1784)St Vincent Grenadines</option>
                                                    <option value="1868">(+1868)Trinidad and Tobago</option>
                                                    <option value="1649">(+1649)Turks and Caicos Islands</option>
                                                    <option value="1284">(+1284)Virgin Islands, British</option>
                                                    <option value="1340">(+1340)Virgin Islands, U.S.</option>
                                                    <option value="91">(+91)India</option>
                                                </select>
                                            </div>
                                            <span class="errors">
                                               @error('code')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="col-md-6">


                                            <div class="add-player">
                                                <input class="form-control Custom-Field placeholder-color fontSize"
                                                       type="text"
                                                       name="contact_no" id="contact_no"
                                                       placeholder="{{ strtoupper(__('Contact Number')) }}"
                                                       value="{{old('contact_no')}}">
                                                <span id="contact1"></span>
                                            </div>
                                            <span class="errors">
                                               @error('contact_no')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">


                                            <div class="add-player">
                                                <input class="form-control Custom-Field placeholder-color fontSize"
                                                       type="text"
                                                       name="address" id="address"
                                                       placeholder="{{ strtoupper(__('Address')) }}"
                                                       value="{{old('address')}}">
                                                <span id="address"></span>
                                            </div>
                                            <span class="errors">
                                               @error('address')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="col-md-6">


                                            <div class="add-player">
                                                <input class="form-control Custom-Field placeholder-color fontSize"
                                                       type="text"
                                                       name="state" id="state"
                                                       placeholder="{{ strtoupper(__('State')) }}"
                                                       value="{{old('state')}}">
                                                <span id="state"></span>
                                            </div>
                                            <span class="errors">
                                               @error('state')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6">


                                            <div class="add-player">
                                                <input class="form-control Custom-Field placeholder-color fontSize"
                                                       type="text"
                                                       name="country" id="country"
                                                       placeholder="{{ strtoupper(__('Country')) }}"
                                                       value="{{old('country')}}">
                                                <span id="contact"></span>
                                            </div>
                                            <span class="errors">
                                               @error('country')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="col-md-6">


                                            <div class="add-player">
                                                <input class="form-control Custom-Field placeholder-color fontSize"
                                                       type="text"
                                                       name="zipcode" id="zipcode"
                                                       placeholder="{{ strtoupper(__('Zip Code')) }}"
                                                       value="{{old('zipcode')}}">
                                                <span id="zipcode"></span>
                                            </div>
                                            <span class="errors">
                                               @error('zipcode')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div></div>
                                        <div class="text-white">
                                            <label>{{ strtoupper(__('Promo Code')) }}: @if(session('vdata'))
                                                    {{session('vdata')}}
                                                @endif</label>
                                        </div>
                                        <div></div>
                                    </div>
                                    <div>
                                        <input class="btn btn-hero btn-primary btnGreen w-50 mt-3" type="submit"
                                               name="profile" value="{{ strtoupper(__('Save')) }}">


                                    </div>
                                </form>

                            </div>


                        </div>
                    </div><!--end of border1-->
                </div>
            </div>
        </div>


        <!-- Right-Hand-Bar(rhb) -->
        @include('rhb')
    </div>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @if(session()->has('message'))
        <script>
            swal("Success!", "{!! session()->get('message')!!}", "success");

        </script>
    @endif
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @if(session()->has('not'))
        <script>
            swal("Alert!", "{!! session()->get('not')!!}", "error");

        </script>
    @endif
@endsection
