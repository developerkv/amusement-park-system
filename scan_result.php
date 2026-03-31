<?php
include "php/db.php";

if(isset($_FILES['qr_image'])){

    $file = $_FILES['qr_image']['tmp_name'];

    echo "<h3>QR Image Uploaded Successfully</h3>";

    echo "<img src='".$file."' width='200'><br><br>";

    echo "Now decode using online tool:<br>";
    echo "<a href='https://zxing.org/w/decode.jspx' target='_blank'>Open QR Decoder</a>";
}
?>