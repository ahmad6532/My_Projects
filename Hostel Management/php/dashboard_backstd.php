<?php
require "connection.php";
$total_record="";
$total_std="";
$total_left="";
$query="select * from students where status='Present'";
$result=mysqli_query($connection,$query) or die("Query Fail");
if(mysqli_num_rows($result)>0)
{

   while($row=mysqli_fetch_assoc($result)){
    $out[]=$row;
   }
$total_std=count($out);

}
$query="select * from students";
$result=mysqli_query($connection,$query) or die("Query Fail");
if(mysqli_num_rows($result)>0)
{

   while($roww=mysqli_fetch_assoc($result)){
    $outt[]=$roww;
   }
$total_record=count($outt);
}
$total_lef=$total_record - $total_std;
$output=["total_std"=>"$total_record","present"=>"$total_std","left"=>"$total_lef"];
echo json_encode($output);

mysqli_close($connection);
?>