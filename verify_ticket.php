<?php
session_start();
include "php/db.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="staff"){
    echo "unauthorized";
    exit();
}

if(!isset($_GET['id'])){
    echo "invalid";
    exit();
}

$id = intval($_GET['id']);

$q = mysqli_query($conn,"SELECT * FROM bookings WHERE id='$id'");

if(mysqli_num_rows($q)==0){
    echo "invalid";
    exit();
}

$data = mysqli_fetch_assoc($q);
$today = date("Y-m-d");

/* DATE CHECK */
if($data['booking_date'] != $today){
    echo "not_today";
    exit();
}

/* ALREADY USED */
if($data['status']=="Verified"){
    echo "used";
    exit();
}

/* UPDATE */
mysqli_query($conn,"UPDATE bookings SET status='Verified' WHERE id='$id'");

echo "success";
?>