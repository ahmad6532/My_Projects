<style>
    .company_name_input{
        border: none;
        text-align: center;
        font-weight: 600;
    }
    .company_name_input:hover{
        border: 1px dotted #b2a7a7;
    }
</style>
<nav class='page-menu bordered'>
    <ul class="nav nav-tab">
        
        {{-- <li ><a id="companyInfoClick" onclick="changeTabUrl('infoClick','companyInfoClick')" data-bs-toggle="tab" data-bs-target="#company_info" class="company_info active" href="javascript:void(0)">Company Info</a></li> --}}
        {{-- <li><a id="depInfoClick" onclick="changeTabUrl('infoClick','depInfoClick')" data-bs-toggle="tab" data-bs-target="#department_info" class="department_info" href="javascript:void(0)">Departments</a></li> --}}
        {{-- <li><a id="veriDeviceClick" onclick="changeTabUrl('infoClick','veriDeviceClick')" data-bs-toggle="tab" data-bs-target="#verified_devices" class="verified_devices" href="javascript:void(0)">Verified Devices</a></li> --}}
        {{-- <li><a id="themeClick" onclick="changeTabUrl('infoClick','themeClick')" data-bs-toggle="tab" data-bs-target="#themes" class="themes" href="javascript:void(0)">Themes</a></li> --}}
    </ul>
</nav>
<div class="text-center">
    {{-- <div class="profile-pic">
        <label class="-label" for="file">
            <span class="glyphicon glyphicon-camera"></span>
            <span>Change Image</span>
        </label>
        <input id="file" type="file" onchange="loadFile(event)" />
        <img src="{{$head_office->logo}}" id="output" width="200" />
    </div> --}}
    {{-- <div class="profile-user-name-heading"></div> --}}
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

@section('scripts')
<script>
    $(document).ready(function() {
    // changeTabUrl('infoClick','companyInfoClick');
});
</script>
@endsection