<?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["u"])){
	// CONNECT TO THE DATABASE
	include_once("php_includes/db_connect.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$fname = preg_replace('#[^a-z0-9]#i', '', $_POST['fname']);
	$lname = preg_replace('#[^a-z0-9]#i', '', $_POST['lname']);
	$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$e = strtolower($e);
	$p = $_POST['p'];
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
	$sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
	$u_check = mysqli_num_rows($query);
	// -------------------------------------------
	$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql); 
	$e_check = mysqli_num_rows($query);
	// FORM DATA ERROR HANDLING
	if($u == "" || $e == "" || $p == "" || $fname == "" || $lname == ""){
		echo "The form submission is missing values.";
        exit();
	} else if ($u_check > 0){ 
        echo "The username you entered is alreay taken";
        exit();
	} else if ($e_check > 0){ 
        echo "That email address is already in use in the system";
        exit();
	} else if (strlen($u) < 3 || strlen($u) > 16) {
        echo "Username must be between 3 and 16 characters";
        exit(); 
    }
    else if (strlen($p) < 7 ){
        echo "Password must be more than 7 characters";
        exit(); 
    } else if (is_numeric($u[0])) {
        echo 'Username cannot begin with a number';
        exit();
    } else {
	// END FORM DATA ERROR HANDLING
	    // Begin Insertion of data into the database
		// Hash the password and apply your own mysterious unique salt
    	$p_hash =md5($p);
		// Add user info into the database table for the main site table
		$sql = "INSERT INTO users (id, first_name, last_name, username, email, password, ip, signup, lastlogin)       
		        VALUES('', '$fname','$lname','$u','$e','$p_hash','$ip',now(),now())";
		$query = mysqli_query($db_conx, $sql); 
		// Establish their row in the useroptions table
		$sql = "INSERT INTO useroptions (id, username, background) VALUES ('','$u','original')";
		$query = mysqli_query($db_conx, $sql);
		echo "Sign up successful.";
		// Email the user their activation link
		$to = "$e";							 
		$from = "techsupport@truthmug.com";
		$subject = 'Truthmug Account Activation';
		$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Truthmug Message</title>
		</head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;">
		<div style="padding:10px; background:#333; font-size:24px; color:#CCC;">
		Truthmug Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br />
		Click the link below to activate your account when ready:<br /><br />
		<a href="http://www.truthmug.com/activation.php?u='.$u.'&e='.$e.'&p='.$p_hash.'">
		Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />
		* E-mail Address: <b>'.$e.'</b></div></body></html>';
		$headers = "From: $from\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		mail($to, $subject, $message, $headers, '-freturn@truthmug.com');
		echo " Check your inbox, spam folder and click on the acitvation link in it to activate you account.";
		exit();
	}
	exit();
}
?>