<?php
include_once("php_includes/check_login_status.php");
// If user is already logged in, header that weenis away
if($user_ok == true){
	$usernameprofile = $_SESSION["username"];
	header("location: $usernameprofile");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Truthmug</title>
	<meta name="keywords" content="truthmug, connect with truthmug, social networking site, anonymous social network" />
	<meta name="description" content="Truthmug is an anonymous social networking website which allows you to express the true feeling related to anyone freely. Read and write about anyone you feel like while staying anonymous. Sign up now and connect with people across the globe." />
	<meta name="author" content="Nishant Parekh and Raunak Parekh" />
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<link rel="stylesheet" type="text/css" href="css/style1.css"/>
	<script src="js/main.js"></script>
	<script src="js/jquery.js"></script>							
	<script src="js/ajax.js"></script>
	<script type="text/javascript">
	//$(document).ready(function(){
		//$('#openlogin').click(function(){
			//$('#loginform').fadeToggle(200);
		//}); //end toggle
	//}); // end ready 
		
	function emptyElement(x){
	_(x).innerHTML = "";
}
function login(){
	var elogin = _("email").value;
	var plogin = _("plogin").value;
	if(elogin == "" || plogin == ""){
		_("status").innerHTML = "Fill out all of the Login data";
	} else { _("loginbtn").innerHTML = "Log in";
		 _("loginbtn").style.backgroundColor = "#aaa";
		_("loginbtn").disabled = true;
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "login.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText == "login_failed"){
	           			 
					 _("status").innerHTML = "Login unsuccessful, please try again.";
					 _("loginbtn").style.display = "inline";
					 _("loginbtn").style.backgroundColor = "#336699";
					 _("loginbtn").disabled = false;
					 _("loginbtn").innerHTML = "Log in";
				} else {
					window.location = ajax.responseText;
				}
	        }
        } 
        ajax.send("elogin="+elogin+"&plogin="+plogin);
	}
}
function restrict(elem){
	var tf = _(elem);
	var rx = new RegExp;
	if(elem == "email"){
		rx = /[' ']/gi;
	} else if(elem == "username"){
		rx = /[^a-z0-9]/gi;
	}
	tf.value = tf.value.replace(rx, "");
}
function checkpassword(){
var p1 = _("pass1").value;
var p2 = _("pass2").value;

	if(p1!=p2){
	passwordstatus.style.color = "#F00";
	passwordstatus.innerHTML = "Passwords do not match"
}
else{
	passwordstatus.style.color = "#339933";
	passwordstatus.innerHTML = "Passwords match!"
}

}
function checkusername(){
	var u = _("username").value;
	if(u != ""){
		_("unamestatus").innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "checkusername.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("unamestatus").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("usernamecheck="+u);
	}
}

function signup(){
	var fname = _("firstname").value;
	var lname = _("lastname").value;
	var u = _("username").value;
	var e = _("emailsignup").value;
	var p1 = _("pass1").value;
	var p2 = _("pass2").value;
	var status = _("signupstatus");
	if(u == "" || e == "" || p1 == "" || p2 == "" || fname == "" || lname == ""){
		signupstatus.innerHTML = "Fill out all of the form data";
	} else if(p1 != p2){
		passwordstatus.innerHTML = "Your password fields do not match";
	} else {
		_("signupbtn").style.display = "none";
		signupstatus.innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText == "Sign up successful. Check your inbox, spam folder and click on the acitvation link in it to activate you account."){
	                                _("signupform").style.color= "#339900";
	                                window.scrollTo(0,0);
					_("signupform").innerHTML = "Sign up process successful. Check your email inbox,<b><i> spam folder</i></b> and click on the activation link sent there. You must activate your account successfully to be able to login.";
					_("signupbtn").style.display = "hidden";
				} else {
					_("signupform").style.color= "#555";
					_("signupform").style.size= "25px";
					window.scrollTo(0,0);
					status.innerHTML = ajax.responseText;
					_("signupbtn").style.display = "block";
				}
	        }
        }
        ajax.send("u="+u+"&e="+e+"&p="+p1+"&fname="+fname+"&lname="+lname);
	}
}

/* function addEvents(){
	_("elemID").addEventListener("click", func, false);
}
window.onload = addEvents; */
	</script>
</head>

<body>
	<div id="header_wrapper">
		<div id ="indexlogo">
		
		<img src="./img/newlogo.gif">
		</div>
		</div>		
<br />
<div id="wrapper">
 		<div id="signindiv">
			<p id="openlogin">+Login</p>
			  <form id="loginform" onsubmit="return false;">
  
    <input type="text" id="email" onfocus="emptyElement('status')" maxlength="88" placeholder="Email">
  	</br />
  	</br />
    <input type="password" id="plogin" onfocus="emptyElement('status')" maxlength="100" placeholder="Password">
    <br />
        <a href="forgot_pass.php" id="forgotpasswordlink">Forgot Your Password?</a>
        <button id="loginbtn" onclick="login()">Log In</button>
        <br />
         <p id="status"></p>
        <br />
     
   

  </form>	

  <br/><br/>
  	<div id="ads">
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Add one -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-4782870110626793"
     data-ad-slot="4882926267"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
	</div>
			</div>

	<div id="signupdiv">
 			<p id="opensignup">+Register here!
				</p>
	  <form name="signupform" id="signupform" onsubmit="return false;">

        <input id="firstname" type="text" onfocus="emptyElement('signupstatus')" placeholder="First Name" ><br/><br/>
 		<input id="lastname" type="text" onfocus="emptyElement('signupstatus')" placeholder="Last Name"><br/><br/>
    	<span id="unamestatus"></span>
    	<input id="username" type="text" onfocus="emptyElement('signupstatus')" onblur="checkusername()" onkeyup="restrict('username')" placeholder="Username" maxlength="16"><br/><br/>
    	<input id="pass1" type="password" onfocus="emptyElement('signupstatus')" onkeyup="checkpassword()" maxlength="16" placeholder="Password"><br/><br/>
    	<input id="pass2" type="password" onfocus="emptyElement('signupstatus')" onkeyup="checkpassword()" maxlength="16" placeholder="Password (again)"><br />
    	<span id="passwordstatus" style="color:#F00;"></span><br />
    	<input id="emailsignup" type="text" onfocus="emptyElement('signupstatus')" placeholder="Email Id" onkeyup="restrict('emailsignup')" maxlength="88">
    	
    	
        <span id="signupstatus"></span><br />
        </br />
    	<button id="signupbtn" onclick="signup()">Create Account</button>

  
					<br />
					<br />
					<br />
					<p id="SignUptandc">By clicking Create Account, you agree that you have read our <a class="signupanchor" href="termsandcondition.php">Terms and Conditions</a>
					&nbsp;including our <a class="signupanchor" href="privacypolicy.php">
						Privacy Policy</a> completey and agree upon them.</p></form>
			</div>
						</div>

<?php  include("footer.inc.php" ); ?>