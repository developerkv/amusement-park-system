
<?php
session_start();

/* ADMIN ACCESS CHECK */

if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
header("Location: login.html");
exit();
}

include "php/db.php";

$msg = "";

/* ADD RIDE */

if(isset($_POST['add_ride'])){

$ride_name = $_POST['ride_name'];
$category = $_POST['category'];
$price = $_POST['price'];
$age_limit = $_POST['age_limit'];
$capacity = $_POST['capacity'];
$description = $_POST['description'];

/* IMAGE UPLOAD */

$image = $_FILES['image']['name'];
$temp = $_FILES['image']['tmp_name'];

$folder = "images/".$image;

move_uploaded_file($temp,$folder);

/* INSERT */

mysqli_query($conn,"INSERT INTO rides
(ride_name,category,price,age_limit,capacity,description,images)
VALUES
('$ride_name','$category','$price','$age_limit','$capacity','$description','$image')");

$msg = "<span style='color:green;font-weight:bold;'>Ride added successfully</span>";
}


/* CREATE STAFF */

if(isset($_POST['create_staff'])){

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$ride_category = $_POST['ride_category'];

/* check email already exists */

$check = mysqli_query($conn,"SELECT * FROM staff WHERE email='$email'");

if(mysqli_num_rows($check) > 0){

$msg = "<span style='color:red;font-weight:bold;'>Email already exists</span>";

}else{

mysqli_query($conn,"INSERT INTO staff (name,email,password,ride_category)
VALUES ('$name','$email','$password','$ride_category')");

$msg = "<span style='color:green;font-weight:bold;'>Staff created successfully</span>";

}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Rides & Staff</title>
<link rel="stylesheet" href="css/style.css">

<style>

.success{
background:#d4edda;
color:#155724;
padding:10px;
border-radius:5px;
width:300px;
margin-bottom:10px;
}

table{
border-collapse:collapse;
width:70%;
}

table th,table td{
padding:8px;
border:1px solid #ccc;
}

.form-container{
    display:flex;
    gap:25px;
    padding:20px;
}

.form-box{
    flex:1;
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    transition:0.3s;
}

.form-box:hover{
    transform:translateY(-3px);
}

.form-box h2{
    margin-bottom:15px;
}

/* INPUTS */
input, select, textarea{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    border-radius:6px;
    border:1px solid #ccc;
}

textarea{
    resize:none;
}

/* BUTTON */
button{
    width:100%;
    padding:10px;
    border:none;
    border-radius:6px;
    background:#2563eb;
    color:white;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    background:#1d4ed8;
}

/* SUCCESS MESSAGE */
.success{
    background:#d1fae5;
    color:#065f46;
    padding:10px;
    border-radius:6px;
    margin-bottom:10px;
}

/* TABLE */
table{
    width:90%;
    margin:20px auto;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

th{
    background:#1e293b;
    color:#fff;
}

th, td{
    padding:12px;
    text-align:center;
}

tr:nth-child(even){
    background:#f9fafb;
}

a{
    color:red;
    text-decoration:none;
    font-weight:bold;
}

</style>

</head>

<body>

<!-- NAVBAR -->

<div class="navbar">
<div class="logo">
<span class="joy-text">JOY</span>
</div>

<div class="nav-links">
<a href="admin_dashboard.php">Dashboard</a>
<a href="admin_logout.php">Logout</a>
</div>
</div>

<div class="form-container">

    <!-- ADD RIDE -->
    <div class="form-box">
        <h2>Add Ride</h2>

        <?php if($msg!=""){ echo "<div class='success'>$msg</div>"; } ?>

        <form method="POST" enctype="multipart/form-data">

        <label>Ride Name</label>
        <input type="text" name="ride_name">

        <label>Category</label>
        <select name="category">
            <option>Thrill</option>
            <option>Family</option>
            <option>Mini Ride</option>
        </select>

        <label>Price</label>
        <input type="number" name="price">

        <label>Age Limit</label>
        <input type="number" id="age_limit" name="age_limit" oninput="showPlus()">
        <span id="ageDisplay"></span>

        <label>Capacity</label>
        <input type="number" name="capacity">

        <label>Description</label>
        <textarea name="description"></textarea>

        <label>Image</label>
        <input type="file" name="image">

        <button name="add_ride">Add Ride</button>

        </form>
    </div>


    <!-- CREATE STAFF -->
    <div class="form-box">
        <h2>Create Staff</h2>

        <form method="POST"> 

        <label>Name</label>
        <input type="text" data type="char" name="name" autocomplete="off">

        <label>Email</label>
        <input type="email" name="email" autocomplete="off">

        <label>Password</label>
        <input type="password" name="password" autocomplete="new-password">

        <label>Ride Category</label>
        <select name="ride_category">
            <option>Thrill</option>
            <option>Family</option>
            <option>Mini Ride</option>
        </select>

        <button name="create_staff">Create Staff</button>

        </form>
    </div>

</div>


<!-- RIDES LIST -->

<h2>Rides List</h2>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Category</th>
<th>Price</th>
<th>Action</th>
</tr>

<?php
$r = mysqli_query($conn,"SELECT * FROM rides");

while($row=mysqli_fetch_assoc($r)){
?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['ride_name']; ?></td>
<td><?php echo $row['category']; ?></td>
<td><?php echo $row['price']; ?></td>
<td>
<a href="delete_ride.php?id=<?php echo $row['id']; ?>">Delete</a>
</td>
</tr>

<?php } ?>

</table>


<hr>


<!-- STAFF LIST -->

<h2>Staff List</h2>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Ride Category</th>
<th>Action</th>
</tr>

<?php
$s = mysqli_query($conn,"SELECT * FROM staff");

while($row=mysqli_fetch_assoc($s)){
?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['ride_category']; ?></td>
<td>
<a href="delete_staff.php?id=<?php echo $row['id']; ?>">Delete</a>
</td>
</tr>

<?php } ?>

</table>

</body>
</html>
