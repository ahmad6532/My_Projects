<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css">
    <link href="{{ asset('v2/fonts/LitteraText/stylesheet.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('v2/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('v2/css/my-profile.css') }}">
    <link rel="stylesheet" href="{{ asset('v2/css/colors.css') }}">
    <link href="{{ asset('bootstrap-datepicker/css/bootstrap-datepicker3.standalone.css') }}" rel="stylesheet">
    <link href="{{ asset('admin_assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
    <link href="{{ asset('admin_assets/css/select2.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/css/dataTable-custom.css') }}" rel="stylesheet">
    <title>@yield('title') :: {{ env('APP_NAME') }}</title>
    <style>
        .msg-wrapper {
            position: absolute;
            top: 1rem;
            left: 1rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            z-index: 100;
            background: black;
            padding: 1rem;
            border-radius: 4px;
            color: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.104);
            cursor: move;
        }

        #logout-warning {
            display: none;
            position: fixed;
            top:50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #ffcccb;
            padding: 20px;
            border: 1px solid #f00;
            color: #000;
            z-index: 9999;
        }
    </style>

    @yield('styles')
</head>

<body>

    @include('layouts.location.header')

    {{-- @if (session()->has('remote_access'))
    <div class="msg-wrapper" id="draggableDiv">
        @isset($location)
        <p style="margin: 0;">Remotely accessing location: {{$location->trading_name}}</p>
        <button class="btn btn-outline-light" onclick='clearRemoteAccessSession()'>Exit</button>
        @endisset
    </div>
    @endif --}}

    <script>
        @if (session()->has('remote_access'))
            sessionStorage.setItem('remote_access', '1');
        @endif
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            if (sessionStorage.getItem('remote_access') === '1') {
                document.getElementById('draggableDiv').style.display = 'flex';
            }
        });

        function clearRemoteAccessSession() {
            sessionStorage.removeItem('remote_access');
            document.getElementById('draggableDiv').style.display = 'none';
        }
    </script>
        @if(isset($location) && session()->has('remote') && session('remote') == true) 
    <div class="msg-wrapper" id="draggableDiv" style="display:none;">
            <p style="margin: 0;">Remotely accessing location: {{ $location->trading_name }}</p>
            <button class="btn btn-outline-light" onclick='clearRemoteAccessSession()'>Exit</button>
        </div>
        @endif

    <div class="sub-header">
        @yield('subHeader')
    </div>
    <div class="wrapper">
        @yield('content')
    </div>

    <div id="logout-warning">
        <p>You will be logged out in <span id="countdown">10</span> seconds...</p>
    </div>
    <div class="modal-backdrop fade show" id="pinder" style="display: none;">
        <!-- Font awesome -->
        <script src="https://kit.fontawesome.com/350d033a5a.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
        <script src="{{ asset('js/alertify.min.js') }}"></script>
        <script src="{{ asset('v2/js/main.js') }}"></script>
        <script src="{{ asset('admin_assets/js/select2.min.js') }}"></script>
        @yield('scripts')
        <script>
            function closeWindow() {
                window.close();
            }

            var draggableDiv = document.getElementById('draggableDiv');

            // Function to make the div draggable
            function dragElement(element) {
                var pos1 = 0,
                    pos2 = 0,
                    pos3 = 0,
                    pos4 = 0;
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


            function clearRemoteAccessSession() {
                $.ajax({
                    url: "{{ route('session.clearRemoteAccess') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                window.close();
            }

            function closeWindow() {
                clearRemoteAccessSession().done(function(data) {
                    if (data.success) {
                        window.close(); // Close the tab
                    } else {
                        alert('Failed to clear session');
                    }
                }).fail(function(error) {
                    console.error('Error:', error);
                });
            }

            $(window).on('beforeunload', function() {
                clearRemoteAccessSession().fail(function(error) {
                    console.error('Error:', error);
                });
            });
        </script>

<script>
    $(document).ready(function () {
        let inactivityTime = 5 * 60 * 1000; // 5 minutes in milliseconds
        let countdownTime = 10; // 10 seconds countdown
        let inactivityTimer, countdownTimer;

        // Reset inactivity timer on user activity
        function resetInactivityTimer() {
            clearTimeout(inactivityTimer);
            clearInterval(countdownTimer);
            $('#logout-warning').hide(); // Hide warning if visible
            inactivityTimer = setTimeout(showLogoutWarning, inactivityTime);
        }

        // Show logout warning and start countdown
        function showLogoutWarning() {
            let countdown = countdownTime;
            $('#logout-warning').show();
            $('#countdown').text(countdown);

            countdownTimer = setInterval(() => {
                countdown--;
                $('#countdown').text(countdown);
                if (countdown <= 0) {
                    clearInterval(countdownTimer);
                    logoutUser();
                }
            }, 1000);
        }

        // Simulate user logout
        function logoutUser() {
            // alert('You have been logged out due to inactivity!');
            // Redirect to logout page or perform logout action
            window.location.href = '/user/logout'; // Replace with actual logout URL
        }

        // Detect user activity
        $(document).on('mousemove keypress click scroll', resetInactivityTimer);

        // Start inactivity timer on page load
        resetInactivityTimer();
    });
</script>

<script>
    function setRandomBackgroundColor(elementId) {
        var randomColor = '#' + Math.floor(Math.random() * 16777215).toString(16);
        console.log(randomColor)
        $('#' + elementId).css('background-color', randomColor);
    }

    setRandomBackgroundColor('user-img-place');
    setRandomBackgroundColor()


    $('.new-card-wrap').on('mouseenter', function(event) {
        $(this).find('.new-info-wrapper').stop(true,true).fadeIn().css({
                'display': 'flex'
            });
    });

    $('.new-card-wrap').on('mouseleave', function(event) {
       
        $(this).find('.new-info-wrapper').stop(true,true).fadeOut();
    });

    $('.new-info-wrapper').on('mouseenter', function(element) {
        $(this).stop(true,true).fadeIn('fast').css({
            'display': 'flex',
            'transition': '0'
        })


    });

    $('.new-info-wrapper').on('mouseleave', function() {
        $('.new-info-wrapper').stop(true,true).fadeOut();
    });

    $('.view-info-btn').on('click',function(e){
        console.log('asad call')
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().find('.expirtise-wrap').slideToggle();
    })

</script>

        <script src="{{ asset('admin_assets/location-script.js') }}"></script>
        <?php if ($location): ?>
        <script>
            var primaryColor = <?php echo json_encode($location->organization_setting_assignment->organization_setting->bg_color_code ?? '#34BFAF'); ?>;
            document.documentElement.style.setProperty('--location-primary-color', primaryColor);
        </script>
        <?php endif; ?>

</body>

</html>
