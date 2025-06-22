<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata&family=M+PLUS+1p&family=Rubik:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link href="css/login.css" rel="stylesheet">
    <title>Rehman Hostel | Login</title>
</head>
<body>
    <div class="main">
        <div class="sub">
            <form method="">
                <h1>LogIn</h1>

                <h6 class="fail-message">Username or Password Incorrect</h6>
                <div class="input-div">
            <input type="text" name="username" id="uname" onkeyup=(username_fun()) placeholder="Enter Username"><i class="fa-solid fa-user"></i>
            </div>
            <span id="userspan" class="span">Please Fill Username</span>
            <div class="input-div">
            <input type="password" name="pass" id="pass" onkeyup=(password_fun()) placeholder="Enter Password"><i class="fa-solid fa-lock"></i>
            </div>
            <span id="passwordspan" class="span">Please Fill Password</span>

            <input type="button"  value="LogIn" name="save" id="submit">
            <span class="bottom_text" >To change your password <span id="change">Click Here!</span></span>
            </form>
        </div>
        <div class="sub1">
            <form>
                <h1>Change Password</h1>
                <h6 class="fail-message">Username or Password Incorrect</h6>
                <div class="input-div">
            <input type="password" id="oldpass" placeholder="Enter Old Password"><i class="fa-solid fa-lock"></i>
            </div>
               <span id="oldspan" class="span">Please Fill Password</span>
            <div class="input-div">
            <input type="password" id="newpass" placeholder="Enter New Password"><i class="fa-solid fa-lock"></i>
            </div>
                <span id="newspan" class="span">Please Fill Password</span>
            <div class="input-div">
            <input type="password" id="conpass" placeholder="Enter Confirm Password"><i class="fa-solid fa-lock"></i>
            </div>
               <span id="conspan" class="span">Please Fill Password</span>
            <input type="button" value="Change Password" id="changepassword">
            <span  class="bottom_text" >To login <span id="login">Click Here!</span></span>
            </form>
        </div>
    </div>
    <script src="javascript/login.js"></script>
</body>
</html>