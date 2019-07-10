<?php include_once("php_includes/db_connect.php");?>

<?php 
	if (isset($_POST['post'])) {

  $postinitial = @$_POST['post'];
  $post = htmlspecialchars($postinitial, ENT_QUOTES);
if ($post !="") {
	$date_added = date("Y-m-d");
	$added_by = $usersession;

	$user_posted_to = $username;

	$sqlCommand = "INSERT INTO posts VALUES ('', '$post','$added_by','$user_posted_to', now(), '0')";
	$query = mysqli_query($db_conx, $sqlCommand);
}
}else{
	echo "post not set";
}
?>