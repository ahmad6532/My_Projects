@extends('layouts.head_office_app')
@section('title', 'Head office Settings')
@section('content')
<div id="content">
    <!-- Page Heading -->
    <div style="display: flex; justify-content: center; align-items: center;">
        <div class="content-page-heading">
            {{$contact->name}} Contact
        </div>
        <div style="position: absolute;left: 40px;" class="search">
            <input type="search" placeholder="Search" />
            <i style="margin-left: -25px; color: #777;" class="fa fa-search icon"></i>
        </div>
        
    </div>
    @include('layouts.error')
    <!-- Content Row -->
    
                           
    <div class="profile-page-contents hide-placeholder-parent" id="cm_case_overview">
        <div>{{$contact->gender}}</div>
        <div>DOB: <b> @if($contact->date_of_birth)
                {{Carbon\Carbon::parse($contact->date_of_birth)->format(config('app.dateFormat'))}}
                @endif</b></div>
        <div>@if($contact->nhs_number) NHS Number:
            <b>{{$contact->nhs_number}}</b>@elseif($contact->registration_no)
            Registration Number: <b>{{$contact->registration_no}}</b> @endif
        </div>
        <div>Profession:
            {{$contact->profession}}
            </div>
        @if($contact->company)
            <div>Company:
                {{$contact->company}}
            </div>
        @endif
        @if($contact->website)
            <div>Website:
                {{$contact->website}}
            </div>
        @endif
        @if($contact->practice_name)
            <div>Practice Name:
                {{$contact->practice_name}}
            </div>
        @endif
        <br>
        <div>Email Address: <b> 
            @if($contact->emails)
            @foreach ($contact->emails as $email)
                {{$email}},
            @endforeach
            @endif
            </b>
        </div>
        <div>Telephone No: 
            <b>
                @if($contact->telephones)
                @foreach ($contact->telephones as $telephone)
                    {{$telephone}},
                @endforeach
                
            @endif
            </b>
        </div>
        <br>
        <h5>Addresses <a href="#" data-bs-toggle="modal" data-bs-target="#add_new_address" class="">(ADD New)</a></h5>
        @if($contact->contact_address)
        @foreach ($contact->contact_address()->orderBy('id','desc')->get() as $address)

        <div>{{$address->address->address}} @if($loop->iteration == 1) <span
                class="badge bg-success"> Current</span> @else <span class="badge bg-warning">
                Past</span> @endif</div>
        @endforeach
        @endif
        <br>

        <h5>Incidents <a href="#" data-bs-toggle="modal" data-bs-target="#add_new_case" class="">(ADD
                New)</a></h5>

        @foreach ($contact->contact_cases as $case)
        <div>
            <a href="{{route('case_manager.view',$case->case->id)}}" target="_blank">Date:
                {{$case->case->created_at->format(config('app.dateFormat'))}}
                (@if($case->case->status == 'open') <span class="badge bg-success">
                    {{$case->case->status}} </span> @else <span class="badge bg-danger">
                    {{$case->case->status}} </span> @endif)



            </a>
            |
            {{$case->type}}
            |
            <a href="{{route('head_office.contact.delete_new_case', [$case->id,$case->case->id])}}"
                class="badge bg-danger delete" class="delete ">Delete</a>

        </div>

        @endforeach

        <br>

        <h5>Relations <a href="#" data-bs-toggle="modal" data-bs-target="#add_new_relation"
                class="">(ADD New)</a></h5>

        @foreach ($contact->contact_connections as $contact_connection)
        @if($contact_connection)
        <div>
            <a
                href="{{route('head_office.contact.view', $contact_connection->connected_with_id)}}">{{$contact_connection->connected_with->name}}</a>
            |
            <a href="#" class="badge bg-success">{{$contact_connection->relation_type}}</a>
            |
            <a data-bs-toggle="modal" class="badge badge-info"
                data-bs-target="#edit_relation_{{$contact_connection->id}}" href="#">Edit</a>
            |
            <a href="{{route('head_office.contact.delete_relation', [$contact_connection->id,$contact->id,'_token' => csrf_token()])}}"
                class="badge bg-danger delete" class="delete ">Delete</a>

        </div>

        @include('head_office.edit_relation',['contact_connection' => $contact_connection])
        @endif
        @endforeach
        <br>
        <h5>Notes</h5>
        <div>
            {{$contact->note}}
        </div>
    </div>
                        
</div>

<div class="modal fade" id="add_new_case" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form method="post" action="{{route('head_office.contact.assign_new_case',$contact->id)}}">
                @csrf
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Add New Case
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body case_body">
                    <div class="card">
                        <div class="card-body">

                            
                            @foreach($cases as $case)
                            @if(!in_array($case->id,$case_ids))
                            <div class="case_1 relative">

                                @if(!$case->case_closed)
                                <input type="checkbox" class="form-control inline w-21px cm_left_checkbox"
                                    value="{{$case->id}}" onchange="close_cases()" name="ids[]" multiple="multiple">
                                @endif
                                <div class="card border-left-secondary shadow w-100">
                                    <div class="card-body">
                                        <div class="row align-items-center ">
                                            <div class="col-sm-2 ">
                                                <div class="float-left mr-2">
                                                    <img class="img-profile rounded-circle" width="40"
                                                        src="{{asset('admin_assets/img/profile-pic.png')}}">
                                                </div>
                                                <div class="cm_case_number font-weight-bold text-black text-uppercase"
                                                    title="Case Number">
                                                    {{$case->id()}}</div>
                                                <div
                                                    class="cm_case_status font-weight-bold  @if($case->status == 'open') text-success @endif">
                                                    {{$case->status()}}</div>
                                            </div>
                                            <div class="col-sm-2 ">
                                                <span class="cm_incident_type">Incident Type:
                                                    {{$case->incident_type}}</span><br>
                                                <span class="cm_location_id">Location:
                                                    {{$case->location_name}}</span><br>
                                                <span class="cm_incident_date">Incident Date: {{date('d M
                                                    y',strtotime($case->created_at))}}</span><br>
                                                ({{$case->days_ago()}})
                                            </div>
                                            <div class="col-sm-2 ">
                                                Last accessed: <b>{{$case->last_accessed()}}</b><br>
                                                Last action: <b>{{$case->last_action()}}</b><br>
                                                <ul class="step cm-steps d-flex flex-nowrap mt-1">
                                                    @foreach($case->tasks as $k => $t)
                                                    <li
                                                        class="step-item step-1 {{count($case->tasks_completed) > $k ? 'active' : ''}}">
                                                        <a></a>
                                                    </li>
                                                    @endforeach
                                                </ul>

                                            </div>
                                            <div class="col-sm-4 cm_description_tab ">
                                                @if(strlen($case->description) > 180)
                                                {{substr($case->description,0,180)}}<span
                                                    class="cm_dots">...</span><span
                                                    class="cm_more_text">{{substr($case->description,180)}}</span>
                                                <a href="#" class="cm_see_more_btn">See more</a>
                                                <a href="#" style="display:none" class="cm_see_less_btn">See less</a>
                                                @else
                                                {{$case->description}}
                                                @endif
                                            </div>
                                            <div class="col-sm-2 ">
                                                <a href="{{route('case_manager.view',$case->id)}}" target="_blank"
                                                    class="float-right"><i
                                                        class="fa fa-angle-right fa-2x text-gray-300"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group involvement" style="display: none">
                                <label>Involvement </label>
                                <input type="text" name="type_{{$case->id}}" class="form-control">
                            </div>
                            @endif
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="add_new_address" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form method="post" action="{{route('head_office.contact.add_new_address',$contact->id)}}">
                @csrf
                <div class="modal-header text-center">
                    <h4 class="modal-title text-info w-100">
                        Add New Address
                    </h4>
                    <button type="button" class="btn-close float-right" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="inputGroup">Address</label>
                        <input type="text" name="address" class="form-control free-type-address pac-target-input"
                            required="">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group right">
                        <button type="button" class="btn btn-outline-secondary" onclick="this.form.reset();"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@include('head_office.edit_relation',['contact_connection' => null])





@section('scripts')

<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        $(document).ready(function() {
            $('.select_2').select2();
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
        
</script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzxvYQxd1yHydcBFIRNOLQjcbQtThH6rI&libraries=places&callback=initPlaces">
</script>
<script>

    
    $(document).on('change','.cm_left_checkbox',function(){


if($(this).is(":checked")){
    $(this).closest('.relative').next('.involvement').show();
    console.log(
    $(this).closest('.relative').next('.involvement').find("input").attr("required", true))
  }
  else
  {
    $(this).closest('.relative').next('.involvement').hide();
    $(this).closest('.relative').next('.involvement').find("input").attr("required", false);
    $(this).closest('.relative').next('.involvement').find("input").empty();
  }
})
   
</script>
@endsection
@endsection
@section('styles')

<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
<link rel="stylesheet" href="{{asset('admin_assets/css/style.css')}}">
<style>
    .case_body{
        overflow-y: auto;
    height: 700px;
    }
    </style>
@endsection