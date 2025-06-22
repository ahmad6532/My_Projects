@extends('layouts.users_app')
@section('styles')
    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css"/>

@endsection

<style>
    .page-title {
        /*margin-top: -44px;*/
        /*margin-bottom: 34px;*/
        font-weight: 400;
        font-size: 2rem;
        /* padding-bottom: 10px; */
        /* margin:20px; */
    }
</style>

@section('content')
<div class="headingWithSearch flex-column " style="width:100%">
     
    

    <h3>Your Drafts<i class="fa-solid fa-circle-info" data-toggle="tooltip" data-placement="top" title="Your drafts are only visible to you"></i>
    </h3>
    <div>
        @if (isset($drafts) && count($drafts) != 0)
        <table id="blockUser-table" class="table table-responsive table-bordered mx-auto dataTable w-100 new-table" >
            <thead class="text-center">
                <tr>
                    <th>Form Name</th>
                    <th class="text-center">Draft Saved</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
           
            <tbody class="all_drafts text-center">
                @foreach($drafts as $draft)
                @if($draft->form->allow_drafts_off_site == true)
                <tr>
                    <td class="position">{{$draft->form->name}}</td>
                    <td class="fw-semibold email">{{$draft->created_at->format('D d/m/Y g:ia')}}</td>
                    <td><p title="{{$draft->form->allow_drafts_off_site ? "You can complete this draft off site by logging into your user account": 'You can not complete this draft offsite.'}}" class="badge bg-{{$draft->form->allow_drafts_off_site ? 'success' : 'danger'}} m-0">{{$draft->location->trading_name.' '.$draft->location->address_line1}}</p></td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center align-items-center mx-auto"
                            style="width: fit-content;">
                            
                            <a href="{{route('users.delete_drafts',['id'=>$draft->id,'_token'=>csrf_token()])}}" data-link="" type="button" class="btn p-0 px-2 delete-btn" title="Delete Draft">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                            <a href="{{url('/bespoke_form_v3/#!/submit-draft/' . $draft->id)}}" type="button" class="btn p-0 px-2" title="Continue Draft">
                                <i class="fa-regular fa-paper-plane"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        @endif
        @if (isset($drafts))
        @php
            $filtered_drafts = $drafts->where('form.allow_drafts_off_site', true);
        @endphp
        @if (count($filtered_drafts) == 0)

        <div id="emptyMessage" style="font-size: 18px; font-weight: normal; color: black; text-align: left;padding-left:30px">
            <p class="emp-msg">You have no saved drafts.</p> 
        </div>
            
        @endif
    @endif
    </div>
</div>


@endsection



@section('sidebar')
@include('layouts.user.sidebar-header')
@endsection
@section('scripts')
         <script>
        $(function (){
            if($('#blockUser-table tbody.all_drafts td').length === 0){
                $('#blockUser-table thead').css('display', 'none');
                $('.draft-err-msg').css('display', 'block');
            }
        });
    </script>
@endsection
