@extends('layouts.admin.master')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/css/bootstrap-colorpicker.min.css"
        rel="stylesheet">
    <style>
        .hr-alert {
            position: absolute;
            right: 0;
            top: 0;
        }
    </style>
    <!-- Start Content-->
    @if (session('error'))
        <div class="alert alert_vt" id="alertID">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div class="alert alert-danger small " style="max-width:100%;">{{ session('error') }}</div>
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <form action="{{ route('save.appearance') }}" method="POST">
                @csrf
                @if ($getThemeData != null)
                    <input type="hidden" name="theme_id" value="{{ $getThemeData->id }}">
                @endif
                <div class="col-xl-12 mt-4">
                    <div class="card-box border-1">
                        <div class="row pb-2 border-bottom" style="border-color:#C8C8C8 !important;">
                            @if ($getThemeData == null)
                                <div class="col-lg-8">
                                    <h4>New Interface Appearance</h4>
                                </div>
                            @else
                                <div class="col-lg-8">
                                    <h4>Update Interface Appearance</h4>
                                </div>
                            @endif
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12 mb-2">
                                <div class="form-group mb-0">
                                    <label for="title">Appearance Name</label>
                                    <input type="text" name="theme_name" class="form-control demo" data-control="hue"
                                        value="{{ $getThemeData == null ? '' : $getThemeData->theme_name }}">
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Heading Color</label>
                                        <input type="text" name="heading_color" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->heading_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Text Color</label>
                                        <input type="text" name="text_color" class="form-control demo" data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->text_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Sidebar Color</label>
                                        <input type="text" name="sidebar_color" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->sidebar_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Sidebar Background Color</label>
                                        <input type="text" name="sidebar_background_color" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->sidebar_background_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Body Background Color</label>
                                        <input type="text" name="body_background_color" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->body_background_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Header Background Color</label>
                                        <input type="text" name="header_background_color" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->header_background_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Sidebar Hover</label>
                                        <input type="text" name="sidebar_hover" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->sidebar_hover }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Button Background Color</label>
                                        <input type="text" name="button_background_color" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->button_background_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Button Text Color</label>
                                        <input type="text" name="button_text_color" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->button_text_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Button Border Color</label>
                                        <input type="text" name="btn_border_color" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->btn_border_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Pagination Active Background Color</label>
                                        <input type="text" name="pagination_active_bg" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->pagination_active_bg }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Pagination Active Color</label>
                                        <input type="text" name="pagination_active_color" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->pagination_active_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Tabs Color</label>
                                        <input type="text" name="tabs_color" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->tabs_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Tabs Active Color</label>
                                        <input type="text" name="tabs_active_color" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->tabs_active_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Tabs Active Background Color</label>
                                        <input type="text" name="tabs_active_background_color"
                                            class="form-control demo" data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->tabs_active_background_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <div class="form-group_vt">
                                    <div class="cl-picker" class="col-sm-12" class="input-group colorpicker-component">
                                        <label for="title">Icon Color</label>
                                        <input type="text" name="icon_color" class="form-control demo"
                                            data-control="hue"
                                            value="{{ $getThemeData == null ? '#ed6868' : $getThemeData->icon_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-3 mb-2">
                    @if ($getThemeData == null)
                        <button type="submit" class="page-btn">Save</button>
                        <button type="submit" class="page-btn" name="apply">Save and Apply</button>
                    @else
                        <button type="submit" class="page-btn">Update</button>
                        <button type="submit" class="page-btn" name="apply">Update and Apply</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/js/bootstrap-colorpicker.min.js">
    </script>
    <script type="text/javascript">
        $('.cl-picker').colorpicker();
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 2000);
    </script>
@endsection
