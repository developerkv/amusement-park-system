<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "user"){
    header("Location: login.html");
    exit();
}

include "php/db.php";
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Rides</title>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <span class="joy-text">JOY</span>
    </div>

    <div class="nav-links">
        <a href="rides.php">Home</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>
<hr>    

<h1>Thrill Rides</h1>

<?php
$result = mysqli_query($conn,"SELECT * FROM rides WHERE category='Thrill'");
while($row = mysqli_fetch_assoc($result)){
?>
    <div class="ride-card">
        <img src="images/<?php echo trim($row['images']); ?>" alt="ride">
        <h3><?php echo $row['ride_name']; ?></h3>
        <p>Price: ₹<?php echo $row['price']; ?></p>
        <p>Age Limit: <?php echo $row['age_limit']; ?>+</p>
        <a href="book.php?id=<?php echo $row['id']; ?>" class="btn">Book Now</a>
    </div>
<?php
}
?>

<hr>

<h1>Family Rides</h1>

<?php
$result = mysqli_query($conn,"SELECT * FROM rides WHERE category='Family'");
while($row = mysqli_fetch_assoc($result)){
?>
    <div class="ride-card">
        <img src="images/<?php echo trim($row['images']); ?>" alt="ride">
        <h3><?php echo $row['ride_name']; ?></h3>
        <p>Price: ₹<?php echo $row['price']; ?></p>
        <p>Age Limit: <?php echo $row['age_limit']; ?>+</p>
        <a href="book.php?id=<?php echo $row['id']; ?>" class="btn">Book Now</a>
    </div>
<?php
}
?>

<hr>

<h1> Water Rides</h1>

<?php
$result = mysqli_query($conn,"SELECT * FROM rides WHERE category='Water'");
while($row = mysqli_fetch_assoc($result)){
?>
    <div class="ride-card">
        <img src="images/<?php echo trim($row['images']); ?>" alt="ride">
        <h3><?php echo $row['ride_name']; ?></h3>
        <p>Price: ₹<?php echo $row['price']; ?></p>
        <p>Age Limit: <?php echo $row['age_limit']; ?>+</p>
        <a href="book.php?id=<?php echo $row['id']; ?>" class="btn">Book Now</a>
    </div>
<?php
}
?>

</body>
</html>