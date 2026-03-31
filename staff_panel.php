<?php
session_start();
include "php/db.php";

/* AUTH CHECK */
if(!isset($_SESSION['role']) || $_SESSION['role']!="staff"){
    header("Location: login.html");
    exit();
}

$staff_id = $_SESSION['staff_id'];
$category = $_SESSION['ride_category'];

/* BASE WHERE */
$where = "WHERE r.category='$category' AND b.status!='Walk-In'";

/* SINGLE DATE FILTER */
if(!empty($_GET['single_date'])){
    $single = $_GET['single_date'];
    $where .= " AND b.booking_date = '$single'";
}

/* DATE RANGE FILTER */
if(!empty($_GET['from_date']) && !empty($_GET['to_date'])){
    $from = $_GET['from_date'];
    $to = $_GET['to_date'];
    $where .= " AND b.booking_date BETWEEN '$from' AND '$to'";
}

/* MONTH FILTER */
if(!empty($_GET['month'])){
    $m = $_GET['month'];
    $where .= " AND MONTH(b.booking_date)='$m'";
}

/* PAST BOOKINGS */
if(isset($_GET['past'])){
    $where .= " AND b.booking_date < CURDATE()";
}

/* ONLINE BOOKINGS QUERY */
$sql = "SELECT 
b.id,
r.ride_name,
b.booking_date,
b.slot_time,
b.status,
b.amount
FROM bookings b
JOIN rides r ON b.ride_id=r.id
$where
ORDER BY b.id DESC";

$result = mysqli_query($conn,$sql);
$online_count = mysqli_num_rows($result);

/* WALK-IN BOOKINGS */
$walkin_count_query = mysqli_query($conn,"
SELECT COUNT(*) as total
FROM bookings
WHERE status='Walk-In'
AND staff_id='$staff_id'
");

$walkin_data = mysqli_fetch_assoc($walkin_count_query);
$walkin_count = $walkin_data['total'];

$walkin_query = mysqli_query($conn,"
SELECT b.staff_id, r.ride_name, b.booking_date, b.amount
FROM bookings b
JOIN rides r ON b.ride_id = r.id
WHERE b.status='Walk-In'
AND b.staff_id='$staff_id'
AND b.booking_date = CURDATE()
ORDER BY b.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Staff Panel</title>
<link rel="stylesheet" href="css/style.css">

<style>
    <link rel="stylesheet" href="css/style.css">
body{font-family:Arial;background:#f1f5f9;margin:0}

/* NAVBAR */
.navbar{
display:flex;justify-content:space-between;
align-items:center;padding:12px 20px;
background: linear-gradient(45deg,#ff512f,#dd2476);
color:white;
}
.nav-links a{color:white;margin-left:15px;text-decoration:none;font-weight:bold}

/* DASHBOARD */
.dashboard{display:flex;gap:20px;padding:20px}
.card{
flex:1;background:#fff;padding:20px;
border-radius:10px;text-align:center;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
.card p{font-size:22px;font-weight:bold}

/* FILTER */
.filter-box{
background:#fff;margin:20px;padding:15px;
border-radius:10px;
}
.filter-box input,.filter-box select{
padding:8px;margin-right:10px;border-radius:5px;border:1px solid #ccc;
}
.filter-box button{
padding:8px 12px;background:#22c55e;color:white;border:none;border-radius:5px;
}

/* TABLE */
table{
width:95%;margin:20px auto;border-collapse:collapse;
background:#fff;border-radius:10px;overflow:hidden;
}
th{background:#1e293b;color:white}
th,td{padding:12px;text-align:center}
tr:nth-child(even){background:#f8fafc}

.verified{color:green;font-weight:bold}
.notverified{color:red;font-weight:bold}
</style>
</head>

<body>

<div class="navbar">
<div class="logo">
<span class="joy-text">JOY</span>
</div>

<div class="nav-links">
<a href="index.php">Home</a>
<a href="walkin_booking.php">New Book</a>
<a href="scan_ticket.php">Verify tickets</a>
<a href="staff_logout.php">Logout</a>
</div>
</div>

<h2 style="text-align:center;margin-top:20px;">
Staff Panel - <?php echo $category; ?> Rides
</h2>

<!-- DASHBOARD -->
<div class="dashboard">
<div class="card">
<h3>Online Bookings</h3>
<p><?php echo $online_count; ?></p>
</div>

<div class="card">
<h3> Walk-In Bookings</h3>
<p><?php echo $walkin_count; ?></p>
</div>
</div>

<!-- FILTER -->
<div class="filter-box">
<form method="GET">
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

<a href="?past=1"><button type="button">Past</button></a>
</form>
</div>

<!-- ONLINE BOOKINGS -->
<h3 style="text-align:center;">Online Bookings</h3>

<table>
<tr>
<th>ID</th>
<th>Ride</th>
<th>Date</th>
<th>Slot</th>
<th>Status</th>
<th>Amount</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($result)){
echo "<tr>";
echo "<td>".$row['id']."</td>";
echo "<td>".$row['ride_name']."</td>";
echo "<td>".$row['booking_date']."</td>";
echo "<td>".$row['slot_time']."</td>";

if($row['status']=="Verified"){
echo "<td class='verified'>Verified</td>";
}else{
echo "<td class='notverified'>Not Verified</td>";
}

echo "<td>₹".$row['amount']."</td>";
echo "</tr>";
}
?>
</table>

<!-- WALK-IN BOOKINGS -->
<h3 style="text-align:center;">Today Walk-In Bookings</h3>

<table>
<tr>
<th>Staff ID</th>
<th>Ride</th>
<th>Date</th>
<th>Amount</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($walkin_query)){
echo "<tr>";
echo "<td>".$row['staff_id']."</td>";
echo "<td>".$row['ride_name']."</td>";
echo "<td>".$row['booking_date']."</td>";
echo "<td>₹".$row['amount']."</td>";
echo "</tr>";
}
?>
</table>

</body>
</html>