<!DOCTYPE html>
<html data-wf-page="65829a47c1eba9fcf05aa8b8" data-wf-site="6564282be531be60fd0d391f">

<head>
    <meta charset="utf-8" />
    <title>About Us</title>
    <meta content="About Us" property="og:title" />
    <meta content="About Us" property="twitter:title" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="Webflow" name="generator" />
    <link href="{{ asset('admin_assets/css/select2.min.css') }}" rel="stylesheet" />
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
    <style>
        * {
            font-family: 'Littera Text'
        }
    </style>
</head>

<body>
    <div class="navigation-bar-copy">
        <div data-animation="default" data-collapse="medium" data-duration="400" data-easing="ease" data-easing2="ease"
            role="banner" class="navbar-logo-left-container shadow-three w-nav">
            <div class="container-2">
                <div class="navbar-wrapper"><a href="{{ route('home') }}" class="navbar-brand w-nav-brand">
                        <img src="{{ asset('webflow_assets/home-about/images/qi-20tech-20logo-ai-20-1-.png') }}"
                            loading="lazy" width="125" alt="" class="image-10" /></a>
                    <nav role="navigation" class="nav-menu-wrapper w-nav-menu">
                        <ul role="list" class="nav-menu-two w-list-unstyled">
                            <li><a href="{{ route('home') }}#Features" class="nav-link">Features</a></li>
                            <li><a href="#Meet-The-Team" class="nav-link">Meet the Team</a></li>
                            <li><a href="https://qi-tech-uk.webflow.io/#Contact-Us" class="nav-link">Contact Us</a></li>
                        </ul>
                    </nav>
                    <div class="w-layout-blockcontainer top-right-buttons w-container"><a href="{{ route('signup') }}"
                            class="button w-button">Sign Up</a><a href="{{ route('login') }}"
                            class="button-2 w-button">Account Login</a></div>
                </div>
            </div>
        </div>
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
                                        alt="" /></div><a href="/" class="nav-link">Home</a>
                            </li>
                            <li class="list-item-5">
                                <div class="div-block-57"><img
                                        src="{{ asset('webflow_assets/home-about/images/features.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/features-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/features-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/features-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/features-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/features.png') }} 1883w"
                                        alt="" /></div><a href="{{ route('home') }}#Features"
                                    class="nav-link">Features</a>
                            </li>
                            <li class="list-item-4">
                                <div class="div-block-60"><img
                                        src="{{ asset('webflow_assets/home-about/images/our-20team.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/our-20team-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/our-20team-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/our-20team-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/our-20team-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/our-20team.png') }} 1618w"
                                        alt="" /></div><a href="/about-us" aria-current="page"
                                    class="nav-link w--current">Our Team</a>
                            </li>
                            <li class="list-item-7">
                                <div class="div-block-58"><img
                                        src="{{ asset('webflow_assets/home-about/images/contact-20us.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/contact-20us-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/contact-20us-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/contact-20us-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/contact-20us-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/contact-20us.png') }} 1778w"
                                        alt="" /></div><a href="#" class="nav-link">Contact</a>
                            </li>
                            <li class="list-item-9">
                                <div class="div-block-59"><img
                                        src="{{ asset('webflow_assets/home-about/images/policies.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/policies-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/policies-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/policies-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/policies-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/policies.png') }} 1778w"
                                        alt="" /></div><a href="/about-us" aria-current="page"
                                    class="nav-link w--current">Policies</a>
                            </li>
                            <li class="list-item-9">
                                <div class="div-block-59"><img
                                        src="{{ asset('webflow_assets/home-about/images/careers.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/careers-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/careers-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/careers-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/careers-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/careers.png') }} 1778w"
                                        alt="" /></div><a href="/about-us" aria-current="page"
                                    class="nav-link w--current">Careers</a>
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
                                        alt="" /></div><a href="#" class="nav-link">Home</a>
                            </li>
                            <li class="list-item-5">
                                <div class="div-block-57"><img
                                        src="{{ asset('webflow_assets/home-about/images/features.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/features-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/features-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/features-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/features-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/features.png') }} 1883w"
                                        alt="" /></div><a href="{{ route('home') }}#Features"
                                    class="nav-link nav-link-new">Features</a>
                            </li>
                            <li class="list-item-4">
                                <div class="div-block-60"><img
                                        src="{{ asset('webflow_assets/home-about/images/our-20team.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/our-20team-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/our-20team-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/our-20team-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/our-20team-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/our-20team.png') }} 1618w"
                                        alt="" /></div><a href="#" class="nav-link">Our Team</a>
                            </li>
                            <li class="list-item-7">
                                <div class="div-block-58"><img
                                        src="{{ asset('webflow_assets/home-about/images/contact-20us.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/contact-20us-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/contact-20us-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/contact-20us-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/contact-20us-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/contact-20us.png') }} 1778w"
                                        alt="" /></div><a href="/about-us" aria-current="page"
                                    class="nav-link nav-link-new">About Us</a>
                            </li>
                            <li class="list-item-9">
                                <div class="div-block-59"><img
                                        src="{{ asset('webflow_assets/home-about/images/policies.png') }}"
                                        loading="lazy" sizes="100vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/policies-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/policies-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/policies-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/policies-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/policies.png') }} 1778w"
                                        alt="" /></div><a href="{{ route('home') }}#Contact-Us"
                                    class="nav-link nav-link-new">Contact Us</a>
                            </li>
                        </ul>

                    </nav>
                    <div class="w-layout-blockcontainer top-right-buttons w-container"><a
                            href="{{ route('signup') }}" class="button w-button">Sign Up</a><a
                            href="{{ route('login') }}" class="button-2 w-button">Account Login</a></div>
                </div>
            </div>
        </div>
        <div data-animation="default" class="navbar w-nav" data-easing2="ease" data-easing="ease"
            data-collapse="medium" role="banner" data-no-scroll="1" data-duration="400" data-doc-height="1">
            <div class="container-43 w-container">
                <a href="{{ route('home') }}" class="navbar-brand w-nav-brand">
                    <img src="{{ asset('webflow_assets/home-about/images/qi-20tech-20logo-ai-20-1-.png') }}"
                        loading="lazy" width="95" alt="" class="image-10" />
                </a>
                <nav role="navigation" class="nav-menu w-nav-menu">
                    <ul role="list" class="nav-menu-mobile w-list-unstyled">
                        <li class="list-item-3">
                            <div class="div-block-56">
                                <img src="{{ asset('webflow_assets/home-about/images/home.png') }}" loading="lazy"
                                    sizes="100vw"
                                    srcset="{{ asset('webflow_assets/home-about/images/home-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/home-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/home-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/home-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/home.png') }} 1910w"
                                    alt="" />
                            </div>
                            <a href="/" class="nav-link">Home</a>
                        </li>
                        <li class="list-item-5">
                            <div class="div-block-57">
                                <img src="{{ asset('webflow_assets/home-about/images/features.png') }}"
                                    loading="lazy" sizes="100vw"
                                    srcset="{{ asset('webflow_assets/home-about/images/features-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/features-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/features-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/features-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/features.png') }} 1883w"
                                    alt="" />
                            </div>
                            <a href="#" class="nav-link">Features</a>
                        </li>
                        <li class="list-item-9 login">
                            <a href="{{ route('login') }}" class="link-block-3 w-inline-block">
                                <div class="text-block-126">Login</div>
                            </a>
                        </li>
                        <li class="list-item-9 signup">
                            <a href="{{ route('signup') }}" class="link-block-3-copy w-inline-block">
                                <div class="text-block-126">Sign Up</div>
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="menu-button-3 w-nav-button">
                    <div class="w-icon-nav-menu"></div>
                </div>
            </div>

        </div>
    </div>
    <div class="w-layout-blockcontainer first-page-about-us w-container">
        <div class="w-layout-blockcontainer main-content w-container"><a
                data-w-id="47ade428-e643-d6a6-6a01-dea66e66c8b3" href="#" class="menu-button w-inline-block">
                <div class="text-block-134">Open Dropdown</div>
                <div class="div-block-118"><svg class="ikonik-egj0f" xmlns="http://www.w3.org/2000/svg"
                        width="26" height="26" viewBox="0 0 1024 1024" app="ikonik">
                        <path class="path-s9u6r" fill="currentColor"
                            d="M840.4 300H183.6c-19.7 0-30.7 20.8-18.5 35l328.4 380.8c9.4 10.9 27.5 10.9 37 0L858.9 335c12.2-14.2 1.2-35-18.5-35z"
                            app="ikonik"></path>
                    </svg></div>
            </a>
            <div data-current="Tab 1" data-easing="ease" data-duration-in="300" data-duration-out="100"
                class="tabs w-tabs">
                <div class="tabs-menu w-tab-menu"><a data-w-tab="Tab 1"
                        class="tab-link-tab-1 w-inline-block w-tab-link w--current">
                        <div>About Us</div>
                    </a><a data-w-tab="Tab 2" class="tab-link-tab-2 w-inline-block w-tab-link">
                        <div>Privacy &amp; Cookies</div>
                    </a><a data-w-tab="Tab 3" class="tab-link-tab-3 w-inline-block w-tab-link">
                        <div>Terms of Service</div>
                    </a><a data-w-tab="Tab 5" class="tab-link-tab-5 w-inline-block w-tab-link">
                        <div>Corporate Responsibility</div>
                    </a><a data-w-tab="Tab 6" class="tab-link-tab-6 w-inline-block w-tab-link">
                        <div>Modern Slavery</div>
                    </a><a data-w-tab="Tab 7" id="Careers-page" class="tab-link-tab-7 w-inline-block w-tab-link">
                        <div class="text-block-115">Careers</div>
                    </a></div>
                <div class="tabs-content w-tab-content">
                    <section data-w-tab="Tab 1" id="About-Us" class="tab-pane-tab-1 w-tab-pane w--tab-active">
                        <div class="w-layout-blockcontainer content w-container">
                            <div class="w-layout-blockcontainer top-middle w-container">
                                <div class="w-layout-blockcontainer doctor-with-cloud-wire w-container">
                                    <div data-w-id="a0acaccb-9aca-2848-6828-81898ea0e640"
                                        class="w-layout-blockcontainer wire w-container">
                                        <img sizes="(max-width: 6400px) 100vw, 6400px"
                                            srcset="{{ asset('webflow_assets/home-about/images/layer-2090-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/layer-2090-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/layer-2090-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/layer-2090-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/layer-2090-p-2000.png') }} 2000w, {{ asset('webflow_assets/home-about/images/layer-2090-p-2600.png') }} 2600w, {{ asset('webflow_assets/home-about/images/layer-2090-p-3200.png') }} 3200w, {{ asset('webflow_assets/home-about/images/layer-2090.png') }} 6400w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/layer-2090.png') }}"
                                            loading="lazy" class="image-77" />
                                    </div>
                                    <div data-w-id="5266e54c-82e9-6b3c-8c4f-c2c88fae02b2"
                                        class="w-layout-blockcontainer doctor w-container">
                                        <img sizes="(max-width: 479px) 30vw, 10vw"
                                            srcset="{{ asset('webflow_assets/home-about/images/9110029-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/9110029-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/9110029.png') }} 836w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/9110029.png') }}"
                                            loading="lazy" class="image-75" />
                                    </div>
                                    <div data-w-id="0acbdda5-a123-ed10-c4cf-b5957224211f"
                                        class="w-layout-blockcontainer cloud w-container">
                                        <img sizes="(max-width: 479px) 60vw, (max-width: 9190px) 20vw, 1838px"
                                            srcset="{{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy.png') }} 1838w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy.png') }}"
                                            loading="lazy" class="image-76" />
                                    </div>

                                </div>
                            </div>
                            <div class="w-layout-blockcontainer our-mission w-container">
                                <div data-w-id="309a911f-5c1a-d883-04e1-48476e1a9a17"
                                    class="w-layout-blockcontainer glitters-a w-container">
                                    <img sizes="(max-width: 479px) 25vw, 10vw"
                                        srcset="{{ asset('webflow_assets/home-about/images/vector-20smart-20object-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/vector-20smart-20object.png') }} 634w"
                                        alt=""
                                        src="{{ asset('webflow_assets/home-about/images/vector-20smart-20object.png') }}"
                                        loading="lazy" />
                                </div>
                                <div class="w-layout-blockcontainer a w-container">
                                    <div class="w-layout-blockcontainer text-mission w-container">
                                        <h1 class="heading-6">Our Mission</h1>
                                        <div class="text-block-43">We help people to:</div>
                                        <div class="div-block-116">
                                            <div class="div-block-62">
                                                <div class="div-block-63"><img
                                                        src="{{ asset('webflow_assets/home-about/images/arrow-20right-1.png') }}"
                                                        loading="lazy" alt="" class="image-83" /></div>
                                                <div class="text-block-125">Easily build logic-driven forms</div>
                                            </div>
                                            <div class="div-block-62">
                                                <div class="div-block-63"><img
                                                        src="{{ asset('webflow_assets/home-about/images/arrow-20right-1.png') }}"
                                                        loading="lazy" alt="" class="image-83" /></div>
                                                <div class="text-block-125">Manage data in boards and cases</div>
                                            </div>
                                            <div class="div-block-62-copy">
                                                <div class="div-block-63"><img
                                                        src="{{ asset('webflow_assets/home-about/images/arrow-20right-1.png') }}"
                                                        loading="lazy" alt="" class="image-83" /></div>
                                                <div class="text-block-125">Improve reporting, investigations and
                                                    workflow</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div data-w-id="73c47375-04fd-2fa7-a500-9f5865edc23c"
                                    class="w-layout-blockcontainer b w-container">
                                    <div class="w-layout-blockcontainer floating-island w-container">
                                        <img sizes="(max-width: 479px) 70vw, (max-width: 6030px) 20vw, 1206px"
                                            srcset="{{ asset('webflow_assets/home-about/images/group-203-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/group-203-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/group-203-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/group-203.png') }} 1206w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/group-203.png') }}"
                                            loading="lazy" />
                                    </div>
                                    <div class="w-layout-blockcontainer island-cloud w-container">
                                        <img sizes="(max-width: 479px) 40vw, 13vw"
                                            srcset="{{ asset('webflow_assets/home-about/images/636d34ec2a2ccf328bffadd7_cloud-1-p-500-1.png') }} 500w, {{ asset('webflow_assets/home-about/images/636d34ec2a2ccf328bffadd7_cloud-1-1.png') }} 783w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/636d34ec2a2ccf328bffadd7_cloud-1-1.png') }}"
                                            loading="lazy" />
                                    </div>
                                    <div class="w-layout-blockcontainer sun w-container">
                                        <img sizes="(max-width: 479px) 50vw, 15vw"
                                            srcset="{{ asset('webflow_assets/home-about/images/ellipse-202-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/ellipse-202-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/ellipse-202.png') }} 867w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/ellipse-202.png') }}"
                                            loading="lazy" />
                                    </div>
                                </div>
                            </div>

                            <div data-w-id="7f5a53c7-101a-0593-0c5e-03e2d1507176"
                                class="w-layout-blockcontainer culture w-container">
                                <div class="w-layout-blockcontainer c-w w-container">
                                    <h1 class="heading-7">Our Culture<br />&amp; Values</h1>
                                </div>
                                <div class="w-layout-blockcontainer cards-culture w-container">
                                    <div class="div-block-29">
                                        <div class="w-layout-blockcontainer container-20 w-container">
                                            <div class="text-block-45">You are the expert. <br />Not us.</div>
                                        </div>
                                        <div class="w-layout-blockcontainer container-21 w-container"><img
                                                sizes="(max-width: 479px) 60vw, 24vw"
                                                srcset="{{ asset('webflow_assets/home-about/images/box-201-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/box-201-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/box-201-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/box-201.png') }} 1254w"
                                                alt=""
                                                src="{{ asset('webflow_assets/home-about/images/box-201.png') }}"
                                                loading="lazy" class="image-78" /></div>
                                        <div class="div-block-65">
                                            <div class="div-block-62">
                                                <div class="div-block-63"><img
                                                        src="{{ asset('webflow_assets/home-about/images/arrow-20right-1.png') }}"
                                                        loading="lazy" alt="" class="image-83" /></div>
                                                <div class="text-block-125"> Continually develop &amp; improve.</div>
                                            </div>
                                            <div class="div-block-62">
                                                <div class="div-block-63"><img
                                                        src="{{ asset('webflow_assets/home-about/images/arrow-20right-1.png') }}"
                                                        loading="lazy" alt="" class="image-83" /></div>
                                                <div class="text-block-125"> Listen to our users.</div>
                                            </div>
                                            <div class="div-block-62">
                                                <div class="div-block-63"><img
                                                        src="{{ asset('webflow_assets/home-about/images/arrow-20right-1.png') }}"
                                                        loading="lazy" alt="" class="image-83" /></div>
                                                <div class="text-block-125">Be guided by you.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="div-block-29">
                                        <div class="w-layout-blockcontainer container-20 w-container">
                                            <div class="text-block-45">Innovate &amp; think big.</div>
                                        </div>
                                        <div class="w-layout-blockcontainer container-21 w-container"><img
                                                sizes="(max-width: 479px) 60vw, 24vw"
                                                srcset="{{ asset('webflow_assets/home-about/images/group-204-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/group-204-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/group-204-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/group-204.png') }} 1403w"
                                                alt=""
                                                src="{{ asset('webflow_assets/home-about/images/group-204.png') }}"
                                                loading="lazy" class="image-78" /></div>
                                        <div class="div-block-65">
                                            <div class="div-block-62">
                                                <div class="div-block-63"><img
                                                        src="{{ asset('webflow_assets/home-about/images/arrow-20right-1.png') }}"
                                                        loading="lazy" alt="" class="image-83" /></div>
                                                <div class="text-block-125">Explore industry specific features.</div>
                                            </div>
                                            <div class="div-block-62">
                                                <div class="div-block-63"><img
                                                        src="{{ asset('webflow_assets/home-about/images/arrow-20right-1.png') }}"
                                                        loading="lazy" alt="" class="image-83" /></div>
                                                <div class="text-block-125">Encourage out-of-the-box thinking.</div>
                                            </div>
                                            <div class="div-block-62">
                                                <div class="div-block-63"><img
                                                        src="{{ asset('webflow_assets/home-about/images/arrow-20right-1.png') }}"
                                                        loading="lazy" alt="" class="image-83" /></div>
                                                <div class="text-block-125">Think big data.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="div-block-29">
                                        <div class="w-layout-blockcontainer container-20 w-container">
                                            <div class="text-block-45">Fast is better than slow.</div>
                                        </div>
                                        <div class="w-layout-blockcontainer over-the-cloud w-container"><img
                                                sizes="(max-width: 479px) 47vw, 19vw"
                                                srcset="{{ asset('webflow_assets/home-about/images/box-203-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/box-203-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/box-203.png') }} 952w"
                                                alt=""
                                                src="{{ asset('webflow_assets/home-about/images/box-203.png') }}"
                                                loading="lazy" class="image-78-copy" /></div>
                                        <div class="div-block-65">
                                            <div class="div-block-62">
                                                <div class="div-block-63"><img
                                                        src="{{ asset('webflow_assets/home-about/images/arrow-20right-1.png') }}"
                                                        loading="lazy" alt="" class="image-83" /></div>
                                                <div class="text-block-125">Provide lightening fast solutions.</div>
                                            </div>
                                            <div class="div-block-62">
                                                <div class="div-block-63"><img
                                                        src="{{ asset('webflow_assets/home-about/images/arrow-20right-1.png') }}"
                                                        loading="lazy" alt="" class="image-83" /></div>
                                                <div class="text-block-125">Offer rapid customer support.</div>
                                            </div>
                                            <div class="div-block-62">
                                                <div class="div-block-63"><img
                                                        src="{{ asset('webflow_assets/home-about/images/arrow-20right-1.png') }}"
                                                        loading="lazy" alt="" class="image-83" /></div>
                                                <div class="text-block-125">Adapt quickly to change.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <section id="Meet-The-Team"
                                    class="w-layout-blockcontainer meet-with-team w-container">
                                    <div class="w-layout-blockcontainer container-23 w-container">
                                        <h1 class="heading-8">Meet the Executive Team</h1>
                                    </div>
                                    <div class="w-layout-blockcontainer member-a w-container">
                                        <div class="w-layout-blockcontainer container-25 w-container"><img
                                                sizes="(max-width: 479px) 65vw, 15vw"
                                                srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/6586991de22b9fb1eeaead67_Group%206-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6586991de22b9fb1eeaead67_Group%206-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6586991de22b9fb1eeaead67_Group%206-p-1080.png 1080w, {{ asset('webflow_assets/home-about/images/group-206.png') }} 1513w"
                                                alt=""
                                                src="{{ asset('webflow_assets/home-about/images/group-206.png') }}"
                                                loading="lazy" class="asif-iqbal-copy" /></div>
                                        <div class="w-layout-blockcontainer container-24 w-container">
                                            <div class="text-block-47">Asif Iqbal MRPharmS</div>
                                            <div class="text-block-48">Chief Executive</div>
                                            <div class="text-block-49">Bringing over 30 years of healthcare expertise,
                                                Asif Iqbal holds
                                                various senior leadership positions including Clinical Services Director
                                                at 4MCS, a
                                                distinguished clinical research company that has conducted essential
                                                trials for major pharma and
                                                biotech organisations.<br />‍<br />Previously, Asif served as a clinical
                                                pharmacist specialising
                                                in Intensive and Coronary Care, leading advancements in medicines
                                                management and patient
                                                safety.<br /><br />As a co-founder and Managing Director of Eaststone
                                                Pharmaceuticals, Asif
                                                spearheaded research and development efforts for innovative dosage forms
                                                tailored for rare
                                                diseases. His leadership fostered strategic business alliances spanning
                                                the Middle East, Europe,
                                                and America.</div>
                                        </div>
                                    </div>
                                    <div class="w-layout-blockcontainer member-c-copy-copy w-container">
                                        <div class="w-layout-blockcontainer container-25 w-container"><img
                                                sizes="(max-width: 479px) 65vw, 15vw"
                                                srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f694bd649806f4315859_5-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f694bd649806f4315859_5-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f694bd649806f4315859_5-p-1080.png 1080w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f694bd649806f4315859_5.png 1513w"
                                                alt=""
                                                src="https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f694bd649806f4315859_5.png"
                                                loading="lazy" class="image-79-copy-copy" /></div>
                                        <div class="w-layout-blockcontainer container-24 w-container">
                                            <div class="text-block-47">David Woods FPS FRPharmS FRGS</div>
                                            <div class="text-block-48">Medicines Management &amp; Pharmacovigilance
                                                Manager</div>
                                            <div class="text-block-49">David Woods is a consultant pharmacist, educator
                                                and researcher with a
                                                background in medicines information, rational drug use and
                                                evidence-based practice,
                                                pharmaceutical education and paediatric clinical pharmacy.<br /><br />He
                                                is currently working
                                                with the Best Practice Advocacy Centre (BPAC), a consultant clinical
                                                pharmacist in chronic pain
                                                management, an honorary academic, Faculty of Medical and Health
                                                Sciences, School of Pharmacy,
                                                University of Auckland and is an active researcher in
                                                pharmacoepidemiology. He is also a
                                                research collaborator at the New Zealand Pharmacovigilance Centre,
                                                University of
                                                Otago.<br /><br />He is a visiting academic at several overseas
                                                universities and was recently
                                                appointed as a partnership associate professor at Vilnius University.
                                                He has worked extensively
                                                in the field of evidence-based medicine and rational drug use and has
                                                contributed to related
                                                educational and resource development programs both in New Zealand and
                                                internationally,
                                                particularly in developing countries. Consultancies include work with
                                                the WHO, USAID, UNICEF and
                                                GRIP (Global Research in Paediatrics). David was integral to the
                                                development of the New Zealand
                                                Formulary and New Zealand Formulary for Children and also directed the
                                                introduction of the
                                                national drug formulary for Kazakhstan.<br /><br />David is a founder
                                                member of the Medicines
                                                Safety Expert Advisory Group of the Health Quality and Safety
                                                Commission. On the education front
                                                David has recently developed the first interprofessional
                                                micro-credential on medicines
                                                optimisation in older people for Auckland University.</div>
                                        </div>
                                    </div>
                                    <div class="w-layout-blockcontainer member-b w-container">
                                        <div class="w-layout-blockcontainer container-25 w-container"><img
                                                srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f6934415cad293a5551d_2-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f6934415cad293a5551d_2-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f6934415cad293a5551d_2-p-1080.png 1080w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f6934415cad293a5551d_2.png 1513w"
                                                alt=""
                                                src="https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f6934415cad293a5551d_2.png"
                                                loading="lazy" sizes="(max-width: 479px) 65vw, 15vw"
                                                class="asif-iqbal" /></div>
                                        <div class="w-layout-blockcontainer container-24 w-container">
                                            <div class="text-block-47">Rahma Javed</div>
                                            <div class="text-block-48">Board Advisor</div>
                                            <div class="text-block-49">Rahma is the Director of Engineering at
                                                Deliveroo. Rahma is also an
                                                Angel Investor as part of the Accel starters program and a Board
                                                Advisor.<br /><br />Prior to
                                                this, Rahma was a senior engineering leader at Wealthfront where she led
                                                the financial services
                                                area that focussed on building products in the financial advisory space
                                                like the 529 College
                                                Savings Plan as well as the Portfolio Line of Credit.</div>
                                        </div>
                                    </div>
                                    <!-- <div class="w-layout-blockcontainer member-c w-container">
                                        <div class="w-layout-blockcontainer container-25 w-container"><img
                                                sizes="(max-width: 479px) 65vw, 15vw"
                                                srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f6932384e3889d855a20_3-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f6932384e3889d855a20_3-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f6932384e3889d855a20_3-p-1080.png 1080w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f6932384e3889d855a20_3.png 1513w"
                                                alt=""
                                                src="https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f6932384e3889d855a20_3.png"
                                                loading="lazy" class="asif-iqbal dr-g" /></div>
                                        <div class="w-layout-blockcontainer container-24 w-container">
                                            <div class="text-block-47">Dr Ghulam Ashraf</div>
                                            <div class="text-block-48">Operations Director</div>
                                            <div class="text-block-49">Dr. Ashraf brings 30 years of project
                                                manegement, quality compliance
                                                and auditing expertise in IT and healthcare, having previously spent 14
                                                years on notable
                                                projects at Hewlett Packard. He specialises in International Standards
                                                such as ISO 27001, 14001,
                                                18001 and 9001.<br /><br />He is a founding member, trustee and director
                                                of numerous charities,
                                                schools and social care organisations. The projects have included care
                                                in the community, and
                                                poverty alleviation through establishment of global education, health
                                                and microfinance
                                                programmes.</div>
                                        </div>
                                    </div> -->
                                    <div class="w-layout-blockcontainer member-c-copy w-container">
                                        <div class="w-layout-blockcontainer container-24 w-container">
                                            <div class="text-block-47">Andrew Gibb</div>
                                            <div class="text-block-48">Clinical Safety and Safeguarding Manager</div>
                                            <div class="text-block-49">With a career spanning over three decades,
                                                Andrew is an expert in
                                                patient safety, safeguarding and risk management.<br /><br />His pivotal
                                                role in integrating
                                                safeguarding into the British Retail Consortium set the stage for
                                                industry-wide advancements in
                                                ensuring consumer protection.<br /><br />He has created safeguarding
                                                solutions for UAE
                                                international schools, elevating standards in educational institutions
                                                and fostering safer
                                                environments for students.<br /><br />Andrew’s strategies gained
                                                recognition from prestigious
                                                entities like Lloyds of London, who underwrote his safeguarding systems,
                                                acknowledging their
                                                effectiveness and reliability.</div>
                                        </div>
                                        <div class="w-layout-blockcontainer container-25 w-container"><img
                                                sizes="100vw"
                                                srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f693c489a49f4842b7a6_4-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f693c489a49f4842b7a6_4-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f693c489a49f4842b7a6_4-p-1080.png 1080w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f693c489a49f4842b7a6_4.png 1513w"
                                                alt=""
                                                src="https://assets-global.website-files.com/6564282be531be60fd0d391f/6598f693c489a49f4842b7a6_4.png"
                                                loading="lazy" class="andrew-gibb" /></div>
                                    </div>
                                    <div class="w-layout-blockcontainer footer-copy w-container">
                                        <div class="w-layout-blockcontainer footer w-container">
                                            <section class="footer-dark">
                                                <div class="container-16">
                                                    <div class="footer-wrapper">
                                                        <div data-w-id="234e7673-b861-2443-8407-83ad6e044508"
                                                            class="footer-content"><a href="#"
                                                                class="footer-brand w-inline-block"><img
                                                                    src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                                                    loading="lazy" alt=""
                                                                    class="image-69" /><img
                                                                    src="{{ asset('webflow_assets/home-about/images/clouds.png') }}"
                                                                    loading="lazy" width="262"
                                                                    sizes="(max-width: 479px) 57.5px, 12vw"
                                                                    alt=""
                                                                    srcset="{{ asset('webflow_assets/home-about/images/clouds-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/clouds-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/clouds-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/clouds.png') }} 1093w"
                                                                    class="image-70" /></a>
                                                            <div id="w-node-_234e7673-b861-2443-8407-83ad6e04450c-6e044504"
                                                                class="footer-block">
                                                                <div class="title-small">Company</div><a
                                                                    href="{{ route('about_us') }}#tab-link-tab-1"
                                                                    class="footer-link">About</a>
                                                                <a href="{{ route('about_us') }}#tab-link-tab-2"
                                                                    class="footer-link">Privacy Policy</a>
                                                                <a href="{{ route('about_us') }}#tab-link-tab-3"
                                                                    class="footer-link">Terms of Service</a>
                                                                <a href="{{ route('about_us') }}#tab-link-tab-2"
                                                                    class="footer-link">Cookies Policy</a>
                                                                <a href="{{ route('about_us') }}#tab-link-tab-5"
                                                                    class="footer-link">Corporate Responsibility</a>
                                                                <a href="{{ route('about_us') }}#tab-link-tab-6"
                                                                    class="footer-link">Modern Slavery</a>
                                                                <a href="{{ route('about_us') }}#tab-link-tab-7"
                                                                    class="footer-link">Careers</a>
                                                            </div>
                                                            <div id="w-node-_234e7673-b861-2443-8407-83ad6e04452c-6e044504"
                                                                class="footer-block">
                                                                <div class="title-small">Social</div>
                                                                <div class="footer-social-block"><a href="#"
                                                                        class="footer-social-link w-inline-block"><img
                                                                            src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                                                            loading="lazy" width="34.5"
                                                                            alt="" class="image-71" /></a><a
                                                                        href="#"
                                                                        class="footer-social-link w-inline-block"><img
                                                                            src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                                                            loading="lazy" width="37"
                                                                            alt="" class="image-72" /></a><a
                                                                        href="#"
                                                                        class="footer-social-link w-inline-block"><img
                                                                            src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                                                            loading="lazy" width="20"
                                                                            alt="" class="image-73" /></a>
                                                                </div>
                                                                <div class="footer-cloud"><img
                                                                        src="{{ asset('webflow_assets/home-about/images/cloud-20big.png') }}"
                                                                        loading="lazy"
                                                                        data-w-id="234e7673-b861-2443-8407-83ad6e044537"
                                                                        alt="" width="987.5"
                                                                        srcset="{{ asset('webflow_assets/home-about/images/cloud-20big-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/cloud-20big.png') }} 1975w"
                                                                        sizes="(max-width: 479px) 141.78750610351562px, (max-width: 991px) 30vw, 28vw" />
                                                                </div>
                                                            </div>
                                                            <div id="w-node-_234e7673-b861-2443-8407-83ad6e04451d-6e044504"
                                                                class="footer-block">
                                                                <!-- <div class="title-small">Contact us</div><a
                                                                    href="#" class="footer-link">42 Reading Road
                                                                    <br />South Fleet<br />Hampshire<br />GU51 3QP</a><a
                                                                    href="#" class="footer-link">+44
                                                                    01252 613425</a><a href="#"
                                                                    class="footer-link">support@qi-tech.co.uk</a> -->
                                                            </div>
                                                            
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="footer-copyright-center">© 2025 QI-Tech. All rights
                                                    reserved.</div>
                                            </section>
                                            <div class="footer-mobile">
                                                <div class="div-block-108"><img
                                                        src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                                        loading="lazy" alt="" /></div>
                                                <div class="div-block-109"><a href="{{ route('about_us') }}#tab-link-tab-1"
                                                        class="link-6">About</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                        class="link-6">Privacy Policy</a><a href="{{ route('about_us') }}#tab-link-tab-3"
                                                        class="link-6">Terms of Service</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                        class="link-6">Cookies Policy</a><a href="{{route('about_us')}}#tab-link-tab-5"
                                                        class="link-6">Corporate Responsibility  </a><a href="{{ route('about_us') }}#tab-link-tab-6"
                                                        class="link-6">Modern Slavery</a><a href="{{ route('about_us') }}#tab-link-tab-7"
                                                        class="link-6">Careers</a></div>
                                                <div class="div-block-110">
                                                    <div class="div-block-111"><img
                                                            src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                                            loading="lazy" alt="" /></div>
                                                    <div class="div-block-112"><img
                                                            src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                                            loading="lazy" alt="" /></div>
                                                    <div class="div-block-113"><img
                                                            src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                                            loading="lazy" alt="" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="div-block-114"><img
                                                            src="{{ asset('webflow_assets/home-about/images/cloud-202.png') }}"
                                                            loading="lazy" sizes="100vw"
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
                                </section>
                            </div>
                        </div>
                    </section>
                    <section data-w-tab="Tab 2" id="privacy" class="tab-pane-tab-2 privacy w-tab-pane">
                        <div class="w-layout-blockcontainer content---b w-container">
                            <div class="w-layout-blockcontainer top-middle w-container">
                                <div class="w-layout-blockcontainer doctor-with-cloud-wire w-container">
                                    <div data-w-id="1911986f-b4a3-2d3b-c6eb-5fa21f921ceb"
                                        class="w-layout-blockcontainer wire w-container"><img
                                            sizes="(max-width: 6400px) 100vw, 6400px"
                                            srcset="{{ asset('webflow_assets/home-about/images/layer-2090-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/layer-2090-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/layer-2090-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/layer-2090-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/layer-2090-p-2000.png') }} 2000w, {{ asset('webflow_assets/home-about/images/layer-2090-p-2600.png') }} 2600w, {{ asset('webflow_assets/home-about/images/layer-2090-p-3200.png') }} 3200w, {{ asset('webflow_assets/home-about/images/layer-2090.png') }} 6400w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/layer-2090.png') }}"
                                            loading="lazy" class="image-77" />
                                    </div>
                                    <div data-w-id="1911986f-b4a3-2d3b-c6eb-5fa21f921ced"
                                        class="w-layout-blockcontainer doctor-copy w-container"><img
                                            sizes="(max-width: 479px) 30vw, 8vw"
                                            srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/6586cd407f34db93e6166aea_Ca-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/6586cd407f34db93e6166aea_Ca.png 648w"
                                            alt=""
                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/6586cd407f34db93e6166aea_Ca.png"
                                            loading="lazy" class="image-75" /></div>
                                    <div data-w-id="1911986f-b4a3-2d3b-c6eb-5fa21f921cef"
                                        class="w-layout-blockcontainer cloud w-container"><img
                                            sizes="(max-width: 479px) 60vw, (max-width: 9190px) 20vw, 1838px"
                                            srcset="{{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy.png') }} 1838w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy.png') }}"
                                            loading="lazy" class="image-76" />

                                    </div>
                                </div>
                            </div>
                            <div class="w-layout-blockcontainer culture-b w-container">
                                <div class="w-layout-blockcontainer container-26 w-container">
                                    <h1 class="heading-10"><strong>Privacy and Cookies Policy<br />‍</strong></h1>
                                    <p>We are Quality Improvement Technology Limited (trading as QI-Tech) (company
                                        number 14255905) (“we,” “our,” or “us”).<br /><br /> This policy pertains to
                                        personal data provided to us (“Your Information”) in connection with the
                                        services we offer to you as per an agreement entered into between us and you
                                        (the “Agreement”). <br /><br />It does not cover personal data obtained from
                                        your use of our website, including accessing our services.<br /><br />Data
                                        Protection Officer contact details: Dr Ghulam Ashraf, 155 Deane Rd, Bolton,
                                        United Kingdom, BL3 5AH, ghulam@qi-tech.co.uk.<br />
                                        <br>
                                        This policy outlines how we will use Your Information, the reasons for its
                                        usage, the entities with whom it may be shared, and other pertinent details.‍
                                    </p>
                                    <div class="text-block-50">How we collect and use your information<br />‍</div>
                                    <p>Your Information is provided to us by you in accordance with the Agreement. The
                                        types of information we receive and process include:</p>

                                    <ul role="list" class="list-2">
                                        <li>User registration details</li>
                                        <li>Company registration details</li>
                                        <li>Data submitted in the forms that is transmitted to your Company account.
                                            This may be for investigation of patient safety events, risks, complaints,
                                            claims, and mortalities.</li>
                                        <li>These data categories, chosen by you, may contain details such as names,
                                            addresses, email addresses, date of births, phone numbers, hospital numbers,
                                            NHS/patient/client identification numbers, ethnicity, religion, sexual
                                            orientation, language spoken, details of disabilities, and medical
                                            information.</li>
                                        <li>We may also request access to your device's microphone and camera, as well
                                            as the voice, video, photo, or other digital content on your mobile device
                                            to receive and process Your Information submitted via the mobile
                                            application.</li>
                                    </ul>


                                    <div class="paragraph-4"><strong>Confidentiality<br />‍</strong></div>
                                    <p>We recognize the confidentiality of Your Information and commit to safeguarding
                                        it in accordance with the legislative and compliance frameworks of the UK,
                                        adhering to the principles of the ISO27001 security standard.</p>

                                    <p class="paragraph-4"><br />Security and Disaster Management<br />‍<br /></p>
                                    <p>QI-Tech is hosted in multiple data centres within the legislative boundaries of
                                        the UK, and complies with the NHS Information Governance (NHS IG) toolkit. The
                                        system is designed to survive complete data centre outages, engineered for
                                        redundancy, resilience and continuity.<br />‍</p>
                                    <p>Data is consistently shielded from interception during transmission across
                                        networks and while stored on disk, using cryptography. We monitor assess our
                                        code for vulnerabilities and actively monitor the infrastructure for potential
                                        threats.<br />‍</p>
                                    <div class="paragraph-4">How we use Your Information<br />‍</div>
                                    <p>We use Your Information to offer you the QI-Tech service and related services. We
                                        gather anonymised statistical information about your activity while using the
                                        services we provide to you under the Agreement. This includes details such as
                                        the number of users viewing pages on a site or the frequency of feature usage.
                                        This monitoring aims to assess the effectiveness and responsiveness of the
                                        services provided in accordance with the Agreement and assist in their
                                        continuous improvement.<br /><br />Access to the network where Your Information
                                        is stored is limited to our operational software engineers. Development, test
                                        and live systems are separated to reduce the risks of unauthorised access or
                                        changes.<br /><br />QI-Tech collects technical information to identify the
                                        devices including mobile devices to generate encryption keys for secure data
                                        transmission.<br />‍</p>
                                    <div class="paragraph-4">Identifiable Data<br />‍</div>
                                    <p>The patient data entered into our system allows the identification of individual
                                        patients and clients ("Identifiable Data"), which we securely hold in compliance
                                        with stringent security policies and data protection laws. This Identifiable
                                        Data is exclusively used for the purpose of providing you with our services.
                                    </p>
                                    <p>Analysing this data on a large scale, anonymized basis, and aggregating it with
                                        your own or other organizations' anonymized data has the potential to yield
                                        insights for enhancing patient/client care and outcomes. Our mission includes
                                        contributing to the general improvement in patient care, supporting you and
                                        other healthcare organizations in deriving learnings and insights from
                                        aggregated anonymized data that may not be apparent when examining smaller,
                                        individually identifiable datasets.</p>
                                    <p>Therefore, we will separately de-identify the data you input into our system and
                                        utilize it for healthcare applications. We will NOT:</p>
                                    <ul role="list" class="list-4">
                                        <li>Re-identify the data or attempt to do so unless with your permission.</li>
                                        <li>Use Identifiable Data in the manner we will use de-identified data.</li>
                                    </ul>
                                    <div class="paragraph-4">Who we share your information with</div>
                                    <p><br />In order to assist us in delivering services as outlined in the Agreement,
                                        we may authorize specialised data hosting organizations or other third-party
                                        specialists to store or manage Your Information on our behalf. However, access
                                        to Your Information by staff from these organizations is not permitted unless
                                        such access is essential for the provision of services or required to comply
                                        with the law or a binding governmental order. We mandate that any such
                                        contractors, service providers, or third parties maintain the confidentiality of
                                        Your Information and use it solely for the limited purposes disclosed to
                                        them.<br /><br />Your Information may also be shared with other organizations in
                                        the event of selling or purchasing any business or assets (Your Information may
                                        be shared with the prospective seller or buyer), if another party acquires us or
                                        substantially all of our company assets (Your Information will be among the
                                        transferred assets), or when sharing Your Information is necessary to comply
                                        with legal or regulatory requirements.

                                        QI-Tech may share Your Information to diagnose or investigate a serious issue
                                        related to the QI-Tech network.</p>

                                    <div class="paragraph-4">Data Retention</div>
                                    <p><br />We will retain Your Information for the duration of your subscription to
                                        services as outlined in the Agreement. Subsequently, your information will be
                                        securely deleted from our systems within 30 days following the conclusion of the
                                        subscription or trial.<br />‍</p>

                                    <div class="paragraph-4">Cookies</div>
                                    <p><br />Cookies are small text files that are downloaded to your device during your
                                        website visit. They are subsequently sent back to the original website or
                                        another site recognizing the cookie, functioning as a form of website memory.
                                        This enables the site to recall details during future visits. Cookies play a
                                        role in remembering user preferences, enhancing user experience, and tailoring
                                        content to items most pertinent to users.<br /><br>

                                        Essential cookies play a vital role in ensuring a website is functional by
                                        enabling key features like page navigation and access to secure areas. These
                                        cookies are essential for the website to work and cannot be turned off in our
                                        systems. They are typically activated in response to your actions, such as
                                        setting privacy preferences, logging in, or filling in forms. While you may have
                                        the option to decline these cookies in your browser settings, it's important to
                                        note that disabling them may result in certain parts of the site not functioning
                                        properly. Additionally, it's worth mentioning that these cookies do not store
                                        any personally identifiable information.

                                        <br /><br />

                                        Performance and operational cookies enable us to track visits and traffic
                                        sources, conduct customer surveys, and perform other web analytics to measure
                                        and enhance our site's performance. They provide insights into the popularity of
                                        specific pages, the movement of visitors throughout the site, and help us make
                                        improvements. The data collected by these cookies is aggregated, and in some
                                        cases, limited identifiable information may be gathered.
                                        <br>
                                        <br />

                                        Functional cookies make the website work better by enhancing its features,
                                        helping personalize the site, and keeping track of user preferences and
                                        navigation aids. They could be set by us or third-party providers whose services
                                        we've added. If you don't allow these cookies, some or all of these services may
                                        not work properly.
                                        <br>
                                        <br>
                                        We use cookies and comparable tracking technologies on our website to improve
                                        site functionality and analyse usage patterns. Our objective is to enhance the
                                        overall experience for visitors to our websites, including those accessible
                                        through our mobile applications.
                                        <br>
                                        <br>
                                        <br>
                                        QI-Tech uses cookies for the following purposes:
                                    </p>
                                    <ul role="list" class="list-4">
                                        <li>Providing website functionality and assisting in navigation</li>
                                        <li>Assisting in registration and login to our events and provide the ability to
                                            deliver feedback</li>
                                        <li>Analysing site usage</li>
                                    </ul>
                                    <br>
                                    <br>
                                    <p>
                                        When a site is launched, session and persistent cookies attempt to remember
                                        information about you, such as your language preference or login information.
                                        Session cookies exist only until you close your web browser. Persistent cookies
                                        exist longer but for a specified period of time.
                                        <br>
                                        <br>
                                        In addition to the Cookie Preferences Panel, you have the option to adjust your
                                        browser and/or mobile device settings to prevent cookies from this website being
                                        placed on your computer or mobile device. You can typically modify your browser
                                        settings to decline new cookies, deactivate existing ones, or receive
                                        notifications when new ones are sent to your device. To configure your browser
                                        to reject cookies, consult the help instructions provided by the browser
                                        provider, usually located within the “Help,” “Tools,” or “Edit” menu, or check
                                        the settings of your mobile device. For more detailed guidance, visit <a
                                            href="www.aboutcookies.org." target="_blank">www.aboutcookies.org.</a>

                                        <br>
                                        <br>
                                        It's important to note that refusing or disabling cookies may result in the loss
                                        of some website functionality.
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <div class="w-layout-blockcontainer footer w-container">
                                    <section class="footer-dark">
                                        <div class="container-16">
                                            <div class="footer-wrapper">
                                                <div data-w-id="234e7673-b861-2443-8407-83ad6e044508"
                                                    class="footer-content"><a href="#"
                                                        class="footer-brand w-inline-block"><img
                                                            src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                                            loading="lazy" alt="" class="image-69" /><img
                                                            src="{{ asset('webflow_assets/home-about/images/clouds.png') }}"
                                                            loading="lazy" width="262"
                                                            sizes="(max-width: 479px) 57.5px, 12vw" alt=""
                                                            srcset="{{ asset('webflow_assets/home-about/images/clouds-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/clouds-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/clouds-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/clouds.png') }} 1093w"
                                                            class="image-70" /></a>
                                                    <div id="w-node-_234e7673-b861-2443-8407-83ad6e04450c-6e044504"
                                                        class="footer-block">
                                                        <div class="title-small">Company</div><a href="{{ route('about_us') }}#tab-link-tab-1"
                                                            class="footer-link">About</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                            class="footer-link">Privacy Policy</a><a href="#"
                                                            class="footer-link">Terms
                                                            of Service</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                            class="footer-link">Cookies Policy</a><a href="{{route('about_us')}}#tab-link-tab-5"
                                                            class="footer-link">Corporate Responsibility</a><a
                                                            href="{{ route('about_us') }}#tab-link-tab-6" class="footer-link">Modern
                                                            Slavery</a><a href="{{ route('about_us') }}#tab-link-tab-7"
                                                            class="footer-link">Careers</a>
                                                    </div>
                                                    <div id="w-node-_234e7673-b861-2443-8407-83ad6e04451d-6e044504"
                                                        class="footer-block">
                                                        <div class="title-small">Contact us</div><a href="#"
                                                            class="footer-link">42 Reading Road
                                                            <br />South Fleet<br />Hampshire<br />GU51 3QP</a><a
                                                            href="#" class="footer-link">+44
                                                            01252 613425</a><a href="#"
                                                            class="footer-link">support@qi-tech.co.uk</a>
                                                    </div>
                                                    <div id="w-node-_234e7673-b861-2443-8407-83ad6e04452c-6e044504"
                                                        class="footer-block">
                                                        <div class="title-small">Social</div>
                                                        <div class="footer-social-block"><a href="#"
                                                                class="footer-social-link w-inline-block"><img
                                                                    src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                                                    loading="lazy" width="34.5" alt=""
                                                                    class="image-71" /></a><a href="#"
                                                                class="footer-social-link w-inline-block"><img
                                                                    src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                                                    loading="lazy" width="37" alt=""
                                                                    class="image-72" /></a><a href="#"
                                                                class="footer-social-link w-inline-block"><img
                                                                    src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                                                    loading="lazy" width="20" alt=""
                                                                    class="image-73" /></a></div>
                                                        <div class="footer-cloud"><img
                                                                src="{{ asset('webflow_assets/home-about/images/cloud-20big.png') }}"
                                                                loading="lazy"
                                                                data-w-id="234e7673-b861-2443-8407-83ad6e044537"
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
                                                src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                                loading="lazy" alt="" /></div>
                                        <div class="div-block-109"><a href="{{ route('about_us') }}#tab-link-tab-1" class="link-6">About</a><a
                                        href="{{ route('about_us') }}#tab-link-tab-2" class="link-6">Privacy Policy</a><a href="{{ route('about_us') }}#tab-link-tab-3"
                                                class="link-6">Terms of Service</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                class="link-6">Cookies Policy</a><a href="{{route('about_us')}}#tab-link-tab-5"
                                                class="link-6">Corporate Responsibility  </a><a href="{{ route('about_us') }}#tab-link-tab-6"
                                                class="link-6">Modern Slavery</a><a href="{{ route('about_us') }}#tab-link-tab-7"
                                                class="link-6">Careers</a></div>
                                        <div class="div-block-110">
                                            <div class="div-block-111"><img
                                                    src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                                    loading="lazy" alt="" /></div>
                                            <div class="div-block-112"><img
                                                    src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                                    loading="lazy" alt="" /></div>
                                            <div class="div-block-113"><img
                                                    src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                                    loading="lazy" alt="" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="div-block-114"><img
                                                    src="{{ asset('webflow_assets/home-about/images/cloud-202.png') }}"
                                                    loading="lazy" sizes="100vw"
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
                    </section>
                    <section data-w-tab="Tab 3" id="terms" class="tab-pane-tab-3 w-tab-pane">
                        <div class="w-layout-blockcontainer content---b terms-of-service-copy w-container">
                            <div class="w-layout-blockcontainer top-middle w-container">
                                <div class="w-layout-blockcontainer doctor-with-cloud-wire w-container">
                                    <div data-w-id="ca5d07b0-f8c8-f92e-0f59-5a2b3df2c012"
                                        class="w-layout-blockcontainer squares w-container"><img
                                            sizes="(max-width: 479px) 100vw, (max-width: 6696px) 50vw, 3348px"
                                            srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/65956a6f6950ea4dc273c679_sqaures-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/65956a6f6950ea4dc273c679_sqaures-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/65956a6f6950ea4dc273c679_sqaures-p-1080.png 1080w, https://assets-global.website-files.com/6564282be531be60fd0d391f/65956a6f6950ea4dc273c679_sqaures-p-1600.png 1600w, https://assets-global.website-files.com/6564282be531be60fd0d391f/65956a6f6950ea4dc273c679_sqaures-p-2000.png 2000w, https://assets-global.website-files.com/6564282be531be60fd0d391f/65956a6f6950ea4dc273c679_sqaures-p-2600.png 2600w, https://assets-global.website-files.com/6564282be531be60fd0d391f/65956a6f6950ea4dc273c679_sqaures-p-3200.png 3200w, https://assets-global.website-files.com/6564282be531be60fd0d391f/65956a6f6950ea4dc273c679_sqaures.png 3348w"
                                            alt=""
                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65956a6f6950ea4dc273c679_sqaures.png"
                                            loading="lazy" /></div>
                                    <div data-w-id="171ffec8-8b56-f89e-67ea-580bd0a82b07"
                                        class="w-layout-blockcontainer wire w-container"><img
                                            sizes="(max-width: 6400px) 100vw, 6400px"
                                            srcset="{{ asset('webflow_assets/home-about/images/layer-2090-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/layer-2090-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/layer-2090-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/layer-2090-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/layer-2090-p-2000.png') }} 2000w, {{ asset('webflow_assets/home-about/images/layer-2090-p-2600.png') }} 2600w, {{ asset('webflow_assets/home-about/images/layer-2090-p-3200.png') }} 3200w, {{ asset('webflow_assets/home-about/images/layer-2090.png') }} 6400w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/layer-2090.png') }}"
                                            loading="lazy" class="image-77" />
                                    </div>
                                    <div data-w-id="171ffec8-8b56-f89e-67ea-580bd0a82b09"
                                        class="w-layout-blockcontainer doctor-copy w-container"><img
                                            sizes="(max-width: 479px) 30vw, 8vw"
                                            srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/65898acb205fdc3745add288_terms%20of%20service-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/65898acb205fdc3745add288_terms%20of%20service.png 643w"
                                            alt=""
                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65898acb205fdc3745add288_terms%20of%20service.png"
                                            loading="lazy" class="image-75" /></div>
                                    <div data-w-id="171ffec8-8b56-f89e-67ea-580bd0a82b0b"
                                        class="w-layout-blockcontainer cloud w-container"><img
                                            sizes="(max-width: 479px) 60vw, (max-width: 9190px) 20vw, 1838px"
                                            srcset="{{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy.png') }} 1838w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy.png') }}"
                                            loading="lazy" class="image-76" />

                                    </div>
                                </div>
                            </div>
                            <div class="w-layout-blockcontainer culture-b w-container">
                                <div class="w-layout-blockcontainer container-28 w-container">
                                    <h1 class="heading-15"><strong class="bold-text">Terms And Conditions</strong>
                                    </h1>
                                    <p>For patient care, our software is not used in delivering patient services, and it
                                        is not considered mission-critical. Consequently, we operate with a lower legal
                                        risk, allowing us to offer the software at a more affordable price.<br />
                                        <br>
                                        QI-Tech provides commercial off-the-shelf solutions.

                                        <br>
                                        <br>
                                        You (also known as "client") agree to be bound by this terms of use license
                                        agreement ("agreement") in any of the following ways: (a) by accepting an order
                                        form, (b) by opening the packaging containing the software, (c) by indicating
                                        your acceptance of the following terms (by selecting "agreed," "yes," or another
                                        word or phrase of affirmation), or (d) by installing, copying, or in any way
                                        using the licensed materials (as defined in section 1(f), below) provided to you
                                        by QI-Tech and/or that of its affiliates as provided hereunder, together with
                                        any updates thereto.

                                        <br>
                                        <br>
                                        Where you have not previously and unambiguously agreed to the terms of this
                                        agreement (save where expressly amended by written agreement signed by both
                                        QI-Tech and client), then by installing or using this software, you are agreeing
                                        to be bound by these terms. Accordingly, if you do not agree to these terms, do
                                        not install or use the software and notify QI-Tech within ten (10) days for a
                                        full refund.
                                        <br>
                                        <br>
                                        The functionality of the software available to client is controlled by the
                                        software key supplied by QI-Tech to client. For the avoidance of doubt, where
                                        client wishes additional functionality to be released in the software, client
                                        shall approach QI-Tech, who may make available such additional functionality in
                                        return for an additional license fee paid to QI-Tech. This agreement shall
                                        govern client’s use of any such additional functionality.
                                        <br>
                                        <br>

                                        Where QI-Tech is hosting the software for client or making it available via a
                                        cloud-based subscription service, the terms of the attached hosting addendum
                                        shall apply in addition to this agreement. In the event of any conflict between
                                        the provisions of this agreement and that hosting addendum, the provisions of
                                        the hosting addendum shall prevail.
                                        <br>
                                        <br>
                                        Where the software is supplied to client as demonstration versions, then (a) the
                                        provisions of the previous paragraph regarding a cloud-based service shall
                                        apply, and (b) client shall have no right to use the software or to access the
                                        data used during the demonstration period after the expiry of the demonstration
                                        period permitted by QI-Tech.
                                    </p>
                                    <div class="text-block-57">Definitions<br />‍</div>
                                    <ul role="list" class="list-6"
                                        style="display: flex;flex-direction:column;gap:0.5rem;list-style: none;">
                                        <li>(a) “Affiliated” means affiliated in the manner indicated in the Order Form.
                                        </li>
                                        <li>(b) “Authorized Users” shall consist of the individuals Client permits to
                                            either access or use the Licensed Materials. </li>
                                        <li>(c) “Documentation” means the published user manuals and other written
                                            materials concerning the Software that QI-Tech generally makes available to
                                            its clients from time to time.</li>
                                        <li>(d) “Enhancements” means any updates, upgrades, improvements or new versions
                                            of the Software or Documentation that QI-Tech may release or make generally
                                            available to its clients from time to time, which items are also subject to
                                            license.</li>
                                        <li>(e) “Licensed Materials” means (i) the Software, (ii) the Documentation,
                                            (iii) any Enhancements; any Modifications; and any copy of the Software,
                                            Documentation, Enhancements or Modifications and Third Party Software.</li>
                                        <li>(f) “Licensed Thresholds” refers to the limitations on use specified on the
                                            Order Form such as, without limitation, the following: license type (i.e.
                                            the functionality included in the license to Client); number of licensed
                                            users; number of licensed locations.</li>
                                        <li>(g) “Licensed Locations” are as indicated on the Order Form or stated by
                                            their number in the Order Form, or created within the Company Account -
                                            Listed Licensed Locations being specified by name and address. All
                                            authorized locations must be listed and may be excluded from the list only
                                            in accordance with terms for locations on the Order Form.</li>
                                        <li>(h) “Modifications” means any alteration, change or modification to any
                                            Licensed Materials made at Client’s request.</li>
                                        <li>(i) “Order Form” or “Order” refers to the order form or quotation provided
                                            by QI-Tech to Client that specifies the fees and certain parameters for the
                                            Licensed Materials, such as, without limitation, License Thresholds.</li>
                                        <li>(j) “Permitted Independent IT Contractor” means an individual or group of
                                            individuals not employed by Client but who are engaged in work that supports
                                            Client’s use of the Licensed Materials, for example as outsourced
                                            information technology resources. To qualify as Permitted Independent IT
                                            Contractors, such individuals or group of individuals must be identified on
                                            the Order Form.</li>
                                        <li>(k) “Software” means the executable object code form of the QI-Tech-owned
                                            Software identified on the Order Form, together with any Enhancements or
                                            Modifications. The term “Software” excludes any software licensed by third
                                            parties.</li>
                                        <li>(l) “Third Party Software” means any computer programs not owned by QI-Tech
                                            that are licensed to Client and provided along with the Licensed Materials.
                                        </li>
                                    </ul>

                                    <br>
                                    <br>
                                    <br>

                                    <div class="text-block-58">Grant of Rights/Client Responsibilities</div>
                                    <br>
                                    <ul role="list" class="list-6"
                                        style="display: flex;flex-direction:column;gap:0.5rem;list-style: none;">
                                        <li><strong>(a) License Grant:</strong> QI-Tech hereby grants to Client a
                                            non-transferable, non-exclusive subscription license for its Authorized
                                            Users to remotely access and use the Licensed Material, in each case,
                                            subject to the License Threshold limitations set forth in this Agreement and
                                            the associated Order Form (including the duration of any Subscription
                                            License or renewal thereof) up to the Licensed Thresholds for which the Fee
                                            has been paid.</li>

                                        <li><strong>(b) Limitations:</strong> Any right not specifically granted herein
                                            is reserved. Client shall have no right to assign, sublicense, transfer,
                                            rent, lease, or distribute the Licensed Materials. No right of ownership or
                                            any other exclusive right in any particular manner of configuration,
                                            customization or setup of the Software performed by QI-Tech is granted to
                                            Client. No right is granted to use the Licensed Materials other than in
                                            support of Client's own business processes and activities. No right is
                                            granted herein to operate the Software in a service bureau, outsourcing
                                            business or other manner in which the Software is used to process or manage
                                            information other than that generated by Client in the course of Client's
                                            own operations. Subject to this section, Client specifically agrees to
                                            refrain from any direct or indirect efforts or attempts to reverse engineer
                                            the Software or to develop any derivative work thereof of any kind. Client
                                            shall permit only Authorized Users to access the Software and only for the
                                            exclusive purpose of operating the Software in the course of Client's
                                            business. Client shall ensure that each Authorized User has and only uses
                                            his or her own unique account name/email address and password combination to
                                            access the Software. Client shall not permit more than one person to use any
                                            one account name and password combination. The Documentation may be
                                            reproduced for distribution solely within Client's business as needed for
                                            training and support, provided that all copyright and other notices shall
                                            also be reproduced intact along with such copies. Client shall not permit
                                            any person or entity other than QI-Tech to maintain or in any way change or
                                            modify the Software or any element thereof. Client's right to the use of the
                                            Licensed Materials is limited to the duration of the Subscription License
                                            (or renewal thereof) for which the Subscription License fee has been paid.
                                            Some elements of Third Party Software require the distribution of separate
                                            notices, license terms and/or source code, and all Third Party Software is
                                            subject to the license terms of such Third Party Software. None of the terms
                                            of the Third Party Software licenses diminish or minimize the rights QI-Tech
                                            is otherwise offering to Client in this Agreement. For each such element of
                                            Third Party Software, the applicable licenses, notices or other elements can
                                            be found on the distribution media for the Software licensed by this
                                            Agreement in the folder named "Third Party Software. Nothing in this section
                                            shall be construed as removing any right Client may have under European
                                            Union Directive 2009/24/EC.</li>

                                        <li><strong>(c) Authorized Users:</strong> Access to and utilization of the
                                            Licensed Materials are restricted to Authorized Users. This access is
                                            permissible only utilizing a secure connection to the server hosting the
                                            Software for Client's use. Furthermore, this access is exclusively for the
                                            operation of the Software within the framework of Client’s business
                                            activities. Client is responsible for ensuring that only Authorized Users
                                            are granted access to the Licensed Materials. Patients and customers of
                                            Client are not considered, nor required to be, Authorized Users, except for
                                            instances where they provide feedback that becomes part of the Software.
                                        </li>

                                        <li><strong>(d) Location Substitution:</strong> Client has the option to replace
                                            one authorized location with another.</li>

                                        <li><strong>(e) Hardware and Additional Software:</strong> It is the sole
                                            responsibility of the Client to procure and ensure the proper functioning of
                                            the hardware and software required to operate and utilize the Licensed
                                            Materials. The minimum hardware and software prerequisites are outlined in
                                            the proposal and background information provided for the Software, which may
                                            be subject to updates during system transitions. Any expenses related to the
                                            acquisition, upkeep, or utilization of the hardware or supporting software
                                            (such as operating systems) and/or any connectivity essential for the
                                            utilization or support of the Licensed Materials are entirely the
                                            responsibility of the Client.</li>

                                        <li><strong>(f) Other Obligations:</strong> Client is required to collaborate
                                            with QI-Tech to facilitate the installation, support, troubleshooting, or
                                            any other necessary services. This cooperation may involve providing
                                            adequate facilities and granting access to systems and equipment, as well as
                                            assigning appropriately skilled and trained personnel to engage with QI-Tech
                                            representatives, whether through telephone support, in-person service
                                            visits, or other means. Client must assist QI-Tech in establishing remote
                                            access via an Internet-based third-party remote access solution when
                                            necessary for effective Software support. Failure by Client to fulfil these
                                            responsibilities may relieve QI-Tech from the obligation to provide services
                                            that become more difficult or expensive due to Client’s non-compliance.
                                            QI-Tech reserves the right, at its discretion, to offer continued services
                                            to Client under such circumstances for an additional fee.</li>

                                        <li><strong>(g) Acceptance:</strong> Within three (3) months of receiving the
                                            Licensed Materials (or, if applicable, within three (3) months of making
                                            them available for use or download), Client must initiate testing and
                                            evaluation of the Licensed Materials. If there is a significant operational
                                            discrepancy in the Software or a substantial defect in other Licensed
                                            Materials during this period, Client must notify QI-Tech in writing. QI-Tech
                                            will then have fourteen (14) days to address the discrepancy or defect and
                                            provide Client with a written Notice of Repair. Following this, there will
                                            be another fourteen (14) day period for Client to retest and reevaluate the
                                            Licensed Materials. If the discrepancy is not resolved within this
                                            timeframe, Client may, at its discretion, extend the resolution period or
                                            terminate the relevant Order Form. Client acknowledges acceptance of the
                                            Licensed Materials upon the earliest of the following: (i) Client providing
                                            written acceptance notice, (ii) Client not reporting a discrepancy or defect
                                            within the first thirty (30) days after deploying the Software in a
                                            production/live environment for go-live, (iii) Client not reporting a
                                            discrepancy or defect within the first three (3) months of the Licensed
                                            Materials being made available for use (or, if applicable, within three (3)
                                            months of making them available for download), or (iv) more than fourteen
                                            (14) days passing since QI-Tech's last Notice of Repair without Client
                                            issuing a written notice of significant non-conformity, with this date being
                                            the "Acceptance Date".</li>
                                    </ul>


                                    <br>
                                    <br>



                                    <div class="text-block-61">Support and Maintenance<br /></div>
                                    <br>
                                    <ul role="list" class="list-6"
                                        style="display: flex;flex-direction:column;gap:0.5rem;list-style: none;">
                                        <li><strong>(a) Maintenance:</strong> Support and maintenance services
                                            ("Maintenance") will adhere to the latest version of the Support Guide. The
                                            Subscription License Fee includes an irrevocable subscription to Maintenance
                                            for the Subscription License term or any subsequent renewals.</li>

                                        <li><strong>(b) Maintenance Duration:</strong> Maintenance is provided for
                                            one-year intervals. Unless otherwise specified on the Order Form, the
                                            "Initial Term" begins from the Effective Date of the Agreement, marking the
                                            first year of Maintenance, with the Anniversary Date set as the
                                            corresponding month and day. Each subsequent one-year maintenance term
                                            (termed a "Renewal Term") commences on the Anniversary Date. Following the
                                            Initial Term (and completion of any Minimum Commitment period, if
                                            applicable), Maintenance may be renewed for additional one-year terms on the
                                            Anniversary Date upon receipt of the invoice from QI-Tech for the Renewal
                                            Term. However, either party has the option to terminate Maintenance by
                                            issuing written notice at least three (3) months prior to the expiration of
                                            the Initial Term or any Renewal Term, with termination effective at the
                                            later of (i) the end of the current maintenance term and (ii) the Minimum
                                            Commitment period. In the absence of a Minimum Commitment, Client may
                                            terminate Maintenance by failing to pay the renewal invoice from QI-Tech by
                                            the due date. If Client chooses not to renew Maintenance with QI-Tech but
                                            later decides to resume Maintenance, QI-Tech may, at its discretion,
                                            reinstate Maintenance, provided that QI-Tech continues to offer Maintenance
                                            on the Software. The reinstatement fee includes (i) the prorated fee Client
                                            would have paid if Maintenance had been maintained since its termination,
                                            (ii) prepayment of Maintenance fees for the subsequent full term, and (iii)
                                            a reactivation fee equal to 10% of the total of (i) and (ii) above.
                                            Maintenance cannot be terminated during a Minimum Commitment term under this
                                            section.</li>

                                        <li><strong>(c) Minimum Commitment:</strong> The payment obligations outlined in
                                            this section apply only if a Minimum Commitment is specified on the Order
                                            Form. If Client opts for an extended commitment to receive Maintenance,
                                            hosting services, and/or continue with its Subscription License, the
                                            duration of this commitment is specified on the Order Form ("Minimum
                                            Commitment"). The Minimum Commitment begins concurrently with the Initial
                                            Term of Maintenance. If Client cancels its order, fails to pay the specified
                                            fees for the Minimum Commitment duration, or if this Agreement is terminated
                                            for reasons other than Software acceptance failure, Client agrees to
                                            immediately settle all outstanding invoices and 100% of all remaining fees
                                            due for the remainder of the Minimum Commitment term.</li>

                                        <li><strong>(f) Support Guide:</strong> QI-Tech reserves the right to make
                                            improvements, substitutions, or modifications to any element or part of the
                                            Support Guide as determined by QI-Tech at its discretion, provided such
                                            changes do not significantly degrade the services received by Client under
                                            the Support Guide as a whole.</li>
                                    </ul>
                                    <br>


                                    <div class="text-block-63">Fees<br />‍</div>
                                    <ul role="list" class="list-6"
                                        style="display: flex; flex-direction: column; gap: 0.5rem; list-style: none;">
                                        <li><strong>(a) Amount:</strong> The License Fee for the Licensed Materials is
                                            determined based on the number of potential users and the scale of Client’s
                                            enterprise, as specified on the Order Form. The Fee is outlined therein.
                                            Should Client’s organization expand, supplemental license fees
                                            ("Supplemental License Fees") may become applicable to accommodate the
                                            increased use of the Licensed Materials.</li>

                                        <li><strong>(b) Payment Schedule:</strong> The Initial Fees are payable within
                                            30 days from the date of the invoice. If Client surpasses the Licensed
                                            Thresholds specified for the Licensed Materials on the Order Form due to
                                            business growth or any other reason, QI-Tech may invoice Supplemental
                                            License Fees accordingly. If applicable, these fees are due within thirty
                                            (30) days from QI-Tech's invoice date.</li>

                                        <li><strong>(c) Annual Fees:</strong> In cases where the Anniversary Date aligns
                                            with the Effective Date of the Agreement, the Initial Term Maintenance fees,
                                            hosting fees, and/or Subscription Fees (as applicable) are due within thirty
                                            (30) days from the date of QI-Tech's invoice; otherwise, the Initial Term
                                            Annual Fees are payable prior to the commencement of the Initial Term.
                                            QI-Tech will notify Client of the Annual Fees no later than forty-five (45)
                                            days before the beginning of each Renewal Term, and Client must settle these
                                            fees before the commencement of each Renewal Term.</li>
                                    </ul>
                                    <br>
                                    <div class="text-block-64">Training & Other Services<br />‍</div>
                                    <ul role="list" class="list-6"
                                        style="display: flex; flex-direction: column; gap: 0.5rem; list-style: none;">
                                        <li><strong>(a) Service Provision:</strong> Training, implementation,
                                            integration, and other services will be provided by QI-Tech as specified on
                                            the Order Form.</li>

                                        <li><strong>(b) Service Terms and Conditions:</strong> In instances where
                                            agreed-upon service dates and times are subsequently cancelled or
                                            rescheduled at Client’s request, the following consequences apply: (i)
                                            Client is responsible for reimbursing QI-Tech for expenses incurred prior to
                                            receiving the cancellation or rescheduling notice, and (ii) if QI-Tech is
                                            notified less than twenty (20) business days before the scheduled date,
                                            Client forfeits the service hours that QI-Tech is unable to reallocate to
                                            another client for the same date and time (Client must compensate QI-Tech
                                            for these hours if not already done so). Any services specified on the
                                            associated Order Form must be utilized by Client before the one-year
                                            anniversary of the Effective Date. Any services unused by Client at that
                                            point will expire and cannot be transferred to other engagements.</li>

                                        <li><strong>(c) Reimbursement of Expenses:</strong> Client shall reimburse
                                            QI-Tech for reasonable out-of-pocket expenses incurred in providing training
                                            or other services. Costs are passed directly to Client without any mark-up.
                                            QI-Tech does not charge for time spent in transit for onsite services.</li>
                                    </ul>
                                    <br>
                                    <div class="text-block-65">Termination & Breach<br />‍</div>
                                    <ul role="list" class="list-6"
                                        style="display: flex; flex-direction: column; gap: 0.5rem; list-style: none;">
                                        <li><strong>(a) Client reserves the right to terminate this Agreement at any
                                                time for convenience, provided that the Agreement is not currently
                                                subject to a Minimum Commitment and that Client has kept up with
                                                payments to QI-Tech of applicable fees before termination.</strong></li>

                                        <li><strong>(b) In the case of a Subscription License, this Agreement will
                                                terminate if Client fails to renew the Subscription License by paying
                                                the invoice before the expiration of the Subscription License
                                                term.</strong></li>

                                        <li><strong>(c) Client retains the right to terminate this Agreement in its
                                                entirety (including any prevailing Schedule or Addendum) if QI-Tech is
                                                in breach of QI-Tech Service (as defined in section 7(f) below), and
                                                thirty (30) days have passed since Client provided written notice of the
                                                breach to QI-Tech, detailing the nature and specifics of the breach,
                                                without the breach being rectified.</strong></li>

                                        <li><strong>(d) The parties acknowledge that the purpose of the right outlined
                                                in 7(c) is to allow Client to exit an untenable situation. Therefore, if
                                                Client opts not to exercise the right to terminate under 7(c) within six
                                                (6) months of the breach by QI-Tech, Client's right to terminate for
                                                that breach will lapse.</strong></li>

                                        <li><strong>(e) QI-Tech has the right to terminate this License immediately upon
                                                written notice if (i) Client materially breaches this Agreement and
                                                fails to remedy the breach within thirty (30) days of receiving written
                                                notice from QI-Tech detailing the nature and specifics of the breach,
                                                (ii) Client materially breaches the restrictions on distributing the
                                                Licensed Materials to third parties, in which case there is no right to
                                                cure; or (iii) Client undergoes receivership, administration,
                                                liquidation, or a similar event under the laws of its
                                                jurisdiction.</strong></li>

                                        <li><strong>(f) A "QI-Tech Service Breach" encompasses any of the following: (i)
                                                QI-Tech consistently fails to provide Maintenance services substantially
                                                in line with the Support Guide, or (ii) there is a significant
                                                non-conformance in the Software's operation, persisting for at least 30
                                                consecutive days (without a workaround provided by QI-Tech), subsequent
                                                to QI-Tech being duly notified of the issue(s), or (iii) the Software
                                                consistently and substantially fails to perform in accordance with the
                                                applicable Documentation, and the identified issues have not been
                                                resolved according to the Support Guide, or (iv) QI-Tech has not made
                                                general release updates to the Licensed Materials available to Client
                                                within a timeframe consistent with similar releases to other
                                                clients.</strong></li>
                                    </ul>

                                    <br>

                                    <div class="text-block-66">Title<br />‍</div>
                                    <ul role="list" class="list-6"
                                        style="display: flex; flex-direction: column; gap: 0.5rem; list-style: none;">
                                        <li><strong>(a) </strong>QI-Tech shall maintain full and exclusive right, title,
                                            and ownership of the Licensed Materials and all associated intellectual
                                            property rights, including any derivative works, regardless of their origin,
                                            excluding Third Party Software, which shall remain the property of its
                                            respective provider. Any Modifications to any part of the Licensed
                                            Materials, excluding Third Party Software, will immediately become the
                                            property of QI-Tech upon creation, regardless of whether the Modifications
                                            were initiated by Client or not. Should Client possess or obtain any rights,
                                            title, or interest in any Modifications, Client hereby transfers all such
                                            rights, title, and interest to QI-Tech, including all intellectual property
                                            rights therein. QI-Tech shall hold all intellectual property rights in any
                                            works produced during the performance of this Agreement or the provision of
                                            any services.</li>

                                        <li><strong>(b) Client Data:</strong> Client shall always retain exclusive
                                            ownership of all data entered into the Software licensed to Client.</li>
                                    </ul>
                                    <br>
                                    <div class="text-block-67">Warranties And Limitations<br />‍</div>
                                    <ul role="list" class="list-6"
                                        style="display: flex; flex-direction: column; gap: 0.5rem; list-style: none;">
                                        <li><strong>(a) General Warranty:</strong> QI-Tech warrants its right to (i)
                                            enter into this Agreement, (ii) provide the licenses offered under this
                                            Agreement, and (iii) grant the right for Client and its Authorized Users to
                                            utilize the Third Party Software.</li>

                                        <li><strong>(b) Limited Warranty:</strong> QI-Tech also warrants that the
                                            Software and any Enhancements will, for a period of six (6) months from the
                                            Effective Date, perform substantially as described in the current
                                            Documentation. No warranty or assurance is made (i) regarding the Software's
                                            ability to fulfill all or any of Client’s specific requirements or (ii) that
                                            the use of the Software will be uninterrupted or error-free. These Limited
                                            Warranties do not apply if (i) Client fails to report a nonconformity or
                                            defective aspect of the Software within the specified Limited Warranty
                                            period, (ii) the Software is not used in accordance with the current
                                            Documentation, (iii) Client makes unauthorized changes to the underlying
                                            Software, not approved in writing by QI-Tech, and/or (iv) the nonconformity
                                            arises from the misuse of the Software.</li>

                                        <li><strong>(c) Remedies:</strong> Upon written notice of a breach of the
                                            Limited Warranty stated in the above section (b), QI-Tech or its
                                            representative will make all commercially reasonable efforts to rectify the
                                            nonconformity or repair or replace any defective aspect of the Licensed
                                            Materials. If the breach cannot be rectified, QI-Tech will (i) accept the
                                            return of the Licensed Materials, (ii) terminate the license granted herein,
                                            and (iii) refund the Initial Fees and Maintenance fees paid by Client as of
                                            the date of the written notice provided to QI-Tech.</li>

                                        <li><strong>(d) DISCLAIMER:</strong> EXCEPT AS EXPRESSLY PROVIDED IN THIS
                                            AGREEMENT OR AS REQUIRED BY APPLICABLE LAW, ALL WARRANTIES, CONDITIONS,
                                            INDEMNITIES, AND GUARANTEES REGARDING THE LICENSED MATERIALS, WHETHER
                                            EXPRESS OR IMPLIED, ARISING BY LAW, CUSTOM, PRIOR ORAL OR WRITTEN STATEMENTS
                                            BY CLIENT, QI-TECH, OR ITS REPRESENTATIVES, OR OTHERWISE (INCLUDING, BUT NOT
                                            LIMITED TO ANY WARRANTY OF MERCHANTABILITY, SATISFACTORY QUALITY, OR FITNESS
                                            FOR A PARTICULAR PURPOSE, OR ANY ASSURANCE OF SATISFACTION) ARE HEREBY
                                            DISCLAIMED, OVERRIDDEN, AND EXCLUDED. ANY PROMISE, COMMITMENT, OR ASSURANCE
                                            OF ERROR-FREE OR UNINTERRUPTED USE OF THE LICENSED MATERIALS IS ALSO HEREBY
                                            DISCLAIMED.</li>
                                    </ul>


                                    <br>
                                    <div class="text-block-68">Indemnification<br />‍</div>
                                    <ul role="list" class="list-6"
                                        style="display: flex; flex-direction: column; gap: 0.5rem; list-style: none;">
                                        <li><strong>(a) Intellectual Property Indemnification:</strong> If an action is
                                            brought against Client claiming that any part of the Licensed Materials
                                            infringes a patent, trade secret, or copyright, QI-Tech will defend,
                                            indemnify, protect, and hold harmless Client (along with its shareholders,
                                            directors, officers, and employees) from such claim or action. This
                                            indemnification is contingent upon (i) Client promptly notifying QI-Tech
                                            upon learning of the claim, (ii) QI-Tech having sole control over the
                                            defense and any negotiation for settlement or compromise of the claim, (iii)
                                            Client taking no action in the litigation that undermines any defense
                                            available to Client or QI-Tech, and (iv) Client at all times mitigating its
                                            losses in such circumstances.</li>

                                        <li><strong>(b) Alternative Solution:</strong> If a claim as described above
                                            arises or is asserted, Client will allow QI-Tech, at QI-Tech’s sole
                                            discretion and expense, to (i) secure the right for Client to continue using
                                            the Licensed Materials, (ii) modify or replace the Licensed Materials to
                                            rectify the infringement while ensuring equivalent functionality, or (iii)
                                            terminate this Agreement and request the return of the Licensed Materials.
                                        </li>

                                        <li><strong>(c) Restriction:</strong> QI-Tech shall not bear any indemnity or
                                            liability obligation to Client under this section 11 if any intellectual
                                            property infringement claim arises from (i) a modification of the Licensed
                                            Materials not carried out by QI-Tech or not approved by QI-Tech in writing,
                                            (ii) Client's failure to promptly install an Enhancement or new release, if
                                            such installation would have prevented the infringement, in case Client
                                            hosts the Software, or (iii) the combination of the Licensed Materials or
                                            any component thereof with materials provided by others, resulting in the
                                            claim of infringement, whereas the individual use of the Licensed Materials
                                            or any component thereof would not have resulted in such a claim.</li>

                                        <li><strong>(d) QI-Tech's Liability:</strong> Client shall bear full liability
                                            to QI-Tech for all claims and actions arising from Client’s utilization or
                                            misuse of the Licensed Materials, without any limitations.</li>
                                    </ul>


                                    <br>
                                    <div class="text-block-69">Liability Limitation<br />‍</div>
                                    <ul role="list" class="list-6"
                                        style="display: flex; flex-direction: column; gap: 0.5rem; list-style: none;">
                                        <li><strong>(a) To the fullest extent permitted by law:</strong> all implied
                                            conditions and warranties, whether statutory or otherwise, related to this
                                            Agreement, the Licensed Materials, or the services provided by QI-Tech, are
                                            hereby excluded.</li>

                                        <li><strong>(b) The charges levied by QI-Tech to Client:</strong> are determined
                                            based on the exclusions and limitations of liability outlined in this
                                            Agreement. Client expressly acknowledges the reasonableness of these
                                            exclusions and limitations, considering the potential disproportionate
                                            damages that may be awarded to Client in the event of a breach by QI-Tech.
                                            QI-Tech is open to exploring additional insurance coverage to assume
                                            additional liability, provided that Client agrees to pay a higher price.
                                            Should Client wish for QI-Tech to seek a quotation for such additional
                                            insurance coverage, Client must notify QI-Tech prior to entering into this
                                            Agreement.</li>

                                        <li><strong>(c) The subsequent provisions in this section 12:</strong> outline
                                            QI-Tech's entire liability (including any liability for the acts or
                                            omissions of its employees, agents, or subcontractors) to Client concerning:
                                            <ul>
                                                <li>1. Breach of QI-Tech’s contractual obligations;</li>
                                                <li>2. Tortious acts or omissions for which QI-Tech is liable;</li>
                                                <li>3. Actions arising from misrepresentations made by or on behalf of
                                                    QI-Tech in connection with the performance or anticipated
                                                    performance of this Agreement or as a consequence of QI-Tech
                                                    entering into this Agreement.</li>
                                            </ul>
                                        </li>

                                        <li><strong>(d) QI-Tech's total liability to Client for all claims:</strong>
                                            shall not exceed the greater of (i) the Specified Monies, (ii) two hundred
                                            and fifty thousand pounds (£250,000), and (iii) five hundred thousand pounds
                                            (£500,000) in the case of actions covered by the indemnity in section 11(a).
                                            Here, “Specified Monies” denotes the total payments due from Client to
                                            QI-Tech under this Agreement for the initial twelve (12) month period from
                                            the Agreement's commencement date.</li>

                                        <li><strong>(e) Under no circumstances shall QI-Tech be liable to
                                                Client:</strong> for economic loss, loss of profit, loss of trademark
                                            use, loss of business, or similar consequential losses.</li>

                                        <li><strong>(f) QI-Tech shall not be liable to Client:</strong> for any indirect
                                            losses under any circumstances.</li>

                                        <li><strong>(g) QI-Tech shall not be liable to Client:</strong> for any loss of
                                            or damage to data or programs used or held by Client, whether before or
                                            after termination of this Agreement. Client must maintain adequate backup
                                            copies of data and programs used or held by Client.</li>

                                        <li><strong>(h) Client acknowledges that the Software:</strong> is not intended
                                            for use in situations where its failure could cause severe losses.
                                            Therefore, Client must not use, or cause the use of, the Software in such
                                            circumstances. Client must conduct a risk analysis assessment to minimize
                                            their risk if the Software is to be used in certain industries or contexts
                                            specified herein.</li>

                                        <li><strong>(i) The exclusions and limitations of liability outlined in this
                                                section 12:</strong> do not apply to QI-Tech's liability to Client for:
                                            <ul>
                                                <li>1. Death or personal injury resulting from QI-Tech's negligence, its
                                                    employees, agents, or subcontractors;</li>
                                                <li>2. Breach of QI-Tech's implied warranty as to title to the Software
                                                    or the implied warranty as to quiet possession implied by law;</li>
                                                <li>3. Damage for which QI-Tech is liable to Client under the Consumer
                                                    Protection Act 1987 where Client acts as a consumer;</li>
                                                <li>4. Fraudulent activities.</li>
                                            </ul>
                                        </li>
                                    </ul>
                                    <br>
                                    <div class="text-block-70">Confidentiality<br />‍</div>
                                    <ul role="list" class="list-6"
                                        style="display: flex; flex-direction: column; gap: 0.5rem; list-style: none;">
                                        <li><strong>(a) QI-Tech's Proprietary Information:</strong> QI-Tech maintains
                                            full ownership and all associated rights to any information and data related
                                            to the Licensed Materials, including but not limited to routines, source
                                            code, algorithms, and know-how, as well as this Agreement, Order Forms,
                                            proposals, and responses to Client requests for proposals or quotes
                                            (collectively referred to as "QI-Tech's Proprietary Information"). Client,
                                            along with its agents, employees, representatives, and contractors, is
                                            obligated to maintain strict confidentiality regarding QI-Tech's Proprietary
                                            Information. Client shall not disclose or utilize QI-Tech's Proprietary
                                            Information except as outlined within this Agreement. In circumstances where
                                            Client is compelled to provide QI-Tech's Proprietary Information due to a
                                            request for public records, Client must promptly inform QI-Tech and
                                            collaborate to defend against such disclosure. Client must ensure that any
                                            external consultants accessing QI-Tech's Proprietary Information sign a
                                            confidentiality agreement and are made aware of the confidential nature of
                                            the information, using it only as necessary to support Client's use of the
                                            Licensed Materials.</li>

                                        <li><strong>(b) Client's Proprietary Information:</strong> Client maintains full
                                            ownership and all rights to any information and data related to financial
                                            matters, technical or accounting data, confidential patient or client
                                            information, or any other information pertaining to Client's operations not
                                            covered under QI-Tech's Proprietary Information ("Client's Proprietary
                                            Information"). QI-Tech, along with its agents, employees, representatives,
                                            and contractors, must uphold strict confidentiality regarding Client's
                                            Proprietary Information and refrain from disclosing or using it except as
                                            required by this Agreement or in connection with QI-Tech's services for
                                            Client. QI-Tech is responsible for ensuring that any external consultants
                                            accessing Client's Proprietary Information sign a confidentiality agreement
                                            (and, where applicable, a HIPAA Business Associate Agreement) and understand
                                            the confidential nature of the information, using it solely for conducting
                                            business with QI-Tech in service to Client.</li>

                                        <li><strong>(c) Security Measures:</strong> Both QI-Tech and Client are required
                                            to implement reasonable security measures, consistent with the protection of
                                            their own confidential information, to safeguard the other party's
                                            Proprietary Information during and after the termination of this Agreement.
                                            Neither party shall disclose or utilize the other party's Proprietary
                                            Information for any purpose without explicit written consent, except as
                                            necessary for fulfilling their obligations under this Agreement, in response
                                            to a court order (with reasonable notice), or to authorized individuals such
                                            as employees, agents, subcontractors, legal counsel, and financial
                                            institutions on a need-to-know basis, subject to confidentiality obligations
                                            similar to those in this Agreement.</li>

                                        <li><strong>Exclusions from Confidentiality:</strong> The provisions of this
                                            section 13 do not apply to Proprietary Information of either party if the
                                            receiving party can demonstrate that such information was already in its
                                            possession prior to the execution of this Agreement, is required for legal
                                            defence or performance of this Agreement, is publicly available through no
                                            fault of the receiving party, or was obtained in good faith from third
                                            parties without confidentiality obligations.</li>
                                    </ul>
                                    <br>
                                    <div class="text-block-71"><br />Compliance with Privacy Provisions
                                    </div>
                                    <ul role="list" class="list-6"
                                        style="display: flex; flex-direction: column; gap: 0.5rem; list-style: none;">
                                        <li>
                                            <strong>(a) Definitions:</strong> In this section 14:
                                            <ul>
                                                <li>a. “DPA” means the Data Protection Act 1998, the General Data
                                                    Protection Regulation or any replacement legislation; and</li>
                                                <li>b. “Personal Data” means personal data, as defined in the DPA, about
                                                    Client’s employees, users and patients provided or made available to
                                                    QI-Tech by Client in connection with QI-Tech’s provision of the
                                                    Licensed Materials and the services pursuant to the Agreement; and
                                                </li>
                                                <li>c. “Data Controller” and “Data Processor” shall have the meanings
                                                    ascribed to them by the DPA.</li>
                                            </ul>
                                        </li>
                                        <li>
                                            <strong>(b) General:</strong>
                                            <ul>
                                                <li>a. Unless authorised in the Agreement or otherwise by Client,
                                                    QI-Tech shall not use or disclose any Personal Data for any purpose
                                                    save that (i) QI-Tech may use the Personal Data as reasonably
                                                    necessary to provide or assist in the provision of the Services and
                                                    to exercise any rights granted to it under the Agreement and (ii)
                                                    QI-Tech may disclose the Personal Data as required by applicable law
                                                    and (iii) QI-Tech may analyse and store the Personal Data for
                                                    statistical purposes using pseudonymisation.</li>
                                                <li>b. Each Party agrees to comply with the provisions of the DPA in
                                                    relation to the collection, exchange and processing of the Personal
                                                    Data pursuant to this Agreement. Each party shall take appropriate
                                                    measures in accordance with the provisions of the DPA to protect
                                                    against the unauthorised or unlawful processing of the Personal Data
                                                    and against accidental loss or destruction of, or damage to the
                                                    Personal Data.</li>
                                                <li>c. Notwithstanding anything to the contrary in the Agreement, Client
                                                    acknowledges and agrees that QI-Tech obligations and Client’s rights
                                                    under the Agreement shall not apply to any Personal Data that is
                                                    required by applicable law, rule, order, or regulation, or any
                                                    government request, to be retained, disposed of, or disclosed in
                                                    accordance with a lawful governmental or judicial demand.</li>
                                                <li>d. Client shall remain the Data Controller of the Personal Data of
                                                    Client and Users processed by QI-Tech. QI-Tech shall be the data
                                                    processor of such Personal Data.</li>
                                            </ul>
                                        </li>
                                        <li>
                                            <strong>(c) Data Security and Records Retention:</strong> QI-Tech will
                                            employ appropriate commercially reasonable administrative, technical, and
                                            physical measures to safeguard the security and confidentiality of Personal
                                            Data. Following the expiration or termination of the Agreement, QI-Tech
                                            shall not retain Personal Data beyond what is necessary to fulfill its
                                            obligations under the Agreement or as mandated by relevant laws,
                                            regulations, or rules, whichever duration is longer. Any retention of
                                            Personal Data by QI-Tech beyond this period will strictly adhere to
                                            QI-Tech's obligations under the Data Protection Act (DPA).
                                        </li>
                                        <li>
                                            <strong>(d) Data Security Breach:</strong> In the event of any unauthorized
                                            or unlawful access to or use of Personal Data, constituting a Security
                                            Incident as stipulated under the Data Protection Act (DPA) and necessitating
                                            notification by QI-Tech or Client, QI-Tech will promptly notify Client of
                                            such incident, maintaining confidentiality as required by applicable
                                            obligations and in compliance with the DPA. Should a Security Incident
                                            occur, QI-Tech and Client will collaborate in good faith to address any data
                                            privacy or security concerns related to Personal Data.
                                        </li>
                                    </ul>
                                    <br> <br>
                                    <div class="text-block-72">Miscellaneous Provisions</div>
                                    <ul role="list" class="list-6"
                                        style="display: flex; flex-direction: column; gap: 0.5rem; list-style: none;">
                                        <li>
                                            <strong>(a) Entire Agreement:</strong> This Agreement, along with the Order
                                            Form, and any other document explicitly referenced in this Agreement or the
                                            Order Form (such as the Support Guide), constitutes the complete agreement
                                            between the parties concerning any software or services acquired by Client
                                            from QI-Tech. It supersedes all prior agreements, understandings, and
                                            representations on the subject matter. The terms of this Agreement can only
                                            be modified by a written agreement signed by both QI-Tech and Client or by
                                            acceptance of an updated version of this Agreement presented by QI-Tech.
                                            Headings in the Agreement are for convenience only and do not affect its
                                            interpretation. In case of conflict between the terms of this Agreement and
                                            any other document forming part of it, the order of precedence shall be as
                                            follows: the Order Form, the Terms of Use Addendum (if signed by both
                                            parties), the Hosting Addendum, this Agreement, the Service Level Agreement,
                                            and the Support Guide. Any other document must be signed by both parties and
                                            specifically reference this Agreement by section or paragraph number to take
                                            precedence. Both parties agree that a future version of this Agreement
                                            presented to and accepted by Client shall automatically replace this
                                            Agreement. This Agreement prevails over any terms and conditions in Client's
                                            purchase order or any other document submitted by Client. The parties
                                            confirm that they have not relied on any representations not documented in
                                            this Agreement. This section does not apply to fraudulent misrepresentation.
                                        </li>
                                        <li>
                                            <strong>(b) Incompatibility with Law; Severability:</strong> If a law,
                                            regulation, or ordinance prevents a party from agreeing to one or more terms
                                            of this Agreement, or if any terms become or are declared invalid or
                                            unenforceable, this Agreement will be amended to the extent permitted by
                                            law.
                                        </li>
                                        <li>
                                            <strong>(c) Notices:</strong> Any notice under this Agreement is considered
                                            given when delivered personally, sent by confirmed facsimile transmission
                                            (next business day after sending), sent by commercial overnight courier with
                                            verification of receipt (next business day after delivery to the courier
                                            during normal business hours), or sent by certified or registered mail,
                                            return receipt requested (fifth business day after posting). Notices must be
                                            in writing and addressed to the other party at the address listed on the
                                            Order Form or any replacement address provided.
                                        </li>
                                        <li>
                                            <strong>(d) Waiver:</strong> The failure to exercise any right under this
                                            Agreement does not waive that right or any other right in the future.
                                        </li>
                                        <li>
                                            <strong>(e) Dispute Resolution:</strong> Any dispute will be resolved
                                            through at least two discussions between senior executives of each party. If
                                            unresolved, mediation will be attempted. If not resolved within 30 days of
                                            the first mediation request, either party may resort to litigation, unless
                                            this causes a statute of limitations to expire, in which case, the action
                                            may proceed.
                                        </li>
                                        <li>
                                            <strong>(f) Time Limitation on Claims:</strong> Any claim arising from this
                                            Agreement must be the subject of a demand letter within 24 months of the
                                            party becoming aware of its right to bring the claim.
                                        </li>
                                        <li>
                                            <strong>(g) Liability and Costs:</strong> Client is liable to QI-Tech for
                                            any claim, damage, loss, or cost incurred by QI-Tech due to Client's breach
                                            of the Agreement, negligence, or wrongful act or omission. Client must pay
                                            QI-Tech's proper costs incurred in recovering owed monies or enforcing
                                            rights under the Agreement.
                                        </li>
                                        <li>
                                            <strong>(h) Applicable Law:</strong> This Agreement is governed by English
                                            law, and the parties submit to the non-exclusive jurisdiction of English
                                            courts, except where section 15(e) applies.
                                        </li>
                                        <li>
                                            <strong>(i) No Agency:</strong> Nothing herein creates an agency,
                                            partnership, joint venture, or other joint enterprise between the parties.
                                        </li>
                                        <li>
                                            <strong>(j) Migration:</strong> Upon termination, cessation of support, or
                                            Client's desire to transition data to another system, QI-Tech will assist in
                                            transferring data to an industry-accepted format at prevailing time and
                                            materials charges.
                                        </li>
                                        <li>
                                            <strong>(k) Software Delivery:</strong> In cases where QI-Tech is not
                                            hosting the Software, all Licensed Materials will be delivered
                                            electronically and/or shipped on memory device(s), FOB Origin, QI-Tech, or
                                            made available for downloading by QI-Tech. Any Client shipping terms
                                            indicating shipments are effective upon arrival at Client’s location are
                                            rejected and superseded.
                                        </li>
                                        <li>
                                            <strong>(l) Force Majeure:</strong> Neither party shall be liable to the
                                            other for any delay or default in performing hereunder if such delay or
                                            default is caused by conditions beyond that party’s reasonable control,
                                            including acts of God, governmental restrictions, wars, insurrection,
                                            terrorism, natural disasters, and telecommunications link failures under the
                                            control of others. Both parties shall promptly resume performance once the
                                            force majeure event has passed.
                                        </li>
                                        <li>
                                            <strong>(m) Audit Rights:</strong> Client shall maintain accurate books and
                                            records related to the Licensed Materials, including but not limited to
                                            their use compared to the License Thresholds and limitations on the Order
                                            Form. These records should, wherever possible, permit remote access and
                                            review. QI-Tech may, at its sole cost and expense, conduct an audit of
                                            Client’s books and records concerning the Licensed Materials during normal
                                            business hours, with reasonable advanced notice and no more frequently than
                                            annually, and subject to any reasonable confidentiality requirements of
                                            Client. If an audit reveals Client’s use of the Licensed Materials is in
                                            excess of any License Thresholds, Client shall promptly pay the necessary
                                            Supplemental License Fees. If Client’s use exceeds any License Threshold by
                                            more than 5%, Client shall reimburse QI-Tech for the audit costs.
                                        </li>
                                        <li>
                                            <strong>(n) Effect of Termination:</strong> Provisions of any document
                                            forming part of this Agreement that must survive termination to have full
                                            effect, including confidentiality and indemnification obligations, shall
                                            survive termination. Termination does not prejudice the rights and duties of
                                            either party accrued prior to termination.
                                        </li>
                                        <li>
                                            <strong>(o) Assignment:</strong> Client may not assign this Agreement or its
                                            rights and benefits without QI-Tech's express written consent, except in the
                                            event of acquisition of all or a majority of Client's assets by a similar
                                            business entity, in which case no consent is required. QI-Tech may
                                            reasonably assign this Agreement in whole or in part. QI-Tech is free to
                                            subcontract its rights and obligations under this Agreement as it sees fit.
                                            Subject to the limitations of liability, QI-Tech is liable to Client for
                                            acts and omissions of its subcontractors.
                                        </li>
                                        <li>
                                            <strong>(p) Instructions:</strong> QI-Tech assumes that operational and
                                            implementation instructions related to the Software provided by Client’s
                                            employees, directors, and officers are authorized.
                                        </li>
                                        <li>
                                            <strong>(q) Non-competition:</strong> This Agreement does not prevent
                                            QI-Tech from providing Software or services of a similar nature to any
                                            person, entity, or enterprise conducting a business competitive to Client’s.
                                        </li>
                                        <li>
                                            <strong>(r) Costs:</strong> Each party must bear its own costs and expenses
                                            in performing obligations under the Agreement unless specified otherwise.
                                        </li>
                                        <li>
                                            <strong>(s) Attorney Fees:</strong> Client must pay QI-Tech all costs
                                            incurred on a lawyer/client basis in recovering owed monies or enforcing
                                            rights against Client under the Agreement.
                                        </li>
                                        <li>
                                            <strong>(t) Currency:</strong> All charges are in Pounds Sterling unless
                                            indicated otherwise on the Order Form.
                                        </li>
                                        <li>
                                            <strong>(u) Payments:</strong> Payments must be made in full, and Client may
                                            not deduct from the price any set off, counterclaim, or other sum unless
                                            agreed by QI-Tech in writing. If Client selects a payment method causing
                                            QI-Tech to incur charges, Client agrees to enlarge the payment to fully
                                            offset the expense incurred by QI-Tech.
                                        </li>
                                        <li>
                                            <strong>(v) Taxes:</strong> In addition to specified charges, Client shall
                                            pay or reimburse QI-Tech for all applicable taxes, excluding taxes on
                                            QI-Tech's income. If tax-exempt, Client must provide a copy of the
                                            tax-exempt certificate to QI-Tech.
                                        </li>
                                        <li>
                                            <strong>(w) "Including":</strong> Wherever "including" occurs in this
                                            Agreement, it means "including without limitation."
                                        </li>
                                        <li>
                                            <strong>(x) Third-Party Rights:</strong> A third party not party to this
                                            Agreement has no right under the Contract (Rights of Third Parties) Act 1999
                                            to enforce any provision herein. This provision does not affect any right or
                                            remedy of any third party existing apart from that Act.
                                        </li>
                                        <li>
                                            <strong>(y) Gender and Number:</strong> References to the plural include the
                                            singular and vice versa. References to masculine, feminine, or neuter
                                            genders include each gender.
                                        </li>
                                    </ul>

                                    <br>
                                    <br>
                                    <br>


                                </div>
                            </div>
                            <div>
                                <div class="w-layout-blockcontainer footer w-container">
                                    <section class="footer-dark">
                                        <div class="container-16">
                                            <div class="footer-wrapper">
                                                <div data-w-id="234e7673-b861-2443-8407-83ad6e044508"
                                                    class="footer-content"><a href="#"
                                                        class="footer-brand w-inline-block"><img
                                                            src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                                            loading="lazy" alt="" class="image-69" /><img
                                                            src="{{ asset('webflow_assets/home-about/images/clouds.png') }}"
                                                            loading="lazy" width="262"
                                                            sizes="(max-width: 479px) 57.5px, 12vw" alt=""
                                                            srcset="{{ asset('webflow_assets/home-about/images/clouds-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/clouds-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/clouds-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/clouds.png') }} 1093w"
                                                            class="image-70" /></a>
                                                    <div id="w-node-_234e7673-b861-2443-8407-83ad6e04450c-6e044504"
                                                        class="footer-block">
                                                        <div class="title-small">Company</div><a href="{{ route('about_us') }}#tab-link-tab-1"
                                                            class="footer-link">About</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                            class="footer-link">Privacy Policy</a><a href="#"
                                                            class="footer-link">Terms
                                                            of Service</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                            class="footer-link">Cookies Policy</a><a href="{{route('about_us')}}#tab-link-tab-5"
                                                            class="footer-link">Corporate Responsibility</a><a
                                                            href="{{ route('about_us') }}#tab-link-tab-6" class="footer-link">Modern
                                                            Slavery</a><a href="{{ route('about_us') }}#tab-link-tab-7"
                                                            class="footer-link">Careers</a>
                                                    </div>
                                                    <div id="w-node-_234e7673-b861-2443-8407-83ad6e04451d-6e044504"
                                                        class="footer-block">
                                                        <div class="title-small">Contact us</div><a href="#"
                                                            class="footer-link">42 Reading Road
                                                            <br />South Fleet<br />Hampshire<br />GU51 3QP</a><a
                                                            href="#" class="footer-link">+44
                                                            01252 613425</a><a href="#"
                                                            class="footer-link">support@qi-tech.co.uk</a>
                                                    </div>
                                                    <div id="w-node-_234e7673-b861-2443-8407-83ad6e04452c-6e044504"
                                                        class="footer-block">
                                                        <div class="title-small">Social</div>
                                                        <div class="footer-social-block"><a href="#"
                                                                class="footer-social-link w-inline-block"><img
                                                                    src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                                                    loading="lazy" width="34.5" alt=""
                                                                    class="image-71" /></a><a href="#"
                                                                class="footer-social-link w-inline-block"><img
                                                                    src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                                                    loading="lazy" width="37" alt=""
                                                                    class="image-72" /></a><a href="#"
                                                                class="footer-social-link w-inline-block"><img
                                                                    src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                                                    loading="lazy" width="20" alt=""
                                                                    class="image-73" /></a></div>
                                                        <div class="footer-cloud"><img
                                                                src="{{ asset('webflow_assets/home-about/images/cloud-20big.png') }}"
                                                                loading="lazy"
                                                                data-w-id="234e7673-b861-2443-8407-83ad6e044537"
                                                                alt="" width="987.5"
                                                                srcset="{{ asset('webflow_assets/home-about/images/cloud-20big-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/cloud-20big.png') }} 1975w"
                                                                sizes="(max-width: 479px) 141.78750610351562px, (max-width: 991px) 30vw, 28vw" />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="footer-copyright-center">© 2025 QI-Tech. All rights reserved.
                                        </div>
                                    </section>
                                    <div class="footer-mobile">
                                        <div class="div-block-108"><img
                                                src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                                loading="lazy" alt="" /></div>
                                        <div class="div-block-109"><a href="{{ route('about_us') }}#tab-link-tab-1" class="link-6">About</a><a
                                        href="{{ route('about_us') }}#tab-link-tab-2" class="link-6">Privacy Policy</a><a href="{{ route('about_us') }}#tab-link-tab-3"
                                                class="link-6">Terms of Service</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                class="link-6">Cookies Policy</a><a href="{{route('about_us')}}#tab-link-tab-5"
                                                class="link-6">Corporate Responsibility  </a><a href="{{ route('about_us') }}#tab-link-tab-6"
                                                class="link-6">Modern Slavery</a><a href="{{ route('about_us') }}#tab-link-tab-7"
                                                class="link-6">Careers</a></div>
                                        <div class="div-block-110">
                                            <div class="div-block-111"><img
                                                    src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                                    loading="lazy" alt="" /></div>
                                            <div class="div-block-112"><img
                                                    src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                                    loading="lazy" alt="" /></div>
                                            <div class="div-block-113"><img
                                                    src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                                    loading="lazy" alt="" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="div-block-114"><img
                                                    src="{{ asset('webflow_assets/home-about/images/cloud-202.png') }}"
                                                    loading="lazy" sizes="100vw"
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
                    </section>
                    <section data-w-tab="Tab 5" id="corporate" class="tab-pane-tab-5 w-tab-pane">
                        <div class="w-layout-blockcontainer content---b terms w-container">
                            <div class="w-layout-blockcontainer top-middle w-container">
                                <div class="w-layout-blockcontainer doctor-with-cloud-wire w-container">
                                    <div data-w-id="353231cb-064f-881f-eadd-3fc42eb3f0a8"
                                        class="w-layout-blockcontainer birds w-container"><img
                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65957b273bce498e7af3002b_Layer%2090.png"
                                            loading="lazy"
                                            sizes="(max-width: 479px) 40vw, (max-width: 6705px) 20vw, 1341px"
                                            srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/65957b273bce498e7af3002b_Layer%2090-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/65957b273bce498e7af3002b_Layer%2090-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/65957b273bce498e7af3002b_Layer%2090-p-1080.png 1080w, https://assets-global.website-files.com/6564282be531be60fd0d391f/65957b273bce498e7af3002b_Layer%2090.png 1341w"
                                            alt="" /></div>
                                    <div class="w-layout-blockcontainer wire w-container"><img
                                            sizes="(max-width: 6400px) 100vw, 6400px"
                                            srcset="{{ asset('webflow_assets/home-about/images/layer-2090-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/layer-2090-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/layer-2090-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/layer-2090-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/layer-2090-p-2000.png') }} 2000w, {{ asset('webflow_assets/home-about/images/layer-2090-p-2600.png') }} 2600w, {{ asset('webflow_assets/home-about/images/layer-2090-p-3200.png') }} 3200w, {{ asset('webflow_assets/home-about/images/layer-2090.png') }} 6400w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/layer-2090.png') }}"
                                            loading="lazy" class="image-77" />
                                    </div>
                                    <div data-w-id="36f257eb-2a8c-71ee-adbb-fd9082b7a5ed"
                                        class="w-layout-blockcontainer doctor-copy-copy w-container"><img
                                            sizes="(max-width: 479px) 30vw, (max-width: 8276px) 13vw, 1076px"
                                            srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/658aadf32f16f6d6629d0191_social%20corporate%20responsbility-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658aadf32f16f6d6629d0191_social%20corporate%20responsbility-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658aadf32f16f6d6629d0191_social%20corporate%20responsbility.png 1076w"
                                            alt=""
                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/658aadf32f16f6d6629d0191_social%20corporate%20responsbility.png"
                                            loading="lazy" class="image-75" /></div>
                                    <div data-w-id="36f257eb-2a8c-71ee-adbb-fd9082b7a5ef"
                                        class="w-layout-blockcontainer cloud w-container"><img
                                            sizes="(max-width: 479px) 60vw, (max-width: 9190px) 20vw, 1838px"
                                            srcset="{{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy.png') }} 1838w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy.png') }}"
                                            loading="lazy" class="image-76" />

                                    </div>
                                </div>
                            </div>
                            <div class="w-layout-blockcontainer culture-b w-container">
                                <div class="w-layout-blockcontainer container-29 w-container">
                                    <h1 class="heading-12">Corporate Social Responsibility Policy</h1>
                                    <p><br />Corporate Social Responsibility (CSR) embodies the commitment of businesses
                                        to self-regulate and ensure that their actions contribute positively to society
                                        at large. CSR policies are designed to ensure ethical business conduct,
                                        considering human rights and the broader social, economic, and environmental
                                        impacts of business activities. It emphasizes adherence to relevant legislation,
                                        surpassing them when possible, and adopting best practices in areas where
                                        legislation might be absent.<br /><br />QI-Tech is dedicated to conducting its
                                        business activities with the utmost ethical considerations.<br /><br />We
                                        prioritize people, boasting a dedicated team of healthcare professionals,
                                        patient safety and safeguarding experts and internationally recognised software
                                        engineers. Our commitment is to deliver benefits to clients while upholding high
                                        standards of quality, scope, and value.<br />‍</p>
                                    <div class="text-block-76">Employee Well-being</div>
                                    <p><br />Our commitment extends to the well-being and continual development of our
                                        employees. We foster a workplace where every employee feels valued and
                                        appreciated, with a clear understanding of their role and contributions to the
                                        business.<br />
                                        <br>

                                        We provide opportunities for professional development based on individual
                                        interests and talents, supported by clear personal development plans and
                                        relevant training. Operating on a meritocracy, we recognize and reward employees
                                        based on performance, effort, contribution, and achievements.
                                        <br>
                                        <br>
                                        We maintain a culture of integrity, diversity, fairness, and equal
                                        opportunities. Regular employee involvement and consultation shape the direction
                                        of our business.
                                    </p>

                                    <div class="text-block-78">Customer Relations<br />‍</div>
                                    <p>QI-Tech seeks to build enduring and meaningful relationships with customers and
                                        stakeholders. Our commitment is to understand objectives thoroughly, meet
                                        requirements consistently, and exceed expectations. We pledge to deliver fair
                                        value, consistent quality, and reliability, adhering to the highest professional
                                        and ethical standards.<br />‍</p>

                                    <div class="text-block-79">Supplier Standards</div>
                                    <p><br />We aim to cultivate strong relationships with key suppliers and contractors
                                        who share our values in employment practices, quality, and environmental
                                        controls. Rigorous vetting processes ensure engagement with entities committed
                                        to best practices.<br />‍</p>

                                    <div class="text-block-80">Health and Safety</div>
                                    <p><br />Our commitment to providing a safe and healthy working environment is
                                        unwavering. We prioritize a health and safety culture, maintaining the highest
                                        standards and adhering to requirements. This commitment extends to employee
                                        well-being and our relationships with customers and suppliers.

                                        An annually reviewed Health and Safety Policy is in place and communicated to
                                        all employees.</p>

                                    <div class="text-block-80">Environmental Responsibility</div>
                                    <p><br />We recognize our environmental impact and take steps to mitigate it. An
                                        environmental policy, reviewed and updated annually, sets objectives and
                                        targets. We provide training to ensure employees and contractors understand
                                        their environmental responsibilities and actively seek ways to improve our
                                        environmental performance.

                                        <br>
                                        <br>
                                        Our commitment includes promoting greener transport, recycling initiatives,
                                        collaborating with environmentally conscious suppliers, and ensuring compliance
                                        with all relevant legislation
                                    </p>

                                    <div class="text-block-80">Community Engagement</div>
                                    <p><br />Acknowledging the significance of the local community, QI-Tech aims to
                                        enhance its contribution by being sensitive to local needs, promoting ethical
                                        and socially responsible trading, and actively supporting local charities and
                                        community centres.

                                        <br>
                                        <br>
                                        We contribute to the community through monetary donations, staff volunteering,
                                        and employment opportunities for local individuals, including apprenticeships
                                        and work experience programs.
                                    </p>
                                    <div class="text-block-80">Measurement</div>
                                    <p><br />QI-Tech proudly holds certification from the British Standards Institute
                                        ISO/IEC 27001. Our Quality Management System supports continual monitoring and
                                        improvement across all aspects of our business. Constantly seeking ways to
                                        enhance our systems and practices, we strive to leave a positive societal
                                        footprint.
                                    </p>

                                    <br>
                                    <br>
                                    <br>
                                    <br>


                                </div>
                                <div>
                                    <div class="w-layout-blockcontainer footer w-container">
                                        <section class="footer-dark">
                                            <div class="container-16">
                                                <div class="footer-wrapper">
                                                    <div data-w-id="234e7673-b861-2443-8407-83ad6e044508"
                                                        class="footer-content"><a href="#"
                                                            class="footer-brand w-inline-block"><img
                                                                src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                                                loading="lazy" alt=""
                                                                class="image-69" /><img
                                                                src="{{ asset('webflow_assets/home-about/images/clouds.png') }}"
                                                                loading="lazy" width="262"
                                                                sizes="(max-width: 479px) 57.5px, 12vw"
                                                                alt=""
                                                                srcset="{{ asset('webflow_assets/home-about/images/clouds-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/clouds-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/clouds-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/clouds.png') }} 1093w"
                                                                class="image-70" /></a>
                                                        <div id="w-node-_234e7673-b861-2443-8407-83ad6e04450c-6e044504"
                                                            class="footer-block">
                                                            <div class="title-small">Company</div><a
                                                                href="{{ route('about_us') }}#tab-link-tab-1"
                                                                class="footer-link">About</a>
                                                            <a href="{{ route('about_us') }}#tab-link-tab-2"
                                                                class="footer-link">Privacy Policysdf</a>
                                                            <a href="{{ route('about_us') }}#tab-link-tab-3"
                                                                class="footer-link">Terms of Service</a>
                                                            <a href="{{ route('about_us') }}#tab-link-tab-4"
                                                                class="footer-link">Cookies Policy</a>
                                                            <a href="{{ route('about_us') }}#tab-link-tab-5"
                                                                class="footer-link">Corporate Responsibility</a>
                                                            <a href="{{ route('about_us') }}#tab-link-tab-6"
                                                                class="footer-link">Modern Slavery</a>
                                                            <a href="{{ route('about_us') }}#tab-link-tab-7"
                                                                class="footer-link">Careers</a>
                                                        </div>
                                                        <div id="w-node-_234e7673-b861-2443-8407-83ad6e04451d-6e044504"
                                                            class="footer-block">
                                                            <div class="title-small">Contact us</div><a
                                                                href="#" class="footer-link">42 Reading Road
                                                                <br />South Fleet<br />Hampshire<br />GU51 3QP</a><a
                                                                href="#" class="footer-link">+44
                                                                01252 613425</a><a href="#"
                                                                class="footer-link">support@qi-tech.co.uk</a>
                                                        </div>
                                                        <div id="w-node-_234e7673-b861-2443-8407-83ad6e04452c-6e044504"
                                                            class="footer-block">
                                                            <div class="title-small">Social</div>
                                                            <div class="footer-social-block"><a href="#"
                                                                    class="footer-social-link w-inline-block"><img
                                                                        src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                                                        loading="lazy" width="34.5"
                                                                        alt="" class="image-71" /></a><a
                                                                    href="#"
                                                                    class="footer-social-link w-inline-block"><img
                                                                        src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                                                        loading="lazy" width="37"
                                                                        alt="" class="image-72" /></a><a
                                                                    href="#"
                                                                    class="footer-social-link w-inline-block"><img
                                                                        src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                                                        loading="lazy" width="20"
                                                                        alt="" class="image-73" /></a></div>
                                                            <div class="footer-cloud"><img
                                                                    src="{{ asset('webflow_assets/home-about/images/cloud-20big.png') }}"
                                                                    loading="lazy"
                                                                    data-w-id="234e7673-b861-2443-8407-83ad6e044537"
                                                                    alt="" width="987.5"
                                                                    srcset="{{ asset('webflow_assets/home-about/images/cloud-20big-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/cloud-20big.png') }} 1975w"
                                                                    sizes="(max-width: 479px) 141.78750610351562px, (max-width: 991px) 30vw, 28vw" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="footer-copyright-center">© 2025 QI-Tech. All rights reserved.
                                            </div>
                                        </section>
                                        <div class="footer-mobile">
                                            <div class="div-block-108"><img
                                                    src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                                    loading="lazy" alt="" /></div>
                                            <div class="div-block-109"><a href="{{ route('about_us') }}#tab-link-tab-1"
                                                    class="link-6">About</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                    class="link-6">Privacy Policy</a><a href="{{ route('about_us') }}#tab-link-tab-3"
                                                    class="link-6">Terms of Service</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                    class="link-6">Cookies Policy</a><a href="{{route('about_us')}}#tab-link-tab-5"
                                                    class="link-6">Corporate Responsibility  </a><a href="{{ route('about_us') }}#tab-link-tab-6"
                                                    class="link-6">Modern Slavery</a><a href="{{ route('about_us') }}#tab-link-tab-7"
                                                    class="link-6">Careers</a></div>
                                            <div class="div-block-110">
                                                <div class="div-block-111"><img
                                                        src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                                        loading="lazy" alt="" /></div>
                                                <div class="div-block-112"><img
                                                        src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                                        loading="lazy" alt="" /></div>
                                                <div class="div-block-113"><img
                                                        src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                                        loading="lazy" alt="" />
                                                </div>
                                            </div>
                                            <div>
                                                <div class="div-block-114"><img
                                                        src="{{ asset('webflow_assets/home-about/images/cloud-202.png') }}"
                                                        loading="lazy" sizes="100vw"
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
                            </div>
                    </section>
                    <section data-w-tab="Tab 6" id="modern" class="tab-pane-tab-6 w-tab-pane">
                        <div class="w-layout-blockcontainer content---b terms w-container">
                            <div class="w-layout-blockcontainer top-middle w-container">
                                <div class="w-layout-blockcontainer doctor-with-cloud-wire w-container">
                                    <div data-w-id="53cf15db-3612-ff96-9d09-2d3a8a44011f"
                                        class="w-layout-blockcontainer wire w-container"><img
                                            sizes="(max-width: 6400px) 100vw, 6400px"
                                            srcset="{{ asset('webflow_assets/home-about/images/layer-2090-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/layer-2090-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/layer-2090-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/layer-2090-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/layer-2090-p-2000.png') }} 2000w, {{ asset('webflow_assets/home-about/images/layer-2090-p-2600.png') }} 2600w, {{ asset('webflow_assets/home-about/images/layer-2090-p-3200.png') }} 3200w, {{ asset('webflow_assets/home-about/images/layer-2090.png') }} 6400w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/layer-2090.png') }}"
                                            loading="lazy" class="image-77" />
                                    </div>
                                    <div data-w-id="53cf15db-3612-ff96-9d09-2d3a8a440121"
                                        class="w-layout-blockcontainer doctor-copy-copy-copy w-container"><img
                                            sizes="(max-width: 479px) 40vw, (max-width: 7315px) 20vw, 1463px"
                                            srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/658ad4035bddf78fa195304d_modern%20slavery-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658ad4035bddf78fa195304d_modern%20slavery-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658ad4035bddf78fa195304d_modern%20slavery-p-1080.png 1080w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658ad4035bddf78fa195304d_modern%20slavery.png 1463w"
                                            alt=""
                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/658ad4035bddf78fa195304d_modern%20slavery.png"
                                            loading="lazy" class="image-75" /></div>
                                    <div data-w-id="53cf15db-3612-ff96-9d09-2d3a8a440123"
                                        class="w-layout-blockcontainer cloud w-container"><img
                                            sizes="(max-width: 479px) 60vw, (max-width: 9190px) 20vw, 1838px"
                                            srcset="{{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy.png') }} 1838w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy.png') }}"
                                            loading="lazy" class="image-76" />

                                    </div>
                                </div>
                            </div>
                            <div class="w-layout-blockcontainer culture-b w-container">
                                <div class="w-layout-blockcontainer container-30 w-container">
                                    <p>At QI-Tech, our mission is to revolutionize reporting and incident management by
                                        empowering organizations with intelligent form building, case management and
                                        workflow tools that enhance overall quality and safety, through our cloud-based
                                        software. <br /><br />Our vision is a world where reporting and incident
                                        management is seamless, improving outcomes for people and preventing incidents
                                        by learning from events of the past. QI-Tech comprises of individuals who
                                        passionately believe in our mission - a team that is dedicated to making a
                                        positive impact.<br />‍<br /></p>

                                    <div class="text-block-96">Commitment to Ethical Practices<br />‍</div>
                                    <p>QI-Tech is unwavering in its commitment to ethical business conduct, integrity,
                                        and the implementation of effective systems and controls to eradicate modern
                                        slavery within our business and supply chains. Transparency in addressing modern
                                        slavery aligns with our disclosure obligations under the Modern Slavery Act
                                        2015.</p>

                                    <div class="text-block-97">Policy Statement<br />‍</div>
                                    <p>Modern slavery, encompassing servitude, forced labour, and human trafficking, is
                                        a criminal offense under the Modern Slavery Act 2015. QI-Tech has developed this
                                        policy to prevent opportunities for modern slavery within our businesses or
                                        supply chains. The term "modern slavery" in this policy aligns with the Act's
                                        definition.<br /><br />Modern slavery is both a crime and a violation of
                                        fundamental human rights. QI-Tech is dedicated to ethical conduct and integrity
                                        in all business dealings, actively implementing systems and controls to prevent
                                        modern slavery within our operations and supply chains.<br />‍</p>

                                    <div class="text-block-98">Transparency and Expectations</div>
                                    <p><br />QI-Tech emphasizes transparency in its approach to combating modern
                                        slavery. We extend the same high standards to our contractors, suppliers, and
                                        business partners. Through stringent contracting processes, we include specific
                                        prohibitions against forced, compulsory, or trafficked labour, slavery, or
                                        servitude. We expect our suppliers to uphold these standards with their own
                                        suppliers.</p>

                                    <div class="text-block-99"><br />Applicability of the Policy<br />‍</div>
                                    <p>This policy applies to all individuals working for or on behalf of QI-Tech,
                                        encompassing employees at all levels, directors, officers, agency workers,
                                        seconded workers, volunteers, interns, agents, contractors, external
                                        consultants, third-party representatives, and business partners.</p>

                                    <div class="text-block-100"><br />Understanding Supply Chain Risks<br />‍</div>
                                    <p>As a software development company, QI-Tech acknowledges the inherent modern
                                        slavery risks associated with our supply chain. Our dependence on suppliers for
                                        various services, including hosting, software applications, equipment, business
                                        support services, and contractors, requires a vigilant approach.<br />‍
                                    </p>
                                    <div class="text-block-101">Risk Analysis and Mitigation<br />‍</div>
                                    <p>QI-Tech conducts supplier risk analysis as part of our vendor review program. We
                                        actively vet our suppliers, particularly those in industries with a known record
                                        of modern slavery. Through ongoing diligence and monitoring, we strive to
                                        minimize risks associated with electronic product production within our company
                                        supply chain.‍</p>
                                    <div class="text-block-102">Risk Minimization Strategies</div>
                                    <p><br />QI-Tech expects a zero-tolerance approach to modern slavery from all
                                        suppliers, contractors, and business partners. This approach should be
                                        integrated into their procurement processes, incorporating risk assessment, due
                                        diligence, and supplier auditing. Our contractual arrangements include
                                        legally-binding obligations for suppliers to meet these standards and undergo
                                        compliance auditing.</p>
                                    <div class="text-block-103"><br />Partnership for Compliance<br />‍</div>
                                    <p>QI-Tech collaborates with organizations sharing our vision and commitment to
                                        compliance. We are implementing a supplier code of conduct to support compliance
                                        auditing and monitoring of supplier practices. Our ongoing efforts include
                                        promoting understanding and awareness throughout the organization through
                                        internal communications. Furthermore, we will continue to integrate appropriate
                                        requirements into policies and quality management procedures as part of our
                                        ongoing compliance monitoring process.<br /><br /><br />‍</p>
                                </div>
                                <div>
                                    <div class="w-layout-blockcontainer footer w-container">
                                        <section class="footer-dark">
                                            <div class="container-16">
                                                <div class="footer-wrapper">
                                                    <div data-w-id="234e7673-b861-2443-8407-83ad6e044508"
                                                        class="footer-content"><a href="#"
                                                            class="footer-brand w-inline-block"><img
                                                                src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                                                loading="lazy" alt=""
                                                                class="image-69" /><img
                                                                src="{{ asset('webflow_assets/home-about/images/clouds.png') }}"
                                                                loading="lazy" width="262"
                                                                sizes="(max-width: 479px) 57.5px, 12vw"
                                                                alt=""
                                                                srcset="{{ asset('webflow_assets/home-about/images/clouds-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/clouds-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/clouds-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/clouds.png') }} 1093w"
                                                                class="image-70" /></a>
                                                        <div id="w-node-_234e7673-b861-2443-8407-83ad6e04450c-6e044504"
                                                            class="footer-block">
                                                            <div class="title-small">Company</div><a
                                                                href="{{ route('about_us') }}#tab-link-tab-1"
                                                                class="footer-link">About</a>
                                                            <a href="{{ route('about_us') }}#tab-link-tab-2"
                                                                class="footer-link">Privacy Policy</a>
                                                            <a href="{{ route('about_us') }}#tab-link-tab-3"
                                                                class="footer-link">Terms of Service</a>
                                                            <a href="{{ route('about_us') }}#tab-link-tab-4"
                                                                class="footer-link">Cookies Policy</a>
                                                            <a href="{{ route('about_us') }}#tab-link-tab-5"
                                                                class="footer-link">Corporate Responsibility</a>
                                                            <a href="{{ route('about_us') }}#tab-link-tab-6"
                                                                class="footer-link">Modern Slavery</a>
                                                            <a href="{{ route('about_us') }}#tab-link-tab-7"
                                                                class="footer-link">Careers</a>
                                                        </div>
                                                        <div id="w-node-_234e7673-b861-2443-8407-83ad6e04451d-6e044504"
                                                            class="footer-block">
                                                            <div class="title-small">Contact us</div><a
                                                                href="#" class="footer-link">42 Reading Road
                                                                <br />South Fleet<br />Hampshire<br />GU51 3QP</a><a
                                                                href="#" class="footer-link">+44
                                                                01252 613425</a><a href="#"
                                                                class="footer-link">support@qi-tech.co.uk</a>
                                                        </div>
                                                        <div id="w-node-_234e7673-b861-2443-8407-83ad6e04452c-6e044504"
                                                            class="footer-block">
                                                            <div class="title-small">Social</div>
                                                            <div class="footer-social-block"><a href="#"
                                                                    class="footer-social-link w-inline-block"><img
                                                                        src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                                                        loading="lazy" width="34.5"
                                                                        alt="" class="image-71" /></a><a
                                                                    href="#"
                                                                    class="footer-social-link w-inline-block"><img
                                                                        src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                                                        loading="lazy" width="37"
                                                                        alt="" class="image-72" /></a><a
                                                                    href="#"
                                                                    class="footer-social-link w-inline-block"><img
                                                                        src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                                                        loading="lazy" width="20"
                                                                        alt="" class="image-73" /></a></div>
                                                            <div class="footer-cloud"><img
                                                                    src="{{ asset('webflow_assets/home-about/images/cloud-20big.png') }}"
                                                                    loading="lazy"
                                                                    data-w-id="234e7673-b861-2443-8407-83ad6e044537"
                                                                    alt="" width="987.5"
                                                                    srcset="{{ asset('webflow_assets/home-about/images/cloud-20big-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/cloud-20big-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/cloud-20big.png') }} 1975w"
                                                                    sizes="(max-width: 479px) 141.78750610351562px, (max-width: 991px) 30vw, 28vw" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="footer-copyright-center">© 2025 QI-Tech. All rights reserved.
                                            </div>
                                        </section>
                                        <div class="footer-mobile">
                                            <div class="div-block-108"><img
                                                    src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                                    loading="lazy" alt="" /></div>
                                            <div class="div-block-109"><a href="{{ route('about_us') }}#tab-link-tab-1"
                                                    class="link-6">About</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                    class="link-6">Privacy Policy</a><a href="{{ route('about_us') }}#tab-link-tab-3"
                                                    class="link-6">Terms of Service</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                    class="link-6">Cookies Policy</a><a href="{{route('about_us')}}#tab-link-tab-5"
                                                    class="link-6">Corporate Responsibility  </a><a href="{{ route('about_us') }}#tab-link-tab-6"
                                                    class="link-6">Modern Slavery</a><a href="{{ route('about_us') }}#tab-link-tab-7"
                                                    class="link-6">Careers</a></div>
                                            <div class="div-block-110">
                                                <div class="div-block-111"><img
                                                        src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                                        loading="lazy" alt="" /></div>
                                                <div class="div-block-112"><img
                                                        src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                                        loading="lazy" alt="" /></div>
                                                <div class="div-block-113"><img
                                                        src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                                        loading="lazy" alt="" />
                                                </div>
                                            </div>
                                            <div>
                                                <div class="div-block-114"><img
                                                        src="{{ asset('webflow_assets/home-about/images/cloud-202.png') }}"
                                                        loading="lazy" sizes="100vw"
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
                            </div>
                    </section>
                    <section data-w-tab="Tab 7" id="careers" class="tab-pane-tab-7 w-tab-pane">
                        <div class="w-layout-blockcontainer content---b terms-copy w-container">
                            <div class="w-layout-blockcontainer top-middle w-container">
                                <div class="w-layout-blockcontainer doctor-with-cloud-wire w-container">
                                    <div class="w-layout-blockcontainer wire w-container"><img
                                            sizes="(max-width: 6400px) 100vw, 6400px"
                                            srcset="{{ asset('webflow_assets/home-about/images/layer-2090-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/layer-2090-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/layer-2090-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/layer-2090-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/layer-2090-p-2000.png') }} 2000w, {{ asset('webflow_assets/home-about/images/layer-2090-p-2600.png') }} 2600w, {{ asset('webflow_assets/home-about/images/layer-2090-p-3200.png') }} 3200w, {{ asset('webflow_assets/home-about/images/layer-2090.png') }} 6400w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/layer-2090.png') }}"
                                            loading="lazy" class="image-77" />
                                    </div>
                                    <div data-w-id="c811f66d-cb03-9069-a294-a8240acca055"
                                        class="w-layout-blockcontainer stars w-container"><img loading="lazy"
                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/658d0de897589af28fbe7b0f_Layer%20106.png"
                                            alt="" /></div>
                                    <div data-w-id="8be5cd46-1b5d-cfac-95a1-0eefa9ea4670"
                                        class="w-layout-blockcontainer pic w-container">
                                        <img sizes="(max-width: 479px) 40vw, (max-width: 8261px) 13vw, 1074px"
                                            srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/658d0d5b91a9e3f9f0124f2a_Layer%2086-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658d0d5b91a9e3f9f0124f2a_Layer%2086-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658d0d5b91a9e3f9f0124f2a_Layer%2086.png 1074w"
                                            alt=""
                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/658d0d5b91a9e3f9f0124f2a_Layer%2086.png"
                                            loading="lazy" class="image-75" />
                                    </div>
                                    <div data-w-id="8be5cd46-1b5d-cfac-95a1-0eefa9ea4672"
                                        class="w-layout-blockcontainer cloud w-container"><img
                                            sizes="(max-width: 479px) 60vw, (max-width: 9190px) 20vw, 1838px"
                                            srcset="{{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy-p-1600.png') }} 1600w, {{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy.png') }} 1838w"
                                            alt=""
                                            src="{{ asset('webflow_assets/home-about/images/636e40fa4a3e3c88de792989_cloud-2-20copy.png') }}"
                                            loading="lazy" class="image-76" />

                                    </div>
                                </div>
                            </div>
                            <div class="w-layout-blockcontainer culture-b careers w-container">
                                <div class="w-layout-blockcontainer content-careers w-container">
                                    <div data-w-id="cb55a34f-577b-7ddc-d67a-87d690434a5c"
                                        class="w-layout-blockcontainer container-31 w-container">
                                        <div class="w-layout-blockcontainer page-a w-container">
                                            <div class="w-layout-blockcontainer backgrounf-page-a w-container">
                                                <div class="text-block-104">Be part of something great!</div>
                                                <div class="text-block-105">Explore remote-friendly, flexible
                                                    opportunities and join our mission
                                                    to make <br />work life simpler, reporting easier and managing
                                                    incidents faster.</div><img
                                                    sizes="(max-width: 479px) 311.4750061035156px, (max-width: 7636px) 65vw, 4964px"
                                                    srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/658d1aed95935ff97c6aedb9_Top-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658d1aed95935ff97c6aedb9_Top-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658d1aed95935ff97c6aedb9_Top-p-1080.png 1080w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658d1aed95935ff97c6aedb9_Top-p-1600.png 1600w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658d1aed95935ff97c6aedb9_Top-p-2000.png 2000w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658d1aed95935ff97c6aedb9_Top-p-2600.png 2600w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658d1aed95935ff97c6aedb9_Top-p-3200.png 3200w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658d1aed95935ff97c6aedb9_Top.png 4964w"
                                                    alt=""
                                                    src="https://assets-global.website-files.com/6564282be531be60fd0d391f/658d1aed95935ff97c6aedb9_Top.png"
                                                    loading="lazy" class="image-81" />
                                            </div>
                                        </div>
                                        <div class="page-a-mobile">
                                            <div class="text-block-135">Explore remote-friendly, flexible
                                                opportunities and join our mission
                                                to make work life simpler, reporting easier and managing incidents
                                                faster.</div>
                                            <div><img
                                                    src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65bb948f5534b7ccf2142747_8454808.png"
                                                    loading="lazy" sizes="100vw"
                                                    srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/65bb948f5534b7ccf2142747_8454808-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/65bb948f5534b7ccf2142747_8454808.png 638w"
                                                    alt="" /></div>
                                        </div>
                                        <div class="w-layout-blockcontainer cards-culture w-container">
                                            <div class="div-block-29">
                                                <div class="w-layout-blockcontainer container-20 w-container">
                                                    <div class="text-block-45">Connected</div>
                                                </div>
                                                <div class="w-layout-blockcontainer container-21 w-container"><img
                                                        sizes="(max-width: 479px) 60vw, 24vw"
                                                        srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5a993d98b9ecd5e0933f_636e40fa4a3e3c88de792989_Cloud-2-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5a993d98b9ecd5e0933f_636e40fa4a3e3c88de792989_Cloud-2-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5a993d98b9ecd5e0933f_636e40fa4a3e3c88de792989_Cloud-2-p-1080.png 1080w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5a993d98b9ecd5e0933f_636e40fa4a3e3c88de792989_Cloud-2.png 1155w"
                                                        alt=""
                                                        src="https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5a993d98b9ecd5e0933f_636e40fa4a3e3c88de792989_Cloud-2.png"
                                                        loading="lazy" class="image-78" /></div>
                                                <div class="w-layout-blockcontainer container-22 w-container">
                                                    <div class="text-block-46">We unite wherever we may be - across
                                                        time zones, areas, offices,
                                                        and screens.</div>
                                                </div>
                                            </div>
                                            <div class="div-block-29">
                                                <div class="w-layout-blockcontainer container-20 w-container">
                                                    <div class="text-block-45">Included</div>
                                                </div>
                                                <div class="w-layout-blockcontainer container-21 w-container"><img
                                                        sizes="(max-width: 479px) 67vw, 27vw"
                                                        srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5ab5a7939e4251be4ef4_Untitled-1-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5ab5a7939e4251be4ef4_Untitled-1-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5ab5a7939e4251be4ef4_Untitled-1-p-1080.png 1080w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5ab5a7939e4251be4ef4_Untitled-1.png 1155w"
                                                        alt=""
                                                        src="https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5ab5a7939e4251be4ef4_Untitled-1.png"
                                                        loading="lazy" class="image-78-copy" /></div>
                                                <div class="w-layout-blockcontainer container-22 w-container">
                                                    <div class="text-block-46">Our team reflects the rich diversity of
                                                        the communities we live in.
                                                        We believe in equal access to opportunities for all.</div>
                                                </div>
                                            </div>
                                            <div class="div-block-29">
                                                <div class="w-layout-blockcontainer container-20 w-container">
                                                    <div class="text-block-45">Flexible</div>
                                                </div>
                                                <div
                                                    class="w-layout-blockcontainer over-the-cloud career w-container">
                                                    <img sizes="(max-width: 479px) 64vw, 26vw"
                                                        srcset="https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5ac261f9907c2d297b36_636e40fa4a3e3c88de792989_Cloud-2%20copy%202-p-500.png 500w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5ac261f9907c2d297b36_636e40fa4a3e3c88de792989_Cloud-2%20copy%202-p-800.png 800w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5ac261f9907c2d297b36_636e40fa4a3e3c88de792989_Cloud-2%20copy%202-p-1080.png 1080w, https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5ac261f9907c2d297b36_636e40fa4a3e3c88de792989_Cloud-2%20copy%202.png 1394w"
                                                        alt=""
                                                        src="https://assets-global.website-files.com/6564282be531be60fd0d391f/658e5ac261f9907c2d297b36_636e40fa4a3e3c88de792989_Cloud-2%20copy%202.png"
                                                        loading="lazy" class="image-78-copy-copy" /></div>
                                                <div class="w-layout-blockcontainer container-22 w-container">
                                                    <div class="text-block-46">Work when you want and the way you work
                                                        best. Let’s grow and
                                                        develop together.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-layout-blockcontainer core-values w-container">
                                        <div class="w-layout-blockcontainer text-core-block w-container">
                                            <div class="text-block-107">Our core values</div>
                                            <div class="text-block-108">We build software that we believe in, software
                                                that has real value in
                                                helping teams and people. We have a set of principles that we live and
                                                work by:</div>
                                        </div>
                                        <div class="div-block-31">
                                            <div id="w-node-_1ceb76a7-7d16-a1da-b3c7-0b4893a51b80-f05aa8b8"
                                                class="w-layout-layout quick-stack-4 wf-layout-layout">
                                                <div id="w-node-_1ceb76a7-7d16-a1da-b3c7-0b4893a51b81-f05aa8b8"
                                                    class="w-layout-cell cell-11">
                                                    <div class="div-block-54"><img
                                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/658e601f61f9907c2d2c865f_1.png"
                                                            loading="lazy" width="45" alt="" /></div>
                                                    <div class="div-block-55">
                                                        <div class="text-block-120">Passion</div>
                                                    </div>
                                                </div>
                                                <div id="w-node-_1ceb76a7-7d16-a1da-b3c7-0b4893a51b82-f05aa8b8"
                                                    class="w-layout-cell cell-12">
                                                    <div class="div-block-54"><img
                                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65979967074392b5ef3330a8_4.png"
                                                            loading="lazy" width="45" alt=""
                                                            class="image-86" /></div>
                                                    <div class="div-block-55">
                                                        <div class="text-block-120">Accountability</div>
                                                    </div>
                                                </div>
                                                <div id="w-node-b8f82c3e-84d7-a167-5f05-edb2a86473bf-f05aa8b8"
                                                    class="w-layout-cell"></div>
                                                <div id="w-node-fcefbd18-0859-3e43-42bd-7dbe7a2a7596-f05aa8b8"
                                                    class="w-layout-cell cell-13">
                                                    <div class="div-block-54"><img
                                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65979967afba0913ae4799db_2.png"
                                                            loading="lazy" width="45" alt="" /></div>
                                                    <div class="div-block-55">
                                                        <div class="text-block-120">Innovation</div>
                                                    </div>
                                                </div>
                                                <div id="w-node-_0d1fbab2-e926-c56f-53a9-583bc137e1ba-f05aa8b8"
                                                    class="w-layout-cell cell-14">
                                                    <div class="div-block-54"><img
                                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/6597996899e5877af80a2416_5.png"
                                                            loading="lazy" width="45" alt="" /></div>
                                                    <div class="div-block-55">
                                                        <div class="text-block-120">Perseverance</div>
                                                    </div>
                                                </div>
                                                <div id="w-node-_321e2e57-66e2-3516-bed5-4748c4b1a4d3-f05aa8b8"
                                                    class="w-layout-cell"></div>
                                                <div id="w-node-f20a8005-bbd4-a6f1-a62f-9965065851f6-f05aa8b8"
                                                    class="w-layout-cell cell-15">
                                                    <div class="div-block-54"><img
                                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65979968d686628d97859c4b_3.png"
                                                            loading="lazy" width="45" alt="" /></div>
                                                    <div class="div-block-55">
                                                        <div class="text-block-120">Craftmanship</div>
                                                    </div>
                                                </div>
                                                <div id="w-node-_8896316c-8972-3a7d-d551-6d1e4d4e2ecb-f05aa8b8"
                                                    class="w-layout-cell cell-16">
                                                    <div class="div-block-54"><img
                                                            src="https://assets-global.website-files.com/6564282be531be60fd0d391f/659799688b617e7eb05610de_6.png"
                                                            loading="lazy" width="45" alt="" /></div>
                                                    <div class="div-block-55">
                                                        <div class="text-block-120">Growth</div>
                                                    </div>
                                                </div>
                                                <div id="w-node-_88727121-bd3b-2901-af4f-e680282eee9b-f05aa8b8"
                                                    class="w-layout-cell"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="div-block-40">
                                        <div class="career-oppor">
                                            <div class="text-block-110">Career Opportunities</div>
                                        </div>
                                        <div class="div-block-37">
                                            <div class="w-layout-blockcontainer container-32 w-container">
                                                <div class="text-block-111">Filter by</div>
                                            </div>
                                            <div class="w-layout-blockcontainer container-33 w-container">
                                                <div class="w-layout-blockcontainer container-42 w-container">
                                                    <div style="width: 180px;">
                                                        <select class="select-custom" name="state"
                                                            id="stateFilter">
                                                            <option value="AL" selected>All Locations</option>
                                                            <option value="uk">United Kingdom</option>
                                                            <option value="aus">Australia</option>
                                                            <option value="can">Canada</option>
                                                            <option value="pak">Pakistan</option>
                                                            <option value="ken">Kenya</option>
                                                            <option value="uae">United Arab Emirates</option>
                                                            <option value="nz">New Zealand</option>
                                                            <option value="usa">United States</option>
                                                        </select>
                                                    </div>
                                                    <div class="w-layout-blockcontainer container-34 w-container">
                                                    </div>
                                                </div>
                                                <div class="w-layout-blockcontainer w-container">
                                                    <div style="width: 180px;">
                                                        <select class="select-custom" name="departments">
                                                            <option value="AL" selected>ALL </option>
                                                            <option value="se">SOFTWARE ENGINEERING</option>
                                                            <option value="sp">SALES</option>
                                                            <option value="qa">QUALITY ASSUARANCE</option>
                                                            <option value="mc">MARKETING AND COMMUNICATION</option>
                                                        </select>
                                                    </div>
                                                    <div class="w-layout-blockcontainer container-34 w-container">
                                                    </div>
                                                </div>
                                                <div class="w-layout-blockcontainer w-container">
                                                    <div style="width: 180px;">
                                                        <select class="select-custom" name="job_type">
                                                            <option value="AL" selected>All Types</option>
                                                            <option value="p">Permanent</option>
                                                            <option value="f">Fixed Term</option>
                                                        </select>
                                                    </div>
                                                    <div class="w-layout-blockcontainer container-34 w-container">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-layout-blockcontainer results w-container">
                                            <div class="div-block-38" data-job-location="uk"
                                                data-job-department="se" data-job-type="p">
                                                <p class="paragraph-6">Senior Software Engineer</p>
                                                <p>Permanent</p>
                                                <p>London (Remote)</p>
                                                <div class="div-block-39">
                                                    <div class="div-block-84"><a href="#"
                                                            class="link-2 apply-link">Apply</a>
                                                        <div class="div-block-83"><img
                                                                src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65b3beb031a58fe298517404_65ae481ba768e4ae062f7e73_Arrow%20right.png"
                                                                loading="lazy" alt="" /></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="div-block-38" data-job-location="uk"
                                                data-job-department="se" data-job-type="p">
                                                <p class="paragraph-6">Junior Full Stack</p>
                                                <p>Permanent</p>
                                                <p>London (Remote)</p>
                                                <div class="div-block-39">
                                                    <div class="div-block-84"><a href="#"
                                                            class="link-2 apply-link">Apply</a>
                                                        <div class="div-block-83"><img
                                                                src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65b3beb031a58fe298517404_65ae481ba768e4ae062f7e73_Arrow%20right.png"
                                                                loading="lazy" alt="" /></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="div-block-38" data-job-location="uk"
                                                data-job-department="sp" data-job-type="p">
                                                <p class="paragraph-6">Account Executive</p>
                                                <p>Permanent</p>
                                                <p>UK</p>
                                                <div class="div-block-39">
                                                    <div class="div-block-84"><a href="#"
                                                            class="link-2 apply-link">Apply</a>
                                                        <div class="div-block-83"><img
                                                                src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65b3beb031a58fe298517404_65ae481ba768e4ae062f7e73_Arrow%20right.png"
                                                                loading="lazy" alt="" /></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="div-block-38" data-job-location="pak"
                                                data-job-department="qa" data-job-type="p">
                                                <p class="paragraph-6">Quality Assurance</p>
                                                <p>Permanent</p>
                                                <p>Islamabad</p>
                                                <div class="div-block-39">
                                                    <div class="div-block-84"><a href="#"
                                                            class="link-2 apply-link">Apply</a>
                                                        <div class="div-block-83"><img
                                                                src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65b3beb031a58fe298517404_65ae481ba768e4ae062f7e73_Arrow%20right.png"
                                                                loading="lazy" alt="" /></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="div-block-38" data-job-location="uk"
                                                data-job-department="mc" data-job-type="p">
                                                <p class="paragraph-6">Digital Marketing Assistant</p>
                                                <p>Permanent</p>
                                                <p>Manchester</p>
                                                <div class="div-block-39">
                                                    <div class="div-block-84"><a href="#"
                                                            class="link-2 apply-link">Apply</a>
                                                        <div class="div-block-83"><img
                                                                src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65b3beb031a58fe298517404_65ae481ba768e4ae062f7e73_Arrow%20right.png"
                                                                loading="lazy" alt="" /></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display:{{ session('error') ? 'grid' : 'none' }};"
                                        class="div-block-52" id="job-modal">
                                        <div class="div-block-41" style="position: relative;z-index:2000;"><a
                                                href="#" class="close-btn link-block w-inline-block"><img
                                                    src="https://assets-global.website-files.com/6564282be531be60fd0d391f/659653a563cfb342ef6884c0_X.png"
                                                    loading="lazy" data-w-id="" alt=""
                                                    width="29.5" /></a>
                                            <div class="div-block-43">
                                                <div class="text-block-116" style="color:#1faa9f">Senior Software
                                                    Engineer (Machine Learning)</div>
                                            </div>
                                            <div class="div-block-45">
                                                <div id="w-node-_62fefaa5-64c3-f549-27a4-9e302edad697-f05aa8b8"
                                                    class="w-layout-layout wf-layout-layout">
                                                    <div class="w-layout-cell">
                                                        <div class="div-block-51">
                                                            <div class="form-block w-for">
                                                                <form id="job-form" name="email-form"
                                                                    data-name="Email For" method="post"
                                                                    action="{{ route('about-us.job-apply') }}"
                                                                    class="form-2"
                                                                    data-wf-page-id="65829a47c1eba9fcf05aa8b8"
                                                                    data-wf-element-id="2948a77a-4e3a-77ca-695b-bb0d3a1008e7"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <label for="name"
                                                                        class="field-label-4 label"
                                                                        style="font-weight: bold">Name</label>
                                                                    <input class="text-field-2 w-input form-control"
                                                                        required maxlength="256" name="name"
                                                                        data-name="Name" placeholder=""
                                                                        type="text" id="name" />
                                                                    <label for="email"
                                                                        class="field-label-5 label"
                                                                        style="font-weight: bold">Email Address</label>
                                                                    <input class="text-field-3 w-input form-control"
                                                                        required maxlength="256" name="email"
                                                                        data-name="Email" placeholder=""
                                                                        type="email" id="email"
                                                                        required="" />
                                                                    <label for="field"
                                                                        class="field-label-6 label"
                                                                        style="font-weight: bold">Message</label>
                                                                    <textarea spellcheck="true"  placeholder="Enter your message here" required maxlength="5000" id="field" name="message"
                                                                        data-name="Field" class="textarea-2 w-input form-control"></textarea>
                                                                    {{-- <input type="text" hidden name="job_title"
                                                                        class="form-control" id="job-title"
                                                                        value=""
                                                                        placeholder="Enter Job title here"> --}}
                                                                    <input type="file" id="fileInput"
                                                                        name="attachment" style="display: none;">
                                                                    <input type="button" data-wait="Please wait..."
                                                                        class=" close-btn submit-button-2 w-button"
                                                                        value="Cancel" /><input type="submit"
                                                                        data-wait="Please wait..."
                                                                        class="submit-button-3 w-button"
                                                                        value="Apply" />
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="w-layout-cell cell-10">
                                                        <div class="div-block-44">
                                                            <div class="div-block-46"><img
                                                                    src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65965a1c766aa0acf8d816d6_Layer%2029.png"
                                                                    loading="lazy" width="81.5"
                                                                    alt="" /></div>
                                                            <div class="div-block-47">
                                                                <div class="text-block-117">Upload your resume</div>
                                                            </div>
                                                            <div class="div-block-50">
                                                                <div class="div-block-49">
                                                                    <div class="text-block-118"><strong
                                                                            class="bold-text-2">_______________</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="div-block-48">
                                                                    <div class="text-block-119"><strong>or</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <a href="#" class="button-3 w-button"
                                                                id="browse-btn">Browse Files</a>
                                                        </div>
                                                        <p class="error-msg" id="upload-error">Please upload
                                                            required document!</p>
                                                    </div>
                                                </div>
                                                <div class="w-form-done"
                                                    style="display: {{ session('success') ? 'block' : '' }}">
                                                    <div>Thank you! Your submission has been received!</div>
                                                </div>
                                                <div class="w-form-fail"
                                                    style="display: {{ session('error') ? 'block' : '' }}">
                                                    <div style="text-align:center;">Oops! Something went wrong while
                                                        submitting the form.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-layout-blockcontainer container-35 w-container">
                                    <div class="div-block-119">
                                        <div class="text-block-112">Don’t see a suitable<br />vacancy?</div>
                                        <div class="text-block-113">We are always on the look out for exceptional
                                            talent. <br />If you feel
                                            your skills can help us on our journey, get in touch!</div>
                                        <div class="div-block-89">
                                            <div class="text-block-114 apply-link2" style="cursor: pointer;">Submit your resume</div>

                                            <div class="div-block-90"><img
                                                    src="https://assets-global.website-files.com/6564282be531be60fd0d391f/65b3beb031a58fe298517404_65ae481ba768e4ae062f7e73_Arrow%20right.png"
                                                    loading="lazy" alt="" /></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-layout-blockcontainer footer w-container">
                            <section class="footer-dark">
                                <div class="container-16">
                                    <div class="footer-wrapper">
                                        <div data-w-id="234e7673-b861-2443-8407-83ad6e044508"
                                            class="footer-content"><a href="#"
                                                class="footer-brand w-inline-block"><img
                                                    src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                                    loading="lazy" alt="" class="image-69" /><img
                                                    src="{{ asset('webflow_assets/home-about/images/clouds.png') }}"
                                                    loading="lazy" width="262"
                                                    sizes="(max-width: 479px) 57.5px, 12vw" alt=""
                                                    srcset="{{ asset('webflow_assets/home-about/images/clouds-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/clouds-p-800.png') }} 800w, {{ asset('webflow_assets/home-about/images/clouds-p-1080.png') }} 1080w, {{ asset('webflow_assets/home-about/images/clouds.png') }} 1093w"
                                                    class="image-70" /></a>
                                            <div id="w-node-_234e7673-b861-2443-8407-83ad6e04450c-6e044504"
                                                class="footer-block">
                                                <div class="title-small">Company</div><a href="{{ route('about_us') }}#tab-link-tab-1"
                                                    class="footer-link">About</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                                    class="footer-link">Privacy Policy</a><a href="{{ route('about_us') }}#tab-link-tab-3"
                                                    class="footer-link">Terms
                                                    of Service</a><a href="{{ route('about_us') }}#tab-link-tab-2" class="footer-link">Cookies
                                                    Policy</a><a href="{{ route('about_us') }}#tab-link-tab-5" class="footer-link">Corporate
                                                    Responsibility</a><a href="{{ route('about_us') }}#tab-link-tab-6" class="footer-link">Modern
                                                    Slavery</a><a href="{{ route('about_us') }}#tab-link-tab-7" class="footer-link">Careers</a>
                                            </div>
                                            <div id="w-node-_234e7673-b861-2443-8407-83ad6e04451d-6e044504"
                                                class="footer-block">
                                                <div class="title-small">Contact us</div><a href="#"
                                                    class="footer-link">42 Reading Road
                                                    <br />South Fleet<br />Hampshire<br />GU51 3QP</a><a
                                                    href="#" class="footer-link">+44
                                                    01252 613425</a><a href="#"
                                                    class="footer-link">support@qi-tech.co.uk</a>
                                            </div>
                                            <div id="w-node-_234e7673-b861-2443-8407-83ad6e04452c-6e044504"
                                                class="footer-block">
                                                <div class="title-small">Social</div>
                                                <div class="footer-social-block"><a href="#"
                                                        class="footer-social-link w-inline-block"><img
                                                            src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                                            loading="lazy" width="34.5" alt=""
                                                            class="image-71" /></a><a href="#"
                                                        class="footer-social-link w-inline-block"><img
                                                            src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                                            loading="lazy" width="37" alt=""
                                                            class="image-72" /></a><a href="#"
                                                        class="footer-social-link w-inline-block"><img
                                                            src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                                            loading="lazy" width="20" alt=""
                                                            class="image-73" /></a></div>
                                                <div class="footer-cloud"><img
                                                        src="{{ asset('webflow_assets/home-about/images/cloud-20big.png') }}"
                                                        loading="lazy"
                                                        data-w-id="234e7673-b861-2443-8407-83ad6e044537"
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
                                        src="{{ asset('webflow_assets/home-about/images/layer-2030.png') }}"
                                        loading="lazy" alt="" /></div>
                                <div class="div-block-109"><a href="{{ route('about_us') }}#tab-link-tab-1" class="link-6">About</a><a
                                href="{{ route('about_us') }}#tab-link-tab-2" class="link-6">Privacy Policy</a><a href="{{ route('about_us') }}#tab-link-tab-3"
                                        class="link-6">Terms of Service</a><a href="{{ route('about_us') }}#tab-link-tab-2"
                                        class="link-6">Cookies Policy</a><a href="{{route('about_us')}}#tab-link-tab-5"
                                        class="link-6">Corporate Responsibility  </a><a href="{{ route('about_us') }}#tab-link-tab-6"
                                        class="link-6">Modern Slavery</a><a href="{{ route('about_us') }}#tab-link-tab-7"
                                        class="link-6">Careers</a></div>
                                <div class="div-block-110">
                                    <div class="div-block-111"><img
                                            src="{{ asset('webflow_assets/home-about/images/x.png') }}"
                                            loading="lazy" alt="" /></div>
                                    <div class="div-block-112"><img
                                            src="{{ asset('webflow_assets/home-about/images/facebook.png') }}"
                                            loading="lazy" alt="" /></div>
                                    <div class="div-block-113"><img
                                            src="{{ asset('webflow_assets/home-about/images/linkedin-20logo.png') }}"
                                            loading="lazy" alt="" />
                                    </div>
                                </div>
                                <div>
                                    <div class="div-block-114"><img
                                            src="{{ asset('webflow_assets/home-about/images/cloud-202.png') }}"
                                            loading="lazy" sizes="100vw"
                                            srcset="{{ asset('webflow_assets/home-about/images/cloud-202-p-500.png') }} 500w, {{ asset('webflow_assets/home-about/images/cloud-202.png') }} 524w"
                                            alt="" /></div>
                                    <div class="div-block-115"><img
                                            src="{{ asset('webflow_assets/home-about/images/636d34ec2a2ccf328bffadd7_cloud-1-1.png') }}"
                                            loading="lazy" sizes="100vw"
                                            srcset="{{ asset('webflow_assets/home-about/images/636d34ec2a2ccf328bffadd7_cloud-1-p-500-1.png') }} 500w, {{ asset('webflow_assets/home-about/images/636d34ec2a2ccf328bffadd7_cloud-1-1.png') }} 783w"
                                            alt="" /></div>
                                </div>
                            </div>
                    </section>
                </div>
            </div>
        </div>
        <div id="thank-modal" style="display: {{ session('success') ? 'grid' : 'none' }}">
            <div class="thanks-wrapper">
                <a href="#" class="close-btn2 link-block w-inline-block"><img
                        src="https://assets-global.website-files.com/6564282be531be60fd0d391f/659653a563cfb342ef6884c0_X.png"
                        loading="lazy" data-w-id="" alt="" width="29.5" /></a>
                <div class="wrapper">
                    <h2 style="font-weight: 400;">Thank You!</h2>
                    <p>We will be in touch soon</p>
                    <img src="{{ asset('images/our_team.png') }}" alt="our team">
                    <input type="button" class=" close-btn2 btn" value="Close" style="width: 80%;" />
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('webflow_assets/home-about/js/jquery.js') }}" type="text/javascript"></script>
    <script src="{{ asset('webflow_assets/home-about/js/webflow-script.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin_assets/js/select2.min.js') }}" type="text/javascript"></script>
    <script>
        $('.select-custom').select2();
        $(document).ready(function() {
            var hashValue = window.location.hash.substring(1);
            if (hashValue.includes('tab-link')) {
                $('.' + hashValue).click();
            }
        });
        $('.w-tab-link').on('click', function(event) {
            event.preventDefault();
            var tabClass = $(this).attr('class').split(' ')[0];
            window.location.hash = '#' + tabClass;
        });
        $('.footer-link').on('click', function(event) {
            event.preventDefault();
            var tabClass = $(this).attr('class').split(' ')[0];
            console.log()
            window.location.href = $(this).attr('href');
            window.location.reload()
        });

        $('.apply-link').on('click', function() {
            const parent = $(this).closest('.div-block-38');
            const jobs = $(parent).find('p');
            const job_title = `${$(jobs[0]).text()}/${$(jobs[1]).text()}/${$(jobs[2]).text()}`
            $('#job-title').val(job_title).fadeOut('fast').attr('hidden', true);
            const modal_title = $('#job-modal .text-block-116').text($(jobs[0]).text())
            const modal = $('#job-modal').fadeIn();
        })
        $('.apply-link2').on('click', function() {
            $('#job-title').removeAttr('hidden').fadeIn().val('');
            const modal_title = $('#job-modal .text-block-116').text('Custom Request')
            const modal = $('#job-modal').fadeIn();
        })

        $('.apply-link2').on('click', function() {
            $('#job-title').removeAttr('hidden').fadeIn().val('');
             const modal_title = $('#job-modal .text-block-116')
                 .text('Tell us about You')
                 .css('color', '#74c4bb');
                 const modal = $('#job-modal').fadeIn();
});


        $('.close-btn').on('click', function() {
            $('#job-modal').fadeOut()
        })
        $('#browse-btn').on('click', function() {
            $('#fileInput').click()
        })
        $('#fileInput').on('change', function() {
            const maxFileSize = 2 * 1024 * 1024; // 2MB
            const filePath = $(this).val();

            if (filePath) {
                const fileSize = this.files[0].size; // Get file size in bytes
                if (fileSize > maxFileSize) {
                    $('#upload-error').text('File size exceeds the maximum limit of 2MB!').fadeIn();
                    $(this).val('');
                    $('#browse-btn').text('Browse');
                    return;
                    t
                } else {
                    $('#upload-error').text('File size exceeds the maximum limit of 2MB!').fadeOut();
                }

                // Get only the file name without the path
                const fileName = filePath.split(/[\\/]/).pop();
                const displayedName = fileName.length > 14 ? fileName.slice(0, 11) + '...' : fileName;
                $('#browse-btn').text(displayedName);
            } else {
                $('#browse-btn').text('Browse');
            }
        });

        $('#job-form').on('submit', function(event) {
            if ($('#fileInput').val() == '') {
                event.preventDefault();
                $('#upload-error').text('Please upload required document!').fadeIn();
            } else {
                $('#upload-error').fadeOut();
            }
        })

        $('.close-btn2').on('click', function() {
            $('#thank-modal').fadeOut();
        })


        $(document).ready(function() {
            filterJobs();
        });

        function filterJobs() {
            var selectedLocation = $('select[name="state"]').val();
            var selectedDepartment = $('select[name="departments"]').val();
            var selectedJobType = $('select[name="job_type"]').val();

            $('.div-block-38').each(function() {
                var jobLocation = $(this).data('job-location');
                var jobDepartment = $(this).data('job-department');
                var jobType = $(this).data('job-type');

                var locationMatch = (selectedLocation === 'AL' || jobLocation === selectedLocation);
                var departmentMatch = (selectedDepartment === 'AL' || jobDepartment === selectedDepartment);
                var typeMatch = (selectedJobType === 'AL' || jobType === selectedJobType);

                if (locationMatch && departmentMatch && typeMatch) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        $('select[name="state"], select[name="departments"], select[name="job_type"]').on('change', function() {
            filterJobs();
        });
    </script>



</body>

</html>
