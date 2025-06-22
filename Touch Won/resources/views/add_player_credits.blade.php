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
                                    <form method="post" action="{{route('pcbtn')}}">
                                        @csrf
                                        <table class="table table-vcenter text-white tab-bord1 dataTablePackages"
                                               id="dataTables">
                                            <thead class="tabel-color">
                                            <tr>
                                                <th class="border-1">{{ __('Mobile Number') }}</th>
                                                <th class="border-1">{{ __('Amount') }}</th>
                                                <th class="border-1">Credit Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody id="geeks">

                                            <form>
                                                <tr class="tab-bord">
                                                    <td align="center">
                                                        <input type="hidden" name="phone"
                                                               value="{{request()->account_id}}">
                                                        {{ Request::get('account_id') }}</td>
                                                    <td align="center">
                                                        <input type="hidden" name="amt" value="{{request()->val}}">
                                                        {{ Request::get('val') }}
                                                    </td>
                                                    <td align="center"><input type="submit"
                                                                              class="btn btn-success h-100"
                                                                              value="Confirm"></td>
                                                </tr>
                                            </form>
                                            </tbody>
                                        </table>
                                    </form>
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
    @if(session()->has('alert') && !is_array (session()->get('alert')))
        <script>
        
            swal("Success!", "{!!  session()->get('alert')!!}", "success");
        </script>
    @endif

    </script>
        @if(session()->has('go'))
            <script>
                swal("Warning!", "{!! session()->get('go')!!}", "warning");
            </script>
        @endif
@endsection


