<?php
require '../db.php';
$response = array();
$user_id = $_POST['user_id'];
$gcmid = $_POST['gcmid'];
$devicetype = $_POST['devicetype'];

  //mysqli_query($conn,"insert into tbl_gcm(user_id,device_type,gcmid) values('$userid','$devicetype','$gcmid')");
$sql_query = "select * from tbl_gcm where user_id=$user_id";
$result = mysqli_query($conn, $sql_query)or die(mysql_error());
if (mysqli_num_rows($result) > 0) {
        $updategcm = "update tbl_gcm set gcmid='$gcmid',device_type='$devicetype' where user_id='$user_id'";
        
   mysqli_query($conn,$updategcm) or die(mysql_error());    
    echo 'success';
}  else {

		mysqli_query($conn,"insert into tbl_gcm(user_id,device_type,gcmid) values('$user_id','$devicetype','$gcmid')");
    echo 'success';
}