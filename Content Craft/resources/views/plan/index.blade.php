@extends('layout.master')
@section('content')
    <div class="container">
        <h1 class="mt-5 mb-4 text-center">Choose Your Plan</h1>
        <div class="row">
            @php
                $count = 1;
            @endphp
            @foreach ($plans as $plan)
                <div class="col-md-3">
                    <div class="card">

                        <div class="card-header bg-primary text-white ">
                            @php
                                echo 'Plan ' . $count;
                                $count++;
                            @endphp
                        </div>
                        <div class="card-body d-flex flex-column ">
                            <h5 class="card-title text-center plan-heading">{{ $plan->name }}</h5>
                            <ul class="list-group list-group-flush mb-3 ">
                                <li class="list-group-item">{{ $plan->articles }} Articles</li>
                                <li class="list-group-item">In {{ $plan->amount }} Rupees</li>
                            </ul>
                            <p class="card-text">This Plan is perfect for you and will fulfill your requirments.
                            </p>
                            @if ($planHistory != null && $plan->name === 'Free Plan')
                                <span class="btn utilized-btn ">Utilized</span>
                            @else
                                <a href="{{ route('plan.show', $plan->planId) }}" class="btn btn-primary">Choose Plan</a>
                            @endif
                        </div>


                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
