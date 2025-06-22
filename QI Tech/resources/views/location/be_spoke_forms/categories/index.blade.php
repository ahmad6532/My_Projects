@extends('layouts.location_app')
@section('title', 'Bespoke Form Categories')
@section('content')
<div class="container-fluid">
    <div class="card">
        
    @include('layouts.error')
        <div class="card-body">
            @if(request()->query('success'))
            <div class="alert to_hide_10 alert-success w-50" style="margin:0 auto">
                {{request()->query('success')}}
                <i class="right to_hide_to_manual fa fa-times" onclick="$('.to_hide_10').hide()"></i>
            </div>
            @endif
            @if(request()->query('error'))
            <div class="alert to_hide_10 alert-danger w-50" style="margin:0 auto">
                {{request()->query('error')}}
                <i class="right to_hide_to_manual fa fa-times" onclick="$('.to_hide_10').hide()"></i>
            </div>
            @endif

            <div class="mb-3">
                <div class="float-left">
                    <h4 class="text-info font-weight-bold">Bespoke Forms Categories</h4>
                </div>
                @if(Auth::guard('location')->user()->userCanUpdateSettings())
                <div class="btn-group btn-group-sm float-right" role="group">
                    <a href="{{route('location.be_spoke_form_category.create')}}" class="btn btn-info"
                        title="Create New Be Spoke Form Category">
                        <span class="fas fa-plus" aria-hidden="true"></span>
                    </a>
                </div>
                @endif
            </div>

            @if(!$categories)
            <h4 class="text-info text-center">No Forms Available.</h4>
            @else
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{$category->name}}</td>
                            <td>
                                <form method="POST" action="{!! route('be_spoke_form_categories.be_spoke_form_category.delete', $category->id) !!}" accept-charset="UTF-8">
                                    <input name="_method" value="DELETE" type="hidden">
                                    @csrf
                                <a class="btn btn-info btn-sm"
                                    href="{{route('be_spoke_form_categories.be_spoke_form_category.create', $category->id)}}">Edit</a>
                                |
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete Product Category" onclick="return confirm(&quot;Click Ok to delete Product Category.&quot;)">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        {{-- <div class="card-footer"> --}}
            {{-- {!! $headOffices->render() !!} --}}
            {{-- </div> --}}

        @endif

    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('css/alertify.min.css')}}">
@endsection

@section('scripts')
<script src="{{asset('js/alertify.min.js')}}"></script>
@include('location.be_spoke_forms.script')
@endsection