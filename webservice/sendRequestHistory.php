<?php
$userid=$_POST['user_id'];

require '../db.php';
$response=array();
$data=array();
$qry=  mysqli_query($conn,"select t.*,td.* from tbl_trip_detail td,tbl_trips t where t.trip_id=td.trip_id and user_id='$userid' order by td.updated_at DESC");

if(mysqli_num_rows($qry)){
    while ($row=  mysqli_fetch_array($qry)){
        $data['tripdetailid']=$row['id'];
        $data['tripid']=$row['trip_id'];
        $data['triproute']=$row['source'].' > '.$row['destination'];
        $deptdt=new DateTime($row['trip_depature_time']);
        $data['tripdepature']=$deptdt->format('d M Y g:i a')."";
        
        $senddt=new DateTime($row['created_at']);
        $data['sendtime']=$senddt->format('d M Y g:i a')."";
        $data['requestseat']=$row['seats'];
        $data['comment']=$row['comment'];
        $data['pickuppoint']=$row['pickup_point'];
        $data['droppoint']=$row['drop_point'];
        $data['status']=$row['status'];
        $data['pickuptime']=$row['pickup_time'];
        array_push($response, $data);
    }
}
echo json_encode($response);
mysqli_close($conn);
?>
