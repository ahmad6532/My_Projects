@extends('layouts.admin.master')
@section('content')
        <!-- Start Content-->
        <div class="container-fluid">
            @if(session("success"))
                <div class="alert alert_vt" id="alertID">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <div class="alert alert-success small " style="max-width:100%;">{{ session('success')}}</div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12 px-0">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    @php $user = auth()->user(); @endphp
                                    <div class="nav nav-pills navtab-bg nav-pills-tab text-center justify-content-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        @if($user->role_id > 1)
                                            <a class="nav-link active show mt-2 py-2 user-tab" id="custom-v-pills-billing-tab" href="{{url('user-management')}}" role="tab" aria-controls="custom-v-pills-billing"
                                                aria-selected="true">Users
                                            </a>
                                            @if ($user->haspermission(['roles-&-permission-all','roles-&-permission-write']))
                                                <a class="nav-link mt-2 py-2 user-tab" href="{{route('roles.list')}}" role="tab" aria-controls="custom-v-pills-shipping"
                                                aria-selected="false">Role & Permissions</a>
                                            @endif
                                        @else
                                            <a class="nav-link active show mt-2 py-2 user-tab" id="custom-v-pills-billing-tab" href="{{url('user-management')}}" role="tab" aria-controls="custom-v-pills-billing"
                                                aria-selected="true">Users
                                            </a>
                                            <a class="nav-link mt-2 py-2 user-tab" href="{{route('roles.list')}}" role="tab" aria-controls="custom-v-pills-shipping"
                                            aria-selected="false">Role & Permissions</a>
                                        @endif
                                    </div>
                                </div> <!-- end col-->
                                <div class="col-lg-12">
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show" id="custom-v-pills-billing" role="tabpanel" aria-labelledby="custom-v-pills-billing-tab">
                                            <div class="row">
                                                <div class="col-xl-12 mt-4">
                                                    <div class="card-box border-1">
                                                        <div class="row mb-3">
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <h4 class="header-title m-0 font-weight-bold pt-2">User List</h4>
                                                            </div>
                                                            {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pt-xs-15">
                                                                <div class="d-flex justify-content-end flex-direction">
                                                                    <div class="mr-2 pt-xs-15">
                                                                    @if ($user->haspermission(['user-management-all','user-management-write']))
                                                                    <a href="{{url('/add-user')}}">  <button class="page-btn">Add New User</button> </a>
                                                                    @endif
                                                                    </div>
                                                                    <div class="position-relative search-icon_vt">
                                                                        <input type="text" class="form-control" onkeyup="searchData1()" name="search_input1" id="searchID1" value="" placeholder="Search User By Name">
                                                                        <i class=" fontello icon-search"></i>
                                                                    </div>
                                                                </div>
                                                            </div> --}}
                                                        </div>

                                                        <div class="table-responsive">
                                                            <table id="table1" class="table table-bordered table-striped table-nowrap table-hover table-centered m-0">

                                                                <thead class="table-head border-top border-bottom">
                                                                    <tr>
                                                                        <th>Sr</th>
                                                                        <th>User Name</th>
                                                                        <th>Email Address</th>
                                                                        <th>User Role</th>
                                                                        <th>Office Location</th>
                                                                        <th>User Status</th>
                                                                        @if ($user->haspermission(['user-management-all','user-management-write','user-management-delete']))
                                                                        <th></th>
                                                                        @endif
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="Listing_vt">
                                                                    @foreach ($users as $key => $item)
                                                                    <tr>
                                                                        <td> {{$key + 1}} </td>
                                                                        <td> {{ mb_convert_case($item->fullname, MB_CASE_TITLE, 'UTF-8') }} </td>
                                                                        <td> {{$item->email}} </td>
                                                                        <td> {{ ($item->role != null?$item->role->role_name:'') }}</td>
                                                                        <td>
                                                                            {{$item->branch_names}}
                                                                        </td>
                                                                        <td>
                                                                            @if($item->is_active == '1')
                                                                                <i class="fa-solid fa-circle-dot text-success"></i> Active
                                                                            @else
                                                                                <i class="fa-solid fa-circle-dot text-danger"></i> Inactive
                                                                            @endif
                                                                        </td>
                                                                        @if ($user->haspermission(['user-management-all','user-management-write','user-management-delete']))
                                                                        <td>
                                                                            <div class="btn-group dropdown-btn-group pull-right">
                                                                                <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action <i class="fontello icon-down-dir icon-color" style="color:#ffffff;"></i></button>
                                                                                <ul class="dropdown-menu form-action-menu" style="">
                                                                                    @if ($user->haspermission(['user-management-all','user-management-write']))
                                                                                    <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                                                    <a href="{{url('edit-user/'.$item->id)}}" class="action-content_vt"><label for="toggle-tech-companies-1-col-1" class="action_option">Edit</label></a>
                                                                                        <!-- <label for="toggle-tech-companies-1-col-1" class="action_option">Edit</label> -->
                                                                                    </li>
                                                                                    @endif
                                                                                    @if ($user->haspermission(['user-management-all','user-management-delete']))
                                                                                    <li class="checkbox-row pt-1 pl-1 hover-option_vt">
                                                                                    <a onclick="delUser('{{url('delete-user/'.$item->id)}}')" id="del_user"  class="action-content_vt"><label for="toggle-tech-companies-1-col-2" class="action_option">Delete</label></a>
                                                                                        <!-- <label for="toggle-tech-companies-1-col-2" class="action_option">Delete</label> -->
                                                                                    </li>
                                                                                    @endif
                                                                                </ul>
                                                                            </div>
                                                                        </td>
                                                                        @endif
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end col-->
                            </div> <!-- end row-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
    <script type="text/javascript">
       $(document).ready(function() {
                var customButton =`
                <div class="col-lg-2 col-md-2 px-1 mb-2">
                <div class="d-flex justify-content-center">
                    <div>
                        @if ($user->haspermission(['user-management-all','user-management-write']))
                        <a href="{{url('/add-user')}}">  <button class="page-btn">Add New User</button> </a>
                        @endif
                    </div>
                </div>
            </div>
                `;
                $('#table1').DataTable({
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
                $('#table1_filter').before(customButton);
            });
        function delUser(url){
            Swal.fire({
              title: 'Delete',
              text: 'Are you sure you want to delete? ',
              iconHtml: '<img src="{{asset('assets/images/delete-alert.png')}}">',
                  // showDenyButton: true,
                  showCancelButton: true,
                  // denyButtonText: `Cancelss`,
              reverseButtons: true,
              confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href=url;
                }
            })
        }
        function delUsers(id){
            var new_url = '{{url('delete-user/'."id")}}';
              new_url = new_url.replace('id', id);
                  Swal.fire({
                    title: 'Delete',
                    text: 'Are you sure you want to delete? ',
                    iconHtml: '<img src="{{asset('assets/images/delete-alert.png')}}">',
                        // showDenyButton: true,
                        showCancelButton: true,
                        // denyButtonText: `Cancelss`,
                    reverseButtons: true,
                    confirmButtonText: 'Delete',
                  }).then((result) => {
                      if (result.isConfirmed) {
                          window.location.href=new_url;
                      }
                  })
              }

    function searchData1() {
        var input = document.getElementById('searchID1').value;
        let type = '';
            $.ajax({
                url: '{{ route('user.search') }}',
                type: 'get',
                data: {
                    'user_name' : input,
                },
                dataType: 'json',
                success:function(response){
                    if(response['success']==true){
                        $("#table2 tbody").empty();
                            var result=response.data.user;
                            var id=0;
                                for (var i = 0; i < result.length; i++){
                                var input = '';
                                input+='<tr>';
                                input+=' <td>'+[id+1]+'</td>';
                                input+=' <td>'+result[i].fullname+'</td>';
                                input+=' <td>'+result[i].email+'</td>';
                                    input+=' <td>'+result[i].role_name+'</td>';
                                    if(result[i].is_active== '1'){
                                    input+='<td> <i class="fontello icon-circle font-circle green"></i>Active</td>';
                                    }else{
                                    input+='<td> <i class="fontello icon-circle font-circle red"></i>Inactive</td>';
                                    }
                                    var url = '{{url('edit-user/'."id")}}';
                                    url = url.replace('id', result[i].id);

                                input+='<td> <div class="btn-group dropdown-btn-group pull-right"> <button type="button" class="active-link_vt dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action  </button> <ul class="dropdown-menu form-action-menu" style=""> <li class="checkbox-row  hover-option_vt"> <a href="'+url+'" class="action-content_vt"><label for="toggle-tech-companies-1-col-1" class="action_option">Edit</label></a>  </li> <li class="checkbox-row hover-option_vt"> <a onclick="delUsers('+result[i].id+')" class="action-content_vt" ><label for="toggle-tech-companies-1-col-2" class="action_option">Delete</label></a> </li> </ul> </div> </td></tr>';
                                input+='</tr>';
                                $("#table2").append(input);

                            }
                                }
                                else
                                {

                                      $('#table2 tbody').empty()
                                      let message = response.data;
                                      let input='<tr>';
                                      input+='<td colspan="7">'+[message]+'</td>';
                                      input+='</tr>';
                                       $('#table2 tbody').append(input);
                    }
                }
            });
         }
         setTimeout(function(){
        $('#alertID').hide('slow')
        }, 3000);
</script>
@endsection

