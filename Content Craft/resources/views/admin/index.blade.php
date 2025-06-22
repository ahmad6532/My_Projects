@extends('layout.master')
@section('content')
    <div class="container">
        <div class="row d-flex justify-content-evenly ">

            <div class="col-3 ">
                <div
                    class="counter-box colored d-flex flex-column align-items-center bg-secondary w-75 m-auto counter-card pt-4 pb-4 ">
                    <i class="fa-solid fa-people-roof icon-control"></i>
                    <span class="counter">{{ $totalManagers }}</span>
                    <p>Total Managers</p>
                </div>
            </div>
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
                                            Managers</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Total Earning</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (json_decode($amountAndManager) as $item)
                                        <tr>
                                            <td>
                                                <p class="text-s mb-0">{{ $item->manager }}</p>
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
                <div id="chart-container">
                    <canvas id="bar-chart" data-data= "{{ $amountAndManager }}">
                    </canvas>
                </div>
                <div class="chart-dropdown">
                    <select name="earningDate" data-url={{ route('admin.chart') }} id="earningDate">
                        <option value="ALL">All</option>
                        <option value="10">Oct 2022</option>
                        <option value="11">Nov 2022</option>
                        <option value="12">Dec 2022</option>
                    </select>
                </div>

            </div>
        </div>
    </div>
@endsection
