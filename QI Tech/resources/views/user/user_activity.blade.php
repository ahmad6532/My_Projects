@extends('layouts.users_app')
@section('title', 'user activity')
@section('content')


    <div class="container-fluid">

        <div class="row justify-content-center ">
            <div class="col-md-12 mb-1">
                <div class="card vh-75 ">
                    <div class="card-body">
                        <h3 class="text-info mb-2 h3 font-weight-bold">My Activity</h3>


                            <p class="mb-4">Below is your recent activity </p>
                            <!-- DataTales Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-info">Recent Action</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Time</th>
                                                
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td colspan="3" style="text-align: center">Activity will appear once a case will be shared to you!</td>
                                                
                                            </tr>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection