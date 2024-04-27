<?php

require "connection.php";
session_start();
$inputval= file_get_contents('php://input');
$decoded_value=json_decode($inputval, true);

$username=$decoded_value['username'];
$password=$decoded_value['password'];

$query="select * from admin where username='$username' and password='$password'";
$result=mysqli_query($connection,$query) or die("Query Fail");

if(mysqli_num_rows($result)>0)
{
    while($row=mysqli_fetch_assoc($result))
    {
        $_SESSION['username']=$row['username'];
    }
    echo json_encode(array('message'=>'success'));

}
else{
    echo json_encode(array('message'=>'fail'));

}
mysqli_close($connection);

?>