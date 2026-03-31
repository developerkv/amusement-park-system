<?php
session_start();

if(!isset($_SESSION['user_id'])){
header("Location: login.html");
exit();
}

include "php/db.php";
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/style.css">
<title>My Bookings</title>
</head>

<body>

<div class="navbar">

<div class="logo">
<span class="joy-text">JOY</span>
</div>

<span class="user-name">
Welcome <?php echo $_SESSION['user_name']; ?>
</span>

<div class="nav-links">
<a href="index.php">Home</a>
<a href="my_bookings.php">My Bookings</a>
<a href="logout.php">Logout</a>
</div>

</div>

<h2>My Bookings</h2>

<div style="text-align:center; margin-bottom:20px;">
<a href="category.php" class="back-btn">Back to Rides</a>
</div>

<?php

$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn,"
SELECT 
bookings.id,
rides.ride_name,
bookings.booking_date,
bookings.created_at,
bookings.slot_time,
bookings.amount,
bookings.status
FROM bookings
JOIN rides ON bookings.ride_id = rides.id
WHERE bookings.user_id='$user_id'
AND bookings.booking_date >= CURDATE()
ORDER BY bookings.booking_date ASC
");

echo "<div class='booking-container'>";

if(mysqli_num_rows($result)==0){
echo "<p style='text-align:center;'>No Upcoming Bookings</p>";
}

while($row = mysqli_fetch_assoc($result)){

echo "<div class='ride-card'>";

echo "<h3>".$row['ride_name']."</h3>";
echo "Date : ".$row['booking_date']."<br>";
echo "Slot : ".$row['slot_time']."<br>";
echo "Amount : ₹".$row['amount']."<br>";
echo "Status : ".$row['status']."<br><br>";

/* CANCEL LOGIC */
$booking_created = strtotime($row['created_at']);
$current_time = time();
$cancel_limit = $booking_created + (24 * 60 * 60);

if($current_time <= $cancel_limit && $row['status']=="Confirmed"){

echo "<a href='cancel_booking.php?id=".$row['id']."'>
<button>Cancel</button>
</a>";

}else{
echo "<span style='color:red;'>Cancel Closed</span>";
}

/* DOWNLOAD TICKET (INSIDE LOOP) */
echo "<br><br>
<a href='ticket.php?id=".$row['id']."'>
<button>Download Ticket</button>
</a>";

echo "</div>";
}

echo "</div>";
?>

</body>
</html>