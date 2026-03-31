<?php
include "php/db.php";

$message = "";

if(isset($_POST['add_ride'])){

$ride_name = $_POST['ride_name'];
$category = $_POST['category'];
$price = $_POST['price'];
$age_limit = $_POST['age_limit'];
$description = $_POST['description'];
$capacity = $_POST['capacity'];

$image_name = $_FILES['image']['name'];
$tmp_name = $_FILES['image']['tmp_name'];

move_uploaded_file($tmp_name,"images/".$image_name);

$sql = "INSERT INTO rides 
(ride_name,price,age_limit,description,images,category,capacity)
VALUES 
('$ride_name','$price','$age_limit','$description','$image_name','$category','$capacity')";

if(mysqli_query($conn,$sql)){
$message = "Ride Added Successfully";
}else{
$message = "Error: ".mysqli_error($conn);
}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Ride</title>
</head>
<body>

<h2>Add New Ride</h2>

<?php
if($message != ""){
echo "<p style='color:green;font-weight:bold;'>$message</p>";
}
?>

<form method="POST" enctype="multipart/form-data">

<label>Ride Name:</label><br>
<input type="text" name="ride_name" required>
<br><br>

<label>Category:</label><br>
<select name="category" required>
<option value="Thrill">Thrill</option>
<option value="Family">Family</option>
<option value="Mini ride">Mini ride</option>
</select>
<br><br>

<label>Price:</label><br>
<input type="number" name="price" required>
<br><br>

<label>Age Limit:</label><br>
<p>Age Limit: <?php echo $row['age_limit']; ?>+</p>
<br><br>

<label>Description:</label><br>
<textarea name="description" required></textarea>
<br><br>

<label>Capacity:</label><br>
<input type="number" name="capacity" required>
<br><br>

<label>Ride Image:</label><br>
<input type="file" name="image" required>
<br><br>

<button type="submit" name="add_ride">Add Ride</button>

</form>

</body>
</html>