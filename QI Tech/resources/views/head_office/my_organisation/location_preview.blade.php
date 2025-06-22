
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Location :: {{env('APP_NAME')}}</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('admin_assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('admin_assets/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <link href="{{asset('admin_assets/css/style.css')}}" rel="stylesheet">
    <link href="{{route('head_office.location.color_css',$location->id)}}?p=1" rel="stylesheet">
    @if($location->preview->has_image)
        <style>
        

        html{
            background-image: url("{{$location->preview->bg}}");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: top;
        }

        </style>
        @endif

        <style>
            .msg-wrapper{
                position: absolute;
                top: 1rem;
                left: 1rem;
                display: flex;
                align-items: center;
                gap:2rem;
                z-index: 100;
                background: black;
                padding: 1rem;
                border-radius: 4px;
                color: white;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.104);
            }
        </style>    



</head>

<body id="page-top" class="custom-background-color" style="position: relative;">

    <div class="msg-wrapper" id="draggableDiv">
        <p style="margin: 0;">Remotely accessing location: {{$location->trading_name}}</p>
        <button class="btn btn-outline-light" onclick='closeWindow()'>Exit</button>

    </div>
    

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow" style="text-align: right; margin-right:20px;">
            <a class="nav-link text-info font-weight-bold" href="#" >
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Sign Out
            </a>

        </li>
    </ul>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Begin Page Content -->
            <div class="container-fluid custom-background ">

                <div class="text-center pt-5">
                    <div class="pb-2 text-gray-800">
                        <img src="{{$location->preview->logo}}" class="img-fluid img-thumbnail" width="150">
                    </div>
                    <p class="font-weight-bold">{{ $location->trading_name }}, {{ $location->address_line1}}, 
                        {{ $location->town}}, {{ $location->postcode}}</p>
                </div>

                <div class="row m-5">
                    <div class="col-md-8 box-icon-collection-border text-center">

                        <h3 class="h5 text-info font-weight-bold">Who are you?</h3>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Search" aria-label="search" aria-describedby="basic-addon1">
                        </div>

                        <div>No Quick Logins available...</div>
                        <br />
                        <div class="row text-dark no-link">


                                <div class="col-md-3">
                                    <a class="link-unstyled" href="#" data-toggle="modal" data-target="#pinloginModal">
                                    <div class="card card-hover">
                                        <div class="card-body">
                                            <h1 class="font-weight-bold">YN</h1>
                                            <p class="user-card-name">Your Name</p>
                                        </div>
                                    </div>
                                    </a>
                                </div>


                        </div>

                        <button data-toggle="modal" data-target="#loginModal" class="btn btn-info font-weight-bold">Login with using Email Address instead</button>
                        <p class="mt-3 small font-weight-bold">
                            <a class="link-unstyled" href="/app.html#!/signup/user">Don't have a User Account?</a>
                        </p>
                    </div>
                    <div class="col-md-4 text-center">

                        <h3 class="h5 text-info font-weight-bold">Quick Record</h3>
                        <button class="d-inline-block btn btn-outline-info">Near Miss</button>
                        <button class="d-inline-block btn btn-outline-info">Dispensing incident</button>
                    </div>

                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->


<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal"
     aria-hidden="true">


     <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="change_password_ModalLabel">Login to your user account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form action="#">
            <div class="modal-body">

                    <input type="hidden" name="type" value="1">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Password" class="form-control" required>
                    </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox small">
                        <input type="checkbox" value="1" class="custom-control-input" id="pin_check" name="pin_check">
                        <label class="custom-control-label" for="pin_check">Do you want to create a pin?
                           </label>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-info">Login</button>
            </div>
                </form>
        </div>
    </div>
</div>


<!-- Pin Login -->
<div class="modal fade" id="pinloginModal" tabindex="-1" role="dialog" aria-labelledby="pinloginModal"
     aria-hidden="true">


     <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="change_password_ModalLabel">Login with your pin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form id="pinform" action="{{route('location.pinlogin')}}" method="post">
                    <input type="hidden" id="uid" name="uid">
                    <input type="hidden" id="pin2" name="pin2">
            <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="pin">Please Enter your Pin</label>
                        <div style="width:200px">
                            <input type="text" id="pincode-input1"  >
                        </div>



{{--                        <input tabindex="-1" maxlength="4" minlength="4" type="password" id="pin" name="pin" placeholder="Pin code" class="form-control" required>--}}
                    </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            </div>
                </form>
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

<script>
     var draggableDiv = document.getElementById('draggableDiv');

// Function to make the div draggable
function dragElement(element) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    element.onmousedown = dragMouseDown;

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        element.style.top = (element.offsetTop - pos2) + "px";
        element.style.left = (element.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

// Call the function to make the div draggable
dragElement(draggableDiv)
    
    function closeWindow() {
    window.close();
    }
    var uid = -1;

    $("#pin").on("input",
    function()
    {
        let elem = this;
        //console.log(elem);
        if(elem.value.length > 3)
        {
            $('#uid').val(uid);
            $('#pin2').val(elem.value);
            $(elem).attr('disabled', true);

            $('#pinform').submit();
            //console.log($('#pinform'));
        }
            
    });

    function set_focus()
    {
        
    $('#pinloginModal').focus();
        $('#pin').focus();
        $('#pin').removeAttr('disabled');
    }

    </script>


    <script type="text/javascript" src="{{asset('admin_assets/js/bootstrap-pincode-input.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#pincode-input1').pincodeInput({
                hidedigits: true, complete: function (value, e, errorElement) {

                    $("#pincode-callback").html("This is the 'complete' callback firing. Current value: " + value);

                    // check the code
                    if(value!="1234"){
                        $(errorElement).html("The code is not correct.'");
                    }else{
                        alert('code is correct!');
                    }

                }});

            $('#pincode-input5').pincodeInput({hidedigits:true,inputs:4,placeholders:"0 0 0 1",change: function(input,value,inputnumber){
                    $("#pincode-callback2").html("onchange from input number "+inputnumber+", current value: " + value);
                }            });

            $('#pincode-input1').pincodeInput({hidedigits: true, inputs: 4});
            $('#pincode-input4').pincodeInput({hidedigits: false, inputs: 4});
            $('#pincode-input2').pincodeInput({
                hidedigits: false, inputs: 6, complete: function (value, e, errorElement) {
                    $("#pincode-callback").html("Complete callback from 6-digit test: Current value: " + value);

                    $(errorElement).html("I'm sorry, but the code not correct");
                }
            });
            $('#pincode-input6').pincodeInput({hidedigits: false, inputs: 4});
            $('#pincode-input7').pincodeInput({hidedigits: false, inputs: 4, inputclass: 'form-control-lg'});

            // show modal on button click
            $('#modalshow').click(function () {
                $('#modal-enter-pin').modal('show');
            });

            // show modal once
            $('#modal-enter-pin').on('shown.bs.modal', function (e) {
                $('#pincode-input8').pincodeInput({
                    inputs: 4,
                    complete: function (text) {
                        alert('your code ' + text);
                        $('#modal-enter-pin').modal('hide');
                    }
                });
                //autofocus
                $('#pincode-input8').pincodeInput().data('plugin_pincodeInput').clear();
                $('#pincode-input8').pincodeInput().data('plugin_pincodeInput').focus();
            });
        });
    </script>




</body>

</html>