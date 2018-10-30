<?php

$userid = $_POST['user_id'];
$catid = $_POST['vehicle_category_id'];
$model = $_POST['model'];
$vno = $_POST['vehicle_number'];
$seats = $_POST['seats'];
$type = $_POST['type'];
$file_new_name = "";
require '../db.php';
$response = array();

if ($userid == 0) {
    $response['status'] = "fail";
    $response['message'] = "Registration Failed...";
} else {
 $file_new_name = '';
    if (isset($_FILES['vehicleimage']['name'])) {
//Vehicle image
        $file_name = basename($_FILES['vehicleimage']['name']);
        $target_path = "../upload/vehicle/";
        $target_file = $target_path . basename($_FILES['vehicleimage']['name']);
        $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $file_new_name = generateRandomString() . '.' . $FileType;
        $target_file1 = $target_path . $file_new_name;

        if (!move_uploaded_file($_FILES['vehicleimage']['tmp_name'], $target_file1)) {
            $file_new_name = '';
            $response['status'] = "fail";
            $response['message'] = "Profile Photo Uploaded Failed...";
        }
    }

    $query = "insert into tbl_vehicle(user_id,vehicle_cat_id,model,vehicle_number,vehicle_image,avaliable_seats,vehicle_type)" .
            "values('$userid','$catid','$model','$vno','$file_new_name','$seats','$type')";

    $result = mysqli_query($conn, $query);
    if ($result) {
        $response['status'] = "success";
        $response['message'] = "Saved Successfully Done...";
    } else {
        $response['status'] = "fail";
        $response['message'] = "Registration Failed...";
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


