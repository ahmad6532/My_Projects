<?php
$headOffice = Auth::guard('web')->user()->selected_head_office;
$customTheme = false;
if ($headOffice->link_token == Session::get('token')) {
    $customTheme = true;
    $themeData = $headOffice;
}
?>

@extends('layouts.head_office_app')
@section('title', 'Head office Settings')

<div class="loader-container" ng-show="UI.loading" style="display:none">
    <div class="loader"></div>
</div>
@section('content')

    <input type="hidden" value="{{ route('head_office.update_profile') }}" id="update_profile">
    

    <input type="hidden" id="_token" value="{{ csrf_token() }}">
    <div id="content">
        
        <div style="text-align: center; margin-bottom: 20px;">
            <h1 style="font-size: 24px; font-weight: bold; margin-bottom: 0; color: var(--portal-section-heading-color);;">Contacts</h1>
        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist" style="justify-content: center; margin-bottom: 0;">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="contacts-tab" data-bs-toggle="tab" href="#contacts" role="tab" aria-controls="contacts" aria-selected="true" 
                   style="font-weight: bold; text-decoration: none; position: relative;">Settings</a>
            </li>
        </ul>
        
        <hr style="border: 1px solid #ccc; margin-top: 0;">
        
        <div class="tab-content" id="myTabContent">
            <div id="contacts" class="tab-pane fade show active" role="tabpanel" aria-labelledby="contacts-tab">
                <div class="company-center-area">
                    <div class="tab-content" id="myTabContent">
                        <div id="company_info" class="company_info relative tab-pane active show">
                            <div class="profile-page-contents hide-placeholder-parent mt-3">
                                <label>Auto-merge contact if match is:
                                    <input id="percent_merge" class="form-control form-control-sm shadow-none border" min="0" max="100" value="{{ $head_office->percentage_merge }}" type="number" placeholder="100%" onfocusout="updateMergePercentage(this)" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .nav-link.active {
                text-decoration: none;
                color: black;
            }
            .nav-link.active::after {
                content: '';
                position: absolute;
                bottom: -1px;
                left: 0;
                width: 100%;
                height: 4px;
                background-color: black;
                border-radius: 5px;
            }
            .nav-link:hover {
                text-decoration: underline;
            }
        </style>
        
    </div>




    <input type="hidden" id="route" value="{{ route('head_office.update_head_office_contact_details') }}">
    <input type="hidden" id="token" value="{{ csrf_token() }}">



    {{-- @section('scripts') --}}
    
    <script>
        $(document).ready(function() {
            telnumber = $("#telephone").intlTelInput({
                fixDropdownWidth: true,
                showSelectedDialCode: true,
                strictMode: true,
                utilsScript: "{{ asset('admin_assets/js/utils.js') }}",
                preventInvalidNumbers: true,
                initialCountry: 'auto'
            }).on('countrychange', function(e, countryData) {
                code = $("#telephone").intlTelInput("getSelectedCountryData").dialCode;
            });

        })


        function previewImage(inputId, ImgId) {
            var input = document.getElementById(inputId);
            var preview = document.getElementById(ImgId);

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    console.log(e)
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if ($('#person_backImg').length && inputId == 'backgroundInput') {
                        $('#person_backImg').attr('src', e.target.result);
                    }
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }




        function updateFinanceEmail(element) {
            var email = $(element).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'finance_email',
                value: email,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function updateFinancePhone(element) {
            var email = $(element).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'finance_phone',
                value: email,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function updateTechnicalEmail(element) {
            var email = $(element).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'technical_email',
                value: email,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function updateTechnicalPhone(element) {
            var email = $(element).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'technical_phone',
                value: email,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function updateIsViewableToUser(element) {
            console.log(element);
            var email = $(element).prop('checked') == true ? 1 : 0;
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'is_viewable_to_user',
                value: email,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                        $('#sub-ops').slideToggle('fast')
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })


        }

        function updateIsHelpViewable(element) {
            var isChecked = $(element).prop('checked');
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'is_help_viewable',
                value: isChecked ? 1 : 0,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                        if (response.value == 1) {
                            $('#help_input').removeAttr('readonly');
                        } else {
                            $('#help_input').attr('readonly', true);
                        }
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })


        }

        function updateIsPhoneViewable(element) {
            var isChecked = $(element).prop('checked');
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'is_phone_viewable',
                value: isChecked ? 1 : 0,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                        if (response.value == 1) {
                            alertify.success('settings Updated!');
                        } else {
                            alertify.success('settings Updated!');
                        }
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })


        }

        function updateIsEmailViewable(element) {
            var isChecked = $(element).prop('checked');
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'is_email_viewable',
                value: isChecked ? 1 : 0,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                        if (response.value == 1) {
                            alertify.success('settings Updated!');
                        } else {
                            alertify.success('settings Updated!');
                        }
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })


        }

        function updateIsHoursViewable(element) {
            var isChecked = $(element).prop('checked');
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'is_viewable_hours',
                value: isChecked ? 1 : 0,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                        if (response.value == 1) {
                            alertify.success('settings Updated!');
                        } else {
                            alertify.success('settings Updated!');
                        }
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })


        }

        function updateHelpDescription(element) {
            var helpMsg = $(element).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'help_description',
                value: helpMsg,
                _token: _token
            };
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        $(element).val(response.value);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
        }

        function updateCompanyName(element) {
            var name = $(element).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'company_name',
                value: name,
                _token: _token
            };
            if (name.trim() === '' || name.length <= 4) {
                alertify.error('Invalid Company Name!')
            } else {
                $.post(route, data)
                    .then(function(response) {
                        if (response.result) {
                            $(element).val(response.value);
                            alertify.notify('Company Name updated!', 'success', 5)
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    })
            }
        }

        function updateCompanyAddress(element) {
            var name = $(element).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'address',
                value: name,
                _token: _token
            };
            if (name.trim() === '' || name.length <= 4) {
                alertify.error('Invalid Company Address!')
            } else {
                $.post(route, data)
                    .then(function(response) {
                        if (response.result) {
                            $(element).val(response.value);
                            alertify.notify('Company Address updated!', 'success', 5)
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    })
            }
        }

        function updateCompanyPhone(element) {
            var input = $("#telephone");
            var iti = input.intlTelInput("getInstance");
            var code = $("#telephone").intlTelInput("getSelectedCountryData").dialCode;
            var number = $("#telephone").intlTelInput("getNumber")
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'telephone_no',
                value: number,
                _token: _token
            };
            if ($("#telephone").intlTelInput("isValidNumber") === false) {
                alertify.notify('Invalid Phone', 'error');
                return;
            }
            console.log('datadfaf')
            $.post(route, data)
                .then(function(response) {
                    if (response.result) {
                        alertify.notify('Company Phone Updated!', 'success');
                    }
                })
                .catch(function(error) {
                    alertify.notify('Error Occured', 'error')
                    console.log(error);
                })
        }
        function updateMergePercentage(element) {
            var input = $("#percent_merge").val();
            var _token = $('#token').val();
            var route = "{{ route('head_office.company_info.change_percentage_merge') }}";
            var data = {
                column: 'telephone_no',
                value: input,
                _token: _token
            };
            if (input.trim() === '' || input > 100 || input < 0 || isNaN(input) ) {
                alertify.notify('Invalid Value', 'error');
                return;
            }
            $.post(route, data)
                .then(function(response) {
                    if (response.success == true) {
                        alertify.notify('Value Updated!', 'success');
                    }
                })
                .catch(function(error) {
                    alertify.notify(error.responseJSON.errors[0], 'error')
                    console.log(error);
                })
        }

        var loadFile = function(event) {
            var image = document.getElementById("output");
            image.src = URL.createObjectURL(event.target.files[0]);
            if ($('#login_preview_logo').length) {
                $('#login_preview_logo').attr('src', URL.createObjectURL(event.target.files[0]));
            }
            if ($('#nd-logo').length) {
                $('#nd-logo').attr('src', URL.createObjectURL(event.target.files[0]));
            }
            var route = $('#update_profile').val();
            var token = $('#_token').val();
            let file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = (evt) => {
                console.log(evt.target.result);
                result = evt.target.result;
                var data = {
                    file: result,
                    _token: token
                }
                $.post(route, data).then(function(response) {
                    console.log(response);
                });
            };
            reader.readAsDataURL(file);
        };

        $('#myInput').on('click', function(event) {
            event.stopPropagation();
            $(this).val($(this).data().company);
            $(this).removeAttr('readonly').css('background', 'transparent');
        });

        let isCopying = false;
        $('#myInput').on('blur', function() {
            const protocol = window.location.protocol + '//';
            if (isCopying) {
                isCopying = false;
                return;
            }
            $('#myInput').attr('readonly', true).css('background', '#E9ECEF');
            var company = $(this).val();
            var _token = $('#token').val();
            var route = $('#route').val();
            var data = {
                column: 'link_token',
                value: company,
                _token: _token
            };

            let temp_val = protocol + $('#myInput').data().company + '.qi-tech.co.uk';
            if (company == temp_val) {
                $('#myInput').val(protocol + $('#myInput').data().company + '.qi-tech.co.uk');
                return;
            }
            if (!/^[a-zA-Z0-9]+$/.test(company)) {
                alertify.notify('Link cannot contain symbols', 'error', 5);
                $(this).val(protocol + $('#myInput').data('company') + '.qi-tech.co.uk');
                return;
            }
            if ($('#myInput').data().company !== company && company.trim() !== '' && company.length > 4 &&
                company !== temp_val) {
                $.post(route, data)
                    .then(function(response) {
                        if (response.result) {
                            console.log(response.value);
                            $('#myInput').data('company', response.value);
                            $('#myInput').val(protocol + $('#myInput').data().company + '.qi-tech.co.uk');
                            alertify.notify('Link Updated!', 'success', 5)
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                        alertify.notify('Error Occured!', 'error', 5)
                    })
            } else {
                $('#myInput').val(protocol + $('#myInput').data().company + '.qi-tech.co.uk');
            }
        })

        function myFunction() {
            isCopying = true;
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            var tooltip = document.getElementById("myTooltip");
            tooltip.innerHTML = "Copied: " + copyText.value.slice(0, 36) + '...';
            // isCopying = false;
        }

        function outFunc() {
            var tooltip = document.getElementById("myTooltip");
            tooltip.innerHTML = "Copy to clipboard";
        }


        document.documentElement.style.setProperty('--highlight-company-subnav-color', @json($headOffice ? $headOffice->icon_color : '#34BFAF'));
        document.documentElement.style.setProperty('--highlight-company-btn-color', @json($headOffice ? $headOffice->highlight_color : '#34BFAF'));
        document.documentElement.style.setProperty('--icon-nav-color', @json($headOffice ? $headOffice->icon_color : '#444'));
        document.documentElement.style.setProperty('--primary-nav-color', @json($headOffice ? $headOffice->primary_color : '#fff'));

        $('#portalTextColorInput').on('input', function() {
            neColor = $(this).val();
            $('#portalTextColorInputSvg').css('fill', neColor);
            $('#portalName').css('color', neColor);
            document.documentElement.style.setProperty('--primary-nav-color2', neColor);
            $
        });
        $('#portalBackgroundColorInput').on('input', function() {
            neColor = $(this).val();
            $('#portalBackgroundColorInputSvg').css('fill', neColor);
            $('#portalName').css('background', neColor);
            document.documentElement.style.setProperty('--primary-nav-color2', neColor);
            $
        });
        $('#iconColorInput').on('input', function() {
            neColor = $(this).val();
            $('#iconColorInputSvg').css('fill', neColor);
        });
        $('#highlightColorInput').on('input', function() {
            neColor = $(this).val();
            $('#highlightColorInputSvg').css('fill', neColor);
        });
        $('#loginHighlightColorInput').on('input', function() {
            neColor = $(this).val();
            $('#loginHighlightColorInputSvg').css('fill', neColor);
        });
        $('#signButtonColorInput').on('input', function() {
            neColor = $(this).val();
            $('#signButtonColorInputSvg').css('fill', neColor);
            $('#signButtonColorInputBtn').css('background', neColor);
        });
        $('#tabColorInput').on('input', function() {
            neColor = $(this).val();
            $('#tabColorInputSvg').css('fill', neColor);
            $('#tabText').css('color', neColor);
            $('#tabLine').css('background', neColor);
        });
        $('#primaryButtonInput').on('input', function() {
            neColor = $(this).val();
            $('#primaryButtonInputSvg').css('fill', neColor);
            $('#primaryButtonInputBtn').css('background-color', neColor);
        });
        $('#buttonTextInput').on('input', function() {
            neColor = $(this).val();
            $('#buttonTextInputSvg').css('fill', neColor);
            $('#primaryButtonInputBtn').css('color', neColor);
        });
        $('#sectionHeadingInput').on('input', function() {
            neColor = $(this).val();
            $('#sectionHeadingInputSvg').css('fill', neColor);
            $('#headingText').css('color', neColor);
        });
        $('#portalTitleInput').on('keyup', function() {
            value = $(this).val();
            $('#portalName').text(value);
        });
        $('#signInMessage').on('keyup', function() {
            value = $(this).val();
            $('#signInMessageText').text(value);
        });

        $('#signInButtonTextInput').on('input', function() {
            neColor = $(this).val();
            $('#signInButtonTextInputSvg').css('fill', neColor);
            $('#signButtonColorInputBtn').css('color', neColor);
        });

        $('#file').on('change', function(event) {
            var input = event.target;
            if (input.files && input.files[0]) {
                var newImage = URL.createObjectURL(input.files[0]);
                $('#headOfficeLogo').attr('src', newImage);
            }

        });
        $("#loc_theme_color").on("input", function() {
            var color = $(this).val();
            $("#loc_theme_colorSvg").css("fill", color);
        });
        
        $("#location_button_color").on("input", function() {
            var color = $(this).val();
            $("#location_button_colorSvg").css("fill", color);
        });
        $("#location_section_heading_color").on("input", function() {
            var color = $(this).val();
            $("#location_section_heading_colorSvg").css("fill", color);
        });
        $("#location_button_text_color").on("input", function() {
            var color = $(this).val();
            $("#location_button_text_colorSvg").css("fill", color);
        });
        $("#location_form_setting_color").on("input", function() {
            var color = $(this).val();
            $("#location_form_setting_colorSvg").css("fill", color);
        });

        $('#addThemeCloseBtn').on('click', function() {
            $('#loc_theme_color').val('#000000');
            $('#loc_theme_colorSvg').css('fill', '#000000');
            $('#location_section_heading_color').val('#5ac1b6');
            $('#location_section_heading_colorSvg').css('fill', '#5ac1b6');
            $('#location_button_color').val('#5ac1b6');
            $('#location_button_colorSvg').css('fill', '#5ac1b6');
            $('#location_button_text_color').val('#000000');
            $('#location_button_text_colorSvg').css('fill', '#000000');
            $('#location_form_setting_color').val('#000000');
            $('#location_form_setting_colorSvg').css('fill', '#000000');
            $('#theme_id').val(0);
            $('#loc_theme_name').val('');
        });

    </script>


@if(Session::has('success'))
    <script>
        alertify.success("{{ Session::get('success') }}");
    </script>
@elseif(Session::has('importErrors'))
<script>
    @php
        $errors = Session::get('importErrors');
    @endphp

    @if(!empty($errors))
        @foreach($errors as $error)
            alertify.alert("Import Error", 
                `<strong>Error:</strong> Error occurred while importing data. Following Emails were not imported.<br/>
                <strong>Email:</strong> {{ $error['email'] }}
                `
            ).set({
                transition: 'zoom', 
                closable: false, 
                pinnable: false, 
                label: 'OK'
            }).set('onshow', function() { 
                this.elements.body.style.textAlign = 'left'; 
                this.elements.dialog.style.maxWidth = '600px'; 
            });
        @endforeach
    @endif
</script>
@endif
@endsection
