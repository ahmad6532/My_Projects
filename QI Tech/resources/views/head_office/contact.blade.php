@extends('layouts.head_office_app')
@section('title', 'Head office Settings')
@section('content')
@section('styles')
<style>
    .select2-container {
    z-index: 10000;
    width: 50% !important;
    margin-top: -21px !important;
}
</style>
@endsection
<div id="content">
    <div style="display: flex; justify-content: center; align-items: center;">
        <div class="content-page-heading">
            Contacts
        </div>
        <div class="input-group rounded" style="position: absolute;left: 40px;width:auto;">
            <span class="input-group-text border-0 bg-transparent search-addon" id="search-addon">
                <i class="fas fa-search" style="color: #969697;"></i>
            </span>
            <input type="search" class="form-control rounded shadow-none search-input" placeholder="Search" aria-label="Search" />
        </div>

      
        
    </div>
    {{-- <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-info">Contacts</h1>
    </div> --}}
    @include('layouts.error')
    <!-- Content Row -->
    <div class="profile-center-area">
        <nav class="page-menu bordered">
            <ul class="nav nav-tab main_header">
                <li><a data-bs-toggle="tab" data-bs-target="#patient_contact" class="active patient_contact" href="javascript:void(0)">Contacts<span></span></a></li>
                <li><a data-bs-toggle="tab" data-bs-target="#prescriber_contact" class="prescriber_contact" href="javascript:void(0)">Addresses<span></span></a></li>
            </ul>
        </nav>
        <hr class="hrBeneathMenu">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="patient_contact" role="tabpanel" aria-labelledby="patient_contact-tab">
                <div style="position: absolute;right: 40px;margin-top: -70px;" class="search">
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_new_contact" title="Add New Alert">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19M5 12H19" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                <table class="table table-striped" id="dataTable">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>DOB</th>
                            <th>Current Address</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="contacts_table">
                        @include('head_office.show_contacts',['contacts' => $contacts])
                        <tr class="line-reloading" style="display:none">
                            <td colspan="5">
                                <div class="line line-date  print-display-none">
                                    <div class="timeline-label"><i
                                            class="spinning_icon fa-spin fa fa-spinner"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>          
            </div>

            <div class="tab-pane fade" id="prescriber_contact" role="tabpanel" aria-labelledby="prescriber_contact-tab">
                <div style="position: absolute;right: 40px;margin-top: -70px;" class="search">
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_new_address" title="Add New Alert">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19M5 12H19" stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                <table class="table table-striped" id="dataTable">
                    <thead>
                        <tr>
                            {{-- <th>#</th> --}}
                            <th>Address</th>
                            <th>Current</th>
                            <th>Past</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="addresses_table">
                        
                        @include('head_office.show_address',['addresses' => $addresses])
                        <tr class="line-reloading" style="display:none">
                            <td colspan="4">
                                <div class="line line-date  print-display-none">
                                    <div class="timeline-label"><i
                                            class="spinning_icon fa-spin fa fa-spinner"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('head_office.edit_contact',['contact' => null])
@include('head_office.edit_address',['address' => null])

@section('scripts')
<script>
     $(document).ready(function (){
        loadActiveTab();
    });
    window.addEventListener('DOMContentLoaded', (event) => {
        $(document).ready(function() {
            $(".select_2_custom").select2({
            dropdownParent: $(".select_2_modal .modal-content"),
            tags:true
            });
        });
    });
    function loadActiveTab(tab = null){
        if(tab == null){
            tab = window.location.hash;
        } 
        console.log(tab);
        $('.nav-tabs button[data-bs-target="' + tab + '"]').tab('show');
    }
    $(".delete" ).on( "click", function(e) {
            e.preventDefault();
            let href= $(this).attr('href');
            alertify.defaults.glossary.title = 'Alert!';
            alertify.confirm("Are you sure?", "Are you sure to delete this? ",
            function(){
                window.location.href= href;
            },function(i){
            });
        });
        
    function initPlaces() {
    //var autocomplete = new google.maps.places.Autocomplete(document.getElementByClassName(''));
    var input = document.getElementsByClassName('free-type-address');
    for (let i = 0; i < input.length; i++) {
        var autocomplete = new google.maps.places.Autocomplete(input[i]);
        autocomplete.addListener('place_changed', function () {
        $(input[i]).trigger('change');
        });
    }
}



    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzxvYQxd1yHydcBFIRNOLQjcbQtThH6rI&libraries=places&callback=initPlaces"></script>




<script src="{{asset('js/alertify.min.js')}}"></script>
@include('head_office.be_spoke_forms.script')

<script src="{{asset('admin_assets/js/form-template.js')}}"></script>

@endsection
@endsection