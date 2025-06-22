@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            @if (session('success'))
                <div class="alert alert_vt" id="alertID">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <div class="alert alert-success small " style="max-width:100%;">{{ session('success') }}</div>
                </div>
            @endif
            <div class="col-xl-12 mt-4">
                <div class="card-box border-1">
                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="header-title m-0 font-weight-bold pt-2">Device Management</h4>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pt-xs-15">
                            <div class="d-flex justify-content-end flex-direction">
                                <div class="mr-2 pt-xs-15">
                                    <a href="{{ route('add.device') }}"> <button class="page-btn">Add Device</button> </a>
                                </div>
                                <div class="col-lg-3 col-md-3 mb-2"
                                style="padding-left:2px !important;padding-right:2px !important;">
                                    <form method="get" action="{{route('leave.request')}}" id="dateSubmit">
                                        <div class="form-group position-relative caret-holder px-1">
                                            <select name="selectBranch" id="selectBranch"
                                            required class="form-control" style="appearance: none;">
                                            <option disable>Select Location</option>
                                            <option value="all" {{$selectedBranch == 'all'?'selected':''}}>All</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" {{$selectedBranch == $branch->id?'selected':''}}>{{ ucwords($branch->branch_name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                            <i class="awesom-icon icon-down-dir icon-color"></i>
                                        </div>
                                    </form>
                                </div>
                                {{-- <div class="position-relative search-icon_vt">
                                    <input type="text" class="form-control" onkeyup="searchData1()" name="search_input1"
                                        id="searchID1" value="" placeholder="Search By Name">
                                    <i class=" fontello icon-search"></i>
                                </div> --}}
                                <div class="position-relative search-icon_vt">
                                    <a onclick="changeTableView()" style="cursor:pointer;">
                                        <img class="mx-2" style="max-width: 35px;" id="table_view_icon" src="{{ asset('assets/images/table_view.png') }}" title="Table View">
                                        <img class="mx-2 d-none" style="max-width: 35px;" id="card_view_icon" src="{{ asset('assets/images/cards_view.png') }}" title="Card View">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-none" id="table_view">
                        <div class="table-responsive">
                            <table id="table2" class="table table-bordered table-striped table-nowrap table-hover table-centered table-atten-sheet m-0">

                                <thead class="table-head border-top border-bottom">
                                    <tr>
                                        <th>Sr</th>
                                        <th>Device Name</th>
                                        <th>Company Name</th>
                                        <th>Branch Name</th>
                                        <th>Device Type</th>
                                        <th>Device IP</th>
                                        <th>Serial Number</th>
                                        <th>Heartbeat</th>

                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="Listing_vt">
                                    @forelse ($tableDevices as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->device_name }}</td>
                                            <td>{{ $item->company_name }}</td>
                                            <td>{{ $item->branch_name }}</td>
                                            <td>{{ $item->type_name }}</td>
                                            <td>{{ $item->device_ip }}</td>
                                            <td>{{ $item->serial_number }}</td>
                                            <td>{{ $item->heartbeat }}</td>

                                            <td>
                                                <div class="btn-group dropdown-btn-group pull-right">
                                                    <button type="button" class="active-link_vt dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false">Action</button>
                                                    <ul class="dropdown-menu form-action-menu" style="">

                                                        <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                            <a href="{{ route('edit.device', ['id'=>$item->id]) }}"
                                                                class="action-content_vt"><label
                                                                    for="toggle-tech-companies-1-col-1"
                                                                    class="action_option">Edit</label></a>
                                                        </li>

                                                        <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                            <a href="{{ url('deleteCompany/' . $item->id) }}" id="del_user"
                                                                class="action-content_vt"><label
                                                                    for="toggle-tech-companies-1-col-2"
                                                                    class="action_option">Delete</label></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                    <tr class="text-center">
                                        <td colspan="9">No Record Found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="row mt-2 text-center">
                            {{ $tableDevices->links() }}
                        </div> --}}
                    </div>

                    <div  id="card_view">
                        <div class="table-responsive">
                            @forelse($devices as $device)
                                @php($id = base64_encode($device->id))
                                <div class="davice_devices_vt px-0">
                                    <div class="device_hed_vt">
                                        <div class="device_text_vt">
                                            <div class="flex_deives_icon ">
                                                <div class="heading_device_card" title="Device"
                                                    style="display:flex; align-items:center; margin-top: 3px;">
                                                    <div class="title_name_vt font-weight-bolder"
                                                        id="dev_name_{{ $device->id }}"
                                                        style="font-size: 15px;">
                                                        <span>{{ $device->device_name }} </span>
                                                        <a href="{{ route('edit.device', ['id'=>$device->id]) }}" title="Edit">
                                                            <img src="{{ asset('assets/images/edit_pen2.png') }}" class="pointer" style="width: 15px;" id="dev_id_{{ $item->id }}">
                                                        </a>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="device_img_vt">
                                            @if($device->is_active == '1')
                                                <?php $image = 'online_status.png'; ?>
                                            @else
                                                <?php $image = 'offline_status.png'; ?>
                                            @endif
                                            <img id="image_div_{{$device->id}}" src="{{ asset('assets/images/'.$image) }}"
                                                alt="">
                                        </div>
                                    </div>
                                    <div class="device_detail" style="padding:0 12px !important;margin-top:60px;">
                                        <div>
                                            <h5>
                                                <div class="mac_flex">
                                                    <div class="mac">
                                                        <span>Device Model</span>
                                                    </div>
                                                    <div class="mac">
                                                        <span>{{ $device->device_model?$device->device_model:'-' }}</span>
                                                    </div>
                                                </div>
                                            </h5>
                                        </div>
                                        <div>
                                            <h5>
                                                <div class="mac_flex">
                                                    <div class="mac">
                                                        <span>Serial Number</span>
                                                    </div>
                                                    <div class="mac">
                                                        <span>{{ $device->serial_number?$device->serial_number:'-' }}</span>
                                                    </div>
                                                </div>
                                            </h5>
                                        </div>
                                        <div>
                                            <h5>
                                                <div class="mac_flex">
                                                    <div class="mac">
                                                        <span>Device Type</span>
                                                    </div>
                                                    <div class="mac">
                                                        <span>{{ $device->type_name }}</span>
                                                    </div>
                                                </div>
                                            </h5>
                                        </div>
                                        <div>
                                            <h5>
                                                <div class="mac_flex">
                                                    <div class="mac">
                                                        <span>Enrolled on</span>
                                                    </div>
                                                    <div class="mac">
                                                        <span>{{date('d-m-Y',strtotime($device->created_at))}}</span>
                                                    </div>
                                                </div>
                                            </h5>
                                        </div>
                                        <div>
                                            <h5>
                                                <div class="mac_flex">
                                                    <div class="mac">
                                                        <span>Last Seen</span>
                                                    </div>
                                                    <div class="mac">
                                                        <span id="heartbeat_time_{{$device->id}}">{{date('d-m-Y, h:i A',strtotime($device->heartbeat_time))}}</span>
                                                    </div>
                                                </div>
                                            </h5>
                                        </div>
                                        <div>
                                            <h5>
                                                <div class="mac_flex">
                                                    <div class="mac">
                                                        <span>Device IP</span>
                                                    </div>
                                                    <div class="mac">
                                                        <span>{{ $device->device_ip }}</span>
                                                    </div>
                                                </div>
                                            </h5>
                                        </div>

                                        <div>
                                            <h5>
                                                <div class="mac_flex">
                                                    <div class="mac">
                                                        <span>Heartbeat</span>
                                                    </div>
                                                    <div class="mac">
                                                        <span>{{ $device->heartbeat }}s</span>
                                                    </div>
                                                </div>
                                            </h5>
                                        </div>
                                        <div>
                                            <h5>
                                                <div class="mac_flex">
                                                    <div class="mac">
                                                        <span>Total Records</span>
                                                    </div>
                                                    <div class="mac justify-content-between">
                                                        <span id="totalRecords_{{$device->id}}">{{$device->total_records?$device->total_records:'-'}}</span>
                                                        <i class="fa-solid fa-trash" style="color:red;" title="Delete Records"></i>
                                                    </div>
                                                </div>
                                            </h5>
                                        </div>
                                        <div>
                                            <h5>
                                                <div class="mac_flex">
                                                    <div class="mac">
                                                        <span>Total User Enrolled</span>
                                                    </div>
                                                    <div class="mac">
                                                        <span id="totalUsers_{{$device->id}}">{{$device->total_users?$device->total_users:'-'}}</span>
                                                    </div>
                                                </div>
                                            </h5>
                                        </div>
                                        <div>
                                            <h5>
                                                <div class="mac_flex">
                                                    <div class="mac">
                                                        <span>Warranty Expiry</span>
                                                    </div>
                                                    <div class="mac">
                                                        <span>{{date('d-m-Y',strtotime($device->expiry_date))}}</span>
                                                    </div>
                                                </div>
                                            </h5>
                                        </div>
                                        <div>
                                            <h5 style="padding-top:10px;border-top:1px solid #80808066 !important;">
                                                <div class="mac_flex justify-content-center">
                                                    {{-- <div class="mac">
                                                        <span>Action</span>
                                                    </div>
                                                    <div class="mac"> --}}
                                                        <div class="devices_icon">
                                                            <a href="javascript:void(0)">
                                                                <button onclick="restartDevice('{{$device->id}}')">
                                                                    <img src="{{ asset('assets/images/refresh_icon.png') }}"
                                                                        alt="" title="Restart">
                                                                </button>
                                                            </a>
                                                            <a href="javascript:void(0)" >
                                                                <button onclick="syncRecord('{{$device->id}}')">
                                                                    <img src="{{ asset('assets/images/sync.png') }}"
                                                                        alt="" title="Sync" style="width:18px !important;">
                                                                </button>
                                                            </a>
                                                            <a href="">
                                                                <button>
                                                                    <img src="{{ asset('assets/images/wipe_icon.png') }}"
                                                                        alt="" title="Wipe Device">
                                                                </button>
                                                            </a>
                                                            <a href="javascript:void(0)">
                                                                <button onclick="powerOff('{{$device->id}}')">
                                                                    <img src="{{ asset('assets/images/power-btn.png') }}"
                                                                        alt="" title="Power Off">
                                                                </button>
                                                            </a>
                                                    {{-- </div> --}}
                                                    </div>
                                                </div>
                                            </h5>
                                        </div>
                                        {{-- <a href="" >
                                            <button class="open_button_vt mt-0">
                                                View Details
                                            </button>
                                        </a> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- <div class="row mt-2 text-center">
                            {{ $devices->links() }}
                        </div> --}}
                    <div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
            $('#table2').DataTable({
                dom: '<"d-flex justify-content-between"lBf>rtip',
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search..."
                    },
                buttons: [{
                            extend: 'csvHtml5',
                            text: '<img src="' + "{{ asset('assets/images/csv.png') }}" + '" />',
                            exportOptions: {
                                columns: ':not(:last-child):not(:last-child-1)'
                            }
                        },
                        {
                            extend: 'print',
                            text: '<img src="' + "{{ asset('assets/images/print.png') }}" + '" />',
                            exportOptions: {
                                columns: ':not(:last-child):not(:last-child-1)'
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<img src="' + "{{ asset('assets/images/pdf.png') }}" + '" />',
                            exportOptions: {
                                columns: ':not(:last-child):not(:last-child-1)'
                            }
                        },
                    ]
            });
        });
        setTimeout(function() {
            $('#alertID').hide('slow')
        }, 3000);

        function changeTableView() {
            var tableView = $('#table_view');
            var cardView = $('#card_view');
            var searchDiv = $('#search_div');
            var tableViewIcon = $('#table_view_icon');
            var cardViewIcon = $('#card_view_icon');

            if (tableView.hasClass('d-none')) {
                tableView.removeClass('d-none');
                cardView.addClass('d-none');
                searchDiv.addClass('d-none');
                tableViewIcon.addClass('d-none');
                cardViewIcon.removeClass('d-none');
            } else {
                cardView.removeClass('d-none');
                tableView.addClass('d-none');
                searchDiv.removeClass('d-none')
                tableViewIcon.removeClass('d-none');
                cardViewIcon.addClass('d-none');
            }
        }

        function restartDevice(id){
            $.ajax({
                method: 'get',
                dataType: 'json',
                url: "{{url('restart')}}",
                data: {
                    id: id
                },
                success: function(response) {
                    console.log(response);
                    var message = response.message;
                    if(response.success == true){
                        alert(message);
                    }else{
                        alert(message);
                    }
                }
            });
        }

        function powerOff(id){
            $.ajax({
                method: 'get',
                dataType: 'json',
                url: "{{url('shutdown')}}",
                data: {
                    id: id
                },
                success: function(response) {
                    console.log(response);
                    var message = response.message;
                    if(response.success == true){
                        alert(message);
                    }else{
                        alert(message);
                    }
                }
            });
        }

        function syncRecord(id){
            var xhr = $.ajax({
                method: 'get',
                dataType: 'json',
                url: "{{url('syncCount')}}",
                data: {
                    id: id
                },
                success: function(response) {
                    if(response.success == true){
                        console.log(response);
                        var users = response.data.users;
                        var records = response.data.records;
                        var heartbeat_time = response.data.heartbeat_time;
                        var is_active = response.data.is_active;
                        var image = '';

                        $('#totalUsers_'+id).html(users);
                        $('#heartbeat_time_'+id).html(heartbeat_time);
                        $('#totalRecords_'+id).html(records);
                        if(is_active == '1'){
                            image = '{{asset('assets/images/online_status.png')}}';
                        }else{
                            image = '{{asset('assets/images/offline_status.png')}}';
                        }
                        $('#image_div_'+id).attr('src', image);

                    }
                }
            });
            // Set a timeout of 5 seconds
            setTimeout(function() {
                xhr.abort(); // Abort the request after 5 seconds
                $.ajax({
                    method: 'get',
                    dataType: 'json',
                    url: "{{url('updateDeviceStatus')}}",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        // Handle success or display an alert
                        if(response.success == true){
                            alert("Device not connected");
                        }
                    }
                });
            }, 5000); // 5000 milliseconds = 5 seconds
        }
    </script>
@endsection
