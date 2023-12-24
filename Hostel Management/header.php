<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata&family=M+PLUS+1p&family=Rubik:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link href="css/header.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <header>
        <div class="inner-div">
            <div class="name-div"><h2>Rehman Hostel</h2></div>
            <div class="search-div">
                <div style="display:flex; align-items:center">
                <input type="text" id="search" placeholder="Search Student" onkeyup=(search_record())>
                <i class="fa fa-search" aria-hidden="true"></i>
                </div>
            </div>
            <div class="name-div">
                <span id="well">Welcome! <span class="name-div-span">
                    <?php  echo $_SESSION['username'];  ?>
                </span></span>
                <i class="fa-solid fa-bars burger"></i>
                <span id="logout"><a href="php/logout.php">Logout</a></span>
            </div>
        </div>
    </header>
</body>
</html>