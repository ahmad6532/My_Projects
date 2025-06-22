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
                                    <div class="nav nav-pills navtab-bg nav-pills-tab text-center justify-content-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        <!-- <a class="nav-link mt-2 py-2 user-tab" id="custom-v-pills-billing-tab" href="{{route('user.management')}}" aria-selected="true" role="tab" >Users
                                        </a>
                                        <a class="nav-link active show mt-2 py-2 user-tab" id="custom-v-pills-shipping-tab" href="{{route('roles.list')}}" role="tab" aria-controls="custom-v-pills-shipping"
                                            aria-selected="false">Role & Permissions</a> -->

                                        @if(auth()->user()->role_id > 1)
                                            <a class="nav-link mt-2 py-2 user-tab" id="custom-v-pills-billing-tab" href="{{route('user.management')}}" role="tab" aria-controls="custom-v-pills-billing"
                                                aria-selected="true">Users
                                            </a>
                                            @if ($user->haspermission(['roles-&-permission-all','roles-&-permission-write']))
                                                <a class="nav-link active show mt-2 py-2 user-tab" href="{{route('roles.list')}}" role="tab" aria-controls="custom-v-pills-shipping"
                                                aria-selected="false">Role & Permissions</a>
                                            @endif
                                        @else
                                            <a class="nav-link mt-2 py-2 user-tab" id="custom-v-pills-billing-tab" href="{{route('user.management')}}" role="tab" aria-controls="custom-v-pills-billing"
                                                aria-selected="true">Users
                                            </a>
                                            <a class="nav-link active show mt-2 py-2 user-tab" href="{{route('roles.list')}}" role="tab" aria-controls="custom-v-pills-shipping"
                                            aria-selected="false">Role & Permissions</a>
                                        @endif
                                    </div>
                                </div> <!-- end col-->
                                <div class="col-lg-12">
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show" id="custom-v-pills-shipping" role="tabpanel" aria-labelledby="custom-v-pills-shipping-tab">
                                            <div class="row justify-content-center mt-2">
                                                <div class="col-lg-12">
                                                    <div class="roles-card-section border-1">
                                                        <div class="roles-header border-1">
                                                            <h4 class="header-title m-0 font-weight-bold">User Role Management</h4>
                                                            @if ($user->haspermission(['roles-&-permission-all','roles-&-permission-write']))
                                                            <a href="{{route("add.roles.permissions")}}"><Button type="submit" name="submit" class="page-btn">Add New Role</Button></a>
                                                            @endif
                                                        </div>
                                                        <div class="row mt-2">
                                                            @forelse ($roles as $role)
                                                            <div class="col-lg-3 mb-2">
                                                                <div class="card_vt">
                                                                    <div class="card-body_vt">
                                                                        <img src="{{ asset('assets/images/card-user.png') }}">
                                                                        <h1>{{$role->role_name}}</h1>
                                                                        <p>Read & Write</p>
                                                                    </div>
                                                                    @if ($user->haspermission(['roles-&-permission-all','roles-&-permission-write']))
                                                                    <div class="card-link_vt">
                                                                    <a href="{{route('edit.roles.permissions',['id'=>(json_encode($role->id))])}}">view</a>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @empty
                                                                <div class="row text-center">
                                                                    No Record
                                                                </div>
                                                            @endforelse
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
        </script>
        <script type="text/javascript">
    function searchData1() {
        var input = document.getElementById('searchID1').value;
        // alert(input);
        // alert('hello');
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
                        // console.log(response.data.user);
                    //    console.log(result[i].fullname);
                            var id=0;
                                for (var i = 0; i < result.length; i++){
                                    // alert(result[i].email);
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

