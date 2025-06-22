@extends('layouts.admin.master')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            @if (session('error'))
                <div class="alert alert_vt" id="alertID">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert_vt" id="alertID">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
                </div>
            @endif
            <div class="col-xl-12 mt-4">
                <div class="card-box border-1">
                    <div class="row pb-2 border-bottom">
                        <div class="col-lg-6">
                            <h4 class="header-title m-0">Interface Appearance</h4>
                            <p class="appearance-sub-heading">Select And Customize Interface Appearance</p>
                        </div>
                        <div class="col-lg-6">
                            @if ($user->haspermission(['appearance-settings-all','appearance-settings-write']))
                            <a href="{{ route('new.appearance.setting') }}" class="page-btn float-right">Add Theme</a>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-3">
                        @foreach ($getAllThemes as $theme)
                            <div class="col-lg-3">
                                <div class="card-holder position-relative">
                                    <div class="card-bg-color-area">
                                        <div class="card-inner-bg">
                                            <div class="first-width-div"></div>
                                            <div class="first-width-div second-width-div"></div>
                                        </div>
                                    </div>
                                    @if ($user->haspermission(['appearance-settings-all','appearance-settings-write','appearance-settings-delete']))
                                    <div class="btn-group dropdown-btn-group dropleft card-dropdown-btn">
                                        <button type="button"
                                            class="dropdown-toggle theme-card-dropdown table-action-icon card-icon-right"
                                            data-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu header-menu" aria-labelledby="dropdownMenuButton1">
                                            @if ($user->haspermission(['appearance-settings-all','appearance-settings-write']))
                                            <li><a class="dropdown-item"
                                                    href="{{ route('set.default.theme', ['id' => $theme->id]) }}">Apply</a>
                                            </li>
                                            @endif
                                            @if ($theme->is_editable == 'Y')
                                                @if ($user->haspermission(['appearance-settings-all','appearance-settings-write']))
                                                <li><a class="dropdown-item"
                                                        href="{{ route('update.appearance', ['id' => $theme->id]) }}">Update</a>
                                                </li>
                                                @endif
                                                @if ($user->haspermission(['appearance-settings-all','appearance-settings-delete']))
                                                <li><a class="dropdown-item"
                                                        href="{{ route('delete.theme', ['id' => $theme->id]) }}">Delete</a>
                                                </li>
                                                @endif
                                            @endif
                                        </ul>
                                    </div>
                                    @endif
                                    <div class="login-content-checkbox">
                                        @if (isset($getActiveTheme))
                                            <label class="container-checkbox_vt">
                                                <input type="radio" disabled name="radio"
                                                    {{ $theme->id == $getActiveTheme ? 'checked' : '' }}>
                                                <span class="checkmark" style="border-radius:50%;"></span>
                                            </label>
                                        @endif
                                    </div>
                                </div>
                                <h1 class="card-title_vt">{{ $theme->theme_name }}</h1>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 2000);
    </script>
@endsection
