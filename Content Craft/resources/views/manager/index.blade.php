@extends('layout.master')
@section('content')
    <div class="container">
        <div class="row d-flex justify-content-evenly ">

            <div class="col-3 ">
                <div
                    class="counter-box colored d-flex flex-column align-items-center bg-secondary w-75 m-auto counter-card pt-4 pb-4 ">
                    <i class="fa fa-solid fa-users icon-control"></i>
                    <span class="counter">{{ $totalUsers }}</span>
                    <p>Total Users</p>
                </div>
            </div>
            <div class="col-3 ">
                <div
                    class="counter-box colored d-flex flex-column align-items-center bg-secondary w-75 m-auto counter-card pt-4 pb-4 ">
                    <i class="fa-solid fa-sack-dollar icon-control"></i>
                    <span class="counter">{{ $transactions }}</span>
                    <p>Total Transactions</p>
                </div>
            </div>
            <div class="col-3 ">
                <div
                    class="counter-box colored d-flex flex-column align-items-center bg-secondary w-75 m-auto counter-card pt-4 pb-4 ">
                    <i class="fa-solid fa-bell icon-control"></i>
                    <span class="counter">{{ $notifications }}</span>
                    <p>Total Notifications</p>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-6 mt-3 ">
                <div class="container">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Month</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Total Earning</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (json_decode($mothlyAmount) as $item)
                                        <tr>
                                            <td>
                                                <p class="text-s mb-0">{{ $item->month }}</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-dot me-4">
                                                    <i class="bg-info"></i>
                                                    <span class="text-dark text-sm">{{ $item->amount }}</span>
                                                </span>
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-6">            
                    <canvas id="managerBarChart" data-data= "{{ $mothlyAmount }}">
                    </canvas>
            </div>
        </div>
    </div>
@endsection
