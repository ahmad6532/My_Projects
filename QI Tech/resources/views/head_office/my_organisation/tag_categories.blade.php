@extends('layouts.head_office_app')
@section('title', 'Tags')
@section('content')
    <div class="card card-qi">
        <div class="card-body">
            @include('layouts.error')
            <a href="#" data-toggle="modal" data-target="#add_category" class="btn btn-info float-right"><i class="fa fa-plus"></i> Add New</a>
            <h3 class="text-info h3 font-weight-bold">Tag Categories</h3>
            
            <div class="table-responsive">
                <table class="table table-bordered table-organisation-categories">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Tags <small class="text-muted">(Clicking on a tag will open its actions.)</small></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($categories))
                        @foreach($categories as $cat)
                        <tr>
                            <td>{{$cat->category_name}}</td>
                            <td>
                                @foreach($cat->tags as $tag)
                                <span class="btn tag organisation-tag" style="background:{{$tag->color}}">{{$tag->tag_name}}
                                <div class="action-bar card card-qi">
                                    <a href="#" data-toggle="modal" data-target="#locations_{{$tag->id}}" class="btn text-info"><i class="fa fa-tags"></i> Locations Tagged ({{count($tag->location_tags)}})</a>
                                    <a href="#" data-toggle="modal" data-target="#edit_tag_{{$tag->id}}" class="btn text-info"><i class="fa fa-edit"></i> Edit</a>
                                    <a data-msg="Are you sure to delete this tag? This will also delete all assignments of this tag. <br> Location Affected: {{count($tag->location_tags)}}" href="{{route('head_office.orginisation.delete_tag',[$cat->id,$tag->id,'_token'=>csrf_token()])}}" class="btn text-danger delete_button"><i class="fa fa-trash"></i> Delete</a>
                                </div>
                                </span>

                                <div class="modal fade" id="edit_tag_{{$tag->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                                                <form method="post" action="{{route('head_office.orginisation.save_tag',$cat->id)}}">
                                                    @csrf
                                                    <div class="form-group mb-3">
                                                        <label>Edit Tag</label>
                                                        <input type="hidden" name="tag_id" value="{{$tag->id}}">
                                                        <input type="text" name="tag_name" class="form-control" required value="{{$tag->tag_name}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Color: &nbsp;</label>
                                                        <input type="color" name="tag_color" class="" value="{{$tag->color}}" required>
                                                    </div>
                                                    <input type="submit" name="save" value="Save" class="btn btn-info">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="locations_{{$tag->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                                                 <h4 class="text-info">Locations Tagged</h4>
                                                 @if(count($tag->location_tags))
                                                 <ol>
                                                 @foreach($tag->location_tags as $location_tag)
                                                    <li>{{$location_tag->head_office_location->location->name()}}</li>
                                                 @endforeach    
                                                </ol>
                                                @else
                                                <p class="font-italic text-muted">No location are tagged.</p>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                                <a href="#" data-toggle="modal" data-target="#add_tag_{{$cat->id}}" class="btn btn-circle"><i class="fa fa-plus"></i></a>
                                <div class="modal fade" id="add_tag_{{$cat->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                                                <form method="post" action="{{route('head_office.orginisation.save_tag',$cat->id)}}">
                                                @csrf
                                                <h4 class="text-info">Add New Tag</h4>
                                                <div class="form-group input-group mb-3">
                                                    <label>New Tag Name</label>
                                                    <input type="text" name="tag_name" class="form-control w-50" required> <br>        
                                                </div>
                                                <div class="form-group">
                                                        <label>Color: &nbsp;</label>
                                                        <input type="color" name="tag_color" class="" value="#000000" required>
                                                </div>
                                                    <input type="submit" name="save" value="Save" class="btn btn-info mt-1">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                

                            </td>
                            <td>
                                <a href="#" data-toggle="modal" data-target="#edit_category_{{$cat->id}}" class="btn text-info"><i class="fa fa-edit"></i> Edit</a>
                                <a data-msg="Are you sure to delete this category? This will also delete all tags and their assignments of this category." href="{{route('head_office.tag_category_delete',['id'=>$cat->id,'_token'=>csrf_token()])}}" class="btn text-danger delete_button"><i class="fa fa-trash"></i> Delete</a>

                                <div class="modal fade" id="edit_category_{{$cat->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                                                <form method="post" action="{{route('head_office.tag_category_save')}}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$cat->id}}">
                                                    <div class="form-group input-group mb-3">
                                                        <label>Edit Category</label>
                                                        <input type="text" name="catgeory_name" class="form-control w-25" required value="{{$cat->category_name}}">
                                                        <div class="input-group-append">
                                                            <input type="submit" name="save" value="Save" class="btn btn-info">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="3" class="font-italic">No category found.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_category" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                    <form method="post" action="{{route('head_office.tag_category_save')}}">
                        @csrf
                        <div class="form-group input-group mb-3">
                            <label>Add New Category</label>
                            <input type="text" name="catgeory_name" class="form-control w-25" required>
                            <div class="input-group-append">
                                <input type="submit" name="save" value="Save" class="btn btn-info">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
  </div>


@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('scripts')
    <script src="{{asset('js/alertify.min.js')}}"></script>
@endsection

