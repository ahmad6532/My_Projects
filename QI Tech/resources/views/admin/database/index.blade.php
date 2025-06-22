@extends('layouts.admin_app')

@section('title', 'database')

@section('content')

    @include('layouts.error')
    <div class="card">

        <div class="card-body">
        <div class="mb-3">

            <div class="float-left">
                <h4 class="text-info font-weight-bold">Database</h4>
            </div>
        </div>

        @if(count($databases) == 0)
                <h4 class="text-info text-center">No databases Available.</h4>
         @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Last Updated</th>
                            <th>Notes</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($databases as $database)
                        <tr>
                            <td>
                                {{ $database->name }}
                            </td>
                            <td>{{ $database->updated_at }}
                                <br>
                                {{optional($database->admin)->name}}
                            </td>
                            <td>
                            Notes</td>
                            <td>{{$database->percentage}} %</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-info dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                    </button>
                                    <div class="dropdown-menu animated--fade-in"
                                         aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#uploadModalLong_{{$database->id}}" title="Upload Database">
                                                Update Database
                                            </a>
                                        <a class="dropdown-item" href="#" title="Download Current Database">
                                            Download Current Database
                                        </a>

                                    </div>
                                </div>

                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="uploadModalLong_{{$database->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Database Form</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                        <form action="{{route('database.index')}}" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                            @csrf
                                            <div class="form-group">
                                                <label for="upload_database_{{$database->id}}">Upload Database</label>
                                                <div class="custom-file">
                                                    <label class="custom-file-label" for="upload_database_{{$database->id}}">Choose file</label>
                                                    <input type="file" class="custom-file-input" name="upload_database" id="upload_database_{{$database->id}}">
                                                </div>
                                            </div>
                                                    <input type="hidden" name="database_id" value="{{$database->id}}">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                        </form>
                                </div>
                            </div>
                        </div>


                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

{{--        <div class="card-footer"> --}}
{{--            {!! $headOffices->render() !!} --}}
{{--        </div> --}}
        
        @endif
    
    </div>
@endsection
@section('scripts')
    <script>
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
@endsection
