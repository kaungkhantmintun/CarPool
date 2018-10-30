<?php
require '../db.php';
$user_id = $_POST['user_id'];
$result = mysqli_query($conn, "SELECT * FROM tbl_users where user_id='$user_id'") or die(mysql_error());
if (mysqli_num_rows($result) > 0) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    echo json_encode($row);
} else {
    $response["success"] = 0;
    $response["message"] = "No User found";

    echo json_encode($response);
}
$result->free();
$conn->close();    