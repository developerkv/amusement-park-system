<?php
include "php/db.php";

$id = $_GET['id'];

/* UPDATE STATUS */
mysqli_query($conn,"
UPDATE bookings
SET refund_status='Refunded'
WHERE id='$id'
");

/* MESSAGE + REDIRECT */
echo "<script>
alert('Payment Done & Removed');
window.location='admin_dashboard.php';
</script>";
exit();
?>