<?php
session_start();
include "php/db.php";

if(!isset($_SESSION['role']) || $_SESSION['role']!="staff"){
header("Location: login.html");
exit();
}

$category = $_SESSION['ride_category'];
$staff_id = $_SESSION['staff_id'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Walk-In Booking</title>
<link rel="stylesheet" href="css/style.css">
<style>
.navbar{
display:flex;
justify-content:space-between;
align-items:center;
padding:10px;
background: linear-gradient(45deg,#ff512f,#dd2476);
}

.navbar h3{
color:white;
}

.nav-links a{
color:white;
text-decoration:none;
margin-left:20px;
font-weight:bold;
}

.nav-links a:hover{
color:yellow;
}
/* CONTAINER */
.container{
    width:400px;
    margin:40px auto;
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    text-align:center;
}

/* INPUT */
select{
    width:100%;
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
}

/* BUTTON */
button{
    width:100%;
    padding:10px;
    border:none;
    border-radius:6px;
    background:#22c55e;
    color:white;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    background:#16a34a;
}

/* BACK BUTTON */
.back-btn{
    background:#e5e7eb;
    padding:8px 15px;
    border-radius:6px;
    text-decoration:none;
    color:#333;
}

.back-btn:hover{
    background:#d1d5db;
}

/* TEXT */
h2,h3{
    text-align:center;
}

.info{
    color:#555;
    font-size:14px;
}

.success{
    color:green;
    font-weight:bold;
}

.error{
    color:red;
    font-weight:bold;
}

</style>

</head>

<body>

<div class="navbar">

<div class="logo">
<span class="joy-text">JOY</span>
</div>
<h3>walkin booking</h3>
<div class="nav-links">
<a href="walkin_booking.php">New book</a>
<a href="scan_ticket.php">Verify ticket</a>
<a href="staff_logout.php">Logout</a>
</div>
</div>



<?php

/* STEP 1 */
if(!isset($_POST['ride_id'])){
?>

<div class="container">

<h2>Walk-In Booking</h2>

<form method="POST">

<label>Select Ride</label><br><br>

<select name="ride_id" required>

<?php
$r = mysqli_query($conn,"SELECT id,ride_name FROM rides WHERE category='$category'");
while($row=mysqli_fetch_assoc($r)){
?>
<option value="<?php echo $row['id']; ?>">
<?php echo $row['ride_name']; ?>
</option>
<?php } ?>

</select>

<br><br>

<button type="submit">Next →</button>

</form>

</div>

<?php
exit();
}


/* STEP 2 */
if(!isset($_POST['slot_time'])){

$ride_id = intval($_POST['ride_id']);
$date = date("Y-m-d");

$ride_query = mysqli_query($conn,"SELECT ride_name,capacity FROM rides WHERE id='$ride_id'");
$ride = mysqli_fetch_assoc($ride_query);

$capacity = $ride['capacity'];

$slots = ["10AM-12PM","12PM-2PM","2PM-4PM","4PM-6PM"];

echo "<div class='container'>";

echo "<h3>".$ride['ride_name']."</h3>";
echo "<p class='info'>Capacity: ".$capacity."</p>";

echo "<form method='POST'>";
echo "<input type='hidden' name='ride_id' value='$ride_id'>";

echo "<label>Select Slot</label><br><br>";
echo "<select name='slot_time' required>";

foreach($slots as $s){

$count_query = mysqli_query($conn,"
SELECT COUNT(*) as total FROM bookings
WHERE ride_id='$ride_id'
AND booking_date='$date'
AND slot_time='$s'
AND status IN ('Confirmed','Walk-In')");

$count = mysqli_fetch_assoc($count_query);

$remaining = $capacity - $count['total'];

if($remaining <= 0){
echo "<option disabled>$s (FULL)</option>";
}else{
echo "<option value='$s'>$s ($remaining seats left)</option>";
}

}

echo "</select>";

echo "<br><br>";

echo "<button type='submit'>Create Ticket</button>";

echo "</form>";

echo "</div>";

exit();
}


/* STEP 3 */
$ride_id = intval($_POST['ride_id']);
$slot = $_POST['slot_time'];
$date = date("Y-m-d");

$ride_query = mysqli_query($conn,"SELECT capacity,price FROM rides WHERE id='$ride_id'");
$ride = mysqli_fetch_assoc($ride_query);

$capacity = $ride['capacity'];
$price = $ride['price'];

$count_query = mysqli_query($conn,"
SELECT COUNT(*) as total FROM bookings
WHERE ride_id='$ride_id'
AND booking_date='$date'
AND slot_time='$slot'
AND status IN ('Confirmed','Walk-In')");

$count = mysqli_fetch_assoc($count_query);
$booked = $count['total'];

if($booked >= $capacity){

echo "<div class='container'>";
echo "<h3 class='error'>Slot Full 😓</h3>";
echo "<p>$slot</p>";
echo "<a href='walkin_booking.php'><button>Try Again</button></a>";
echo "</div>";

}else{

$amount = $price + 50;

mysqli_query($conn,"
INSERT INTO bookings
(user_id,ride_id,booking_date,slot_time,amount,status,staff_id)
VALUES
('0','$ride_id','$date','$slot','$amount','Walk-In','$staff_id')");

$booking_id = mysqli_insert_id($conn);

echo "<div class='container'>";
echo "<h3 class='success'>Ticket Created ✅</h3>";
echo "<p>Slot: $slot</p>";
echo "<p><strong>Amount: ₹ $amount</strong></p>";

echo "<br>";

echo "<a href='walkin_booking.php'><button>New Booking</button></a><br><br>";
echo "<a href='staff_panel.php'><button>Back</button></a>";

echo "</div>";

}

?>

</body>
</html>