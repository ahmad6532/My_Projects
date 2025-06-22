<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status and Payment</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #e0eafc, #cfdef3);
        }
        .message {
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 400px;
            text-align: center;
            margin: 20px;
        }
        .message h1 {
            font-size: 22px;
            color: #333333;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .message p {
            font-size: 16px;
            color: #555555;
            margin-bottom: 15px;
        }
        .message a {
            display: inline-block;
            padding: 12px 24px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .message a:hover {
            background-color: #0056b3;
        }
        .message a:active {
            background-color: #004494;
        }
    </style>
</head>
<body>
    <div class="message">
        <h1>Your Booking Status</h1>
        <p>Your booking status is pending. Please make the payment to complete your booking.</p>
        <p>Click the link below to complete your payment:</p>
        <a href="{{ $paymentLink }}">Pay Now</a>
        <p>Thank you for your prompt attention!</p>
    </div>
</body>
</html>
