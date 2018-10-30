<?php
require '../db.php';
$user_id = $_POST['user_id'];
$result = mysqli_query($conn, "SELECT * FROM push_history where user_id=$user_id ORDER BY created_at DESC") or die(mysql_error());
$poets = array();
if (mysqli_num_rows($result) > 0) {
    
    while ($row = $result->fetch_assoc()) {
        $poets[] = $row;
    }
    echo json_encode($poets);
} else {    
    $response["success"] = 0;
    $response["message"] = "No Data found";    
    echo json_encode($response);
}
$result->free();
$conn->close();
