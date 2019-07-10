<?php
if(isset($_POST["e"])){
	include_once("php_includes/db_connect.php");
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$sql = "SELECT id, username, email, each_time FROM users WHERE email='$e' AND activated='1' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows > 0 && $numrows ==1){
		while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
			$id = $row["id"];
			$u = $row["username"];
			$e = $row["email"];
			$eachtime = $row["each_time"];
		}
		$idstr = (string)$id;
		$eachtimestr = (string)$eachtime;
		$implement = "{$idstr}{$eachtimestr}{$u}{$e}";
		$hashimplement = md5($implement);
		$s = md5($eachtime);
		$t2 = "{$eachtime}{$idstr}";
		$t = md5($t2);
		$r = $s.$hashimplement.$t;
		$to = "$e";
		$from = "Team Truthmug<techsupport@truthmug.com>";
		$headers ="From: $from\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1 \n";
		$subject ="Truthmug Password Reset";
		$msg = '<h2>Hello '.$u.'</h2><p>This is an automated message from Truthmug. If you did not recently initiate the Forgot Password process, please disregard this email.</p>
		<p>You indicated that you forgot your login password.  
		</p><p>
		 <a href="http://www.truthmug.com/renewpassword.php?r='.$r.'&u='.$u.'">
		 Click here now to reset your password for your account</a></p>
		 <p>Thank you for your support,<br />Team Truthmug.</p>';
		 
		if(mail($to,$subject,$msg,$headers,'-freturn@truthmug.com')) {
			echo "success";
			exit();
		} else {
			echo "email_send_failed";
			exit();
		}
    } else {
        echo "no_exist";
    }
   exit();
}
?>