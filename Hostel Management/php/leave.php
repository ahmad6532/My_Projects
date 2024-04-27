<?php

require "connection.php";
$id=$_GET['id'];
$query="update students set status='Left' where id='$id'";
mysqli_query($connection,$query) or die("Query Fail");

  
$get="select room from students where id='$id'";
$get_q=mysqli_query($connection,$get) or die('Room Query Fail');
$get_data=mysqli_fetch_assoc($get_q);
$get_out=$get_data['room'];

$q ="select * from rooms where room_no='$get_out'";

$result=mysqli_query($connection,$q) or die("Query Fail");
if(mysqli_num_rows($result)>0)
{

   $row = mysqli_fetch_assoc($result);
   $total_seats= $row['total_seats'];
   $reserved_seats= $row['reserved_seats'];
   $reserved_seats=$reserved_seats -1;
   $remaining_seats=$total_seats - $reserved_seats;
 $update="update rooms set reserved_seats='$reserved_seats',remaining_seats='$remaining_seats' where room_no='$get_out'";
 mysqli_query($connection,$update) or die("Query Fail");
}

    echo json_encode(array('message'=>'success'));

mysqli_close($connection);

?>