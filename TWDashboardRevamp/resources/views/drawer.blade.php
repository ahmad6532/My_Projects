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
                        <a class="SetContentCenterLogo1 img-fluid" href=""> <img src="assets/images/drawer.png"
                                                                                 alt="">
                        </a>
                        <div
                            class="block-content block-content-full px-lg-5 px-xl-8 py-2 py-md-5 py-lg-1 bg-body-extra-light bg-dark-green other-page-border content-Btn  row g-0 justify-content-center align-items-center">


                            <div><br></div>
                            <div><br></div>

                            <div><br></div>
                            <?php if (isset($drawer_data) == 0) {?>
                            <form method="post" action="{{route('iamt')}}">
                                @csrf
                                <div class="input-group input-group-lg" id="amt_initial">
                                    <span class="input-group-text dollar"> <i class="fa fa-dollar"></i></span>
                                    <input type="number" min="0" class="form-control drawer-radius" id="initialAmount"
                                           name="initialAmount" placeholder="{{ __('Initial Amount') }}">
                                    <button type="submit" name="initial_submit" class="drawer-btn1-color"
                                            id="intial_amt">{{ __('INITIAL') }}
                                    </button>

                                </div>
                                <span class="errors">
                                        @error('initialAmount')
                                    {{$message}}
                                    @enderror
                                    </span>
                            </form>
                            <?php }?>
                            <div><br></div>

                            <form method="post" action="{{route('reamt')}}">
                                @csrf
                                <div class="input-group input-group-lg" id="amt_initial">
                                    <span class="input-group-text dollar"> <i class="fa fa-dollar"></i></span>
                                    <input type="number" min="0" class="form-control drawer-radius" id="refillAmount"
                                           name="refillAmount" placeholder="{{ __('Refill Amount') }}">
                                    <?php if (isset($drawer_data) != 0) {?>
                                    <button type="submit" name="refill_submit" class="drawer-btn1-color"
                                            id="refill_submit_r">{{ __('REFILL') }}
                                    </button>
                                    <?php } else {?>
                                    <button type="submit" name="refill_submit" class="drawer-btn1-color refillbtn"
                                            id="refill_submit_r" disabled="disabled">{{ __('REFILL') }}
                                    </button>
                                <?php }?>
                            </form>
                        </div>
                        <span class="errors">
                                        @error('refillAmount')
                            {{$message}}
                            @enderror
                                    </span>
                        <div><br></div>

                        <form method="post" action="{{route('wdrawbtn')}}">
                            @csrf
                            <div class="input-group input-group-lg" id="amt_initial">
                                <span class="input-group-text dollar"> <i class="fa fa-dollar"></i></span>
                                <input type="number" min="0" class="form-control drawer-radius"
                                       id="withdrawAmount"
                                       name="withdrawAmount" placeholder="{{ __('Withdraw Amount') }}">
                                <?php if (isset($drawer_data) != 0) {?>
                                <button type="submit" id="withdraw_submit" class="drawer-btn1-color"
                                        name="withdraw_submit">{{ __('WITHDRAW') }}
                                </button>
                                <?php } else {?>
                                <button type="submit" id="withdraw_submit" class="drawer-btn1-color refillbtn"
                                        name="withdraw_submit" disabled="disabled">{{ __('WITHDRAW') }}
                                </button>
                            <?php }?>
                        </form>
                    </div>
                    <span class="errors">
                                        @error('withdrawAmount')
                        {{$message}}
                        @enderror
                                    </span>

                    <div class="hr">
                        <div><span class="errors">
                            @if(session()->has('bal_error'))
                                    {{session()->get('bal_error')}}
                                @endif
                        </span></div>

                    </div>
                    <hr class="hr01">
                    <div>
                        <div></div>
                        <div class="text-white text-center">
                            <h2 style="margin-top:5px;"
                                class="drawer-heading">{{ __('Current Information') }}</h2>
                        </div>
                    </div>

                    <div
                        class="block-header block-content-full px-xl-3 g-0 justify-content-center container">


                        <div class="text-white boxes leftbox">
                            <strong>{{ __('Initial Amount') }}: </strong>
                        </div>
                        <div class="text-white boxes rightbox">

                                      <span id="amnt">$
                                          <?php if (isset($inival)) {?>
                                          {{$inival}}.00

                                              <?php } else { ?>
                                          0.00
                                          <?php } ?>


                                          </span>

                        </div>
                    </div>

                    <div
                        class="block-header block-content-full px-xl-3 g-0 justify-content-center container">


                        <div class="text-white boxes leftbox">
                            <strong>{{ __('Fills') }}: </strong>
                        </div>
                        <div class="text-white boxes rightbox">

                                     <span>$
                                            <?php if (isset($refilval)) {?>
                                         {{$refilval}}.00

                                              <?php } else { ?>
                                          0.00
                                          <?php } ?>

                                        </span>
                        </div>
                    </div>

                    <div
                        class="block-header block-content-full px-xl-3 g-0 justify-content-center container">


                        <div class="text-white boxes leftbox">
                            <strong>{{ __('Withdraw') }}: </strong>
                        </div>
                        <div class="text-white boxes rightbox">

                                        <span>$
                                           <?php if (isset($withdraw)) {?>
                                            {{$withdraw}}.00

                                              <?php } else { ?>
                                          0.00
                                          <?php } ?>

                                        </span>
                        </div>
                    </div>

                    <div
                        class="block-header block-content-full px-xl-3 g-0 justify-content-center container">


                        <div class="text-white boxes leftbox">
                            <strong>{{ __('Balance') }}: </strong>
                        </div>
                        <div class="text-white boxes rightbox">

                                     <span>$
                                         <?php if (isset($balance)) {?>
                                         {{$balance}}.00
                                         <?php } else { ?>
                                          0.00
                                          <?php } ?>

                                        </span>
                        </div>
                    </div>


                    <div>
                        <div class="text-center">
                            <?php if (isset($drawer_data) != 0) {?>
                            <a type="button" id="save" name="profile" class="drawer-btn1-color m-1"
                               value="" href="{{route('player_view')}}">Save & Continue
                            </a>
                            <a type="submit"
                               id="close_shift" name="close_shift" data-toggle="model" data-bs-toggle="modal"
                               data-bs-target="#modal-block-popin"
                               data-target="#myModal"
                               value="Close Shift"
                               class="m-1 drawer-btn1-color">Close Shift
                            </a>
                            <?php }?>


                        </div>
                        <div></div>
                    </div>
                    <br>

                </div>
            </div><!--end of border1-->
        </div>

    </div>

    <!-- END Main Content -->

    </div>


    <!-- Right-Hand-Bar(rhb) -->
    @include('rhb')
    </div>

    <div class="modal js-animation-object animated bounceInUp" id="modal-block-popin" tabindex="-1"
         aria-labelledby="modal-block-popin" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popin" role="document">
            <div class="modal-content">
                <form method="post" action="{{route('closedraw')}}">
                    @csrf
                    <?php if (isset($start_date)){?>
                    <input type="hidden" name="$startdate" value="{{$start_date}}">
                    <?php } ?>
                    <div class="bgcolor bb">

                        <div class=""
                        <div class="row items-push mt-0 mb-0 text-light ps-2 pe-2">

                            <div class="row items-push text-light ps-2 pe-2 content-wrapper">
                                <h5 class="text-center tcolor mt-5 mb-4">DRAWER REPORT</h5>
                                <hr class="hrr">
                            </div>
                            <div class="row items-push mt-1 text-light ps-2 pe-2 content-wrapper reportdate">
                                <div class="row py-3 tcolor">
                                    <div class="col-6 px-lg-4">Start Time</div>
                                    <div class="col-6 text-right">
                                        <?php if (isset($start_date)){ ?>
                                        {{$start_date}}
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="row py-3 tcolor">
                                    <div class="col-6 px-lg-4">End Time</div>
                                    <div class="col-6 text-right">
                                        <?php $date = date("Y-m-d H:i:s");
                                        echo $date?>
                                    </div>
                                </div>
                            </div>
                            <hr class="hr1">
                            <div class="row items-push mt-5 text-light ps-2 pe-2 content-wrapper reportdate">
                                <div class="row py-3 tcolor">
                                    <div class="col-6 px-lg-4 fw-bold">Initial Amount</div>
                                    <div class="col-6 text-right">
                                    <span id="amnt">$
                                          <?php if (isset($inival)) {?>
                                        {{$inival}}.00

                                              <?php } else { ?>
                                          0.00
                                          <?php } ?>


                                          </span>
                                    </div>
                                </div>
                                <div class="row py-3 tcolor">
                                    <div class="col-6 px-lg-4 fw-bold">Fills</div>
                                    <div class="col-6 text-right">
                                    <span>$
                                            <?php if (isset($refilval)) {?>
                                        {{$refilval}}.00

                                              <?php } else { ?>
                                          0.00
                                          <?php } ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="row py-3 tcolor">
                                    <div class="col-6 px-lg-4 fw-bold">Withdraw</div>
                                    <div class="col-6 text-right">
                                    <span>$
                                           <?php if (isset($withdraw)) {?>
                                        {{$withdraw}}.00

                                              <?php } else { ?>
                                          0.00
                                          <?php } ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <hr class="hr1">
                            <div class="row items-push mt-5 text-light ps-2 pe-2 content-wrapper reportdate">
                                <div class="row py-3 tcolor">
                                    <div class="col-6 px-lg-4 fw-bold">Credit Purchases</div>
                                    <div class="col-6 text-right">
                                    <span>$
                                           <?php if (isset($credits)) {?>
                                        {{$credits}}.00

                                              <?php } else { ?>
                                          0.00
                                          <?php } ?>

                                        </span>
                                    </div>
                                </div>
                                <div class="row py-3 tcolor">
                                    <div class="col-6 px-lg-4 fw-bold">Redeems</div>
                                    <div class="col-6 text-right">
                                     <span>$
                                           <?php if (isset($points)) {?>
                                         {{$points}}.00

                                              <?php } else { ?>
                                          0.00
                                          <?php } ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <hr class="hr1">
                            <div class="row items-push mt-4 mb-3 text-light ps-2 pe-2 content-wrapper">
                                <div class="row py-3 tcolor">
                                    <div class="col-6 px-lg-4"><h2>Balance </h2></div>
                                    <div class="col-6 text-right">
                                    <span>$
                                         <?php if (isset($tbalance)) {?>
                                        {{$tbalance}}.00
                                         <?php } else { ?>
                                          0.00
                                          <?php } ?>

                                        </span>
                                    </div>
                                </div>

                            </div>
                            <hr class="hrr">
                            <div class="row items-push mt-1 text-light ps-2 pe-2 content-wrapper">
                                <div class="row py-3 tcolor">
                                    <div class="col-6 px-lg-4 fw-bold"></div>
                                    <div class="col-6 text-right">
                                        <input type="submit" value="CLOSE" class="drawer-btn1-color closebtnwidth">
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>

                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- END Page Content -->
@endsection
