<?php
session_start();
include("php/db.php");

if (!isset($_SESSION['user_id'])) {
header("Location: login.html");
exit();
}

$user_id = $_SESSION['user_id'];
$ride_id = $_POST['ride_id'];
$booking_date = $_POST['booking_date'];
$slot_time = $_POST['slot_time'];

$today = date("Y-m-d");

if($booking_date < $today){
$_SESSION['error']="Past date booking not allowed";
header("Location: book.php?id=".$ride_id);
exit();
}

if($booking_date == $today){
$_SESSION['error']="Today rides only walk-in users allowed";
header("Location: book.php?id=".$ride_id);
exit();
}

/* RIDE DETAILS */

$ride_query = mysqli_query($conn,"SELECT price,capacity FROM rides WHERE id='$ride_id'");
$ride_data = mysqli_fetch_assoc($ride_query);

$amount = $ride_data['price'];
$capacity = $ride_data['capacity'];

/* SLOT CHECK */

$check = mysqli_query($conn,"
SELECT COUNT(*) as total FROM bookings
WHERE ride_id='$ride_id'
AND booking_date='$booking_date'
AND slot_time='$slot_time'
AND status IN ('Confirmed','Walk-In','Verified')");

$data = mysqli_fetch_assoc($check);

if($data['total'] >= $capacity){
$_SESSION['error']="Slot Full!";
header("Location: book.php?id=".$ride_id);
exit();
}

/* SAVE BOOKING WITH PAYMENT PENDING */

$insert = mysqli_query($conn,"
INSERT INTO bookings
(user_id,ride_id,booking_date,slot_time,amount,status)
VALUES
('$user_id','$ride_id','$booking_date','$slot_time','$amount','Pending')");

$booking_id = mysqli_insert_id($conn);

/* GO TO PAYMENT PAGE */

header("Location: payment.php?id=".$booking_id);
exit();

?>