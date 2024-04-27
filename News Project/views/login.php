<?php

include('connection_config.php');
if (isset($_POST['submit'])) {


    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = "select * from admin where email='$email' and password='$password' ";
    $run = mysqli_query($conn, $query);
    if (mysqli_num_rows($run) > 0) {
        header("Location:{$server_name}test.php");
    } else {
        header("Location: {$server_name}forgot.php");
    }
}



?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300&display=swap" rel="stylesheet">
    <title>Document</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="login_div">

        <div class="inner_div">
            <h2>Login</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="Enter Email">
                <span id="emailspan">Fill Email</span>

                <label for="password">Password</label>
                <input type="text" id="password" name="password" placeholder="Enter Password">
                <span id="passspan">Fill Password</span>
                <input type="submit" id="submit" name="submit" value="Login">
            </form>
            <div class="link_div">
                <a href="/views/signup.php">Create Account</a>
                <a href="/views/forgot.php">Forgot Password</a>
            </div>
        </div>
    </div>
    <script src="../js/login.js"></script>
</body>
</html>