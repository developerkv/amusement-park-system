<?php
include "php/db.php";

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM rides WHERE id='$id'");

header("Location: manage.php");
?>