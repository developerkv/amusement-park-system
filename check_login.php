<?php
session_start();

$ride_id = $_GET['id'];

if(isset($_SESSION['user_id'])){
    
    // Already login
    header("Location: book.php?id=$ride_id");
    exit();

}else{

    // Not login
    $_SESSION['redirect_ride'] = $ride_id;
    header("Location: login.html");
    exit();
}
?>