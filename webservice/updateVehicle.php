<?php

$vehicleid = $_POST['vehicle_id'];
$catid = $_POST['vehicle_category_id'];
$model = $_POST['model'];
$vno = $_POST['vehicle_number'];
$seats = $_POST['seats'];
$type = $_POST['type'];

require '../db.php';
$response = array();
$chkvehicle = mysqli_query($conn, "select count(*),v.* from tbl_trips t,tbl_vehicle v where trip_vehicle_id=v.id and trip_vehicle_id='$vehicleid' and (depature_date>=Now() or return_date>=Now())");
if (mysqli_num_rows($chkvehicle)) {
    $chkrow = mysqli_fetch_array($chkvehicle);
    $cntvehicle = $chkrow[0];
    $carimg = $chkrow['vehicle_image'];
    if ($cntvehicle > 0) {
        $response['status'] = "success";
        $response['message'] = "You are posted Trip with this vehicle.So,vehicle detail not update.";
    } else {
        if (isset($_FILES['vehicleimg']['name'])) {
//vehicle image
            $file_name = basename($_FILES['vehicleimg']['name']);
            $target_path = "../upload/vehicle/";
            $target_file = $target_path . basename($_FILES['vehicleimg']['name']);
            $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $file_new_name = generateRandomString() . '.' . $FileType;
            $target_file1 = $target_path . $file_new_name;

            if (!move_uploaded_file($_FILES['vehicleimg']['tmp_name'], $target_file1)) {
                $response['status'] = "fail";
                $response['message'] = "Vehicle Photo Uploaded Failed...";
            } else {
                $response['status'] = "success";
                $response['message'] = "Vehicle Photo Uploaded Successfully Done...";
                mysqli_query($conn, "update tbl_vehicle set vehicle_image='$file_new_name' where id='$vehicleid'");
                if ($carimg != '' && file_exists($target_path . $carimg))
                    unlink($target_path . $carimg);
            }
        }
        $query = "update tbl_vehicle set vehicle_cat_id='$catid',model='$model',vehicle_number='$vno',avaliable_seats='$seats',vehicle_type='$type' where id='$vehicleid'";
        echo $query;
        $result = mysqli_query($conn, $query);
        if ($result) {
            $response['status'] = "success";
            $response['message'] = "Vehicle Updated Successfully Done...";
        } else {
            $response['status'] = "fail";
            $response['message'] = "Vehicle update Failed...";
        }
    }
}
echo json_encode($response);

function generateRandomString() {
    $d = date("d");
    $m = date("m");
    $y = date("Y");
    $t = time();
    $dmt = $d + $m + $y + $t;
    $ran = rand(0, 10000000);
    $dmtran = $dmt + $ran;
    $un = uniqid();
    $dmtun = $dmt . $un;
    $mdun = md5($dmtran . $un);
    $sort = substr($mdun, 16); // if you want sort length code.

    return $sort;
}

mysqli_close($conn);
?>


