<?php
include_once("php_includes/check_login_status.php");
// If user is already logged in, header that weenis away
if($user_ok == true){
	header("location: profile.php?u=".$_SESSION["username"]);
    exit();
}
?>
<!doctype html>
<html>
<head>
	<title>Forgot Password</title>
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<script src="js/main.js"></script>
	<script src="js/jquery.js"></script>
	<script src="js/ajax.js"></script>
<script>
function forgotpass(){
	var e = _("email").value;
	if(e == ""){
		_("status").innerHTML = "Type in your email address";
	} else {
		_("forgotpassbtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "emailsendcheck.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
				var response = ajax.responseText;
				if(response == "success"){
					_("forgotpassform").innerHTML = '<h3>Step 2. Check your email inbox in a few minutes</h3><p>You can close this window or tab if you like.</p>';
				} else if (response == "no_exist"){
					_("status").innerHTML = "Sorry that email address is not in our system";
					_("forgotpassbtn").style.display = "block";
				} else if(response == "email_send_failed"){
					_("status").innerHTML = "Mail function failed to execute";
					_("forgotpassbtn").style.display = "block";
				} 
				 else {
					_("status").innerHTML = response;
					_("forgotpassbtn").style.display = "block";
				}
	        }
        }
        ajax.send("e="+e);
	}
}
</script>
</head>
<body>
<div id="forgotpasspage">
<div id="forgotpassimage"><img src="./img/aboutus.gif" /></div>
</div>
<div id="pagemiddle">
  <h2>Reset Password</h2>
  <form id="forgotpassform" onsubmit="return false;">
    <div>Enter Your Email Address</div>
    <input id="email" type="text" onfocus="_('status').innerHTML='';" maxlength="88">
    <br /><br />
    <button id="forgotpassbtn" onclick="forgotpass()">SUBMIT</button> 
    <p id="status"></p>
  </form>
</div>
</body>
</html