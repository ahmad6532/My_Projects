<?php
require "connection.php";
$id=$_GET['id'];
$query="select * from students where id='$id'";
$result=mysqli_query($connection,$query) or die("Query Fail");
if(mysqli_num_rows($result)>0)
{

    while($row = mysqli_fetch_assoc($result))
    {
       $output[]=$row;
    }
    echo json_encode($output);
}
else{

    echo json_encode(array('message'=>'fail'));
}
mysqli_close($connection);
?>