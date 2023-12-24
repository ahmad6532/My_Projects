<?php

include('connection_config.php');
if (isset($_POST['submit'])) {


    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $status = 'disapproved';
    $query = "insert into admin (name, email, password, status) values ('$name','$email','$password','$status') ";
    $run = mysqli_query($conn, $query);
    if ($run) {
        header("Location:{$server_name}login.php");
    } else {
        header("Location: {$server_name}'forgot.php'");
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
    <link rel="stylesheet" href="../css/signup.css">
</head>

<body>
    <div class="login_div">

        <div class="inner_div">
            <h2>Sign Up</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter Name">
                <span id="namespan">Fill Name</span>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter Email">
                <span id="emailspan">Fill Email</span>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter Password">
                <span id="passspan">Fill Password</span>
                <label for="cpassword">Confirm Password</label>
                <input type="password" id="cpassword" name="cpassword" placeholder="Confirm Password">
                <span id="cpassspan">Fill Confirm Password</span>
                <input type="submit" id="submit" name="submit" value="Sign Up">

            </form>

            <div class="link_div">
                <a href="/views/login.php">Login</a>
            </div>
        </div>
    </div>




    <script src="../js/signup.js"></script>
</body>

</html>