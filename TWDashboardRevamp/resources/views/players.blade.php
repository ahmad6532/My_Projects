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
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length text-center" id="DataTables_Table_0_length">

                                <h5> {{ __('Enter Customer Phone Number') }} :</h5>

                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="DataTables_Table_0_filter" class="playerdataTables_filter">
                                <form action="" method="">
                                    @csrf

                                    <div id="filter" class=" filter placeholder-color-search"><label><input
                                                type="search"
                                                class="form-control search text-white placeholder-color-search form-control"
                                                placeholder="{{ __('Search Here') }}..." aria-controls="dataTables"
                                                id="searchme"></label></div>

                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="block-content">
                        <div class="scroll sscroll">
                            <table class="table table-vcenter text-white tab-bord1 dataTablePlayers" id="dataTables">
                                <thead class="tabel-color">
                                <tr class="row1">
                                    <th class="border-1 ">{{ __('Phone Number') }}</th>
                                    <th class="border-1 min-phone-l">{{ __('Pin') }}</th>
                                    <th class="border-1">{{ __('Credits Balance') }}</th>
                                    <th class="border-1">{{ __('Points') }}</th>
                                    <th class="border-1">{{ __('Created Date') }}</th>
                                    <th class="border-1">{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody id="geeks">

                                @foreach ($data as $item)
                                    <tr class="tab-bord">
                                        <td class="fw-semibold">
                                            {{ $item->phone_number}}
                                        </td>
                                        <td class="">
                                            {{ $item->player_PIN}}
                                        </td>
                                        <td class="">
                                            {{ $item->credits}}

                                        </td>
                                        <td class="">
                                            {{ $item->points}}
                                        </td>
                                        <td class="">
                                            {{date('d-m-y',strtotime($item->created_on))}}

                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a type="button" class="btn btn-sm btn-alt-secondary"
                                                   href="{{ url('editplayer'.$item->player_id)}}"
                                                   data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fa fa-pencil-alt pencil-color"></i>
                                                </a>
                                                <a type="button" class="btn btn-sm btn-alt-secondary"
                                                   href="{{ url('deleteplayer'.$item->player_id)}}"
                                                        data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                onclick="return confirm('Are you sure you want to delete this account?')">
                                                    <i class="far fa-1x fa-trash-can errors"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br>
                    <!--<div class="text-white">
                            {{ $data->links() }}
                        </div>-->

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


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @if(session()->has('alert') && !is_array (session()->get('alert')))
        <script>

            swal("Success!", "{!! session()->get('alert')!!}", "success");
        </script>
    @endif
@endsection
