<?php
session_start();
include "php/db.php";

$type = isset($_GET['type']) ? $_GET['type'] : "";

$result = mysqli_query($conn,"SELECT * FROM rides WHERE category='$type'");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Rides</title>
    <style>
        .back-btn{
    background:#e5e7eb;
    padding:8px 15px;
    border-radius:6px;
    text-decoration:none;
    color:#333;
    font-weight:500;
}

.back-btn:hover{
    background:#d1d5db;
}

h1{
    margin-top:40px;
    text-align:center;
}
.ride-card{
    transition:0.3s;
}

.ride-card:hover{
    transform:translateY(-5px);
    box-shadow:0 6px 15px rgba(0,0,0,0.15);
}
.btn{
    display:inline-block;
    padding:8px 12px;
    background:#22c55e;
    color:white;
    border-radius:5px;
    text-decoration:none;
}

.btn:hover{
    background:#16a34a;
}
</style>
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
         <div class="dropdown">
<a href="#">Category </a>

<div class="dropdown-content">
<a href="#thrill">Thrill</a>
<a href="#family">Family</a>
<a href="#mini ride">Mini ride</a>
</div>

</div>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>
<div style="text-align:right; padding:10px 30px;">
    <a href="index.php" class="back-btn">← Back</a>
</div>

<h1 id="thrill">Thrill Rides</h1>

<div class="rides-container">

<?php
$result = mysqli_query($conn,"SELECT * FROM rides WHERE category='Thrill'");
while($row = mysqli_fetch_assoc($result)){
?>
    <div class="ride-card">
        <img src="images/<?php echo trim($row['images']); ?>" alt="ride">
        <h3><?php echo $row['ride_name']; ?></h3>
        <p>Price: ₹<?php echo $row['price']; ?></p>
        <p>Age Limit: <?php echo $row['age_limit']; ?>+</p>
        <a href="<?php echo isset($_SESSION['user_id']) ? 'book.php?id='.$row['id'] : 'login.html'; ?>" class="btn">
Book Now
</a>
        </div>
<?php
}
?>
</div>

<hr>

<h1 id="family">Family Rides</h1>

<div class="rides-container">

<?php
$result = mysqli_query($conn,"SELECT * FROM rides WHERE category='Family'");
while($row = mysqli_fetch_assoc($result)){
?>
    <div class="ride-card">
        <img src="images/<?php echo trim($row['images']); ?>" alt="ride">
        <h3><?php echo $row['ride_name']; ?></h3>
        <p>Price: ₹<?php echo $row['price']; ?></p>
        <p>Age Limit: <?php echo $row['age_limit']; ?>+</p>
         <a href="<?php echo isset($_SESSION['user_id']) ? 'book.php?id='.$row['id'] : 'login.html'; ?>" class="btn">
Book Now
</a>
    </div>
<?php
}
?>
</div>
<hr>

<h1 id="mini ride"> Mini Ride</h1>

<div class="rides-container">

<?php
$result = mysqli_query($conn,"SELECT * FROM rides WHERE category='Mini Ride'");
while($row = mysqli_fetch_assoc($result)){
?>
    <div class="ride-card">
        <img src="images/<?php echo trim($row['images']); ?>" alt="ride">
        <h3><?php echo $row['ride_name']; ?></h3>
        <p>Price: ₹<?php echo $row['price']; ?></p>
        <p>Age Limit: <?php echo $row['age_limit']; ?>+</p>
       <a href="<?php echo isset($_SESSION['user_id']) ? 'book.php?id='.$row['id'] : 'login.html'; ?>" class="btn">
Book Now
</a>
    </div>
<?php
}
?>
</div>

</body>
</html>


