@extends('layouts.users_app')
@section('styles')
@endsection


@section('content')
    <div class="headingWithSearch flex-column ">

        <h3>Companies</h3>

        <section class="d-flex flex-wrap gap-4 mt-2">
            @foreach ($hos as $ho)
                <div class="card shadow-sm" style="min-width: 18rem;max-width: 25rem;border-radius:2rem;min-height: 210px;">
                    <div class="card-body d-flex flex-column align-items-center justify-center">
                        <img src="{{ $ho->headOffice->logo }}" alt="company_logo"
                            style="width: 150px;height:120px;object-fit:contain">
                        <h5 class="fw-normal">{{ $ho->headOffice->company_name }}</h5>
                        @if ($ho->headOffice->restricted)
                            <div class="external-wrapper rounded" style="text-align: left;background:#eaecee;padding-inline: 5px;padding-block:2px;">
                                <div style="font-size: 13px;" data-column='external-{{ $ho->headOffice->id }}' type="text"
                                    value="">{{ 'https://'.$ho->headOffice->link_token.'.qi-tech.co.uk' }}</div>
                                <button style="color: #D5D5D5;" title="click to copy text"
                                    onclick="copyFunction('external-{{ $ho->headOffice->id }}')"><i
                                        class="fa-regular fa-copy"></i></button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </section>
    </div>
@endsection



@section('sidebar')
    @include('layouts.user.sidebar-header')
@endsection
<script src="{{ asset('/js/alertify.min.js') }}"></script>

@section('scripts')
<script>
    function copyFunction(inputId) {
        var copyText = document.querySelector(`[data-column="${inputId}"]`);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        alertify.success('link copied!');
    }
</script>
@endsection