<?php
session_start();
include "php/db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.html");
    exit();
}

if(!isset($_GET['id'])){
    echo "Invalid Ride!";
    exit();
}

$ride_id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM rides WHERE id=$ride_id");

if(mysqli_num_rows($result) == 0){
    echo "Ride Not Found!";
    exit();
}

$ride = mysqli_fetch_assoc($result);




?>

<!DOCTYPE html>
<html>
<head>
<title>Book Ride</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="navbar">
    <div class="logo">
        <span class="joy-text">JOY</span>
    </div>
    <span class="user-name">
Welcome <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ""; ?>
</span>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>
<h1>Book Ride</h1>
<div style="text-align:right; margin-bottom:20px;">
    <a href="category.php" class="back-btn">Back to Rides</a>
</div>



<div class="ride-card">

<img src="images/<?php echo $ride['images']; ?>" 
width="250"
style="border-radius:10px; margin-bottom:10px;">

<h3><?php echo $ride['ride_name']; ?></h3>

Price: ₹<?php echo $ride['price']; ?><br>

Age Limit: <?php echo $ride['age_limit']; ?>+

</div>


<form action="confirm_booking.php" method="POST">

<input type="hidden" name="ride_id" value="<?php echo $ride_id; ?>">

<br>

<label>Select Date:</label><br>

<input type="date"
name="booking_date"
min="<?php echo date('Y-m-d'); ?>"
required>

<br><br>


<label>Select Time Slot:</label><br>

<select name="slot_time" required>

<?php

$slot_query = mysqli_query($conn,
"SELECT slot_time FROM ride_slots WHERE ride_id='$ride_id'");

while($slot = mysqli_fetch_assoc($slot_query)){

?>

<option value="<?php echo $slot['slot_time']; ?>">

<?php echo $slot['slot_time']; ?>

</option>

<?php } ?>

</select>

<br><br>

<button type="submit">Confirm Booking</button>

</form>


</body>
</html>