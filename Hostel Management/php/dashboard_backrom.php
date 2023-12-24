<?php
require "connection.php";
$query="select * from rooms";
$result=mysqli_query($connection,$query) or die("Query Fail");
if(mysqli_num_rows($result)>0)
{

   while($row=mysqli_fetch_assoc($result)){
    $out[]=$row;
   }
$len=count($out);

for($i=0; $i < $len; $i++)
{
    $total_seats[]=$out[$i]['total_seats'];
    $remaining_seats[]=$out[$i]['remaining_seats'];
    $reserved_seats[]=$out[$i]['reserved_seats'];
}
$total_sum=array_sum($total_seats);
$total_rem=array_sum($remaining_seats);
$total_res=array_sum($reserved_seats);
$output=["trooms"=>$total_sum,"rrooms"=>$total_rem,"resrooms"=>$total_res];
echo json_encode($output);
}

mysqli_close($connection);
?>