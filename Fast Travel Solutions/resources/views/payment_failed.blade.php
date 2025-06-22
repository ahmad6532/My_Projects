<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Link Expired</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .message-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .message-box img {
            width: 50px;
            height: 50px;
            margin-bottom: 15px;
        }
        .message-box h2 {
            margin: 10px 0;
            color: #dc3545; /* Red color for error */
        }
        .message-box p {
            color: #555;
            margin-bottom: 20px;
        }
        .message-box button {
            background-color: #dc3545; /* Red color for button */
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="overlay">
        <div class="message-box">
            <img src="https://cdn-icons-png.flaticon.com/512/190/190429.png" alt="Error"> <!-- Changed icon to reflect an error -->
            <h2>Payment Link Expired</h2>
            <p>The payment link you used has expired. Please request a new payment link.</p>
            <button onclick="redirectToHome()">Back to Home</button>
        </div>
    </div>

    <script>
        function redirectToHome() {
            // Redirect to home page
            window.location.href = "https://fts.viion.net/";
        }
    </script>
</body>
</html>
