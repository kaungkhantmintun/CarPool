<?php
$conn = mysqli_connect('localhost', 'root', 'aumif;cefYkkmt1', 'funtoxrz_carpool');

if (!$conn) {
    die('Could not connect: ' . mysqli_connect_error());
}

mysqli_select_db($conn,"funtoxrz_carpool");
?>
