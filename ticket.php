<?php
session_start();
include "php/db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.html");
    exit();
}

if(!isset($_GET['id'])){
    echo "Invalid Ticket Access!";
    exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn,
"SELECT b.*, r.ride_name, r.price, r.images
 FROM bookings b
 JOIN rides r ON b.ride_id = r.id
 WHERE b.id='$id'
 AND b.user_id='".$_SESSION['user_id']."'");

$data = mysqli_fetch_assoc($result);

if(!$data){
    echo "Ticket Not Found!";
    exit();
}

$amount = $data['amount'];
$status = $data['status'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Ticket</title>

<style>
body{
font-family: Arial;
text-align:center;
}

.ticket-box{
border:2px dashed #444;
width:380px;
margin:auto;
padding:20px;
border-radius:10px;
}

.ticket-box img{
border-radius:10px;
}

.ticket-img{
width:100%;
margin-top:15px;
}

</style>

</head>

<body>

<div class="ticket-box">

<h2>JOY Amusement Park Ticket</h2>

<img src="images/<?php echo $data['images']; ?>" width="200"><br><br>

<b>Ride:</b> <?php echo $data['ride_name']; ?><br><br>

<b>Date:</b> <?php echo $data['booking_date']; ?><br><br>

<b>Amount:</b> ₹<?php echo $amount; ?><br><br>

<b>Status:</b> <?php echo $status; ?><br>

<hr>

<!-- FULL TICKET IMAGE WITH QR -->
<?php if(!empty($data['ticket_image'])){ ?>

<img class="ticket-img" src="<?php echo $data['ticket_image']; ?>">

<?php } ?>

<p>Thank You For Booking ❤️</p>

</div>

</body>
</html>