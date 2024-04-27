<?php

require "connection.php";
$input_data=file_get_contents('php://input');
$decode_val=json_decode($input_data, true);
$name=$decode_val['name'];
$status=$decode_val['status'];
$fname=$decode_val['fname'];
$cnic=$decode_val['cnic'];
$phone=$decode_val['phone'];
$iname=$decode_val['iname'];
$regno=$decode_val['regno'];
$address=$decode_val['address'];

$query="insert into students (student_id,name,father_name,cnic,phone,institute,room,status,address)
values ('RHS','$name','$fname','$cnic','$phone','$iname','$regno','$status','$address')";
$result=mysqli_query($connection,$query) or die("Query Fail");
if($result)
{
    $last_id = mysqli_insert_id($connection);
    $new_id='RHS'.$last_id;
    $update="update students set student_id='$new_id' where id='$last_id'";
   $update_query= mysqli_query($connection,$update) or die("Update Fail");
   if($update_query)
   {
    echo json_encode(array('message'=>'success'));
   }
}
else{
    echo json_encode(array('message'=>'fail'));
}


$query ="select * from rooms where room_no='$regno'";

$result=mysqli_query($connection,$query) or die("Query Fail");
if(mysqli_num_rows($result)>0)
{

   $row = mysqli_fetch_assoc($result);
   $total_seats= $row['total_seats'];
   $reserved_seats= $row['reserved_seats'];
   $reserved_seats=$reserved_seats +1;
   $remaining_seats=$total_seats - $reserved_seats;
 $update="update rooms set reserved_seats='$reserved_seats',remaining_seats='$remaining_seats' where room_no='$regno'";
 mysqli_query($connection,$update) or die("Query Fail");
}
mysqli_close($connection);

?>