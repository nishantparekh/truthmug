<?php
if(isset($_GET['r']) && isset($_GET['u'])){
	include_once("php_includes/db_connect.php");
	$r1 = preg_replace('#[^a-z0-9]#i', '', $_GET['r']);
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
	if(strlen($r1) != 96){
	$false = 1;
		exit();
	}
	
	$sql = "SELECT id, username, email, each_time FROM users WHERE username='$u' AND activated='1' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows > 0 && $numrows ==1){
		while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
			$id = $row["id"];
			$uname = $row["username"];
			$e = $row["email"];
			$eachtime = $row["each_time"];
		}
		$idstr = (string)$id;
		$eachtimestr = (string)$eachtime;
		$implement = "{$idstr}{$eachtimestr}{$uname}{$e}";
		$hashimplement = md5($implement);
		$s = md5($eachtime);
		$t2 = "{$eachtime}{$idstr}";
		$t = md5($t2);
		$r = $s.$hashimplement.$t;
	if($r != $r1){
		$false = 1;
	} 
}}else {$false = 1;}
?>
<!doctype html>
<html>
<head>
	<title>Forgot Password</title>
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<script src="js/main.js"></script>
	<script src="js/jquery.js"></script>
	<script src="js/ajax.js"></script>

</head>
<body>
<div id="forgotpasspage">
<div id="forgotpassimage"><img src="./img/aboutus.gif" /></div>
</div>
<div id="pagemiddle">
<?php 
if($false ==1){
echo " This link to reset the password is not valid anymore. Please enter your email id again to get the password reset link again. ";
}

else {
echo '
<table>
<tr><td><font class="info">Password:</font></td><td> <input type="text" name="newpassword" id="newpassword" size="40"></td></tr>
<tr><td><font class="info">Repeat Password:</font></td><td> <input type="text" name="newpassword2" id="newpassword2" size="40"><br /></td></tr>
<tr><td><input type="submit" name="senddata" id="senddata" value="Update Password"></tr><td>
</table>
';}
?>
</div>
</body>
</html>