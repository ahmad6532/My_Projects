<!DOCTYPE html>
<html data-wf-page="6564282be531be60fd0d3924" data-wf-site="6564282be531be60fd0d391f">

<head>
    <style>
        .pass-wrapper {
            margin-top: 0.3rem;
        }

        .pass-graybar,
        .pass-colorbar {
            height: 4px;
            border-radius: 10px;
        }

        .emojione {
            width: 15px;
            font-size: 15px;
        }

        .pass-text {
            font-size: 12px;
            color: #999;
            font-weight: 500;
        }

        .img-circle {
            width: 250px;
            position: relative;
        }

        .img-fluid-cus {
            width: 250px;
            bottom: 18%;
            position: relative;
        }

        .img-circle {
            bottom: -72%;
            width: 280px;
        }

        .img-cloud1 {
            top: 30%;
            left: -1%;
        }

        .img-cloud2 {
            bottom: -23%;
        }

        label {
            color: #999;
            font-size: 12px;
            font-weight: 500;
        }

        .btn-block {
            background: #d79d20;
        }

        .btn-block:hover {
            background: #e8ac2d;
        }

        input.form-control:focus {
            border-color: #d79d20;
        }

        .back-button-login {
            box-shadow: none !important;
        }

        /* / */

        .input-wrapper {
            position: relative;
            margin: 10px auto;
        }

        .form-control {
            display: block;
            line-height: 2em;
            margin: 0;
            padding-left: 10px;
            width: 100%;
            font-size: medium;
            border: 2px solid #d9d9d9;
            border-radius: 5px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            font-weight: 500;
        }

        .border-cus {
            border: 2px solid #d9d9d9 !important;

        }

        .border-cus:focus {
            border: 2px solid #d79d20 !important;
        }

        .form-control:focus {
            border: 1px solid #2c7ac9;
        }

        .control-label {
            display: block;
            position: absolute;
            opacity: 0;
            bottom: 22px;
            color: #5d5d5d;
            transition: 0.2s ease-in-out transform;
            font-size: 12px;
        }

        .form-control:placeholder-shown+.control-label {
            visibility: hidden;
            z-index: -1;
            transition: 0.2s ease-in-out;
        }

        .form-control:not(:placeholder-shown)+.control-label,
        .form-control:focus:not(:placeholder-shown)+.control-label {
            visibility: visible;
            z-index: 1;
            opacity: 1;
            transform: translateY(-10px);
            transition: 0.2s ease-in-out transform;
        }

        .form-control:focus+.control-label {
            visibility: visible;
            z-index: 1;
            opacity: 1;
            transform: translateY(-10px);
            transition: 0.2s ease-in-out transform;
            color: #cfa144;
        }

        .form-control:focus::placeholder {
            visibility: hidden;
            color: white;
        }

        .form-control::placeholder {
            color: #bebdbd;
        }

        .box {
            /* padding: 10px; */
            border-radius: 10px;
            background-color: #fff;
            margin: 0 auto;
        }

        .iti--show-selected-dial-code .iti__selected-flag {
            background: none;
            border-right: 2px solid #d9d9d9;
        }

        .checkbox-inline {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 14px;
        }

        .checkbox-subgroup {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        input:valid,
        input:in-range {
            border-color: #d9d9d9;
        }
        .form-field{
            width:80%
        }
    </style>
    <meta charset="utf-8" />
    <title>Qi-Tech</title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="Webflow" name="generator" />
    <link href="{{ asset('admin_assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('webflow_assets/home-about/css/webflow-style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('webflow_assets/shared_styles.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="anonymous" />
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
    <script type="text/javascript">
        WebFont.load({
            google: {
                families: ["Literata:regular"]
            }
        });
    </script>
    <script type="text/javascript">
        ! function(o, c) {
            var n = c.documentElement,
                t = " w-mod-";
            n.className += t + "js", ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n
                .className += t + "touch")
        }(window, document);
    </script>
    <link href="{{ asset('webflow_assets/home-about/images/favicon.png') }}" rel="shortcut icon" type="image/x-icon" />
    <link href="{{ asset('webflow_assets/home-about/images/app-icon.png') }}" rel="apple-touch-icon" />
</head>

<body class="body" style="overflow-x: hidden;">
    <div data-w-id="ab58a37d-af03-2beb-8f01-3e83a4396123" class="w-layout-blockcontainer loading-screen w-container">
        <div class="w-layout-blockcontainer logo w-container"><img
                src="{{ asset('webflow_assets/home-about/images/qi-20tech-20logo-ai-20-1-.png') }}" loading="lazy"
                data-w-id="b4380dcf-5519-861c-c12d-e3a63e75e708" alt="" class="image-36" /></div>
        <div class="w-layout-blockcontainer message w-container"></div>
    </div>
    <div class="navigation-bar">
        <div data-animation="default" data-collapse="medium" data-duration="400" data-easing="ease-out-expo"
            data-easing2="ease-out-expo" data-doc-height="1" role="banner"
            class="navbar-logo-left-container shadow-three w-nav">
            <div class="container-2">
                <div class="navbar-wrapper"><a href="{{ route('home') }}" class="navbar-brand w-nav-brand"><img
                            src="{{ asset('webflow_assets/home-about/images/qi-20tech-20logo-ai-20-1-.png') }}"
                            loading="lazy" width="95" alt="" class="image-10" /></a>
                    <div data-w-id="f8173a61-7eb3-d65f-39f5-69a223cf0a9b" class="menu-button-2 w-nav-button">
                        <div class="icon-4 w-icon-nav-menu"></div>
                    </div>
                    <nav role="navigation" class="nav-menu-wrapper w-nav-menu">
                        <ul role="list" class="nav-menu-mobile w-list-unstyled">
                            <li class="list-item-3">
                                <div class="div-block-56"><img
                                        src="{{ asset('webflow_assets/home-about/images/home.png') }}" loading="lazy"
                                        sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/home-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/home-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/home-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/home-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/home.png') }} 1910w"
                                        alt="" /></div><a href="/" aria-current="page"
                                    class="nav-link w--current">Home</a>
                            </li>
                            <li class="list-item-5">
                                <div class="div-block-57"><img
                                        src="{{ asset('webflow_assets/home-about/images/features-p-500.png') }}"
                                        loading="lazy" alt="" /></div><a href="#Features"
                                    class="nav-link">Features</a>
                            </li>
                            <li class="list-item-4">
                                <div class="div-block-60"><img
                                        src="{{ asset('webflow_assets/home-about/images/our-20team-p-500.png') }}"
                                        loading="lazy" alt="" /></div><a href="/about-us" class="nav-link">Our
                                    Team</a>
                            </li>
                            <li class="list-item-7">
                                <div class="div-block-58"><img
                                        src="{{ asset('webflow_assets/home-about/images/contact-20us-p-500.png') }}"
                                        loading="lazy" alt="" /></div><a href="#Contact-Us"
                                    class="nav-link">Contact</a>
                            </li>
                            <li class="list-item-9">
                                <div class="div-block-59"><img
                                        src="{{ asset('webflow_assets/home-about/images/policies-p-500.png') }}"
                                        loading="lazy" alt="" /></div><a href="/about-us"
                                    class="nav-link">Policies</a>
                            </li>
                            <li class="list-item-9">
                                <div class="div-block-59"><img
                                        src="{{ asset('webflow_assets/home-about/images/careers-p-500.png') }}"
                                        loading="lazy" alt="" /></div><a href="/about-us"
                                    class="nav-link">Careers</a>
                            </li>
                            <li class="list-item-9 login"><a href="{{ route('login') }}"
                                    class="link-block-3 w-inline-block">
                                    <div class="text-block-126">Login</div>
                                </a></li>
                            <li class="list-item-9 signup"><a href="{{ route('signup') }}"
                                    class="link-block-3-copy w-inline-block">
                                    <div class="text-block-126">Sign Up</div>
                                </a></li>
                        </ul>
                        <ul role="list" class="nav-menu-two w-list-unstyled">
                            <li class="list-item-3">
                                <div class="div-block-56"><img
                                        src="{{ asset('webflow_assets/home-about/images/home.png') }}" loading="lazy"
                                        sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/home-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/home-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/home-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/home-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/home.png') }} 1910w"
                                        alt="" /></div><a href="#Features" class="nav-link">Home</a>
                            </li>
                            <li class="list-item-5">
                                <div class="div-block-57"><img
                                        src="{{ asset('webflow_assets/home-about/images/features.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/features-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/features-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/features-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/features-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/features.png') }} 1883w"
                                        alt="" /></div><a href="#" id="select-industry"
                                    class="nav-link nav-link-new select-industry">Select Industry</a>
                            </li>
                            <li class="list-item-4">
                                <div class="div-block-60"><img
                                        src="{{ asset('webflow_assets/home-about/images/our-20team.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/our-20team-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/our-20team-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/our-20team-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/our-20team-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/our-20team.png') }} 1618w"
                                        alt="" /></div><a href="#Features" class="nav-link nav-link-new">Our
                                    Team</a>
                            </li>
                            <li class="list-item-7">
                                <div class="div-block-58"><img
                                        src="{{ asset('webflow_assets/home-about/images/contact-20us.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/contact-20us-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/contact-20us-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/contact-20us-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/contact-20us-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/contact-20us.png') }} 1778w"
                                        alt="" /></div><a href="/about-us" class="nav-link nav-link-new">Who
                                    we are</a>
                            </li>
                            <li class="list-item-9">
                                <div class="div-block-59"><img
                                        src="{{ asset('webflow_assets/home-about/images/policies.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/policies-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/policies-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/policies-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/policies-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/policies.png') }} 1778w"
                                        alt="" /></div><a href="#" id="select-contact"
                                    class="nav-link nav-link-new">Contact Us</a>
                            </li>
                        </ul>

                    </nav>
                    <div class="w-layout-blockcontainer top-right-buttons w-container"><a
                            href="{{ route('signup') }}" class="button w-button">Sign Up</a><a
                            href="{{ route('login') }}" class="button-2 w-button">Account
                            Login</a></div>
                </div>
            </div>
        </div>
        <div data-animation="default" class="navbar w-nav" data-easing2="ease" data-easing="ease"
            data-collapse="medium" role="banner" data-no-scroll="1" data-duration="400" data-doc-height="1">
            <div class="container-43 w-container"><a href="https://qi-tech.webflow.io/"
                    class="navbar-brand w-nav-brand"><img
                        src="{{ asset('webflow_assets/home-about/images/qi-20tech-20logo-ai-20-1-.png') }}"
                        loading="lazy" width="95" alt="" class="image-10" /></a>
                <nav role="navigation" class="nav-menu w-nav-menu">
                    <ul role="list" class="nav-menu-mobile w-list-unstyled">
                        <li class="list-item-3">
                            <div class="div-block-56"><img
                                    src="{{ asset('webflow_assets/home-about/images/home-p-500.png') }}"
                                    loading="lazy" alt="" /></div><a href="/" aria-current="page"
                                class="nav-link w--current">Home</a>
                        </li>
                        <li class="list-item-5">
                            <div class="div-block-57"><img
                                    src="{{ asset('webflow_assets/home-about/images/features-p-500.png') }}"
                                    loading="lazy" alt="" /></div><a href="#Features"
                                class="nav-link">Features</a>
                        </li>
                        <li class="list-item-4">
                            <div class="div-block-60"><img
                                    src="{{ asset('webflow_assets/home-about/images/our-20team-p-500.png') }}"
                                    loading="lazy" alt="" /></div><a href="/about-us" class="nav-link">Our
                                Team</a>
                        </li>
                        <li class="list-item-7">
                            <div class="div-block-58"><img
                                    src="{{ asset('webflow_assets/home-about/images/contact-20us-p-500.png') }}"
                                    loading="lazy" alt="" /></div><a href="#Contact-Us"
                                class="nav-link">Contact</a>
                        </li>
                        <li class="list-item-9">
                            <div class="div-block-59"><img
                                    src="{{ asset('webflow_assets/home-about/images/policies-p-500.png') }}"
                                    loading="lazy" alt="" /></div><a href="/about-us"
                                class="nav-link">Policies</a>
                        </li>
                        <li class="list-item-9">
                            <div class="div-block-59"><img
                                    src="{{ asset('webflow_assets/home-about/images/careers-p-500.png') }}"
                                    loading="lazy" alt="" /></div><a href="/about-us"
                                class="nav-link">Careers</a>
                        </li>
                        <li class="list-item-9 login"><a href="{{ route('login') }}"
                                class="link-block-3 w-inline-block">
                                <div class="text-block-126">Login</div>
                            </a></li>
                        <li class="list-item-9 signup"><a href="{{ route('signup') }}"
                                class="link-block-3-copy w-inline-block">
                                <div class="text-block-126">Sign Up</div>
                            </a></li>
                    </ul>
                </nav>
                <div class="menu-button-3 w-nav-button">
                    <div class="w-icon-nav-menu"></div>
                </div>
            </div>
        </div>
    </div>
    
    
    
  
   
    <div id="book-now" class="" style="margin-top: 5%;">
        <div class="w-layout-blockcontainer footer w-container">
        <div data-w-id="8f5289da-98e7-73e7-7fd1-bfb9a4dd6df7"
                    class="w-layout-blockcontainer top-cloud w-container" style="margin-top: 5%; margin-left:20%; width:150px"><img
                        src="{{ asset('webflow_assets/home-about/images/untitled-1-20v6-20-9-.png') }}"
                        loading="lazy" sizes="(max-width: 479px) 60vw, (max-width: 4073px) 26vw, 1059px"
                        srcset="{{ asset('webflow_assets/home-about/images/untitled-1-20v6-20-9-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/untitled-1-20v6-20-9-p-800.png') }}  800w, {{ asset('webflow_assets/home-about/images/untitled-1-20v6-20-9-.png') }} 1059w"
                        alt="" /></div>

        <div data-w-id="8f5289da-98e7-73e7-7fd1-bfb9a4dd6df7"
                    class="w-layout-blockcontainer top-cloud book-demo-cloud w-container" ><img
                        src="{{ asset('webflow_assets/home-about/images/untitled-1-20v6-20-9-.png') }}"
                        loading="lazy" sizes="(max-width: 479px) 60vw, (max-width: 4073px) 26vw, 1059px"
                        srcset="{{ asset('webflow_assets/home-about/images/511A1ynk.png') }} 500w, {{ asset('webflow_assets/home-about/images/untitled-1-20v6-20-9-p-800.png') }}  800w, {{ asset('webflow_assets/home-about/images/untitled-1-20v6-20-9-.png') }} 1059w"
                        alt="" /></div>
            <!-- 511A1ynk -->
            <div class="eigth-page-form">
                <h3>Book a session</h3>
                <h4 style="margin-bottom:40px">Due to high demand, our services are currently available exclusively to organisations with 30 or
                    more sites.
                </h4>
                <div class="form-field">
                    <div class="box">
                        <div class="input-wrapper">
                            <input type="text" class="form-control" placeholder="Full name"
                                id="name" name="full name" />
                            <label for="name" class="control-label">
                                Full name
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-field">
                    <div class="box">
                        <div class="input-wrapper">
                            <input type="text" class="form-control" placeholder="Company name"
                                id="company" name="companny_name" />
                            <label for="company" class="control-label">
                                Company name
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-field">
                    <div class="box">
                        <div class="input-wrapper">
                            <input type="text" class="form-control" placeholder="Website"
                                id="website" name="website" />
                            <label for="website" class="control-label">
                                Website
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-field">
                    <div class="box">
                        <div class="input-wrapper">
                            <input type="text" class="form-control" placeholder="Position in company"
                                id="position" name="position" />
                            <label for="position" class="control-label">
                                Position in company
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-field">
                    <div class="box">
                        <div class="input-wrapper">
                            <input type="text" class="form-control" placeholder="Email address"
                                id="email" name="email" />
                            <label for="email" class="control-label">
                                Email address
                            </label>
                        </div>
                    </div>
                </div>
                



                <div class="form-field" style="text-align:left;">
                  <div class="box">
                    <div class="input-wrapper">
                      <label class="" style="font-size: 14px;">Preference time to call</label>
                      <div class="radio-group d-flex" style="display: flex; justify-content: space-between">
                       <div> 
                        <label class="" style="font-size: 14px;">Weekdays</label>
                        <label class=" h6 radio-inline d-flex align-items-center gap-2">
                          <input
                          class="mx-2 "
                            type="checkbox"
                            name="timing"
                            value="Morning"
                            require
                          />
                          Morning
                        </label>
                        <label class=" h6 radio-inline d-flex align-items-center gap-2">
                          <input
                          class="mx-2 "
                            type="checkbox"
                            name="timing"
                            value="Afternoon"
                            require
                          />
                          Afternoon
                        </label>
                        <label class=" h6 radio-inline d-flex align-items-center gap-2">
                          <input
                          class="mx-2 "
                            type="checkbox"
                            name="timing"
                            value="Evening"
                            require
                          />
                          Evening
                        </label></div>
                       

                       <div> 
                         <label class="" style="font-size: 14px;">Weekends</label>
                        <label class=" h6 radio-inline d-flex align-items-center gap-2">
                          <input
                          class="mx-2 "
                            type="checkbox"
                            name="timing"
                            value="Morning"
                            require
                          />
                          Morning
                        </label>
                        <label class=" h6 radio-inline d-flex align-items-center gap-2">
                          <input
                          class="mx-2 "
                            type="checkbox"
                            name="timing"
                            value="Afternoon"
                            require
                          />
                          Afternoon
                        </label>
                        <label class=" h6 radio-inline d-flex align-items-center gap-2">
                          <input
                          class="mx-2 "
                            type="checkbox"
                            name="timing"
                            value="Evening"
                            require
                          />
                          Evening
                        </label></div>
                        
                      </div>
                    </div>
                  </div>
                </div>

                <a href="#" class="button w-button btn-color" style="position: relative; transform: translateX(0px); top:0; left:0; border: 0">Request <img
                    src="http://127.0.0.1:8000/webflow_assets/home-about/images/arrow-color.png" loading="lazy"
                    alt="" class="touch-btn-arrow"></a>
            </div>

            






        </div>
    </div>
    <div class="" style="margin-top: 5%;">
        <div class="w-layout-blockcontainer footer w-container">
            
            <section class="footer-dark">
                <div class="container-16">
                    <div class="footer-wrapper">
                        <div data-w-id="234e7673-b861-2443-8407-83ad6e044508" class="footer-content">
                            <a href="#" class="footer-brand w-inline-block">
                                <img src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                    loading="lazy" alt="" class="image-69" />
                                <img src="{{ asset('webflow_assets/home-about/images/clouds.png') }}"
                                    loading="lazy" width="262" sizes="(max-width: 479px) 57.5px, 12vw"
                                    srcset="{{ asset('webflow_assets/home-about/images/clouds-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/clouds-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/clouds-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/clouds.png') }} 1093w"
                                    class="image-70" />
                            </a>
                            <div id="w-node-_234e7673-b861-2443-8407-83ad6e04450c-6e044504" class="footer-block">
                                <div class="title-small">Company</div>
                                <a href="{{ route('about_us') }}#tab-link-tab-1" class="footer-link">About</a>
                                <a href="{{ route('about_us') }}#tab-link-tab-2" class="footer-link">Privacy
                                    Policy</a>
                                <a href="{{ route('about_us') }}#tab-link-tab-3" class="footer-link">Terms of
                                    Service</a>
                                <a href="{{ route('about_us') }}#tab-link-tab-4" class="footer-link">Cookies
                                    Policy</a>
                                <a href="{{ route('about_us') }}#tab-link-tab-5" class="footer-link">Corporate
                                    Responsibility</a>
                                <a href="{{ route('about_us') }}#tab-link-tab-6" class="footer-link">Modern
                                    Slavery</a>
                                <a href="{{ route('about_us') }}#tab-link-tab-7" class="footer-link">Careers</a>
                                <a href="#" class="footer-link" id="select-contact2">Contact Us</a>
                            </div>
                            <div id="w-node-_234e7673-b861-2443-8407-83ad6e04451d-6e044504" class="footer-block">
                                <div class="title-small">Social</div>
                                <div class="title-small">
                                    <div class="footer-social-block">
                                        <a href="#" class="footer-social-link w-inline-block"><img
                                                src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                                loading="lazy" width="34.5" alt=""
                                                class="image-71" /></a>
                                        <a href="#" class="footer-social-link w-inline-block"><img
                                                src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                                loading="lazy" width="37" alt=""
                                                class="image-72" /></a>
                                        <a href="#" class="footer-social-link w-inline-block"><img
                                                src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                                loading="lazy" width="20" alt=""
                                                class="image-73" /></a>
                                    </div>
                                </div>
                                {{-- <a href="{{route('about_us')}}#tab-link-tab-1" class="footer-link">About</a>
                                <a href="{{route('about_us')}}#tab-link-tab-2" class="footer-link">Privacy Policy</a>
                                <a href="{{route('about_us')}}#tab-link-tab-3" class="footer-link">Terms of Service</a>
                                <a href="{{route('about_us')}}#tab-link-tab-4" class="footer-link">Cookies Policy</a>
                                <a href="{{route('about_us')}}#tab-link-tab-5" class="footer-link">Corporate Responsibility</a>
                                <a href="{{route('about_us')}}#tab-link-tab-6" class="footer-link">Modern Slavery</a>
                                <a href="{{route('about_us')}}#tab-link-tab-7" class="footer-link">Careers</a> --}}
                            </div>
                            <div id="w-node-_234e7673-b861-2443-8407-83ad6e04452c-6e044504" class="footer-block">
                                {{-- <div class="title-small">Social</div>
                                <div class="footer-social-block">
                                    <a href="#" class="footer-social-link w-inline-block"><img src="{{asset('webflow_assets/home-about/images/x.png')}}" loading="lazy" width="34.5" alt="" class="image-71" /></a>
                                    <a href="#" class="footer-social-link w-inline-block"><img src="{{asset('webflow_assets/home-about/images/linkedin-20logo.png')}}" loading="lazy" width="37" alt="" class="image-72" /></a>
                                    <a href="#" class="footer-social-link w-inline-block"><img src="{{asset('webflow_assets/home-about/images/facebook.png')}}" loading="lazy" width="20" alt="" class="image-73" /></a>
                                </div> --}}
                                <div class="footer-cloud"><img
                                        src="{{ asset('webflow_assets/home-about/images/cloud-20big.png') }}"
                                        loading="lazy" data-w-id="234e7673-b861-2443-8407-83ad6e044537"
                                        alt="" width="987.5"
                                        srcset="{{ asset('webflow_assets/home-about/images/cloud-20big-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/cloud-20big.png') }} 1975w"
                                        sizes="(max-width: 479px) 141.78750610351562px, (max-width: 991px) 30vw, 28vw" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="footer-copyright-center">© 2025 QI-Tech. All rights reserved.</div>
            </section>
            <div class="footer-mobile">
                <div class="div-block-108"><img
                        src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}" loading="lazy"
                        alt="" /></div>
                <div class="div-block-109">
                    <a href="{{ route('about_us') }}#tab-link-tab-1" class="link-6">About</a>
                    <a href="{{ route('about_us') }}#tab-link-tab-2" class="link-6">Privacy Policy</a>
                    <a href="{{ route('about_us') }}#tab-link-tab-3" class="link-6">Terms of Service</a>
                    <a href="{{ route('about_us') }}#tab-link-tab-2" class="link-6">Cookies Policy</a>
                    <a href="{{route('about_us')}}#tab-link-tab-5" class="link-6">Corporate Responsibility</a>
                    <a href="{{ route('about_us') }}#tab-link-tab-6" class="link-6">Modern Slavery</a>
                    <a href="{{ route('about_us') }}#tab-link-tab-7" class="link-6">Careers</a>
                </div>
                <div class="div-block-110">
                    <div class="div-block-111"><img src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                            loading="lazy" alt="" /></div>
                    <div class="div-block-112"><img
                            src="{{ asset('webflow_assets/home-about/images/facebook.png') }}" loading="lazy"
                            alt="" /></div>
                    <div class="div-block-113"><img
                            src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                            loading="lazy" alt="" /></div>
                </div>
                <div>
                    <div class="div-block-114"><img
                            src="{{ asset('webflow_assets/home-about/images/cloud-202.png') }}" loading="lazy"
                            sizes="100vw"
                            srcset="{{ asset('webflow_assets/home-about/images/cloud-202-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/cloud-202.png') }} 524w"
                            alt="" /></div>
                    <div class="div-block-115"><img
                            src="{{ asset('webflow_assets/home-about/images/636d34ec2a2ccf328bffadd7_cloud-1-1.png') }}"
                            loading="lazy" sizes="100vw"
                            srcset="{{ asset('webflow_assets/home-about/images/636d34ec2a2ccf328bffadd7_cloud-1-p-500-1.png') }} 500w, {{ asset('webflow_assets/home-about/images/636d34ec2a2ccf328bffadd7_cloud-1-1.png') }} 783w"
                            alt="" /></div>
                </div>
            </div>

        </div>
    </div>

  
    

    <div class="industry-wrapper industry">
        <div class="main-center-industry">
            <div class="left-col">
                <h4>By Industry</h4>
                <ul>
                    <a href="#Features" data-target="general">General</a>
                    <a href="#" data-target="healthcare">Healthcare</a>
                    <a href="#" data-target="social-care">Social Care</a>
                    <a href="/pharmacy" data-target="pharmacy">Pharmacy</a>
                    <a href="#" data-target="education">Schools & Education</a>
                    <a href="#" data-target="hospitality">Hospitality</a>
                    <a href="#" data-target="retail">Retail</a>
                    <a href="#" data-target="construction">Construction</a>
                </ul>
            </div>
            <div class="right-col" style="display: flex;justify-content: center;align-items: flex-end; margin-bottom: -20px">
                <div id="general" class="content-block">


                <div data-w-id="8f5289da-98e7-73e7-7fd1-bfb9a4dd6df7"
                class="w-layout-blockcontainer top-cloud w-container person" style="position:relative !important; margin:0; max-width:70%; margin-bottom: 20px"><img src="{{ asset('webflow_assets/home-about/new_images/General.webp') }}" /></div>
                </div>
                <div id="healthcare" class="content-block">
                <div data-w-id="8f5289da-98e7-73e7-7fd1-bfb9a4dd6df7"
                class="w-layout-blockcontainer top-cloud w-container person" style="position:relative !important; margin:0; max-width:50%"><img src="{{ asset('webflow_assets/home-about/new_images/Healthcare.webp') }}" /></div>

                </div>
                <div id="social-care" class="content-block">
                <div data-w-id="8f5289da-98e7-73e7-7fd1-bfb9a4dd6df7"
                class="w-layout-blockcontainer top-cloud w-container person" style="position:relative !important; margin:0; max-width:50%"><img src="{{ asset('webflow_assets/home-about/new_images/Socialcare.webp') }}" /></div>
                </div>
                <div id="pharmacy" class="content-block">
                <div data-w-id="8f5289da-98e7-73e7-7fd1-bfb9a4dd6df7"
                class="w-layout-blockcontainer top-cloud w-container person" style="position:relative !important; margin:0; max-width:50%"><img src="{{ asset('webflow_assets/home-about/new_images/Pharmacy.webp') }}" /></div>
                </div>
                <div id="education" class="content-block">
                <div data-w-id="8f5289da-98e7-73e7-7fd1-bfb9a4dd6df7"
                class="w-layout-blockcontainer top-cloud w-container person" style="position:relative !important; margin:0; max-width:75%; margin-bottom:10%"><img src="{{ asset('webflow_assets/home-about/new_images/schools.webp') }}" /></div>
                </div>
                <div id="hospitality" class="content-block">
                <div data-w-id="8f5289da-98e7-73e7-7fd1-bfb9a4dd6df7"
                class="w-layout-blockcontainer top-cloud w-container person" style="position:relative !important; margin:0; max-width:75%"><img src="{{ asset('webflow_assets/home-about/new_images/Hospitality.webp') }}" /></div>
                </div>
                <div id="retail" class="content-block">
                <div data-w-id="8f5289da-98e7-73e7-7fd1-bfb9a4dd6df7"
                class="w-layout-blockcontainer top-cloud w-container person" style="position:relative !important; margin:0; max-width:75%"><img src="{{ asset('webflow_assets/home-about/new_images/Retail.webp') }}" /></div>


                </div>
                <div id="construction" class="content-block">
                <div data-w-id="8f5289da-98e7-73e7-7fd1-bfb9a4dd6df7"
                class="w-layout-blockcontainer top-cloud w-container person" style="position:relative !important; margin:0; max-width:75%"><img src="{{ asset('webflow_assets/home-about/new_images/Construction.webp') }}" /></div>


                </div>
            </div>
        </div>

    </div>
    <div class="industry-wrapper contact">
        <div class="main-center-industry">
            <div class="left-col">
                <h4 class="contact-heading">Help Center</h4>
                <p class="contact-para">Call, email, or chat with our friendly support team</p>
                <ul style="margin-top: 2rem;">
                    <a href="/support" style="display: flex; align-items:center;width:270px;"
                        data-target="our-team">Contact our support team <img width="28" style="margin-left:4px" src="{{ asset('webflow_assets/home-about/new_images/Arrow.png') }}"
                        alt="General">
                    </a>
                    <a href="#"
                        style="display: flex; align-items:center; justify-content: space-between;width:270px;"
                        data-target="book">Book with a product specialist <img width="28" style="margin-left:4px" src="{{ asset('webflow_assets/home-about/new_images/Arrow.png') }}"
                        alt="General">
                        </svg>
                    </a>
                </ul>
            </div>
            <div class="right-col">
                <div id="always" class="content-block" style="display: block;">
                    <div class="img-wrap">
                        <img class="person" src="{{ asset('webflow_assets/home-about/new_images/always.webp') }}"
                            alt="General">
                    </div>
                </div>
                <div id="our-team" class="content-block">
                    <div class="img-wrap">
                        <img class="person" style="width: 70%"
                            src="{{ asset('webflow_assets/home-about/new_images/our-team.webp') }}" alt="General">
                    </div>
                    <div class="text-wrap-block">
                        <h5>Call our friendly support team</h5>
                        <p style="font-weight: 600 !important; margin-bottom: 20px;">0333 335 6476</p>

                        <h5 >Alternatively</h5>
                        <a href="/support"
                            style="display: flex; align-items:center; justify-content: space-between;font-weight: 600 !important;width:fit-content;gap:8px;"
                            data-target="book">Message Us <img width="28" style="margin-left:4px" src="{{ asset('webflow_assets/home-about/new_images/Arrow.png') }}"
                            alt="General">
                            </svg>
                        </a>
                    </div>
                </div>
                <div id="book" class="content-block">
                    <div class="img-wrap">
                        <img class="person" style="width: 70%"
                            src="{{ asset('webflow_assets/home-about/new_images/book.webp') }}" alt="General">
                    </div>
                    <div class="text-wrap-block">

                        <h5>Book a demo or training</h5>
                        <a 
                        href="/book-session"
                            style="display: flex; align-items:center; justify-content: space-between;font-weight: 600 !important;width:fit-content;gap:8px;">Book Now <img width="28" style="margin-left:4px" src="{{ asset('webflow_assets/home-about/new_images/Arrow.png') }}"
                            alt="General">
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('cookies_banner')
    <script src="{{ asset('webflow_assets/home-about/js/jquery.js') }}" type="text/javascript"></script>
    <script src="{{ asset('webflow_assets/home-about/js/webflow-script.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin_assets/js/intlTelInput-jquery.min.js') }}" type="text/javascript"></script>
    <input type="text" hidden value="{{ asset('admin_assets/js/utils.js') }}" id="utilPath">
    <script>
        $('#select-industry').click(function() {
            if ($('.contact').is(':visible')) {
                // Hide the contact div first
                $('.contact').animate({
                    right: '-100%'
                }, 300, function() {
                    $(this).hide();
                    $('body').css('overflow', ''); // Re-enable scrolling
                });
            }

            // Show the industry div with a left-side animation
            if (!$('.industry').is(':visible')) {
                $('.industry').css({
                    left: '-100%',
                    display: 'block'
                }).animate({
                    left: '0'
                }, 300, function() {
                    $('body').css('overflow', 'hidden'); // Disable scrolling
                });
            } else {
                $('.industry').animate({
                    left: '-100%'
                }, 300, function() {
                    $(this).hide();
                    $('body').css('overflow', ''); // Re-enable scrolling
                });
            }
        });
        $(document).click(function(event) {
    if ($(event.target).is('#select-industry')) {
        return;
    }

    if (!$(event.target).closest('.main-center-industry').length) {
        if ($('.industry').is(':visible')) {
            $('.industry').animate({
                left: '-100%'
            }, 300, function() {
                $(this).hide();
                $('body').css('overflow', '');
            });
        }
    }
});

        

        $('#select-contact').click(function(event) {
            event.preventDefault();

            $('#always').fadeIn()

            if ($('.industry').is(':visible')) {
                // Hide the industry div first
                $('.industry').animate({
                    left: '-100%'
                }, 300, function() {
                    $(this).hide();
                    $('body').css('overflow', ''); // Re-enable scrolling
                });
            }

            // Show the contact div with a right-side animation
            if (!$('.contact').is(':visible')) {
                $('.contact').css({
                    right: '-100%',
                    display: 'block'
                }).animate({
                    right: '0'
                }, 300, function() {
                    $('body').css('overflow', 'hidden'); // Disable scrolling
                });
            } else {
                $('.contact').animate({
                    right: '-100%'
                }, 300, function() {
                    $(this).hide();
                    $('body').css('overflow', ''); // Re-enable scrolling
                });
            }
        });
        $('#select-contact2').click(function(event) {
            event.preventDefault();
            window.scrollTo(0, 0);
            $('#always').fadeIn()

            if ($('.industry').is(':visible')) {
                // Hide the industry div first
                $('.industry').animate({
                    left: '-100%'
                }, 300, function() {
                    $(this).hide();
                    $('body').css('overflow', ''); // Re-enable scrolling
                });
            }

            // Show the contact div with a right-side animation
            if (!$('.contact').is(':visible')) {
                $('.contact').css({
                    right: '-100%',
                    display: 'block'
                }).animate({
                    right: '0'
                }, 300, function() {
                    $('body').css('overflow', 'hidden'); // Disable scrolling
                });
            } else {
                $('.contact').animate({
                    right: '-100%'
                }, 300, function() {
                    $(this).hide();
                    $('body').css('overflow', ''); // Re-enable scrolling
                });
            }
        });
        


        $('.left-col a').hover(
            function() {
                // Get the target block ID from the data-target attribute
                const target = $(this).data('target');

                // Hide all content blocks and show the targeted block
                $('.content-block').hide();
                $('#' + target).fadeIn();
            },
            function() {
                // Optionally, hide the block when the mouse leaves
                // $('#always').fadeIn();
            }
        );

        $('.left-col a').on('click', function() {
            $('body').css('overflow', '');
            $('.industry-wrapper').fadeOut();
        })

        telnumber = $("#telephone").intlTelInput({
            fixDropdownWidth: true,
            showSelectedDialCode: true,
            strictMode: true,
            utilsScript: "{{ asset('admin_assets/js/utils.js') }}",
            preventInvalidNumbers: true
        }).on('countrychange', function(e, countryData) {
            let code = $("#telephone").intlTelInput("getSelectedCountryData").dialCode;
            $("#code-tel").val(code);
        });
        $('#wf-form-Message').on('submit', function(event) {
            if (telnumber.intlTelInput("isValidNumber") == false) {
                event.preventDefault();

                $('.error-msg').text('Not Valid').fadeIn();
            } else {
                $('.error-msg').text('Valid').css('color', 'green').fadeIn();
            }
        });

        $('#telephone').on('blur', function(element) {
            if (telnumber.intlTelInput("isValidNumber") == false) {
                $('.error-msg').text('Not Valid').fadeIn();
            } else {
                $('.error-msg').text('Valid').css('color', 'green').fadeIn();
            }
        })
    </script>
</body>

</html>
