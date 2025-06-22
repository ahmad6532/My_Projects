@if(session('success_message'))
    <div id="successNotification" class="notification" role="alert">
        {{ session('success_message') }}
        <button class="close-notification">&times;</button>
    </div>
@endif
<style>
  .notification {
    position: fixed;
    bottom: 20px;  /* Position it at the bottom */
    right: 20px;   /* Position it at the right */
    background-color: #d4edda; /* Light green background */
    color: #155724; /* Dark green text color */
    padding: 15px;  /* Padding */
    border: 1px solid #c3e6cb; /* Border color */
    border-radius: 5px; /* Rounded corners */
    z-index: 1050; /* Make sure it’s above other content */
    transition: opacity 0.5s ease; /* Fade out transition */
}


.notification .close-notification {
    background: none; /* No background */
    border: none; /* No border */
    color: white; /* Close button color */
    font-size: 20px; /* Font size */
    cursor: pointer; /* Pointer on hover */
    margin-left: 15px; /* Space from text */
}

</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successNotification = document.getElementById('successNotification');
        if (successNotification) {
            setTimeout(() => {
                successNotification.style.opacity = '0'; // Fade out
                setTimeout(() => {
                    successNotification.style.display = 'none'; // Remove from view after fade
                }, 500); // Match this to the CSS transition duration
            }, 5000); // Change 5000 to your desired duration in milliseconds (5 seconds)
        }

        // Close button functionality
        const closeButton = successNotification ? successNotification.querySelector('.close-notification') : null;
        if (closeButton) {
            closeButton.addEventListener('click', () => {
                successNotification.style.opacity = '0'; // Fade out
                setTimeout(() => {
                    successNotification.style.display = 'none'; // Remove from view after fade
                }, 500);
            });
        }
    });
</script>

<div class="card card-qi border ps-3" style="background: linear-gradient(90deg, {{$case->link_case_with_form->form->color_code}} 10px, rgba(255,255,255,1) 0.5%)">
    <div class="card-body">
        <nav class="nav nav-tabs nav-h-bordered d-flex align-items-center justify-content-start gap-2">
            <a href="#" class="active" data-bs-toggle="tab" data-bs-target="#cm_case_overview"><span class="item_with_border">Case Info</span></a>
            <a href="#" data-bs-toggle="tab" data-bs-target="#cm_case_tasks"><span class="item_with_border">Tasks</span> <span class="badge badge-danger" style="background: #2BAFA5;">{{ count($case->tasks()) }} </span> </a>
            
            <a href="#" data-bs-toggle="tab" data-bs-target="#cm_case_interested_parties"><span class="item_with_border">Investigators</span></a>
            
            <a href="#" data-bs-toggle="tab" data-bs-target="#cm_case_link"><span class="item_with_border">Links</span> <span class="badge badge-danger" style="background: #2BAFA5;">{{count($case->all_links())}}</span></a>
            <a href="#" data-bs-toggle="tab" data-bs-target="#cm_case_documents"><span class="item_with_border">Media/Docs</span> <span class="badge badge-danger" style="background: #2BAFA5;">{{count($case->case_documents)}}</span></a>
            @if(count($my_tasks) > 0)
            <a href="#" data-bs-toggle="tab" data-bs-target="#cm_case_my_tasks"><span class="item_with_border">My Tasks</span> <span class="badge badge-danger" style="background: #2BAFA5;">{{count($my_tasks->where('status', 'in_progress'))}}</span></a>
            @endif
            @if($case->submitable_to_nhs_lfpse)
            <a href="#" data-bs-toggle="tab" data-bs-target="#cm_case_nhs_lfpse"><span class="item_with_border">LFPSE Submission</span></a>
            @endif


        </nav>
    <div class="tab-content" id="myTabContent">
        <div id="cm_case_tasks" class="cm_case_tasks cm_comments_wrapper  scrollbar_custom_green relative tab-pane " >
            @include('head_office.case_manager.notes.view_tasks',['case' => $case])
        </div>
        <div class="tab-pane scrollbar_custom_greenfade cm_case_interested_parties" id="cm_case_interested_parties">
            @include('head_office.case_manager.notes.interested_parties',['case' => $case])
        </div>
        <div class="tab-pane fade cm_case_overview show active" id="cm_case_overview">
            <div class="d-flex flex-column gap-2">
            <h6 class="mt-2 fw-bold" style="margin-left: 2px;">
                            {{ isset($case->link_case_with_form->form->name) ? $case->link_case_with_form->form->name : 'Unknown' }}
            </h6>
                <div class="d-flex gap-3 align-items-center w-100">
                    {{-- <div class="cm_case_number text-black text-uppercase fw-normal" style="font-size: 18px;" data-toggle="tooltip" data-bs-placement="top" title="Case Number">#{{$case->id()}}</div> --}}
                </div>
                <div class="cm_case_status font-weight-bold d-flex align-items-center fw-semibold @if($case->isArchive == true) text-info @elseif($case->status == 'waiting' && $case->requires_final_approval == true) text-warning @elseif($case->status == 'open') text-success @elseif($case->status == 'closed') text-danger @endif" style="color:#2bafa5 !important"><i class="fa-solid fa-circle mx-2" style="font-size: 6px;margin-top:3px; color: #2bafa5"></i>@if($case->isArchived) Archived @elseif($case->status == 'waiting' && $case->requires_final_approval == true) Final Approval @else {{$case->status()}} @endif</div>

                
                <div style="font-size: 16px; white-space:nowrap;" class=" d-flex gap-2">
                    <img class="img-profile rounded-circle" width="22" height="22"
                    src="{{isset($case->getReporter()->id) ? ($case->getReporter()->email != 'external@qitech.com' ? $case->getReporter()->logo : asset('images/user-external.svg')) : asset('images/user-external.svg') }}"
                    />
                    @if (isset($case->getReporter()->id) && $case->getReporter()->email != 'external@qitech.com')
                    Reported by
                    <p class="m-0 fw-semibold">
                        {{$case->getReporter()->name ?? 'External User'}}
                    </p>
                    from
                    @else
                    Reported from
                    @endif
                    <p class="m-0 fw-semibold" style="font-size: 16px; white-space:normal;" data-toggle="tooltip" data-bs-placement="top" @if (isset($case->getReporter()->id) && $case->getReporter()->email != 'external@qitech.com')
                        title="{{$case->location_full_address}}"
                    @endif >
                        @if (isset($case->saved_loc))
                        {{ isset($case->saved_loc->location_id) ? $case->saved_loc->location_id : $case->saved_loc->trading_name}}
                        @else
                            {{ isset($case->location->location_id) ? $case->location->location_id : $case->location_name}}
                        @endif
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2" style="font-size: 16px;white-space: nowrap;">                  
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path stroke="rgb(103,105,121)" d="M12 2V6M12 18V22M6 12H2M22 12H18M19.0784 19.0784L16.25 16.25M19.0784 4.99994L16.25 7.82837M4.92157 19.0784L7.75 16.25M4.92157 4.99994L7.75 7.82837" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Created 
                    <p class="m-0 fw-semibold">{{$case->created_at->format('d M Y,  h:i a')}} ({{$case->created_at->diffForHumans() }})</p>
                </div>
                @if(isset($case->saved_loc))
                <div class="d-flex align-items-center gap-2" style="font-size: 16px;white-space: nowrap;">                  
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 3V6.4C7 6.96005 7 7.24008 7.10899 7.45399C7.20487 7.64215 7.35785 7.79513 7.54601 7.89101C7.75992 8 8.03995 8 8.6 8H15.4C15.9601 8 16.2401 8 16.454 7.89101C16.6422 7.79513 16.7951 7.64215 16.891 7.45399C17 7.24008 17 6.96005 17 6.4V4M17 21V14.6C17 14.0399 17 13.7599 16.891 13.546C16.7951 13.3578 16.6422 13.2049 16.454 13.109C16.2401 13 15.9601 13 15.4 13H8.6C8.03995 13 7.75992 13 7.54601 13.109C7.35785 13.2049 7.20487 13.3578 7.10899 13.546C7 13.7599 7 14.0399 7 14.6V21M21 9.32548V16.2C21 17.8802 21 18.7202 20.673 19.362C20.3854 19.9265 19.9265 20.3854 19.362 20.673C18.7202 21 17.8802 21 16.2 21H7.8C6.11984 21 5.27976 21 4.63803 20.673C4.07354 20.3854 3.6146 19.9265 3.32698 19.362C3 18.7202 3 17.8802 3 16.2V7.8C3 6.11984 3 5.27976 3.32698 4.63803C3.6146 4.07354 4.07354 3.6146 4.63803 3.32698C5.27976 3 6.11984 3 7.8 3H14.6745C15.1637 3 15.4083 3 15.6385 3.05526C15.8425 3.10425 16.0376 3.18506 16.2166 3.29472C16.4184 3.4184 16.5914 3.59135 16.9373 3.93726L20.0627 7.06274C20.4086 7.40865 20.5816 7.5816 20.7053 7.78343C20.8149 7.96237 20.8957 8.15746 20.9447 8.36154C21 8.59171 21 8.8363 21 9.32548Z" stroke="rgb(103,105,121)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                        
                    Saved in location
                    <p class="m-0 fw-semibold">{{ isset($case->location->location_id) ? $case->location->location_id : $case->location_name}}</p>
                </div>
                @endif
            </div>
            <hr class="mx-auto w-75 my-3" style="border:1.5px solid #3c3b3b">
            <style>
                .btn:hover{
                        background: gray;
                }
            </style>
            <div style="min-height: 100px;">
                <h6 class="fw-bold d-flex align-items-center gap-1">Linked Cases
                    @if ($case->isArchived == false)
                    <button type="button" data-bs-toggle="modal" data-bs-target="#link_case_modal"  class="btn btn-circle btn-group-assign green d-flex align-items-center justify-content-center "><i class="fa fa-plus"></i></button>
                    @endif
                </h6>
                @if (count($case->allLinkedCases()) != 0)
                    <table class="table new-table">
                        <thead>
                            <th>Case</th>
                            <th>Type</th>
                            <th></th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($case->allLinkedCases() as $link_case )
                            @php
                                $link_case_other = $link_case->otherCase($case->id)->first();
                                $userName = $link_case && $link_case->user_id 
                                   ? DB::table('users')->where('id', $link_case->user_id)->value('first_name') 
                                   : 'Unknown User';
                           
                               $timeAgo = $link_case 
                                   ? \Carbon\Carbon::parse($link_case->created_at)->diffForHumans() 
                                   : '';
                            @endphp         
                                <tr>
                                    <td>#{{$link_case_other->id}}</td>
                                    <td>{{$link_case_other->link_case_with_form->form->name ?? ' '}}</td>
                                    <td>
                                        @if ($link_case->linked_manually == null)
                                        <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <title>Form-logic triggered</title>
                                            <path d="M4.5 22V17M4.5 7V2M2 4.5H7M2 19.5H7M13 3L11.2658 7.50886C10.9838 8.24209 10.8428 8.60871 10.6235 8.91709C10.4292 9.1904 10.1904 9.42919 9.91709 9.62353C9.60871 9.8428 9.24209 9.98381 8.50886 10.2658L4 12L8.50886 13.7342C9.24209 14.0162 9.60871 14.1572 9.91709 14.3765C10.1904 14.5708 10.4292 14.8096 10.6235 15.0829C10.8428 15.3913 10.9838 15.7579 11.2658 16.4911L13 21L14.7342 16.4911C15.0162 15.7579 15.1572 15.3913 15.3765 15.0829C15.5708 14.8096 15.8096 14.5708 16.0829 14.3765C16.3913 14.1572 16.7579 14.0162 17.4911 13.7342L22 12L17.4911 10.2658C16.7579 9.98381 16.3913 9.8428 16.0829 9.62353C15.8096 9.42919 15.5708 9.1904 15.3765 8.91709C15.1572 8.60871 15.0162 8.24209 14.7342 7.50886L13 3Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        @else
                                     

                                        <svg width="35px" height="35px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <title>Manually linked by {{ $userName }} , {{ $timeAgo }}</title>
                                            <path d="M7 10.5L4.99591 13.1721C4.41845 13.9421 4.47127 15.0141 5.1216 15.7236L8.9055 19.8515C9.28432 20.2647 9.81826 20.5 10.3789 20.5C11.4651 20.5 13.2415 20.5 15 20.5C17.4 20.5 19 19 19 16.5C19 16.5 19 16.5 19 16.5C19 16.5 19 9.64287 19 7.92859" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M16 8.49995C16 8.49995 16 8.37483 16 7.92852C16 5.6428 19 5.6428 19 7.92852" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M13 8.50008C13 8.50008 13 7.91978 13 7.02715M13 6.50008C13 6.50008 13 6.804 13 7.02715M16 8.50008C16 8.50008 16 8.37496 16 7.92865C16 7.70549 16 7.25031 16 7.02715C16 4.74144 13 4.74144 13 7.02715" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M13 8.50008C13 8.50008 13 7.91978 13 7.02715C13 4.74144 16 4.74144 16 7.02715C16 7.25031 16 7.70549 16 7.92865C16 8.37496 16 8.50008 16 8.50008" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M10 8.50005C10 8.50005 10 7.85719 10 6.50005C10 4.21434 13 4.21434 13 6.50005C13 6.50005 13 6.50005 13 6.50005C13 6.50005 13 6.80397 13 7.02713C13 7.91975 13 8.50005 13 8.50005" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M7 13.5001V6.50006C7 5.67164 7.67157 5.00006 8.5 5.00006V5.00006C9.32843 5.00006 10 5.55527 10 6.38369C10 6.42151 10 6.4603 10 6.50006C10 7.85721 10 8.50006 10 8.50006" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        @endif
                                    </td>                                    
                                    <td><div class="cm_case_status font-weight-bold d-flex align-items-center fw-semibold @if($link_case_other->isArchive == true) text-info @elseif($link_case_other->status == 'waiting' && $link_case_other->requires_final_approval == true) text-warning @elseif($link_case_other->status == 'open') text-success @elseif($link_case_other->status == 'closed') text-danger @endif" ><i class="fa-solid fa-circle mx-2" style="font-size: 6px;margin-top:3px;"></i>@if($link_case_other->isArchived) Archived @elseif($link_case_other->status == 'waiting' && $link_case_other->requires_final_approval == true) Final Approval @else {{$link_case_other->status()}} @endif</div></td>
                                    @if ($case->isArchive == true)
                                        <td></td>
                                    @else
                                    <td>
                                        <a href="#" class="btn shadow-none unlink-btn" title="Unlink" data-bs-toggle="modal" data-bs-target="#unlink_case_modal" data-toggle="tooltip" data-bs-placement="right" data-id='{{$link_case_other->id}}' id="unlink-btn"><i class="fa-solid fa-link-slash"></i></a>
                                        <a href="{{ route('case_manager.view', $link_case_other->id) }}" target="_blank" class="btn shadow-none" title="View" data-toggle="tooltip" data-bs-placement="right" id=""><i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>    
                                    @endif
                                </tr>
                                
                            @endforeach
                        </tbody>
                    </table>
                @else
                <p>There are no linked cases</p>
                @endif
                
            </div>
            <div>
                <h6 class="fw-bold">Tags</h6>
                @livewire('case-tags-manager', ['case_id' => $case->id])

            </div>


        </div>
        <div class="tab-pane fade cm_case_link" id="cm_case_link">
            @include('head_office.case_manager.notes.link',['links' => $case->case_links,'case' => $case])
        </div>
        <div class="tab-pane fade cm_case_my_tasks" id="cm_case_my_tasks">
            @include('head_office.case_manager.notes.view_my_tasks',['case'=> $case])  
        </div>

        <div class="cm_case_documents scrollbar_custom_green relative tab-pane show " id="cm_case_documents">
            @include('head_office.case_manager.notes.view_documents',['documents' => $case->case_documents,'case' => $case])
        </div>
        <div class="tab-pane fade cm_case_nhs_lfpse" id="cm_case_nhs_lfpse">
            {{-- Note: Currently related to Record (Not Case) This should be review --}}
            @php
                $record = $case->link_case_with_form;

                $submission_array =  json_decode($record->json_submission, true);
                $submission_questions = array_values($submission_array['mandatory_questions']);
               $labelsToMatch = [
                   'To what extent was the patient physically harmed (including pain) in this incident?',
                    'To what extent was the patient psychologically harmed in this incident?'

               ];

                $selected_answer = [];

                foreach ($submission_questions as $val) {
                    if (in_array($val['label'], $labelsToMatch)) {
                        $selected_answer[] = $val;
                    }
                }
            @endphp
            <div>
                @if(count($record->LfpseSubmissions) == 0)
                    <div>Submission Status: <b class="text-warning">Not Submitted</b></div>
                    <a href="{{route('case_manager.submit_nhs_lfpse', $case->id)}}" class="btn btn-info" id="submit-now">Submit Now</a>
                @else
                <div class="d-flex align-items-center gap-2">
                    @if (isset($record->lfpse_deletes))
                    <div>
                        <div>Submission Status: <b class="text-danger">Deleted</b></div>
                            @if(isset($record->lfpse_deletes->message))
                                <div>
                                    <p class=" mb-0 mt-2" style="line-height: 0.5;"><small>Deletion Message:</small></p>
                                    <p class="text-secondary mb-0 ms-1">{{$record->lfpse_deletes->message}}</p>
                                </div>
                            @endif
                    </div>
                    @else
                        <div>Submission Status: <b class="text-success">Submitted</b></div>
                        @php
                            $last_record = $record->all_linked_records()->last();
                                if(!isset($last_record)){
                                    $last_record = $record;
                                }
                        @endphp
                        <a data-toggle="tooltip" title="Edit" href="/bespoke_form_v3/#!/edit/{{$last_record->id}}?case_id={{$case->id}}" class="primary-btn"><i class="fa-solid fa-pencil"></i> Edit</a>
                        <a data-bs-toggle="modal" data-bs-target="#link_del" title="Delete" href="#" class="primary-btn "><i class="fa fa-trash"></i> Delete</a>

                    @endif
                </div>
                    <br />
                    <table class="table">
                        <thead>
                            <tr>
                                <td style="font-weight:bold;">Sr#</td>
                                @foreach ($record->LfpseSubmissions as $sr => $s)
                                    <td>Record {{$sr + 1}}</td>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight:bold;white-space:nowrap">LFPSE ID</td>
                                @foreach ($record->LfpseSubmissions as $s)
                                    <td>{{$s->reference_id}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="font-weight:bold;">Type</td>
                                @php
                                    $FormType = json_decode($record->json_submission,true)['mandatory_questions']['-1080']['value'];
                                @endphp
                                    <td>{{$FormType == '1' ? 'Incident' : "Good Care"}}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;">Version</td>
                                @foreach ($record->LfpseSubmissions as $s)
                                    <td>{{isset($s->version) ? $s->version : '6'}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="font-weight:bold;">Outcome</td>
                                @foreach ($record->LfpseSubmissions as $s)
                                    <td>{{$s->outcome_type_string}}</td>
                                @endforeach
                            </tr>
                            @if($FormType == '1')
                                <tr>
                                    <td style="font-weight:bold;">Psychological Harm</td>
                                    <td>
                                        @php
                                            $psyHarmLevels = [
                                                "4" => "No Psychological harm",
                                                "3" => "Low Psychological harm",
                                                "2" => "Moderate Psychological harm",
                                                "1" => "Severe Psychological harm"
                                            ];

                                            if (isset($selected_answer[1]['value']) && array_key_exists($selected_answer[1]['value'], $psyHarmLevels)) {
                                                echo $psyHarmLevels[$selected_answer[1]['value']];
                                            }

                                        @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Physical Harm</td>
                                    <td>
                                        @php
                                            $harmLevels = [
                                                "5" => "No physical harm",
                                                "4" => "Low physical harm",
                                                "3" => "Moderate physical harm",
                                                "2" => "Severe physical harm",
                                                "1" => "Fatal physical harm"
                                            ];

                                            if (isset($selected_answer[0]['value']) && array_key_exists($selected_answer[0]['value'], $harmLevels)) {
                                                echo $harmLevels[$selected_answer[0]['value']];
                                            }
                                        @endphp
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td style="font-weight:bold;">Remarks</td>
                                @foreach ($record->LfpseSubmissions as $s)
                                    <td>{{$s->remarks}}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td style="font-weight:bold;">Date</td>
                                @foreach ($record->LfpseSubmissions as $s)
                                    <td>{{$s->created_at->format('d M Y (D) h:i a')}}</td>
                                @endforeach
                            </tr>
                        {{-- ==================================================================================================== --}}
                            @foreach ($record->all_linked_records() as $lfpseRec)
                            @if (isset($lfpseRec->LfpseSubmissions) && count($lfpseRec->LfpseSubmissions) > 0)
                                <tr style="opacity: 0;" ><td class="border-0">fsaf</td></tr>
                                <tr style="opacity: 0;" ><td class="border-0">fsaf</td></tr>
                                <tr>
                                    <td style="font-weight:bold;white-space:nowrap">LFPSE ID</td>
                                    @foreach ($lfpseRec->LfpseSubmissions as $s)
                                        <td>{{$s->reference_id}}</td>
                                    @endforeach
                                </tr>
                                {{-- <tr>
                                    <td style="font-weight:bold;">Actual ID</td>
                                    @foreach ($lfpseRec->LfpseSubmissions as $s)
                                        <td>{{$s->lfpse_id}}</td>
                                    @endforeach
                                </tr> --}}
                                <tr>
                                    <td style="font-weight:bold;">Version</td>
                                    @foreach ($lfpseRec->LfpseSubmissions as $s)
                                        <td>{{isset($s->version) ? $s->version : '6'}}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Outcome</td>
                                    @foreach ($lfpseRec->LfpseSubmissions as $s)
                                        <td>{{$s->outcome_type_string}}</td>
                                    @endforeach
                                </tr>
                                    @php
                                        $patients = json_decode($record->json_submission,true)['patients'];
                                        if(isset($patients) && count($patients) > 0){
                                            $patient = $patients[0];
                                            if(isset($patient['PhysicalHarm']) && isset($patient['PsychologicalHarm'])){
                                                $PhysicalHarm = $patient['PhysicalHarm']['value'];
                                                $PsychologicalHarm = $patient['PsychologicalHarm']['value'];
                                            }
                                        }
                                    @endphp
                                @if (isset($PsychologicalHarm))
                                    <tr>

                                        <td style="font-weight:bold;">Psychological Harm</td>
                                        <td>
                                            @php
                                                $psyHarmLevels = [
                                                    "4" => "No Psychological harm",
                                                    "3" => "Low Psychological harm",
                                                    "2" => "Moderate Psychological harm",
                                                    "1" => "Severe Psychological harm"
                                                ];

                                                if (isset($PsychologicalHarm) && array_key_exists($PsychologicalHarm, $psyHarmLevels)) {
                                                    echo $psyHarmLevels[$PsychologicalHarm];
                                                }

                                            @endphp
                                        </td>
                                    </tr>
                                    
                                @endif
                                @if (isset($PhysicalHarm))
                                    <tr>
                                        <td style="font-weight:bold;">Physical Harm</td>
                                        <td>
                                            @php
                                                $harmLevels = [
                                                    "5" => "No physical harm",
                                                    "4" => "Low physical harm",
                                                    "3" => "Moderate physical harm",
                                                    "2" => "Severe physical harm",
                                                    "1" => "Fatal physical harm"
                                                ];

                                                if (isset($PhysicalHarm) && array_key_exists($PhysicalHarm, $harmLevels)) {
                                                    echo $harmLevels[$PhysicalHarm];
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                    
                                @endif
                                <tr>
                                    <td style="font-weight:bold;">Remarks</td>
                                    @foreach ($lfpseRec->LfpseSubmissions as $s)
                                        <td>{{$s->remarks}}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Date</td>
                                    @foreach ($lfpseRec->LfpseSubmissions as $s)
                                        <td>{{$s->created_at->format('d M Y (D) h:i a')}}</td>
                                    @endforeach
                                </tr>
                                
                            @endif
                            @endforeach

                        </tbody>
                    </table>
                    
                @endif
                @if($record->errors->count() != 0)
                    <p class="m-0 mt-5 text-danger d-flex align-items-center"><b>Errors</b> <span class="badge badge-danger bg-danger" style="margin-left: 5px;">{{$record->errors->count()}}</span></p>
                    <table class="table">
                        <tbody>
                        {{-- ==================================================================================================== --}}
                            @foreach ($record->errors as $err)
                            <tr>
                                <td style="font-weight:bold;">Severity</td>
                                    <td>{{$err->severity}}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;">Remarks</td>
                                <td>{{$err->message}}</td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;">Time</td>
                                <td>{{$err->created_at->format('d M Y (D) h:i a')}} </td>
                            </tr>
                            <tr style="opacity: 0 !important;">
                                <td style="opacity: 0 !important;" class="border-0">asdf</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                @endif
            </div>
                
                
        </div>
    </div>

    </div>
    @php
        $caseHandlers = DB::table('case_handler_users')
    ->join('head_office_users', 'case_handler_users.head_office_user_id', '=', 'head_office_users.id')
    ->join('users', 'head_office_users.user_id', '=', 'users.id')
    ->whereIn('case_handler_users.case_id', $case->case_head_office->cases->pluck('id'))
    ->select('users.id as user_id', 'users.first_name')
    ->distinct()
    ->get();
    @endphp
    <div>
        <div class="modal fade" id="link_case_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-0 m-0">
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <h5 class="text-center fw-bold">Link Cases</h5>
                        <form method="POST" action="{{route('head_office.link_cases')}}">
                            @csrf
                            <input type="hidden" name="case_id" value="{{$case->id}}">
                            <input type="hidden" name="linked_manually" value="1">
                            <input type="hidden" name="user_id" value="{{ Auth::guard('web')->user()->getHeadOfficeUser($case->case_head_office->id)->user_id }}">
                            <div class="d-flex flex-column w-100 mb-3">
                                <label for="status_filter" class="form-label">Filter by Status</label>
                                <select id="status_filter" class="form-select">
                                    <option value="all" selected>All</option>
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            <div class="d-flex flex-column w-100 mb-3">
                                <label for="case_handler_filter" class="form-label">Case Handlers</label>
                                <select id="case_handler_filter" class="form-select">
                                    <option value="all" selected>All</option>
                                    @foreach ($caseHandlers as $handler)
                                        <option value="{{ $handler->user_id }}">{{ $handler->first_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex flex-row w-100 mb-3 gap-2">
                                <div class="flex-grow-1">
                                    <label for="from_date" class="form-label">From</label>
                                    <input type="date" id="from_date" class="form-control">
                                </div>
                                <div class="flex-grow-1">
                                    <label for="to_date" class="form-label">To</label>
                                    <input type="date" id="to_date" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex flex-column w-100">
                                <label for="link_cases" class="form-label">Select Cases</label>
                                <select name="link_cases[]" class="form-select select2" id="link_cases" multiple>
                                    @foreach ($case->case_head_office->cases as $link_case)
                                    <option value="{{ $link_case->id }}"   data-status="{{ strtolower($link_case->status) }}" data-created="{{ $link_case->created_at->format('Y-m-d') }}" data-handler="{{ $link_case->handler_id }}">
                                        Case #{{ $link_case->id}} | Status: {{$link_case->status}} | created: {{ $link_case->created_at->diffForHumans() }}
                                    </option>
                                @endforeach
                                </select>
                            </div>
                            <div class="d-flex flex-column w-100">
                                <label for="message" class="form-label">Add a comment</label>
                                <textarea spellcheck="true"  name="message" class="form-control" id="message" cols="30" rows="10"></textarea>
                            </div>
                            <button type="submit" class="btn btn-info shadow-none primary-btn mt-2">Link!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="unlink_case_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header border-0 m-0">
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <h5 class="text-center fw-bold">Unlink Cases</h5>
                    <form method="POST" action="{{route('head_office.un_link_cases')}}">
                        @csrf
                        <input hidden type="text" name="case_id" value="{{$case->id}}">
                        <input hidden type="text" name="other_case_id" id="other_case_id" >
                        <div class="d-flex flex-column w-100">
                            <label for="" class="form-label">Message</label>
                            <textarea spellcheck="true"  name="message" class="form-control" id="" cols="30" rows="10"></textarea>
                        </div>
                        <button type="submit" class="btn btn-info shadow-none primary-btn mt-2">Unlink!</button>
                    </form>
                </div>
            </div>
            </div>


            {{-- This is the filters Script --}}
            <script>
             document.addEventListener('DOMContentLoaded', function () {
    const statusFilter = document.getElementById('status_filter');
    const fromDateFilter = document.getElementById('from_date');
    const toDateFilter = document.getElementById('to_date');
    const caseHandlerFilter = document.getElementById('case_handler_filter');
    const linkCasesSelect = document.getElementById('link_cases');
    const originalOptions = Array.from(linkCasesSelect.options);

    function filterCases() {
        const selectedStatus = statusFilter.value.toLowerCase();
        const fromDate = fromDateFilter.value;
        const toDate = toDateFilter.value;
        const selectedHandler = caseHandlerFilter.value;

        linkCasesSelect.innerHTML = '';
        originalOptions.forEach(option => {
            const caseStatus = option.getAttribute('data-status').toLowerCase();
            const caseDate = option.getAttribute('data-created');
            const handlerId = option.getAttribute('data-handler'); // Add this attribute

            const matchesStatus = (selectedStatus === 'all' || caseStatus === selectedStatus);
            const matchesDate = (!fromDate || caseDate >= fromDate) && (!toDate || caseDate <= toDate);
            const matchesHandler = (selectedHandler === 'all' || handlerId === selectedHandler);

            if (matchesStatus && matchesDate && matchesHandler) {
                linkCasesSelect.appendChild(option);
            }
        });

        $(linkCasesSelect).trigger('change');

        if (linkCasesSelect.options.length === 0) {
            const noMatchOption = new Option('No matching cases', '', true, true);
            noMatchOption.disabled = true;
            linkCasesSelect.add(noMatchOption);
        }
    }

    statusFilter.addEventListener('change', filterCases);
    fromDateFilter.addEventListener('change', filterCases);
    toDateFilter.addEventListener('change', filterCases);
    caseHandlerFilter.addEventListener('change', filterCases);
});

            </script>
        </div>

        @if (count($record->LfpseSubmissions) != 0)
            <div class="modal fade" id="link_del" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-0 m-0">
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <h5 class="text-center fw-bold">Confirm NHS LFPSE Deletion!</h5>
                        <p class="text-secondary">Are you sure you want to delete this LFPSE Record with LFPSE ID {{$record->LfpseSubmissions[0]->reference_id}}?</p>
                        <form method="POST" action="{{route('case_manager.delete_nhs_lfpse', $case->id)}}">
                            @csrf
                            <input type="hidden" name="case_id" value="{{$case->id}}">
                            
                            <div class="d-flex flex-column w-100">
                                <label for="" class="form-label">Message (Optional)</label>
                                <textarea spellcheck="true"  name="msg" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                            <div class="mt-2 d-flex justify-content-between">
                                <div></div>
                                <button type="submit" class="btn btn-info shadow-none primary-btn">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
            
        @endif
    </div>
</div>

<div class="loader-container" id="loader" style="top: 0;z-index:9999999;display:none;">
    <div class="loader"></div>
</div>

<script>
    $('#submit-now').on('click', function() {
        $('#loader').show();
    })

    $('.unlink-btn').on('click', function() {
        $('#other_case_id').val($(this).data('id'));
    })
</script>
<style>
    .select2-container--open {
                            z-index: 9999999 !important;
                        }
</style>