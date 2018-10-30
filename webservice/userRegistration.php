<?php

$userid = $_POST['userid'];
$nm = $_POST['username'];
$email = $_POST['email'];
$mob = $_POST['mobilenum'];
$city = $_POST['city'];
$postal_code = $_POST['postal_code'];
$country = $_POST['country'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$fbid = $_POST['fbid'];
$profile_file_new_name = '';
$identity_file_new_name = '';
$profileimg = '';
$identityimg = '';
require '../db.php';
$response = array();

$getuser = mysqli_query($conn, "select * from tbl_users where user_id='$userid'");
if (mysqli_num_rows($getuser)) {
    $rowuser = mysqli_fetch_array($getuser);
    $profileimg = $rowuser['user_profile_img'];
    $identityimg = $rowuser['user_identity_img'];
}
if (isset($_FILES['profileimg']['name'])) {

//profile image
    $file_name = basename($_FILES['profileimg']['name']);
    $target_path = "../upload/profile/";
    $target_file = $target_path . basename($_FILES['profileimg']['name']);
    $FileType = pathinfo($target_file, PATHINFO_EXTENSION);
    $profile_file_new_name = generateRandomString() . '.' . $FileType;
    $target_file1 = $target_path . $profile_file_new_name;

    if (!move_uploaded_file($_FILES['profileimg']['tmp_name'], $target_file1)) {
        $response['status'] = "fail";
        $response['message'] = "Profile Photo Uploaded Failed...";
    } else {
        $query1 = "update tbl_users set user_profile_img='$profile_file_new_name' where user_id='$userid'";
        mysqli_query($conn, $query1);
        if ($profileimg != '' && file_exists($target_path . $profileimg))
            unlink($target_path . $profileimg);
    }
}
if (isset($_FILES['identityimg']['name'])) {
//Identity image
    $ifile_name = basename($_FILES['identityimg']['name']);
    $itarget_path = "../upload/identity/";
    $itarget_file = $itarget_path . basename($_FILES['identityimg']['name']);
    $iFileType = pathinfo($itarget_file, PATHINFO_EXTENSION);
    $identity_file_new_name = generateRandomString() . '.' . $iFileType;
    $itarget_file1 = $itarget_path . $identity_file_new_name;

    if (!move_uploaded_file($_FILES['identityimg']['tmp_name'], $itarget_file1)) {
        $response['status'] = "fail";
        $response['message'] = "Identity Photo Uploaded Failed...";
    } else {
        $query1 = "update tbl_users set user_identity_img='$identity_file_new_name' where user_id='$userid'";
        mysqli_query($conn, $query1);
        if ($identityimg != '' && file_exists($itarget_path . $identityimg))
            unlink($itarget_path . $identityimg);
    }
}
$query = "update tbl_users set user_email='$email',user_name='$nm',user_mobile='$mob',user_city='$city',postal_code='$postal_code',user_gender='$gender',user_country='$country',user_dob='$dob',user_fb_id='$fbid' where user_id='$userid'";

$result = mysqli_query($conn, $query);
if ($result) {
    $response['status'] = "success";
    $response['message'] = "Registration Successfully Done...";
} else {
    $response['status'] = "fail";
    $response['message'] = "Registration Failed...";
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
