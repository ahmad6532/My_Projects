body{
    font-family: "{{$branding->font}}" !important;
    background-color:transparent;
}
#content-wrapper{
    background-color:transparent !important;
}

.bg-gradient-primary{
    background-image: linear-gradient({{ $branding->gradient2 }}, {{ $branding->bg_color }}) !important;
}
.dropdown-header-gradient-primary{
    background-image: linear-gradient({{ $branding->gradient2 }}, {{ $branding->bg_color }}) !important;
}
.card-header-gradient-primary{
    background-image: linear-gradient({{ $branding->gradient2 }}, {{ $branding->bg_color }}) !important;
}

.text-info{
    color:{{ $branding->bg_color }}  !important;
}
.text-info:hover{
    color: {{ $branding->bg_hover }} !important;
}
.btn-info{
    background-color:  {{ $branding->bg_color }} !important;
}.bg-info{
     background-color:  {{ $branding->bg_color }} !important;
 }
.btn-info:hover{
    background-color: {{ $branding->bg_hover }} !important;
}
.btn-info:active{
    background-color:{{ $branding->bg_color }} !important;
}
.border-bottom-info{
    border-bottom: 0.25rem solid {{ $branding->bg_color }} !important;
}

.custom-control-input:checked ~ .custom-control-label::before {
    color: #fff;
    border-color: {{ $branding->bg_color }};
    background-color: {{ $branding->bg_color }};
}
.page-item.active .page-link{

    background-color:  {{ $branding->bg_color }};
    border:none;
}
.page-item.active .page-link:hover{
    background-color: {{ $branding->bg_hover }};
}
.dropdown-item:active{
    background-color: {{ $branding->bg_color }};
}
.btn-outline-info {
color: {{ $branding->bg_color }};
border-color:{{ $branding->bg_color }};
}

.btn-outline-info:hover {
color: #fff;
background-color: {{$branding->bg_color}};
border-color: {{ $branding->bg_color }};
}


/* Progress bar colors  */
#progressbar .active{
    color:{{ $branding->bg_color }}  !important;
}

#progressbar li.active:before,
#progressbar li.active:after{
    background-color: {{ $branding->bg_color }};
}
.progress-bar{
    background-color: {{ $branding->bg_color }};
}

.fish-bone-border-color {
    border-color:{{ $branding->bg_color }} !important;
}
{{-- .fa-trash
{
    color : {{ $branding->bg_color }} !important;
} --}}