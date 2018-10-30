<?php

$mobile_no = $_POST['mobile_no'];
$otp = $_POST['otp'];
$country = $_POST['country'];
$devicetype = $_POST['device_type'];
$gcmid = $_POST['gcm_id'];
require '../db.php';
$response = array();
$userid = '';
//$result_otp = mysqli_query($conn, "select * from tbl_varifyotp where mobile_no='$mobile_no' and verificationcode='$otp'") or die(mysql_error());
//if (mysqli_num_rows($result_otp) > 0) {

    mysqli_query($conn, "delete from tbl_varifyotp where mobile_no='$mobile_no' and verificationcode='$otp'") or die(mysql_error());

    $query_user = "select * from tbl_users where user_mobile='$mobile_no'";

    $result_user = mysqli_query($conn, $query_user) or die(mysql_error());

    if (mysqli_num_rows($result_user) > 0) {
        $row = $result_user->fetch_array(MYSQLI_ASSOC);
        $userid = $row['user_id'];
        $row['userstatus'] = 'olduser';
        echo json_encode($row);
    } else {
        $query = "insert into tbl_users(user_mobile,user_country,isverified)" .
                "values('$mobile_no','$country','No')";
        $result = mysqli_query($conn, $query);

        if ($result == 1) {
            $query_user = "select * from tbl_users where user_mobile='$mobile_no'";
            $result_user = mysqli_query($conn, $query_user) or die(mysql_error());
            $row = $result_user->fetch_array(MYSQLI_ASSOC);
            $row['userstatus'] = 'newuser';
            echo json_encode($row);
        } else {
            $response["success"] = "0";
            $response["message"] = "Fail";
            echo json_encode($response);
        }
    }
/*} else {
    $response["user_id"]="-1";
    $response["success"] = "0";
    $response["message"] = "Mobile No and OTP did not match";
    echo json_encode($response);
}*/

$conn->close();
?>