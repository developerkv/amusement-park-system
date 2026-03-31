<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['user_id'])){
header("Location: login.html");
exit();
}

if(!isset($_GET['id'])){
echo "Invalid Payment!";
exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn,"
SELECT b.*, r.ride_name, r.price, r.images
FROM bookings b
JOIN rides r ON b.ride_id = r.id
WHERE b.id='$id'
AND b.user_id='".$_SESSION['user_id']."'");

$data = mysqli_fetch_assoc($result);

if(!$data){
echo "Booking Not Found!";
exit();
}

$amount = $data['amount'];
?>

<!DOCTYPE html>
<html>
<head>

<title>Ride Payment</title>

<style>

body{
font-family: Arial;
background:#f4f4f4;
text-align:center;
}

.payment-box{
width:420px;
margin:80px auto;
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,0.2);
}

.payment-box img{
border-radius:10px;
margin-bottom:15px;
}

.pay-btn{
background:#28a745;
color:white;
border:none;
padding:12px 25px;
font-size:16px;
cursor:pointer;
border-radius:6px;
}

.pay-btn:hover{
background:#218838;
}

</style>

</head>

<body>
    
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
<br>

<div style="text-align:right; margin-bottom:20px;">
 
    <a href="book.php?id=<?php echo $data['ride_id']; ?>" class="back-btn">Back</a>
</div>


<div class="payment-box">

<h2>Ride Payment</h2>

<img src="images/<?php echo $data['images']; ?>" width="200"><br>

<p><b>Ride :</b> <?php echo $data['ride_name']; ?></p>

<p><b>Date :</b> <?php echo $data['booking_date']; ?></p>

<p><b>Slot :</b> <?php echo $data['slot_time']; ?></p>

<p><b>Amount :</b> ₹<?php echo $amount; ?></p>

<form action="payment_success.php" method="POST">

<input type="hidden" name="booking_id" value="<?php echo $id; ?>">

<button class="pay-btn" type="submit">Pay Now</button>

</form>

</div>

</body>
</html>