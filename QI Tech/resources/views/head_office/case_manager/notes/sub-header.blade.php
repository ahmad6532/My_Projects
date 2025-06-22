
<ul>
    <li> <a class="@if(request()->route()->getName() == 'case_manager.view') active @endif"
            href="{{route('case_manager.view',$case->id)}}">Case Notes<span></span></a> </li>
    <li> <a class="@if(in_array(request()->route()->getName(),['case_manager.edit_report','case_manager.view_report'])) active @endif"
            href="{{route('case_manager.view_report',$case->id)}}">View Report<span></span></a> </li>
{{-- <li> <a class="@if(request()->route()->getName() == 'case_manager.view_root_cause_analysis') active @endif"
        href="{{route('case_manager.view_root_cause_analysis',$case->id)}}"><span>Root Cause Analysis</span></a> </li> --}}
@if ($case->isArchived == false)
<li> <a data-toggle="tooltip" data-placement="top" title="Coming Soon" class="{{request()->route()->getName() == 'case_manager.case_updates' ? 'active' : ''}} placeholder-link"
        href="{{route('case_manager.case_archives')}}">Root Cause Analysis<span></span></a></li>
<li> <a   class="@if(request()->route()->getName() == 'case_manager.view_sharing') active @endif "
    href="{{route('case_manager.view_sharing',$case->id)}}">Sharing: {{count($case->share_cases()->where('is_deleted',0)->get())}} @if($case->share_case_extensions)<span
            class="badge badge-danger">{{$case->share_case_extensions}}<span> @endif <span></span></a>
</li>
<li> <a class="@if(request()->route()->getName() == 'case_manager.view_intelligence') active @endif"
    href="{{route('case_manager.view_intelligence',$case->id)}}">Intelligence<span></span></a> </li>
{{-- <li> <a class="@if(request()->route()->getName() == 'case_manager.view_drafts') active @endif"
    href="{{route('case_manager.view_drafts',$case->id)}}">Drafts<span></span></a> </li> --}}
<li> <a class="@if(request()->route()->getName() == 'head_office.case.requested_informations' || request()->route()->getName() == 'head_office.case.requested_informations') active @endif"
    href="{{route('head_office.case.requested_informations',$case->id)}}">Information Requested<span></span></a>
</li>
<li> <a class="@if(request()->route()->getName() == 'head_office.case.comment_drafts' || request()->route()->getName() == 'head_office.case.comment_drafts') active @endif"
    href="{{route('head_office.case.comment_drafts',$case->id)}}">Drafts<span></span></a>
</li>
        
@endif
    {{-- <li> <a class="@if(request()->route()->getName() == 'head_office.case.comment_links' || request()->route()->getName() == 'head_office.case.comment_links') active @endif"
            href="{{route('head_office.case.comment_links',$case->id)}}">Tracking Links<span></span></a>
    </li> --}}
</ul>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            var placeholderLinks = document.querySelectorAll('.placeholder-link');
            
            placeholderLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                //     alert('Coming Soon');
                });
            });
        });
    </script>