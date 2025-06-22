<body>
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center; z-index: 1000;">
        <div style="background-color: #fff; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); width: 300px;">
            <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" alt="Success" style="width: 50px; height: 50px; margin-bottom: 15px;">
            <h2 style="margin: 10px 0; color: #28A745;">Success!</h2>
            <p style="color: #555; margin-bottom: 20px;">Your payment is successfully done.</p>
            <button style="background-color: #28A745; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;" onclick="redirectToPayment()">Back to Home</button>
        </div>
    </div>
    <script>
        function redirectToPayment() {
            // Add your redirection logic here
            window.location.href = "https://fts.viion.net/";
        }
    </script>
</body>