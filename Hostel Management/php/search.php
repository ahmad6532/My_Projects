<?php
require "connection.php";
$search=$_GET['search'];
$query="select * from students where name like '%$search%' or student_id='$search'";
$result=mysqli_query($connection,$query) or die("Query Failed");
if(mysqli_num_rows($result)>0)
{
    while($data=mysqli_fetch_assoc($result)){
        $output[]=$data;
    }
echo json_encode($output);

}
else{
   
echo json_encode(array('message'=>'fail'));
    
}


?>