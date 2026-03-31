<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role']!="admin"){
header("Location: login.html");
exit();
}

include "php/db.php";


mysqli_query($conn,"
UPDATE bookings
SET refund_status='Refunded'
WHERE status='Cancelled'
AND refund_status='Pending'
AND cancel_date <= DATE_SUB(CURDATE(), INTERVAL 2 DAY)
");


/* SALARY PAYMENT */

/* BOOKING COUNTERS */

/* TOTAL BOOKED (INCLUDING CANCELLED) */
$total_booked = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount) AS total
FROM bookings
WHERE status !='Cancelled'
AND MONTH(booking_date)=MONTH(CURDATE())
AND YEAR(booking_date)=YEAR(CURDATE())
"));

$total_booked_amount = $total_booked['total'] ?? 0;


/* CANCELLED AMOUNT */
$cancelled = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(amount) AS total
FROM bookings
WHERE status='Cancelled'
AND refund_status!='Refunded'
AND MONTH(booking_date)=MONTH(CURDATE())
AND YEAR(booking_date)=YEAR(CURDATE())
"));

$cancelled_amount = $cancelled['total'] ?? 0;


/* NET INCOME (CORRECT) */




?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>
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




/* DASHBOARD CARDS */
.dashboard{
    display:flex;
    gap:20px;
    padding:20px;
}

.card{
    flex:1;
    padding:20px;
    border-radius:12px;
    color:white;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

.card h4{
    margin:0;
    font-size:16px;
}

.card p{
    font-size:22px;
    font-weight:bold;
    margin-top:10px;
}

.blue{ background:#3b82f6; }
.red{ background:#ef4444; }
.green{ background:#10b981; }

/* FILTER BOX */
.filter-box{
    background:#fff;
    padding:15px;
    margin:20px;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
}

.filter-box input,
.filter-box select{
    padding:8px;
    margin-right:10px;
    border-radius:5px;
    border:1px solid #ccc;
}

.filter-box button{
    padding:8px 15px;
    border:none;
    border-radius:5px;
    background:#4f46e5;
    color:white;
    cursor:pointer;
}

.filter-box button:hover{
    background:#4338ca;
}

/* TABLE */
table{
    width:95%;
    margin:20px auto;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

th{
    background:#1e293b;
    color:white;
}

th,td{
    padding:12px;
    text-align:center;
}

tr:nth-child(even){
    background:#f8fafc;
}

/* BUTTON */
button{
    padding:6px 12px;
    border:none;
    border-radius:5px;
    background:#22c55e;
    color:white;
    cursor:pointer;
}

button:hover{
    background:#16a34a;
}

</style>

</head>

<body>

<div class="navbar">

<div class="logo">
<span class="joy-text">JOY</span>
</div>

<h3>Admin Dashboard</h3>

<div class="nav-links">
<a href="manage.php">Manage</a>
<a href="admin_logout.php">Logout</a>
</div>

</div>


<div style="display:flex; justify-content:space-between; margin:20px 0;">

<div style="background:#3498db;color:white;padding:15px;border-radius:8px;">
<h4>Total Booked</h4>
₹ <?php echo $total_booked_amount; ?>
</div>

<div style="background:#e74c3c;color:white;padding:15px;border-radius:8px;">
<h4>Cancelled</h4>
₹ <?php echo $cancelled_amount; ?>
</div>



</div>


<hr>


<h3>All Bookings</h3>

<form method="GET" style="margin:20px 0;">

From: <input type="date" name="from_date">
To: <input type="date" name="to_date">

Date:
<input type="date" name="single_date">

Month:
<select name="month">
<option value="">All</option>
<?php
for($m=1;$m<=12;$m++){
echo "<option value='$m'>".date("F", mktime(0,0,0,$m,1))."</option>";
}
?>
</select>

<button type="submit">Filter</button>

</form>
<a href="?past=1">
<button type="button">Past Bookings</button>
</a>


<table>

<tr>
<th>Booking ID</th>
<th>User</th>
<th>Ride</th>
<th>Date</th>
<th>Slot</th>
<th>Amount</th>
<th>Status</th>
</tr>

<?php

$where = "1"; // default

/* SINGLE DATE FILTER (TOP PRIORITY) */
if(!empty($_GET['single_date'])){
    $single = $_GET['single_date'];
    $where .= " AND bookings.booking_date = '$single'";
}

/* DATE RANGE */
else if(!empty($_GET['from_date']) && !empty($_GET['to_date'])){
    $from = $_GET['from_date'];
    $to = $_GET['to_date'];
    $where .= " AND bookings.booking_date BETWEEN '$from' AND '$to'";
}

/* MONTH FILTER */
if(!empty($_GET['month'])){
    $month = $_GET['month'];
    $where .= " AND MONTH(bookings.booking_date)='$month'";
}

/* PAST BOOKINGS */
if(isset($_GET['past'])){
    $where .= " AND bookings.booking_date < CURDATE()";
}

$result = mysqli_query($conn,"
SELECT 
bookings.id,
users.name,
rides.ride_name,
bookings.booking_date,
bookings.slot_time,
bookings.amount,
bookings.status
FROM bookings
LEFT JOIN users ON bookings.user_id = users.id
LEFT JOIN rides ON bookings.ride_id = rides.id
WHERE $where
ORDER BY bookings.id DESC
");


while($row=mysqli_fetch_assoc($result)){

$user = $row['name'] ? $row['name'] : "Walk-In";

echo "<tr>";

echo "<td>".$row['id']."</td>";
echo "<td>".$user."</td>";
echo "<td>".$row['ride_name']."</td>";
echo "<td>".$row['booking_date']."</td>";
echo "<td>".$row['slot_time']."</td>";
echo "<td>₹".$row['amount']."</td>";
echo "<td>".$row['status']."</td>";

echo "</tr>";

}

?>

</table>

<hr>


<h3>Refund Status</h3>

<table>
<tr>
<th>Booking ID</th>
<th>User</th>
<th>Amount</th>
<th>Cancelled Date</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php

$q = mysqli_query($conn,"
SELECT 
b.id, 
u.name, 
b.amount, 
b.cancel_date, 
b.refund_status
FROM bookings b
LEFT JOIN users u ON b.user_id = u.id
WHERE b.status='Cancelled'
AND b.refund_status!='Refunded'
ORDER BY b.id DESC
");

if(mysqli_num_rows($q) == 0){
echo "<tr><td colspan='5'>No Cancelled Bookings</td></tr>";
}

while($r = mysqli_fetch_assoc($q)){

echo "<tr>";

echo "<td>".$r['id']."</td>";

/* USER NAME (if null) */
$user = $r['name'] ? $r['name'] : "Walk-In";
echo "<td>".$user."</td>";

echo "<td>₹".$r['amount']."</td>";

/* DATE FIX */
$date = $r['cancel_date'] ? date("d M Y", strtotime($r['cancel_date'])) : "Not Updated";
echo "<td>".$date."</td>";

/* STATUS COLOR */
$status = $r['refund_status'];

if($status == "Refunded"){
echo "<td style='color:green;'>Refunded ✔</td>";
}else{
echo "<td style='color:red;'>Pending</td>";
}

$status = $r['refund_status'];

if($status == "Refunded"){
    echo "<td style='color:green;'>Refunded ✔</td>";
    echo "<td>-</td>";
}else{
    echo "<td style='color:red;'>Pending</td>";
    echo "<td>
    <a href='repay.php?id=".$r['id']."'>
    <button>Pay</button>
    </a>
    </td>";
}

echo "</tr>";
}

?>


</table>

</body>
</html>