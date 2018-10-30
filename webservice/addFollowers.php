<?php

require '../db.php';
$userid = $_POST['userid'];
$followerid = $_POST['followerid'];
$response = array();

$query_exist = "select * from tbl_follows where user_id=$userid and follower_id=$followerid";
$result_exist = mysqli_query($conn, $query_exist);

if (mysqli_num_rows($result_exist) > 0) {
    $response['status'] = "success";
    $response['message'] = "Already Follow";
} else {
    $query = "INSERT INTO `tbl_follows`(user_id,follower_id) VALUES ($userid,$followerid)";
    $result = mysqli_query($conn, $query);
    if ($result == 1) {
        $response['status'] = "success";
        $response['message'] = "Added Successfully";
    } else {
        $response['status'] = "fail";
        $response['message'] = 'Failed To Add Follower.';
    }
}
echo json_encode($response);
mysqli_close($conn);
?>