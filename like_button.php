<?php
include_once("php_includes/check_login_status.php");
if($user_ok != true || $log_username == "") {
      exit();
}
?>
<?php
if (isset($_POST['name']) && isset($_POST['action']) && isset($_POST['reqid'])){
	$reqid = preg_replace('#[^0-9]#', '', $_POST['reqid']);
	$en_name = preg_replace('#[^a-zA-Z0-9]#', '', $_POST['name']);
	if ($_POST['action'] == "likepost") {
	$likeiquery = "INSERT INTO likes VALUES ('','$en_name','$reqid')";
	$executelike = mysqli_query($db_conx, $likeiquery);
			mysqli_close($db_conx);
			if($executelike){echo "likeexecuted";}
			else{echo "unable";}
	}else if($_POST['action'] == "unlike"){
		$remove_likequery = "DELETE FROM likes WHERE username='$en_name' AND postid='$reqid'";
	
	
		if($remove_likequery){echo "disliked";}
		else{echo "unlikeunable";}
	}
	}
	?>