<?php
$userid = '';
if (isset($_REQUEST['userid'])):
    if (!empty($_REQUEST['userid'])):
        $userid = htmlspecialchars($_GET['userid']);
    endif;
endif;
require '../db.php';
$response = array();
$query_user = "select * from users where userid in (select follower_id from user_relation_map where user_id=$userid)";
$result_user = mysqli_query($conn,$query_user) or die(mysqli_error());
if (mysqli_num_rows($result_user) > 0) {
    $response["users"] = array();
     while ($row_user = $result_user->fetch_assoc()) {
        $users = array();
        $users["user_name"] = $row_user["name"];
        $users["profile_pic"] = $row_user["profile_pic"];
        $users["login_type"] = $row_user["login_type"];        
        $users["profileid"] = $row_user["profileid"];
        $users["show_add_icon"] = false;
        array_push($response["users"], $users);
    }
    $response["success"] = 1;
    
} else {
    $response["success"] = 0;
    $response["message"] = "No Followers found";
    
}
mysqli_close($conn);
echo json_encode($response);