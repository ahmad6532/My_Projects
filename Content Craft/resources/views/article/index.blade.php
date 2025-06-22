@extends('layout.master')
@section('content')
    <div class="container">
        <div class="row mt-2">
            <div class="d-flex justify-content-end w-100">
                <button class="btn btn-success" id="articleCreateBtn">Create Article</button>
            </div>
        </div>
        <div class="row">
            <div class="d-flex">
                <a href="{{ route('article.index') }}" id="myArticlesActiveBtn" class="nav-link article-head-btn">
                    My Article
                </a>

                <span id="showAllArticlesBtn" data-url="{{ route('article.index') }}?showAll"
                    class="nav-link article-head-btn">
                    All Articles
                </span>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Articles</div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>    
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
