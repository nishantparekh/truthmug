<?php include_once("php_includes/check_login_status.php");?>
<?php 
if($user_ok ==false) {
 echo "<meta http-equiv=\"refresh\" content=\"0; url=http://www.truthmug.com\">";
 exit();
 } ?>
<! doctype html>
<html>
<head>
<title>Permission</title>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<script src="js/main.js"></script>
	<script src="js/ajax.js"></script>
<script type="text/javascript">

function friendReqHandler(action,reqid,user1,elem){
	var conf = confirm("Press OK to '"+action+"' this Permission request to '"+user1+"'.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = "processing ...";
	var ajax = ajaxObj("POST", "permission_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "accept_ok"){
				_(elem).innerHTML = "<b>Request Accepted!</b><br />You now have permission";
			} else if(ajax.responseText == "reject_ok"){
				_(elem).innerHTML = "<b>Request Rejected</b><br />You chose to reject permission from this user";
			} else {
				_(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("action="+action+"&reqid="+reqid+"&user1="+user1);
}
function friendToggle(type,user,elem){
	var conf = confirm("Press OK to confirm to remove Permissions from <?php echo $username; ?>.");
	if(conf != true){
		return false;
	}
	
	_(elem).innerHTML = 'please wait ...';
	var ajax = ajaxObj("POST", "permission_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "friend_request_sent"){
			_(elem).className = "friendbtn";
				_(elem).innerHTML = 'Request Sent';
			} else if(ajax.responseText == "unfriend_ok"){
				_(elem).innerHTML = 'Permission Removed';
			} else {
				alert(ajax.responseText);
				_(elem).clasName = "friendbtn";
				_(elem).innerHTML = 'Try later';
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}
</script>
</head>
<body>
<div id="aboutus">
<div id="aboutuslogo"><img src="./img/aboutus.gif" /></div>
<div id="extramenu">

		<a href="<?php echo $log_username; ?>">Home</a>&nbsp;&nbsp;|
		<a href="account_settings.php">Settings</a>&nbsp;&nbsp;|
		<a href="logout.php">Logout</a>&nbsp;
		</div></div></div>
		<div id="aboutcontainer">
		<div id="permblocked"><?php
		echo '<div id="permheading"><p id="permheadingtext">Blocked Users</p></div>';
		?>
		</div>
		<div id="permpending">
		<?php
echo '<div id="permheading"><p id="permheadingtext">Permissions pending</p></div>';
$perm_requests = "";
$sqlperm = "SELECT * FROM permission WHERE user2='$log_username' AND accepted='0' ORDER BY datemade ASC";

$perm_query = mysqli_query($db_conx, $sqlperm);
if($perm_query == true){}else{echo"Currently unable to process. Please try again later";}
$numrows = mysqli_num_rows($perm_query);
if($numrows < 1){
	echo "<h2>No requests</h2>";
} else {
	while ($row = mysqli_fetch_array($perm_query, MYSQLI_ASSOC)) {
		$reqID = $row["id"];
		$user1 = $row["user1"];
		$datemade = $row["datemade"];
		$datemade = strftime("%B %d", strtotime($datemade));
		$thumbquery = mysqli_query($db_conx, "SELECT profile_pic, first_name, last_name  FROM users WHERE username='$user1' AND activated='1' LIMIT 1");
		$thumbrow = mysqli_fetch_row($thumbquery);
		$permpic = $thumbrow[0];
		$permfname = $thumbrow[1];
		$permlname = $thumbrow[2];		
if (!isset($permpic)) {
		echo '<div class="permlisting" id="permlisting_'.$reqID.'"><div id="permlistpic"><img  src="userdata/default_pic/default.gif" height="37" width="52"                           alt="'.$user1.'s Profile" title="'.$user1.'" /></div>
						<div id="permlistname">	<a id="permlistnameid" href="'.$user1.'">
							'.$permfname.'&nbsp;'.$permlname.'</a>
							</div><div id="grant"><button class="grantbtn" onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'permlisting_'.$reqID.'\')">Allow</button></div><div id="deny"><button  class="ignorebtn" onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'permlisting_'.$reqID.'\')">Ignore</button></div></div>';}
							else{echo '<div class="permlisting" id="permlisting_'.$reqID.'"><div id="permlistpic"><img  src="userdata/profile_pics/'.$permpic.'" height="37" width="52" alt="'.$user1.'s Profile" title="'.$user1.'" /></div>
							<div id="permlistname"><a id="permlistnameid" href="'.$user1.'">
							'.$permfname.'&nbsp;'.$permlname.'</a>
							</div><div id="grant"><button class="grantbtn" onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'permlisting_'.$reqID.'\')">Allow</button></div><div id="deny"><button class="ignorebtn" onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'permlisting_'.$reqID.'\')">Ignore</button></div></div>';
							}
}}
?>
		</div>
		<div id="permexisting">
		<?php
		echo '<div id="permheading"><p id="permheadingtext">Existing Permissions</p></div>';
$perm_requests = "";
$sqlpermexist = "SELECT * FROM permission WHERE user1='$log_username' AND accepted='1' OR user2='$log_username' AND accepted='1'";

$perm_queryexist = mysqli_query($db_conx, $sqlpermexist);
if($perm_queryexist == true){}else{echo"Currently unable to process. Please try again later";}
$numrowsexist = mysqli_num_rows($perm_queryexist);
if($numrowsexist < 1){
	echo "NO permissions exist";
} else { 
	while ($rowe = mysqli_fetch_array($perm_queryexist, MYSQLI_ASSOC)) {
		$reqIDe = $rowe["id"];
		$user1e = $rowe["user1"];
		$user2e = $rowe["user2"];
		$datemadee = $rowe["datemade"];
		$datemadee = strftime("%B %d", strtotime($datemadee));
		if($user1e == $log_username){
		$thumbquerye = mysqli_query($db_conx, "SELECT profile_pic, first_name, last_name,username  FROM users WHERE username='$user2e' AND activated='1' LIMIT 1");}
		else{
		$thumbquerye = mysqli_query($db_conx, "SELECT profile_pic, first_name, last_name, username  FROM users WHERE username='$user1e' AND activated='1' LIMIT 1");}
		$thumbrowe = mysqli_fetch_row($thumbquerye);
		$permpice = $thumbrowe[0];
		$permfnamee = $thumbrowe[1];
		$permlnamee = $thumbrowe[2];
		$permusernamee = $thumbrowe[3];		
if (!isset($permpice)) {
		echo '<div class="permlisting" id="permlisting_'.$reqIDe.'"><div id="permlistpic"><a href='.$permusernamee.'><img  src="userdata/default_pic/default.gif" height="37" width="52"                           alt="'.$user1e.'s Profile" title="'.$permusernamee.'" /></a></div>
							<div id="permlistname"><a id="permlistnameid" href="'.$permusernamee.'">
							'.$permfnamee.'&nbsp;'.$permlnamee.'</a>
							</div><button class="removepermission" onclick="friendToggle(\'unfriend\',\''.$permusernamee.'\',\'permlisting_'.$reqIDe.'\')">Remove</button></div>';}
							else{echo '<div class="permlisting" id="permlisting_'.$reqIDe.'"><div id="permlistpic"><a href='.$permusernamee.'><img  src="userdata/profile_pics/'.$permpice.'" height="37" width="52" alt="'.$user1e.'s Profile" title="'.$permusernamee.'" /></a></div>
							<div id="permlistname"><a id="permlistnameid" href="'.$permusernamee.'">
							'.$permfnamee.'&nbsp;'.$permlnamee.'</a>
							</div><button class="removepermission" onclick="friendToggle(\'unfriend\',\''.$permusernamee.'\',\'permlisting_'.$reqIDe.'\')">Remove</button></div>';
							}
}}
?>
		
		</div>
<div id="about">
<br />
<br />
</div>
</div>
</body>
<?php include("footer.inc.php");?>
</html>