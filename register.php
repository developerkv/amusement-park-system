<?php
include "db.php";

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$phone=$_POST['phone'];

if(!preg_match("/^[0-9]{10}$/",$phone)){
echo "Invalid phone number";
exit();
}

mysqli_query($conn,"INSERT INTO users 
(name,email,password,phone)
VALUES ('$name','$email','$password','$phone')");

header("Location: ../login.html");
?>