<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="QI-Tech System">
    <meta name="author" content="Khuram Nawaz Khayam">

    <title>@yield('title') :: {{ env('APP_NAME') }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{asset('bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css')}}" rel="stylesheet">
    <link href="{{asset('admin_assets/css/style.css')}}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="{{route('location.color_css')}}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin_assets/css/progress-step.css')}}">
    <link rel="stylesheet" href="{{asset('/easyautocomplete/easy-autocomplete.min.css')}}">
</head>

<body id="page-top">

    <div id="wrapper">



        <!-- Sidebar -->

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="">

                <form action="{{route('external_link.be_spoke_forms.be_spoke_form.save')}}" method="post">
                    @csrf
                    <input type="hidden" id="form_name" name="form_name" value="{{$form->name}}">
                    <input type="hidden" id="form_stages" name="form_stages" value="{{count($form->stages)}}">
                    <input type="hidden" id="form_id" name="form_id" value="{{$form->id}}">

                    @include('layouts.error')
                    <?php $formToFillConditions = request()->query('condtions');
                          if(!empty($formToFillConditions)) { $formToFillConditions =  explode('-',$formToFillConditions);}
                          $currentCondition = \App\Models\Forms\ActionCondition::formToSubmit(request()->query('current_condition'));
                          //dd(request()->query('current_condition'));
                    ?>
                    @if(isset($formToFillConditions) && count($formToFillConditions))
                    @foreach($formToFillConditions as $c)
                    <input type="hidden" id="to_fill" name="to_fill[]" value="{{$c}}">
                    @endforeach
                    @endif

                    @if($currentCondition)
                    <div class="alert alert-success">{{$currentCondition['message']}}</div>
                    @endif
                    <div class="card" id="form">
                        <div class="card-body" class="s2">
                            <div class="mb-3">
                                <div class="float-left">
                                    <h4 class="text-info font-weight-bold">{{$form->name}}</h4>
                                </div>
                                <div class="btn-group btn-group-sm float-right" role="group">
                                    <a href="{{route('be_spoke_forms.be_spoke_form.index')}}" class="btn btn-info"
                                        title=" Be Spoke Form list">
                                        <span class="fas fa-list" aria-hidden="true"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="px-0 pt-4 pb-0 mt-3 mb-3">

                                    <ul id="progressbar" class="m-auto">
                                        @foreach($form->stages as $key=>$stage)
                                        <li class="progress-bar-list @if( $key == 0 ) active @endif"
                                            data-stage="{{($key+1)}}" id="step{{($key+1)}}">
                                            <strong>{{$stage->stage_name}}</strong></li>
                                        @endforeach
                                    </ul>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 33%"></div>
                                    </div>
                                    <br>

                                </div>
                                <span class="stage_name_top">Stage</span>
                            </div>
                            <script>
                                var conditions = [];
                                let condition;
                            </script>
                            @foreach($form->stages as $key=>$stage)
                            <div class="card stages stage_{{$stage->id}} stage_data_{{$key+1}}" @if($key !=0)
                                style="display:none" @endif>
                                <div class="card-body">
                                    @foreach($stage->groups as $group)
                                    <div class="card group group_{{$group->id}}">
                                        <div class="card-body">
                                            <h5 class="form-group-name">{{$group->group_name}}</h5>
                                            <div class="row">
                                                @foreach($group->questions as $question)

                                                @include('location.be_spoke_forms.question')
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach

                            <div class="col-md-12">
                                <div class="px-0 pt-4 pb-0 mt-3 mb-3">
                                    <select class="form-control" name="location_id">
                                        @foreach ($locations as $location)
                                        <option value="{{$location->id}}">{{$location->trading_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="center m-t-10">
                                <button class="previous-step btn btn-info stagePrevButton"
                                    style="float:none">Previous</button>
                                <button class="next-step btn btn-info stageNextButton" style="float:none">Next</button>
                                <input type="submit" style="display:none" class="btn btn-info formSubmitButton"
                                    name="submit" value="Submit">
                            </div>

                        </div>
                    </div>
                </form>




            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <!-- <div class="copyright text-center my-auto">
                        <span>Copyright &copy; {{ env('APP_NAME') . " " . \Carbon\Carbon::now()->year }} </span>
                    </div> -->
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('admin_assets/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('admin_assets/js/sb-admin-2.min.js')}}"></script>
    <script src="{{asset('bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('admin_assets/location-script.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{asset('/easyautocomplete/jquery.easy-autocomplete.min.js')}}"></script>

    @include('location.be_spoke_forms.script')
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzxvYQxd1yHydcBFIRNOLQjcbQtThH6rI&libraries=places&callback=initPlaces">
    </script>
</body>

</html>