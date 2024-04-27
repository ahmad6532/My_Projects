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
                <form action="{{route('shiftdata')}}" method="POST">
                        @csrf

                        <div class="content">
                            <div class="row text-center divmarg">
                                <div class="col-lg-4">
                                    <div class="input-daterange topmargin"
                                         data-date-format="yyyy-mm-dd" data-week-start="1" data-autoclose="true"
                                         data-today-highlight="true">
                                        <input type="text"
                                               class="form-control datebtn"
                                               id="fdate" name="fdate" placeholder="From Date:"
                                               data-week-start="1" data-autoclose="true" data-today-highlight="true"
                                               data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" autocomplete="off"
                                               readonly="readonly">

                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-daterange topmargin"
                                         data-date-format="yyyy-mm-dd" data-week-start="1" data-autoclose="true"
                                         data-today-highlight="true">
                                        <input type="text"
                                               class="form-control datebtn"
                                               id="tdate" name="tdate" placeholder="To Date:"
                                               data-week-start="1" data-autoclose="true" data-today-highlight="true"
                                               data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" autocomplete="off"
                                               readonly="readonly">

                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="">
                                        <input type="submit" name="searchreports" id="range" value="Search"
                                               class="btn btn-info search-btn1 topmargin">


                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="block-content">
                        <div class="">
                            <table class="table table-vcenter text-white tab-bord1 table-condensed dataTableTransactions" id="dataTables">
                                <thead class="tabel-color">
                                    <tr class="row1">
                                        <th>{{ __('Period') }}</th>
                                        <th>{{ __('Initial Amount($)') }}</th>
                                        <th>{{ __('Refills($)[Total]') }}</th>
                                        <th>{{ __('Withdraw($)[Total]') }}</th>
                                        <th>{{ __('Credits($)[Total]') }}</th>
                                        <th>{{ __('Redeems($)[Total]') }}</th>
                                        <th>{{ __('Balance') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(isset($data))
                                    @foreach ($data as $value)

                                    <tr class="tab-bord">
                                        <td class="fw-semibold">
                                           {{$value->drawer_started_on}}

                                           {{$value->drawer_ended_on}}
                                        </td>

                                        <td class="fw-semibold">
                                            {{$value->initial_amount}}
                                        </td>

                                        <td class="fw-semibold">
                                            {{$value->refill_amount}}
                                        </td>
                                        <td class="fw-semibold">
                                            {{$value->withdraw_amount}}
                                        </td>
                                        <td class="fw-semibold">
                                           {{$value->creds}}
                                        </td>
                                        <td class="fw-semibold">
                                            {{$value->points}}
                                        </td>
                                        <td class="fw-semibold">
                                           {{$value->amount}}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="pagination">

                            </div>
                        </div>
                        <div class="text-white text-center mt-5">
                            <table id="dataTables" class="table table-bordered table-condensed table-attractive2">
                                <thead>
                                <tr class="TODO row">
                                    <th class="SimpleText col-md-5">
                                        Shift count:  @if(isset($data)){{$data->shift_count}}@endif
                                    </th>

                                    <th class="SimpleText col-md-5">
                                        Total Purchases: @if(isset($data)){{$data->total_Purchases}}@endif
                                    </th>
                                    <th class="SimpleText col-md-5">
                                        Total Withdraw($) : @if(isset($data)){{$data->total_Withdraw}}@endif
                                    </th>
                                    <th class="SimpleText col-md-5">
                                        Total Credits($) : @if(isset($data)){{$data->total_Credits}}@endif
                                    </th>
                                    <th class="SimpleText col-md-5">
                                        Total Redeem($) : @if(isset($data)){{$data->total_Redeem}}@endif
                                    </th>

                                    <th class="SimpleText col-md-5">
                                        Balance($) : @if(isset($data)){{$data->total_Balance}}@endif
                                    </th>
                                </tr>
                                </thead>
                            </table>
                            <br>
                        </div>
                        <br>
                        <br>
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
