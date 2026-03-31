<?php
session_start();
include "db.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$email=$_POST['email'];
$password=$_POST['password'];

# ADMIN
$admin=mysqli_query($conn,"SELECT * FROM admins WHERE email='$email' AND password='$password'");
if(mysqli_num_rows($admin)>0){

$_SESSION['role'] = "admin";

header("Location: http://localhost/amusement_park/admin_dashboard.php");
exit();
}

# STAFF
$staff=mysqli_query($conn,"SELECT * FROM staff WHERE email='$email' AND password='$password'");
if(mysqli_num_rows($staff)>0){

$row=mysqli_fetch_assoc($staff);

$_SESSION['staff_id'] = $row['id'];
$_SESSION['user_name'] = $row['name'];
$_SESSION['role'] = $row['role'];
$_SESSION['ride_category'] = $row['ride_category'];


header("Location: http://localhost/amusement_park/staff_panel.php");

exit();
}

# USER
$user=mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND password='$password'");

if(mysqli_num_rows($user)>0){

$row=mysqli_fetch_assoc($user);

$_SESSION['user_id'] = $row['id'];
$_SESSION['user_name'] = $row['name'];
$_SESSION['role'] = "user";


/* redirect ride check */
if(isset($_SESSION['redirect_ride'])){

$ride_id = $_SESSION['redirect_ride'];
unset($_SESSION['redirect_ride']);

header("Location: http://localhost/amusement_park/book.php?id=$ride_id");

}else{

header("Location: http://localhost/amusement_park/category.php");

}

exit();
}

$_SESSION['error']="Invalid Email or Password";

header("Location: http://localhost/amusement_park/login.html?error=1");

exit();


}
?>