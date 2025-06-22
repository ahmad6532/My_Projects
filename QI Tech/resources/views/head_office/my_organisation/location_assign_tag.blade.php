@extends('layouts.head_office_app')
@section('title', 'Assign tag to the location')
@section('sidebar')
@include('layouts.company.sidebar')
@endsection
@section('content')
<div id="content">
    <div class="card card-qi">
        <div class="card-body">
            @include('layouts.error')
            <h3 class="text-info h3 font-weight-bold inline">Assign tag to location</h3><br>
            <p><strong>Location: </strong> {{$head_office_location->location->name()}}</p>
            <form method="post" action="{{route('head_office.organisation.assign_tags.save',$head_office_location->id)}}">
                @csrf
                <input type="hidden" name="location_id" value="{{$head_office_location->location_id}}">
                @if(count($cats))
                <div class="form-buttons">
                    <div class="table-responsive">
                        <table class="table table-bordered table-organisation-categories">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Tags <small class="text-muted">(Clicking on a tag will select the tag.)</small></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($cats as $cat)
                                <tr class="category category_{{$cat->id}}">
                                    <td>{{$cat->category_name}}</td>
                                    <td class="tag-category">
                                    @foreach($cat->tags as $tag)
                                    <input type="checkbox"   name="tag_id[]" value="{{$tag->id}}" data-targets=".tags" class="hide tag tag_id_{{$tag->id}}" @if($tag->isAlreadyAssigned($head_office_location->id)) checked @endif>
                                        <button @if($cat->alreadyAssignToLocation($head_office_location->id) && !$tag->isAlreadyAssigned($head_office_location->id)) onclick="showWarningIfTagCategoryIsAlreadyAssigned(this,'{{$cat->category_name}}');" @endif type="button" data-multiple="true" data-value="{{$tag->id}}" data-target=".tag_id_{{$tag->id}}" class="tags @if($tag->isAlreadyAssigned($head_office_location->id)) active @endif  btn btn-outline btn-outline-info"><span class="tag-block" style="background:{{$tag->color}}">&nbsp;</span> {{$tag->tag_name}}</button>
                                    @endforeach
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                @endif
                <a href="{{route('head_office.my_organisation')}}" class="btn btn-assign-tag-back btn btn-secondary">Back</a>
                <input type="submit" name="save" value="Save" class="btn btn-info">
            </form>
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
