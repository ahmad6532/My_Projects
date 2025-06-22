


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('v2/fonts/LitteraText/stylesheet.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/alertify.min.css') }}">
    <link rel="stylesheet" href="{{asset('v2/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('v2/css/my-profile.css')}}">
    <link rel="stylesheet" href="{{asset('v2/css/colors.css')}}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link href="{{asset('admin_assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('/css/dataTable-custom.css')}}" rel="stylesheet">
    <title>QI Tech 2.0</title>

    @yield('styles')
</head>

<body>

    <div class="sub-header">
    </div>

    <div class="wrapper">
        <nav id="sidebar" class="sidebar-container-user">
            @yield('sidebar');
        </nav>
        <!-- Page Content  -->
        <div id="content">
    
            <!-- Profile page heading -->
    
            <div style="display: flex; justify-content: flex-end;">
    
                <div>
    
                    <div class="right-side-items">
                        <div class="notification-icon signout-label">
                            <a href="{{route('user.logout')}}" title="Signout">
                            Sign out 
                            </a>
                        </div>
                     <!--
                        <div class="notification-icon">
                            <a href="#" title="Notifications">
                                <i class="fa-regular fa-bell" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="help-icon">
                            <a href="#" title="Help">
                                <i class="fa-regular fa-question" aria-hidden="true"></i>
                            </a>
                        </div>
                     -->
                    </div>
    
                </div>
    
            </div>
    
            @yield('content')
    
            
        </div>
    </div>
<div class="modal-backdrop fade show" id="pinder" style="display: none;">    
</div> 
    <img class="cloud-img1" src="{{asset('v2/images/cloud1.jpg')}}" />
    <img class="cloud-img2" src="{{asset('v2/images/cloud2.jpg')}}" />

    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/350d033a5a.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('v2/js/main.js')}}"></script>
    
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('admin_assets/js/sb-admin-2.min.js')}}"></script>
    <script src="{{asset('v2/js/user.js')}}"></script>
    @yield('scripts')

    <script>
        function changeTabUrl(tabId, subTabId = null) {
            const currentURL = new URL(window.location.href);
            console.log(subTabId)
            currentURL.searchParams.set('tab', tabId);
            // if (subTabId !== null) {
            //     currentURL.searchParams.set('subTab', subTabId);
            // }
            window.history.pushState({
                tabId: tabId,
                subTabId: subTabId
            }, null, currentURL.href);

            $('#' + tabId).tab('show');

            if (subTabId !== null) {
                $('#' + subTabId).tab('show');
            }
        }

        window.addEventListener('popstate', function(event) {
            if (event.state != null) {
                const tabId = event.state.tabId;
                const subTabId = event.state.subTabId;
                $('#' + tabId).tab('show');
                if (subTabId !== null) {
                    $('#' + subTabId).tab('show');
                }
            } else {
                history.back()
            }
        });

        let tabHistory = [];
        const companyInfoChilds = ['companyInfoClick', 'depInfoClick', 'veriDeviceClick', 'themeClick']

        function changeTab(tabId) {
            const lastTabId = tabHistory.slice(-1)[0];
            if (tabId !== lastTabId) {
                tabHistory.push(tabId);
            }
        }

        function goBack() {
            if (/^\/head_office\/case\/manager\/view\/\d+$/.test(window.location.pathname)) {
                console.log("Hello");
                window.location.replace("/head_office/case/manager");
                return 
            }
            history.back()
            // if (tabHistory.length > 1) {
            //     const previousTabId = tabHistory.pop();
            //     const lastTabId = tabHistory.slice(-1)[0];
            //     if(companyInfoChilds.includes(lastTabId)){
            //         $('#infoClick').tab('show');
            //     } 
            //     $('#' + lastTabId).tab('show');
            // }
        }
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
</body>
@if(Session::has('success'))
    <script>
        alertify.success("{{ Session::get('success') }}");
    </script>
@endif
@if(Session::has('error'))
    <script>
        alertify.error("{{ Session::get('error') }}");
    </script>
@endif

</html>