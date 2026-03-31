<?php
session_start();
include "php/db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.html");
    exit();
}


$id = $_GET['id'];

mysqli_query($conn,"
UPDATE bookings 
SET status='Cancelled',
cancel_date=NOW(),
refund_status='Pending'
WHERE id='$id'
");

header("Location: my_bookings.php");

?>