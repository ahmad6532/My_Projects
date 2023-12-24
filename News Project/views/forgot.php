<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300&display=swap" rel="stylesheet">
    <title>Document</title>
    <link rel="stylesheet" href="../css/forgot.css">
</head>

<body>
    <div class="forgot_div">

        <div class="inner_div">
            <h2>Forgot Password</h2>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter Email">
            <span id="emailspan">Fill Email</span>

            <input type="submit" id="submit" name="submit" value="Send Mail">

            <div class="link_div">
                <a href="/views/login.php">Login</a>
            </div>
        </div>
    </div>
    <script src="/js/forgot.js"></script>
</body>

</html>