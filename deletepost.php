<?php
include_once("php_includes/check_login_status.php");
if($user_ok != true || $log_username == "") {
      exit();
}
?>
<?php
if (isset($_POST['action']) && isset($_POST['reqid'])){
	$reqid = preg_replace('#[^0-9]#', '', $_POST['reqid']);

	if ($_POST['action'] == "delete") {
                date_default_timezone_set('Asia/Kolkata');
                $delete_initiatetime = date("Y-m-d H:i:s");
		$deletequery = "UPDATE posts SET to_be_deleted='1', delete_initiate='$delete_initiatetime' WHERE id='$reqid' LIMIT 1";
		$executedele = mysqli_query($db_conx, $deletequery);
		mysqli_close($db_conx);
		echo "unable";
		exit();
		}
	
	
	else{
		mysqli_close($db_conx);
		echo "unable to process right now";
		
	}
	}
?>
