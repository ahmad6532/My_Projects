@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::guard('user')->user();
    $share_cases = $user->share_cases->where('removed_by_user', 0);
@endphp
@php
    $saveDrafts = [];
@endphp
@if (!empty($drafts))
    @foreach ($drafts as $draft)
        @php
            if ($draft->form->allow_drafts_off_site) {
                $saveDrafts[] = $draft->form->name;
            }
        @endphp
    @endforeach
@endif
<div class="sidebar-logo-section">
    <a href="{{ route('user.view_profile') }}">
        <img src="{{ asset('v2/images/icons/qi-logo new.svg') }}" alt="QI Tech Logo">
    </a>

</div>
<hr class="m-0" />
<div class="pt-4 px-4">
    <div class="sidebar-title">
        <strong>Your</strong>
        <br>
        <h4><strong>User Account</strong></h4>
    </div>
</div>
<div class="sidebar-list-menu px-4">
    <div class="inputSection activeColor">
        Snapshot
    </div>
    <ul>
        @if ($user->head_office_admins->count() > 0)
            <li>
                <a href="{{ route('user.shared_cases') }}"
                    class="text-decoration-none @if (request()->route()->getName() == 'user.shared_cases') activeColor @endif" href="#">
                    <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20.7914 12.6074C21.0355 12.3981 21.1575 12.2935 21.2023 12.169C21.2415 12.0598 21.2415 11.9402 21.2023 11.831C21.1575 11.7065 21.0355 11.6018 20.7914 11.3926L12.3206 4.13196C11.9004 3.77176 11.6903 3.59166 11.5124 3.58725C11.3578 3.58342 11.2101 3.65134 11.1124 3.77122C11 3.90915 11 4.18589 11 4.73936V9.03462C8.86532 9.40807 6.91159 10.4897 5.45971 12.1139C3.87682 13.8845 3.00123 16.1759 3 18.551V19.1629C4.04934 17.8989 5.35951 16.8765 6.84076 16.1659C8.1467 15.5394 9.55842 15.1683 11 15.0705V19.2606C11 19.8141 11 20.0908 11.1124 20.2288C11.2101 20.3486 11.3578 20.4166 11.5124 20.4127C11.6903 20.4083 11.9004 20.2282 12.3206 19.868L20.7914 12.6074Z"
                            stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    Shared <span class="badge bg-custom-user-page float-end rounded-pill" data
                        style="width:25px">{{ count($share_cases) }}</span>
                </a>
            </li>
            <li>
                <a class="text-decoration-none @if (request()->route()->getName() == 'user.statement') activeColor @endif"
                    href="{{ route('user.statement') }}">
                    <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.4996 9.00224C10.6758 8.50136 11.0236 8.079 11.4814 7.80998C11.9391 7.54095 12.4773 7.4426 13.0006 7.53237C13.524 7.62213 13.9986 7.89421 14.3406 8.30041C14.6825 8.70661 14.8697 9.22072 14.8689 9.75168C14.8689 11.2506 12.6205 12 12.6205 12M12.6495 15H12.6595M12.4996 20C17.194 20 20.9996 16.1944 20.9996 11.5C20.9996 6.80558 17.194 3 12.4996 3C7.8052 3 3.99962 6.80558 3.99962 11.5C3.99962 12.45 4.15547 13.3636 4.443 14.2166C4.55119 14.5376 4.60529 14.6981 4.61505 14.8214C4.62469 14.9432 4.6174 15.0286 4.58728 15.1469C4.55677 15.2668 4.48942 15.3915 4.35472 15.6408L2.71906 18.6684C2.48575 19.1002 2.36909 19.3161 2.3952 19.4828C2.41794 19.6279 2.50337 19.7557 2.6288 19.8322C2.7728 19.9201 3.01692 19.8948 3.50517 19.8444L8.62619 19.315C8.78127 19.299 8.85881 19.291 8.92949 19.2937C8.999 19.2963 9.04807 19.3029 9.11586 19.3185C9.18478 19.3344 9.27145 19.3678 9.44478 19.4345C10.3928 19.7998 11.4228 20 12.4996 20Z"
                            stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Info Requests <span class="badge bg-custom-user-page float-end rounded-pill"
                        style="width:25px">{{ count($case_request_informations) }}</span>
                </a>
            </li>
            <li><a class="text-decoration-none @if (request()->route()->getName() == 'user.draft') activeColor @endif"
                    href="{{ route('user.draft') }}">
                    <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 20H21M3.00003 20H4.67457C5.16376 20 5.40835 20 5.63852 19.9447C5.84259 19.8957 6.03768 19.8149 6.21663 19.7053C6.41846 19.5816 6.59141 19.4086 6.93732 19.0627L19.5001 6.49998C20.3285 5.67156 20.3285 4.32841 19.5001 3.49998C18.6716 2.67156 17.3285 2.67156 16.5001 3.49998L3.93729 16.0627C3.59139 16.4086 3.41843 16.5816 3.29475 16.7834C3.18509 16.9624 3.10428 17.1574 3.05529 17.3615C3.00003 17.5917 3.00003 17.8363 3.00003 18.3255V20Z"
                            stroke="#888" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    Drafts <span class="badge bg-custom-user-page float-end rounded-pill"
                        style="width:25px">{{ count($saveDrafts) }}</span>
                </a></li>
            <li><a class="text-decoration-none @if (request()->route()->getName() == 'user.feedback') activeColor @endif"
                    href="{{ route('user.feedback') }}">

                    <svg width="20" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11 4H7.8C6.11984 4 5.27976 4 4.63803 4.32698C4.07354 4.6146 3.6146 5.07354 3.32698 5.63803C3 6.27976 3 7.11984 3 8.8V14C3 14.93 3 15.395 3.10222 15.7765C3.37962 16.8117 4.18827 17.6204 5.22354 17.8978C5.60504 18 6.07003 18 7 18V20.3355C7 20.8684 7 21.1348 7.10923 21.2716C7.20422 21.3906 7.34827 21.4599 7.50054 21.4597C7.67563 21.4595 7.88367 21.2931 8.29976 20.9602L10.6852 19.0518C11.1725 18.662 11.4162 18.4671 11.6875 18.3285C11.9282 18.2055 12.1844 18.1156 12.4492 18.0613C12.7477 18 13.0597 18 13.6837 18H15.2C16.8802 18 17.7202 18 18.362 17.673C18.9265 17.3854 19.3854 16.9265 19.673 16.362C20 15.7202 20 14.8802 20 13.2V13M20.1213 3.87868C21.2929 5.05025 21.2929 6.94975 20.1213 8.12132C18.9497 9.29289 17.0503 9.29289 15.8787 8.12132C14.7071 6.94975 14.7071 5.05025 15.8787 3.87868C17.0503 2.70711 18.9497 2.70711 20.1213 3.87868Z"
                            stroke="#8A8A8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    FeedBack <span class="badge bg-custom-user-page float-end rounded-pill"
                        style="width:25px">{{ isset($user) ? count($user->getCaseFeedbacks()->where('mark_read', 0)) : '0' }}</span>
                </a></li>
            
        @else
        <p class="m-0" style="font-size: 12px;">You have no activity</p>
        @endif
            @if ($user->head_office_admins->count() > 0)
                
                <li><a class="text-decoration-none @if (request()->route()->getName() == 'user.company') activeColor @endif"
                        href="{{ route('user.company') }}">
        
                        <svg width="20" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="#8A8A8A" d="M7.5 11H4.6C4.03995 11 3.75992 11 3.54601 11.109C3.35785 11.2049 3.20487 11.3578 3.10899 11.546C3 11.7599 3 12.0399 3 12.6V21M16.5 11H19.4C19.9601 11 20.2401 11 20.454 11.109C20.6422 11.2049 20.7951 11.3578 20.891 11.546C21 11.7599 21 12.0399 21 12.6V21M16.5 21V6.2C16.5 5.0799 16.5 4.51984 16.282 4.09202C16.0903 3.71569 15.7843 3.40973 15.408 3.21799C14.9802 3 14.4201 3 13.3 3H10.7C9.57989 3 9.01984 3 8.59202 3.21799C8.21569 3.40973 7.90973 3.71569 7.71799 4.09202C7.5 4.51984 7.5 5.0799 7.5 6.2V21M22 21H2M11 7H13M11 11H13M11 15H13" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>                    
                        Companies <span class="badge bg-custom-user-page float-end rounded-pill"
                            style="width:25px">{{ isset($user) ? count($user->head_office_admins) : '0' }}</span>
                    </a></li>
            @endif

    </ul>
</div>