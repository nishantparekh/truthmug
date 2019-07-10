<?php
include_once("../php_includes/db_connect.php");
?>
<?php
date_default_timezone_set('Asia/Kolkata');
$timenow1 = ""; 
$timenow1 = strtotime("now");
$new1 = strftime("%b %d, %Y, %H %M %S", $timenow1);
echo $new1;
echo "<br />";
echo $timenow1;
$select = "SELECT id, delete_initiate, to_be_deleted FROM posts WHERE to_be_deleted='1'";
$executeselect = mysqli_query($db_conx, $select);
echo "<br />";
while ($row = mysqli_fetch_assoc($executeselect)) {
	$id = $row['id'];
echo "<br />";
echo $id;
echo "<br />";
	$check1 = $row['delete_initiate'];
echo $check1;
      $check = strtotime($check1);     
       $status = $row['to_be_deleted'];
	$timeleft = $timenow1 - $check;
echo "<br />";
        echo $timeleft;
echo "<br />";
	$new = strftime("%b %d, %Y, %H %M %S", $check);
echo $new;
	if ($timeleft > 7200) {
		$delte = "DELETE FROM posts WHERE id='$id'";
		$executedelete = mysqli_query($db_conx, $delte);
if($executedelete === TRUE){echo "executed";}else{echo"not executed";}
			}
}

?>