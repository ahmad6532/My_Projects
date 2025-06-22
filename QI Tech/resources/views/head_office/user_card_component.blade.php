<style>
    .new-info-wrapper {

        position: absolute;
        top: 30px;
        right: 60%;
        z-index: 1;
        background-color: white;
        display: flex;
        align-items: center;
        width: 350px;
        padding: 1rem;
        box-shadow: 0px 0px 10px -1px #bbb;
        border-radius: 1.5rem;
        gap: 1rem;
        height: 152px;
        display: none;
        right: 101%;
        top: 0;
        width: 370px;
        flex-direction: column;
        height: auto;
        left: 5%;
    }

    .new-info-wrapper img {
        width: 105px;
        aspect-ratio: 1 / 1;
        border-radius: 50%;
        object-fit: cover;
    }

    .user-info-details {
        text-align: left;
    }

    .new-user-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .expirtise-wrap {
        width: 100%;
    }
</style>
@php
    if($user->getTable() == 'head_office_users'){
        $user = $user->user;
    }
@endphp
<div class="card new-card-wrap" style="border: none;">
<div class=" new-info-wrapper" style="z-index: 15">
    <div class="new-user-wrapper">
        <div class="position-relative">
            @if (isset($user->logo))
                <img src="{{ $user->logo }}" alt="png_img">
            @else
                <div class="user-img-placeholder" id="user-img-place">
                    {{ implode('', array_map(function ($word) {
                        return strtoupper(mb_substr($word, 0, 1));
                    }, array_filter(explode(' ', $user->name)))) }}
                </div>
                @endif
                @php
                    $head_office_user = $user->getHeadOfficeUser();
                @endphp
                @if ($head_office_user->is_active == true)
                <div class="user_status {{ ($head_office_user->work_status == 1 || $head_office_user->work_status == 3) || $head_office_user->do_not_disturb == 1 ? 'grey' : '' }}" style="width: 12px;height: 12px;border-radius: 50%;background-color: #10b70b;position: absolute;right: 5px;bottom: 10%;">
                    @if ($head_office_user->work_status == 0)
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.5 7H14.5M9.5 11H14.5M9.5 15H14.5M18 21V6.2C18 5.0799 18 4.51984 17.782 4.09202C17.5903 3.71569 17.2843 3.40973 16.908 3.21799C16.4802 3 15.9201 3 14.8 3H9.2C8.0799 3 7.51984 3 7.09202 3.21799C6.71569 3.40973 6.40973 3.71569 6.21799 4.09202C6 4.51984 6 5.0799 6 6.2V21M20 21H4" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @elseif($head_office_user->work_status == 1)
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.25 21.9595L12 12.0002M17 3.33995C12.6868 0.849735 7.28964 1.93783 4.246 5.68314C3.94893 6.0487 3.80039 6.23148 3.75718 6.49336C3.7228 6.70172 3.77373 6.97785 3.88018 7.16024C4.01398 7.38947 4.25111 7.52638 4.72539 7.8002L19.2746 16.2002C19.7489 16.474 19.986 16.6109 20.2514 16.6122C20.4626 16.6132 20.7272 16.5192 20.8905 16.3853C21.0957 16.2169 21.1797 15.9969 21.3477 15.5568C23.0695 11.0483 21.3132 5.83017 17 3.33995ZM17 3.33995C15.0868 2.23538 11.2973 5.21728 8.5359 10.0002M17 3.33995C18.9132 4.44452 18.2255 9.21728 15.4641 14.0002M22 22.0002H2" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @elseif ($head_office_user->work_status == 2)
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 21V13.6C9 13.0399 9 12.7599 9.109 12.546C9.20487 12.3578 9.35785 12.2049 9.54601 12.109C9.75993 12 10.04 12 10.6 12H13.4C13.9601 12 14.2401 12 14.454 12.109C14.6422 12.2049 14.7951 12.3578 14.891 12.546C15 12.7599 15 13.0399 15 13.6V21M2 9.5L11.04 2.72C11.3843 2.46181 11.5564 2.33271 11.7454 2.28294C11.9123 2.23902 12.0877 2.23902 12.2546 2.28295C12.4436 2.33271 12.6157 2.46181 12.96 2.72L22 9.5M4 8V17.8C4 18.9201 4 19.4802 4.21799 19.908C4.40974 20.2843 4.7157 20.5903 5.09202 20.782C5.51985 21 6.0799 21 7.2 21H16.8C17.9201 21 18.4802 21 18.908 20.782C19.2843 20.5903 19.5903 20.2843 19.782 19.908C20 19.4802 20 18.9201 20 17.8V8L13.92 3.44C13.2315 2.92361 12.8872 2.66542 12.5091 2.56589C12.1754 2.47804 11.8246 2.47804 11.4909 2.56589C11.1128 2.66542 10.7685 2.92361 10.08 3.44L4 8Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
    
                    @elseif ($head_office_user->work_status == 3)
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 4.6C15 4.03995 15 3.75992 14.891 3.54601C14.7951 3.35785 14.6422 3.20487 14.454 3.10899C14.2401 3 13.9601 3 13.4 3H10.6C10.0399 3 9.75992 3 9.54601 3.10899C9.35785 3.20487 9.20487 3.35785 9.10899 3.54601C9 3.75992 9 4.03995 9 4.6V7.4C9 7.96005 9 8.24008 8.89101 8.45399C8.79513 8.64215 8.64215 8.79513 8.45399 8.89101C8.24008 9 7.96005 9 7.4 9H4.6C4.03995 9 3.75992 9 3.54601 9.10899C3.35785 9.20487 3.20487 9.35785 3.10899 9.54601C3 9.75992 3 10.0399 3 10.6V13.4C3 13.9601 3 14.2401 3.10899 14.454C3.20487 14.6422 3.35785 14.7951 3.54601 14.891C3.75992 15 4.03995 15 4.6 15H7.4C7.96005 15 8.24008 15 8.45399 15.109C8.64215 15.2049 8.79513 15.3578 8.89101 15.546C9 15.7599 9 16.0399 9 16.6V19.4C9 19.9601 9 20.2401 9.10899 20.454C9.20487 20.6422 9.35785 20.7951 9.54601 20.891C9.75992 21 10.0399 21 10.6 21H13.4C13.9601 21 14.2401 21 14.454 20.891C14.6422 20.7951 14.7951 20.6422 14.891 20.454C15 20.2401 15 19.9601 15 19.4V16.6C15 16.0399 15 15.7599 15.109 15.546C15.2049 15.3578 15.3578 15.2049 15.546 15.109C15.7599 15 16.0399 15 16.6 15H19.4C19.9601 15 20.2401 15 20.454 14.891C20.6422 14.7951 20.7951 14.6422 20.891 14.454C21 14.2401 21 13.9601 21 13.4V10.6C21 10.0399 21 9.75992 20.891 9.54601C20.7951 9.35785 20.6422 9.20487 20.454 9.10899C20.2401 9 19.9601 9 19.4 9L16.6 9C16.0399 9 15.7599 9 15.546 8.89101C15.3578 8.79513 15.2049 8.64215 15.109 8.45399C15 8.24008 15 7.96005 15 7.4V4.6Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
    
    
                    @elseif ($head_office_user->work_status == 4)
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.5 19C4.01472 19 2 16.9853 2 14.5C2 12.1564 3.79151 10.2313 6.07974 10.0194C6.54781 7.17213 9.02024 5 12 5C14.9798 5 17.4522 7.17213 17.9203 10.0194C20.2085 10.2313 22 12.1564 22 14.5C22 16.9853 19.9853 19 17.5 19C13.1102 19 10.3433 19 6.5 19Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    @elseif ($head_office_user->work_status == 5)
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 21C20 19.6044 20 18.9067 19.8278 18.3389C19.44 17.0605 18.4395 16.06 17.1611 15.6722C16.5933 15.5 15.8956 15.5 14.5 15.5H9.5C8.10444 15.5 7.40665 15.5 6.83886 15.6722C5.56045 16.06 4.56004 17.0605 4.17224 18.3389C4 18.9067 4 19.6044 4 21M16.5 7.5C16.5 9.98528 14.4853 12 12 12C9.51472 12 7.5 9.98528 7.5 7.5C7.5 5.01472 9.51472 3 12 3C14.4853 3 16.5 5.01472 16.5 7.5Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
    
                    @endif
                </div>
                
            @endif

            @if ($head_office_user->do_not_disturb == true)
            <div class="user_status do_not " style="width: 12px;height: 12px;border-radius: 50%;background-color: rgb(227,74,82);position: absolute;left: 5px;bottom: 10%;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 12H19" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
    
            </div>
            @endif
        </div>

        <div class="user-info-details">
            <h5 class="user-info-name">{{ $user->name }}</h5>
            <h6>{{ $user->getHeadOfficeUser() ? $user->getHeadOfficeUser()->position:'Position not available'}}</h6>

            <!-- Phone Number feild 💔 -->
            @if (!$user->is_phone_hidden)
            <p class="m-0 d-flex align-items-center gap-1">
                <i class="fa-solid fa-phone"></i> 
                {{ optional(optional($user->getHeadOfficeUser())->user)->mobile_no }}
            </p>
            @endif
        
            <!-- Email feild 😭 -->
            @if(!$user->is_email_hidden)
            <div class="email-field">
                {{-- <label for="email">Email:</label> --}}
                <p style="display: inline; word-break: break-all; line-height: 1;">
                    <strong>Email:</strong> {{ $user->email }}
                  </p>
            </div>
            @endif

           
        </div>
    </div>
    <div class="expirtise-wrap" style="display: none;">
        <hr class="w-100" style="margin-block: 5px!important;">

        <p class="m-0 d-flex align-items-center gap-1">
            {{ !empty($user->getHeadOfficeUser()->about_me) ? $user->getHeadOfficeUser()->about_me : '' }}
        </p>
        @if ($user->getHeadOfficeUser() && count($user->getHeadOfficeUser()->head_office_user_area) !=0)
            <p class="m-0" style="color: #999;font-size:14px;">Expertise</p>
            <div>
                @foreach ($user->getHeadOfficeUser()->head_office_user_area as $area)
                    <p class="m-0">Area: <strong>{{$area->area}}</strong></p>
                    <p class="m-0" style="margin-bottom: 5px;">Level: <strong>{{$area->level}}</strong></p>
                @endforeach
            </div>
            @else
            @endif
            
    </div>
        <button class="btn btn-outline-info view-info-btn" type="button" style="border: 0 !important;">View info</button>
</div>
</div>
