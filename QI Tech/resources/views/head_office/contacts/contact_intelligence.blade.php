@extends('layouts.head_office_app')
@section('title', 'Head office Settings')


<style>
    /* Setting the size using CSS */
    .chart-container {
        width: 100%; /* Full width */
        height: 80vh; /* Fixed height */
        margin: 0 auto;
        display: grid;
        place-items: center;
    }
</style>

@section('sub-header')
    <div class="container mx-auto">
        <a href="{{ route('head_office.contacts.view', $new_contact->id) }}" class="link text-info">Details</a>
        <a href="{{route('head_office.contact_view_timeline',$new_contact->id)}}" class="link text-info ms-4">Timeline</a>
        <a href="{{route('head_office.contact_intelligence',$new_contact->id)}}" class="link text-info ms-4">Intelligence</a>
        <a href="{{route('head_office.contact_matchs',$new_contact->id)}}" class="link text-info ms-4">Matches</a>
    </div>
@endsection
@section('content')


    <div id="content" style="margin: 0;padding:0;">
        
        @include('layouts.error')

        <div class="container-lg mx-auto">
            <h1>Incident types</h1>
            <div class="chart-container">
                <canvas id="myChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>


@section('scripts')
    




    <script src="{{ asset('js/alertify.min.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-dayjs/dist/chartjs-adapter-dayjs.umd.js"></script>
    <script>
        const labels = @json($labels); // Dates
        const datasets = @json($datasets); // Incident type datasets
    
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line', // Specify the chart type
            data: {
                labels: labels, // Assign the labels (dates)
                datasets: datasets, // Multiple datasets for each incident type
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // Start the y-axis at zero
                    }
                }
            }
        });
    </script>
    
    
    
    


@endsection
@endsection
