<?php include_once("php_includes/check_login_status.php");?>
<?php
$total = "SELECT id FROM users";
$totalexec = mysqli_query($db_conx, $total);
$count = mysqli_num_rows($totalexec);
echo "<h2>Total users are $count </h2>";
?>