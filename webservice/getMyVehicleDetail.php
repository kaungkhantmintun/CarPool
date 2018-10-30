<?php
$userid=$_POST['user_id'];

require '../db.php';
$response=array();

$getvehicle=  mysqli_query($conn,"select v.*,vc.* from tbl_vehicle v,tbl_vehicle_category vc where vehicle_cat_id=car_id and user_id='$userid' order by id");
if(mysqli_num_rows($getvehicle)){
    $veh=array();
    while ($row=  mysqli_fetch_array($getvehicle)){
        $veh['vehicle_id']=$row['id'];
        $veh['company']=$row['car_name'];
        $veh['model']=$row['model'];
        $veh['number']=$row['vehicle_number'];
        $veh['image']=$row['vehicle_image'];
        $veh['seats']=$row['avaliable_seats'];
        $veh['type']=$row['vehicle_type'];
        array_push($response,$veh);
    }
}
echo json_encode($response);
mysqli_close($conn);
?>