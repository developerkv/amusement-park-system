<?php
$conn = mysqli_connect("localhost", "root", "", "amusement_park");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>