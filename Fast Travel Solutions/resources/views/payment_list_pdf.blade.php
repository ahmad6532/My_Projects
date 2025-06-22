<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        body h1{
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Customer Payment List</h1>
    <table>
        <thead>
            <tr>
                <th>Reference No.</th>
                <th>Customer</th>
                <th>Booking Status</th>
                <th>Date & Time</th>
                <th>Amount</th>
                <th>Booking Type</th>
                <th>Payment Method</th>
                <th>Company</th>
                <th>Driver</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $item)
            <tr>
                <td>{{ $item->tracking_number }}</td>
                <td>{{ $item->head_passenger_name }}</td>
                <td>{{ $item->booking_status }}</td>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->booking_price }}</td>
                <td>{{ $item->type_name }}</td>
                <td>{{ $item->payment_type }}</td>
                <td>{{ $item->company_name }}</td>
                <td>{{ $item->first_name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
