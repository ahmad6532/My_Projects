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
                    <form action="{{route('trans')}}" method="post">
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
                        <div class="scroll sscroll">
                            <table
                                class="table table-vcenter text-white tab-bord1 table-condensed dataTableTransactions"
                                id="dataTables">
                                <thead class="tabel-color">
                                <tr class="row1">
                                    <th>{{ __('Issue Date') }}</th>
                                    <th>{{ __('Customer Mobile Number') }}</th>
                                    <th>{{ __('Credits') }}</th>
                                    <th>{{ __('Redeems') }}</th>
                                    <th>{{ __('Cach Amount') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($tdata)){?>
                                @foreach($tdata as $value)
                                    <tr class="tab-bord">
                                        <td class="fw-semibold">
                                            {{$value->date}}</td>
                                        <td class="d-none d-sm-table-cell">
                                            {{$value->phone_number}}
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            {{$value->creds}}

                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            {{$value->points}}
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            {{$value->amount}}

                                        </td>

                                    </tr>
                                @endforeach
                                <?php }?>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-white text-center mt-5">
                            <table id="dataTables" class="table table-bordered table-condensed table-attractive2">
                                <thead>
                                <tr class="TODO">
                                    <th class="SimpleText">
                                        <?php if (isset($countdata)){ ?>

                                        Customer Count : {{$countdata}}
                                        <?php }
                                        else {
                                            ?>
                                        Customer Count: 0
                                        <?php }?>

                                    </th>

                                    <th class="SimpleText">
                                        <?php if (isset($tsum)){ ?>

                                        Credits Added : {{$tsum}}
                                        <?php }
                                        else {
                                        ?>
                                        Credits Added: 0
                                        <?php }?>
                                    </th>
                                    <th class="SimpleText">

                                        <?php if (isset($tredeems)){ ?>

                                            Credits Redeem($) : {{$tredeems}}
                                        <?php }
                                        else {
                                        ?>
                                            Credits Redeem($) : 0
                                        <?php }?>

                                        </th>
                                    <th class="SimpleText">
                                        <?php if (isset($tbalance)){ ?>

                                            Balance($) : {{$tbalance}}
                                        <?php }
                                        else {
                                        ?>
                                            Balance($) : 0
                                        <?php }?>


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
    <script src="js/lib/jquery.min.js"></script>
    <script>
        $('#searchreports').click(function () {
            Dashmix.loader('show', 'bg-gd-TW');
        });
    </script>
@endsection
