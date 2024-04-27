
<?php   
session_start();
include "php/connection.php";  
if($_SESSION['username'] =="")
{
  header ("Location: {$servername}");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Rehman Hostel</title>
</head>
<body>
    <!-- Header file included here -->
    <?php include "header.php"; ?>

 <div class="main">

     <!-- Side menue div included here -->
     <?php include "menue.php";?>

       <div class="content">
         <!-- Add mobile menue bar here -->
         <?php include "mobile_menue.php" ?>
         <!-- Add new Student div included here -->
         <?php include "add_student.php"; ?>

         <!-- Display Rooms div included here -->
         <div class="room-main"></div>

         <!-- Display students record div included here -->
         <div class="display-data"></div>

         <!-- Detail Record of students record div included here -->
         <?php include "detail.php"; ?>

         
         <!-- Detail Record of students record div included here -->
         <?php include "dash_cont.php"; ?>

         
         <!-- Available seats div included here -->
         <div class="available-seat"></div>

           </div>
</div>
   <script src="javascript/dashboard.js"></script>
</body>
</html>