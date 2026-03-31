<?php
session_start();
include("php/db.php");
include "phpqrcode/qrlib.php";

$booking_id = $_POST['booking_id'];

/* GET BOOKING DETAILS */

$query = mysqli_query($conn,
"SELECT * FROM bookings WHERE id='$booking_id'");

$data = mysqli_fetch_assoc($query);

$ride_id = $data['ride_id'];
$booking_date = $data['booking_date'];
$slot_time = $data['slot_time'];

/* UPDATE STATUS */

mysqli_query($conn,
"UPDATE bookings SET status='Confirmed' WHERE id='$booking_id'");

/* CREATE FOLDER */

if(!file_exists("ticket_image")){
mkdir("ticket_image",0777,true);
}

/* QR DATA */

$qrData = "booking_id=".$booking_id;

$tempQR="temp_qr.png";

QRcode::png($qrData,$tempQR);

/* CREATE IMAGE */

$ticket_file="ticket_image/ticket_".$booking_id.".png";

$img=imagecreatetruecolor(500,350);

$white=imagecolorallocate($img,255,255,255);
$black=imagecolorallocate($img,0,0,0);

imagefill($img,0,0,$white);

/* TEXT */

imagestring($img,5,20,20,"JOY Amusement Park Ticket",$black);
imagestring($img,5,20,80,"Booking ID : ".$booking_id,$black);
imagestring($img,5,20,110,"Ride ID : ".$ride_id,$black);
imagestring($img,5,20,140,"Date : ".$booking_date,$black);
imagestring($img,5,20,170,"Slot : ".$slot_time,$black);

/* LOAD QR */

$qr=imagecreatefrompng($tempQR);

imagecopy($img,$qr,330,100,0,0,150,150);

/* SAVE IMAGE */

imagepng($img,$ticket_file);

imagedestroy($img);

unlink($tempQR);

/* SAVE PATH */

mysqli_query($conn,
"UPDATE bookings SET ticket_image='$ticket_file' WHERE id='$booking_id'");

/* REDIRECT */

header("Location: ticket.php?id=".$booking_id);
exit();

?>