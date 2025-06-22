

<div style="position:relative;margin-right:1rem;">
    {{-- <button class="sidebar-btn"><img id="side-img" src="{{asset('images/chevron-left-double.svg')}}" alt="icon"></button> --}}
<nav id="sidebar" class="sidebar" style="width: 250px; height: 100vh; background-color: #f5f5f500; border-left: 3px solid black;">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb justify-content-center">
            <li class="breadcrumb-item"><a href="{{ route('head_office.be_spoke_form.index') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('head_office.be_spoke_form.index') }}">Forms</a></li>
            @if (isset($near_miss))
                <li class="breadcrumb-item active" aria-current="page">{{ $near_miss->name }}</li>
            @endif
        </ol>
    </nav>
    <div style="min-width: 250px;border-right: 2px solid #dddcdf;background:white;z-index:1;position:relative;height:100%;padding-right:2rem !important;"
        class="px-3 me-2">
        {{-- <ul class="case-nav nav nav-tabs d-flex flex-column " id="myTab" role="tablist">
            <li class="nav-item " role="presentation">
                <button class="nav-link active d-flex align-items-center justify-content-between w-100"
                    id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button"
                    role="tab" aria-controls="home-tab-pane" aria-selected="true">Form Settings <i
                        class="fa-solid fa-chevron-right" style="font-size: 13px;"></i></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link d-flex align-items-center justify-content-between w-100" id="profile-tab"
                    data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab"
                    aria-controls="profile-tab-pane" aria-selected="false">Case Settings <i
                        class="fa-solid fa-chevron-right" style="font-size: 13px;"></i></button>
            </li>
        </ul> --}}
    </div>
</nav>
</div>

