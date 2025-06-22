<div class="users-seen-main">


    {{-- Single and Double Tick check --}}
    <div class="d-flex align-items-center justify-content-end">
        @if ($comment->is_all_seen())
            <button type="button" class="seen-btn">
                <svg width="30px" height="30px" viewBox="0 -0.5 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.03033 11.4697C4.73744 11.1768 4.26256 11.1768 3.96967 11.4697C3.67678 11.7626 3.67678 12.2374 3.96967 12.5303L5.03033 11.4697ZM8.5 16L7.96967 16.5303C8.26256 16.8232 8.73744 16.8232 9.03033 16.5303L8.5 16ZM17.0303 8.53033C17.3232 8.23744 17.3232 7.76256 17.0303 7.46967C16.7374 7.17678 16.2626 7.17678 15.9697 7.46967L17.0303 8.53033ZM9.03033 11.4697C8.73744 11.1768 8.26256 11.1768 7.96967 11.4697C7.67678 11.7626 7.67678 12.2374 7.96967 12.5303L9.03033 11.4697ZM12.5 16L11.9697 16.5303C12.2626 16.8232 12.7374 16.8232 13.0303 16.5303L12.5 16ZM21.0303 8.53033C21.3232 8.23744 21.3232 7.76256 21.0303 7.46967C20.7374 7.17678 20.2626 7.17678 19.9697 7.46967L21.0303 8.53033ZM3.96967 12.5303L7.96967 16.5303L9.03033 15.4697L5.03033 11.4697L3.96967 12.5303ZM9.03033 16.5303L17.0303 8.53033L15.9697 7.46967L7.96967 15.4697L9.03033 16.5303ZM7.96967 12.5303L11.9697 16.5303L13.0303 15.4697L9.03033 11.4697L7.96967 12.5303ZM13.0303 16.5303L21.0303 8.53033L19.9697 7.46967L11.9697 15.4697L13.0303 16.5303Z" fill="#000000"/>
                </svg>
            </button>
        @else
            <button type="button" class="seen-btn">
                <i style="pointer-events: none;" class="fa-solid fa-check"></i>
            </button>
        @endif
    </div>

    <div class="position-relative users-seen-inner" style="display: none;">
        {{-- <div class="user-seen-backdrop"></div> --}}
        <div class="position-absolute top-0 end-0 seen-card-wrapper shadow">

            <div class="d-flex align-items-center justify-content-between">
                <p style="font-size:13px;" class="mb-1">
                    <svg width="20px" height="20px" viewBox="0 -0.5 25 25" fill="#2BAFA5" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#2BAFA5" d="M5.03033 11.4697C4.73744 11.1768 4.26256 11.1768 3.96967 11.4697C3.67678 11.7626 3.67678 12.2374 3.96967 12.5303L5.03033 11.4697ZM8.5 16L7.96967 16.5303C8.26256 16.8232 8.73744 16.8232 9.03033 16.5303L8.5 16ZM17.0303 8.53033C17.3232 8.23744 17.3232 7.76256 17.0303 7.46967C16.7374 7.17678 16.2626 7.17678 15.9697 7.46967L17.0303 8.53033ZM9.03033 11.4697C8.73744 11.1768 8.26256 11.1768 7.96967 11.4697C7.67678 11.7626 7.67678 12.2374 7.96967 12.5303L9.03033 11.4697ZM12.5 16L11.9697 16.5303C12.2626 16.8232 12.7374 16.8232 13.0303 16.5303L12.5 16ZM21.0303 8.53033C21.3232 8.23744 21.3232 7.76256 21.0303 7.46967C20.7374 7.17678 20.2626 7.17678 19.9697 7.46967L21.0303 8.53033ZM3.96967 12.5303L7.96967 16.5303L9.03033 15.4697L5.03033 11.4697L3.96967 12.5303ZM9.03033 16.5303L17.0303 8.53033L15.9697 7.46967L7.96967 15.4697L9.03033 16.5303ZM7.96967 12.5303L11.9697 16.5303L13.0303 15.4697L9.03033 11.4697L7.96967 12.5303ZM13.0303 16.5303L21.0303 8.53033L19.9697 7.46967L11.9697 15.4697L13.0303 16.5303Z" fill="#000000"/>
                </svg>
                Seen by
                </p>

                @if (isset($route))
                    <a href="{{$route}}" class="unseen-btn">Unseen <i class="fa-solid fa-arrow-right"></i></a>
                @endif
            </div>

            <div class="user-inner">
                @foreach ($comment->views->where('is_seen',true) as $user_seen)
                <div class="user-icon-circle new-card-wrap">
                    
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span data-bs-toggle="tooltip" title="Viewed by {{$user_seen->head_office_user->user->name}}">
                                <img src="{{ $user_seen->head_office_user->user->logo }}" alt="{{$user_seen->head_office_user->user->name}}'s logo" class="user-logo" style="width: 30px; height: 30px; border-radius: 50%;">
                            </span>
                            <p class="m-0"> {{$user_seen->head_office_user->user->name}} </p>
                        </div>
                        

                        <p class="m-0">{{$user_seen->created_at->diffForHumans()}} {{$user_seen->created_at->format('h:i')}}</p>
                    </div>
                    @include('head_office.user_card_component',['user'=>$user_seen->head_office_user->user])
                </div>
                <hr class="my-1">
                @endforeach
            </div>
        </div>
    </div>

</div>
