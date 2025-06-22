@extends('layout.master')
@section('content')
<div class="container w-100  d-flex justify-content-center mt-3">
    <div class="col-md-6">
            <div class="d-flex justify-content-end mb-2">
                <a href="{{ route('plan.receipt', auth()->id()) }}" class="btn btn-warning fw-bolder  " >View Receipt</a>
            </div>
            <div class="card">
                <div class="card-header bg-success text-white ">
                    <h4>Your Plan</h4>
                </div>
                <div class="card-body d-flex flex-column ">
                    @if ($userPlanData == null)
                        <h5 class="card-title text-center plan-heading">You have no Purchased Plan</h5>
                    @else
                        <h5 class="card-title text-center plan-heading">{{ $planData->name }}</h5>
                        <ul class="list-group list-group-flush mb-3 ">
                            <li class="list-group-item text-danger  ">{{ $userPlanData->articles }} Articles Remaining</li>
                            <li class="list-group-item">You Paid {{ $planData->amount }} Rupees</li>
                        </ul>
                        <p class="card-text">You can't create more articles after remaining 0 articles, then you will
                            purchase new package.
                        </p>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
