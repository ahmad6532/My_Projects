<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('v2/fonts/LitteraText/stylesheet.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('v2/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('v2/css/my-profile.css') }}">
    <link rel="stylesheet" href="{{ asset('v2/css/colors.css') }}">
    {{-- <link href="{{ asset('admin_assets/css/select2.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('admin_assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/loader.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css">

    <link href="{{ asset('/css/dataTable-custom.css') }}" rel="stylesheet">
    <link href="{{ asset('admin_assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('tribute/tribute.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    <title>@yield('title') :: {{ env('APP_NAME') }}</title>
    @livewireStyles()
    @yield('styles')
    <style>
        .form-outline .form-control:focus~.form-notch .form-notch-middle,
        .form-outline .form-control.active~.form-notch .form-notch-middle {
            border-top: 1px solid #bdbdbd;
        }

        .form-outline .form-control:focus~.form-notch .form-notch-middle {
            box-shadow: 0 -1px 0 0 var(--mdb-input-focus-border-color),
                /* Top shadow */
                0 1px 0 0 var(--mdb-input-focus-border-color);
            border-top: 1px solid #3b71ca;
            transition-delay: 0.05s;
        }

        .form-outline .form-control:focus~.form-label,
        .form-outline .form-control.active~.form-label {
            transform: translateY(-1.7rem) translateY(0.1rem) scale(0.8) translateX(-14px);
        }

        .search-addon {
            margin-right: -40px;
            z-index: 3;
        }

        .search-input::placeholder {
            color: #999 !important;
            padding-left: 1.5rem;
            transition: 0.2s ease-in-out;
        }

        .search-input:focus::placeholder {
            padding-left: 0;
            transition: 0.2s ease-in-out;
        }

        .search-input:not(:placeholder-shown):not(:focus) {
            padding-left: 40px;
            transition: 0.2s ease-in-out;
        }

        .search-input {
            border: 2px solid #D9D9D9;
        }

        .resizing-input span {
            visibility: hidden;
            white-space: pre;
            font-size: inherit;
            font-family: inherit;
            font-weight: inherit;
            letter-spacing: inherit;
            padding: 0;
            margin: 0;
        }
    </style>

</head>

<body>
    @include('layouts.company.header')

    <div class="sub-header">
    @yield('sub-header')

    <!-- Back Button -->
    

    {{-- <button id="backButton" class="header-back-btn" id="backButton" style="display: none;">
        <i class="fa-solid fa-arrow-left"></i> Back
    </button> --}}

    <script>
        document.getElementById("backButton").onclick = function() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
            
                window.location.href = '';
            }
        };
    </script>
    
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var currentUrl = window.location.href;

        var pattern = /\/\d+$/;

        if (!currentUrl.match(pattern) && !currentUrl.includes('update')) {
            document.getElementById('backButton').style.display = 'inline-block';
        }
    });

    function goBack() {
        window.history.back();
    }
</script>

    <div class="wrapper">
        <!-- Sidebar ! Only where required ! -->

        @yield('sidebar')


        @yield('content')

    </div>

    <!-- Font awesome -->
    <script src="https://kit.fontawesome.com/350d033a5a.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
    <link href="{{ asset('admin_assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    @livewireScripts

    @stack('scripts')

    <script src="{{ asset('v2/js/main.js') }}"></script>
    
    <script src="{{ asset('mdboostrap/js/mdb.umd.min.js') }}"></script>
    <script src="{{ asset('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('js/alertify.min.js') }}"></script>
    
    
    {{-- <script src="{{ asset('admin_assets/js/select2.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    
    <script src="{{ asset('admin_assets/js/infinite-scroll.pkgd.min.js') }}"></script>
    <script src="{{ asset('admin_assets/head-office-script.js') }}"></script>

    <script src="{{ asset('admin_assets/js/view_case.js') }}"></script>
    <script src="{{ asset('admin_assets/js/intlTelInput-jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin_assets/js/JavaScriptSpellCheck/include.js') }}" type="text/javascript"></script>



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
        console.log(window.location.pathname, tabHistory)
    </script>

    <script>
        $('.select_group').select2();


            var $inputs = $('.resizing-input');

            // Resize based on text if text.length > 0
            // Otherwise resize based on the placeholder
            function resizeForText(text) {
                var $this = $(this);
                if (!text.trim()) {
                    text = $this.attr('placeholder').trim();
                }
                var $span = $this.parent().find('span');
                $span.text(text);
                var $inputSize = $span.text().length + 1;
                console.log($this);
                $this.css("width", $inputSize + 'ch');
            }

            $inputs.find('input').keypress(function(e) {
                if (e.which && e.charCode) {
                    var c = String.fromCharCode(e.keyCode | e.charCode);
                    var $this = $(this);
                    resizeForText.call($this, $this.val() + c);
                }
            });

            // Backspace event only fires for keyup
            $inputs.find('input').keyup(function(e) {
                if (e.keyCode === 8 || e.keyCode === 46) {
                    resizeForText.call($(this), $(this).val());
                }
            });
            $inputs.find('input').on('input paste', function() {
                var $this = $(this);
                setTimeout(function() { // Ensure the pasted content is available
                    resizeForText.call($this, $this.val());
                }, 0);
            });

            $inputs.find('input').each(function() {
                var $this = $(this);
                resizeForText.call($this, $this.val())
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


    function adjustCardPosition($element) {
    const cardWidth = $element.outerWidth();
    const cardHeight = $element.outerHeight();
    const cardOffset = $element.offset();
    const viewportWidth = $(window).width();
    const viewportHeight = $(window).height();

    // Ensure the card does not overflow on the right
    if (cardOffset.left + cardWidth > viewportWidth) {
        $element[0].style.setProperty('left', `-${cardWidth - 110}` + 'px', 'important');
    }

    // Ensure the card does not overflow on the left
    if (cardOffset.left < 0) {
        // $element[0].style.setProperty('left', '10px', 'important');
    }

    // Ensure the card does not overflow on the bottom
    if (cardOffset.top + cardHeight > viewportHeight) {
        // $element[0].style.setProperty('top', -( cardHeight - 10) + 'px', 'important');
    }

    // Ensure the card does not overflow on the top
    if (cardOffset.top < 0) {
        $element[0].style.setProperty('top', '10px', 'important');
    }
}

$('.new-card-wrap').on('mouseenter', function(event) {
    const $infoWrapper = $(this).find('.new-info-wrapper');
    $infoWrapper.stop(true, true).fadeIn().css({
        'display': 'flex'
    });

    // Adjust position to keep it within bounds
    adjustCardPosition($infoWrapper);
});

$('.new-card-wrap').on('mouseleave', function(event) {
    $(this).find('.new-info-wrapper').stop(true, true).fadeOut();
});

$('.new-info-wrapper').on('mouseenter', function(element) {
    const $this = $(this);
    $this.stop(true, true).fadeIn('fast').css({
        'display': 'flex',
        'transition': '0'
    });

    // Adjust position to keep it within bounds
    adjustCardPosition($this);
});

$('.new-info-wrapper').on('mouseleave', function() {
    $(this).stop(true, true).fadeOut();
});


    $('.view-info-btn').on('click',function(e){
        console.log('asad call')
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().find('.expirtise-wrap').slideToggle();
    })
    
</script>


    @yield('scripts')

@if (!in_array(request()->route()->getName(), [
    'case_manager.index',
    'case_manager.view',
    'head_office.be_spoke_forms_templates.form_template'
]))
    <script>
    $(document).ready(function() {
                $('[spellcheck="true"]').each(function () {
            // Exclude select2 inputs or textarea elements
            if (!$(this).is('textarea, .select2, .select2-container')) {
                $Spelling.SpellCheckAsYouType(this);
            }
        });
        });
    </script>
    
    {{-- <script>
        function toggleDivVisibility() {
            var selectedOption = document.getElementById('help-desk-select').value;
            var subOpsDiv = document.getElementById('sub-ops');
            
            if (selectedOption == '1') {
                sessionStorage.setItem('helpDeskSelection', '1');
            } else {
                sessionStorage.setItem('helpDeskSelection', '2');
            }
            location.reload();
        }
    
        window.onload = function() {
            var storedSelection = sessionStorage.getItem('helpDeskSelection');
            var subOpsDiv = document.getElementById('sub-ops');
            var selectElement = document.getElementById('help-desk-select');
    
            if (storedSelection === '1') {
                selectElement.value = '1';
                subOpsDiv.style.display = 'none';
            } else if (storedSelection === '2') {
                selectElement.value = '2';
                subOpsDiv.style.display = '';
            }
        }
    </script> --}}
    <script>
        function toggleHelpTextarea(checkbox) {
            const textarea = document.getElementById('help_input');
            if (checkbox.checked) {
                textarea.removeAttribute('readonly');
            } else {
                textarea.setAttribute('readonly', 'readonly');
            }
        }
    </script>
    
@endif

</body>

</html>
