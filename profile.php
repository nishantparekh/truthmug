<?php include_once("php_includes/check_login_status.php");?>
<?php 
if($user_ok ==false) {
 echo "<meta http-equiv=\"refresh\" content=\"0; url=http://www.truthmug.com\">";
 exit();
 } 
if (isset($_GET['u'])) {
	$usersession = $_SESSION['username'];
	$ensession = md5($usersession);
	$username = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
	if (ctype_alnum($username)) {
		// check if user exists
		$check = mysqli_query($db_conx,"SELECT username, first_name, last_name, profile_pic FROM users WHERE username= '$username' AND activated='1'");
        if (mysqli_num_rows($check)==1) {
        	$get = mysqli_fetch_assoc($check);
        	$username = $get['username'];
        	$firstname = $get['first_name'];
        	$lastname = $get['last_name'];
        	$profilepic = $get['profile_pic'];
        	}
        	else{
        		echo "<meta http-equiv=\"refresh\" content=\"0; url=http://www.truthmug.com\">";
        		exit();
        	}
	}
}
?>
<?php
$isFriend = false;
$ownerBlockViewer = false;
$viewerBlockOwner = false;
if($username != $log_username && $user_ok == true){
	$friend_check = "SELECT id FROM permission WHERE user1='$log_username' AND user2='$username' AND accepted='1' OR user1='$username' AND user2='$log_username' AND accepted='1' LIMIT 1";
	$execfriend_check = mysqli_query($db_conx, $friend_check);
	$countpermtrue = mysqli_num_rows($execfriend_check);
	if($countpermtrue > 0){
        $isFriend = true;
    }
	$block_check1 = "SELECT id FROM blockedusers WHERE blocker='$username' AND blockee='$log_username' LIMIT 1";
	if(mysqli_num_rows(mysqli_query($db_conx, $block_check1)) > 0){
        $ownerBlockViewer = true;
    }
	$block_check2 = "SELECT id FROM blockedusers WHERE blocker='$log_username' AND blockee='$username' LIMIT 1";
	if(mysqli_num_rows(mysqli_query($db_conx, $block_check2)) > 0){
        $viewerBlockOwner = true;
    }
}
?><?php 
$friend_button = '<button class="friendbtn" disabled>Request Permission</button>';
$block_button = '<button class="blockbtn" disabled>Block User</button>';
// LOGIC FOR FRIEND BUTTON
if($isFriend == true){
	$friend_button = '<button class="friendbtn" onclick="friendToggle(\'unfriend\',\''.$username.'\',\'friendBtn\')">Remove Permission</button>';
} else if($user_ok == true && $username != $log_username && $ownerBlockViewer == false){
	$friend_button = '<button class="friendbtn" onclick="friendToggle(\'friend\',\''.$username.'\',\'friendBtn\')">Request Permission</button>';
}
// LOGIC FOR BLOCK BUTTON
if($viewerBlockOwner == true){
	$block_button = '<button class="blockbtn" onclick="blockToggle(\'unblock\',\''.$username.'\',\'blockBtn\')">Unblock User</button>';
} else if($user_ok == true && $username != $log_username){
	$block_button = '<button class="blockbtn" onclick="blockToggle(\'block\',\''.$username.'\',\'blockBtn\')">Block User</button>';
}
?>

<?php
  $postinitial = $_POST['post'];
  $post = htmlspecialchars($postinitial, ENT_QUOTES);
          if ($post !="") {
           date_default_timezone_set('Asia/Kolkata');
	$date_added = strtotime("now");
	$added_by = $usersession;
	$added_by = md5($added_by);
	$user_posted_to = $username;
	$sqlCommand = "INSERT INTO posts VALUES ('', '$post','$added_by','$user_posted_to', $date_added,'', '0')";
	$query = mysqli_query($db_conx, $sqlCommand);
if($query === TRUE){
echo " processsed";}
} 
?>
<?php
$geteachtime = "SELECT each_time FROM users WHERE username='$usersession' AND activated='1' LIMIT 1";
$execeachtime = mysqli_query($db_conx, $geteachtime);
$eachtrow = mysqli_fetch_row($execeachtime);
$each_time_value = $eachtrow[0];
//echo $each_time_value;
?>

<!doctype html>
<html>
<head>
	<title><?php echo $firstname; ?></title>
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
		<link rel="stylesheet" type="text/css" href="css/style1.css"/>
	<script src="js/main.js"></script>
	<script src="js/jquery.js"></script>
	<script src="js/ajax.js"></script>
	<script type="text/javascript">
	function likeclick(name,action,reqid,elem){
	_(elem).innerHTML = "loading";
	var name1 = name;
	var id1 = reqid;
	var ation1 = _(action)
	var ajax = ajaxObj("POST", "like_button.php");
	ajax.onreadystatechange = function(){
	if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "likeexecuted")
			{
				_(elem).style.background= "#000";
				_(elem).style.color= "#fff";
				_(elem).innerHTML = '<button id="linkebtn" onclick=likeclick(\"'+name1+'\",\"unlike\",\"'+id1+'\",\"likebtninfo_'+id1+'\")>unlike</button>';

			} else if(ajax.responseText == "disliked"){
				_(elem).innerHTML = '<button id="linkebtn" onclick=likeclick(\"'+name1+'\",\"likepost\",\"'+id1+'\",\"likebtninfo_'+id1+'\")>like</button>';
			}else {
			_(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("name="+name+"&action="+action+"&reqid="+reqid);
	}
		
	function postdeletehandler(action,reqid,elem){
	_(elem).innerHTML = "loading..";
	var ajax = ajaxObj("POST", "deletepost.php");
	ajax.onreadystatechange = function(){
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "unable")
			{
				_(elem).style.background= "#339966";
				_(elem).style.color= "#fff";
				_(elem).innerHTML = "Leaving";

			} else {
				_(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("action="+action+"&reqid="+reqid);
}

	function watchlisthandler(action,user,elem){
	_(elem).innerHTML = "processing ...";
	var ajax = ajaxObj("POST", "watchlist.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "added")
			{
				_(elem).style.background= "#339966";
				_(elem).style.color= "#fff";
				_(elem).innerHTML = "Added to your watchlist";

			} else if(ajax.responseText == "removed") {
				_(elem).style.color= "#000";
				_(elem).innerHTML = "Removed from your watchlist";
			}
			else {
				_(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("action="+action+"&user="+user);
}

function friendToggle(type,user,elem){
	var conf = confirm("Press OK to confirm to send a permission request to <?php echo $username; ?>.");
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
				_(elem).innerHTML = '<button class="friendbtn" onclick="friendToggle(\'friend\',\'<?php echo $username; ?>\',\'friendBtn\')">Request Permission</button>';
			} else {
				alert(ajax.responseText);
				_(elem).clasName = "friendbtn";
				_(elem).innerHTML = 'Try later';
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}
function blockToggle(type,blockee,elem){
	var conf = confirm("Press OK to confirm the '"+type+"' action on user <?php echo $username; ?>.");
	if(conf != true){
		return false;
	}
	var elem = document.getElementById(elem);
	
	elem.innerHTML = 'please wait ...';
	var ajax = ajaxObj("POST", "block_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "blocked_ok"){
			 	
				elem.innerHTML = '<button class="blockbtn" onclick="blockToggle(\'unblock\',\'<?php echo $username; ?>\',\'blockBtn\')">Unblock User</button>';
			} else if(ajax.responseText == "unblocked_ok"){
				elem.innerHTML = '<button class="blockbtn" onclick="blockToggle(\'block\',\'<?php echo $username; ?>\',\'blockBtn\')">Block User</button>';
			} else {
				alert(ajax.responseText);
				elem.className = "blockbtn";
				elem.innerHTML = 'Try again later';
			}
		}
	}
	ajax.send("type="+type+"&blockee="+blockee);
}
function permReqHandler(action,reqid,user1,elem){
	var conf = confirm("Press OK to '"+action+"' this Permission request.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = "wait..";
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
	
	$(document).ready(function(){
		$(this).click(function(){
			$('#replybox').slideToggle(200);
		}); //end toggle
	}); // end ready 
	
	</script>
</head>
<body>
<div id="header_wrapper_login">


		<div id="menu">

		<a href="<?php echo $usersession; ?>">Home</a>&nbsp;&nbsp;|
		<a href="account_settings.php">Settings</a>&nbsp;&nbsp;|
		<a href="logout.php">Logout</a>&nbsp;
		</div>
			<a href="<?php echo $usersession; ?>"><div id="loginlogo"><img src="./img/newlogologin.gif" /></div></a>
			</div>
			</div>
			</div>

<br />
<div id="profilewhole">
 			<div id="search_box">
					<form id="searchForm" method="GET" action="search.php">
   
                        <input  id="searchentry" type="text" name="usersearch"  placeholder="Search Truthmug..." />
                        <input type="submit" id="searchSubmit" value="Search" />
                    
            		</form>
            		<div id="watchlistfunc">
            		<?php
            		if ($username == $usersession) {
            			$count_wl = "SELECT * FROM watchlist WHERE user_watchlist='$username'";
            			$wlcount = mysqli_query($db_conx, $count_wl);
            			$countuserwl = mysqli_num_rows($wlcount);
            			echo '<p id="unamewatchlist">Currently in watchlist:&nbsp; '.$countuserwl.'&nbsp;people</p>';
            		}else{
            			//check if the user is already there in the watchlist
            			$checkwl = "SELECT * FROM watchlist WHERE user_watchlist='$usersession' AND user_in_watchlist='$username'";
            			$checkwlquery = mysqli_query($db_conx, $checkwl);
            			
            			$wlcheckrow = mysqli_num_rows($checkwlquery);
       	     			if ($wlcheckrow == 0) {
        				echo '<div id="addto_wl" ><button id="addwlbtn"  onclick="watchlisthandler(\'add\',\''.$username.'\',\'addto_wl\')">Add to Watchlist</button></div>';    				
            			}
            			else if ($wlcheckrow == 1) {
            			echo '<div id="removefrom_wl"><button id="removewlbtn"  onclick="watchlisthandler(\'remove\',\''.$username.'\',\'removefrom_wl\')">Remove from watchlist</button></div>';
            			}
            			
            		}
            		?>
            		

            		</div>
           
            	<div id="watchlist">
            			<div id="wlheading"><p id="wlheadingtext">Watchlist</p>

            			</div>
            			<?php 
            			if($username == $usersession && $countuserwl==0 ){echo "<h2 id='emptywatchlist'> Your watchlist seems to be empty. Add your close one here to keep a watch on them.</h2>";}
            			$getwl = mysqli_query($db_conx,"SELECT user_in_watchlist FROM watchlist WHERE user_watchlist='$username' ORDER BY RAND()") OR die(mysqli_error());
            			
						while($row_wl = mysqli_fetch_assoc($getwl)){
							$wlname = $row_wl['user_in_watchlist'];
							$wlpicquery = mysqli_query($db_conx,"SELECT profile_pic, first_name, last_name, username  FROM users WHERE 		    		  username='$wlname' AND activated='1' LIMIT 1");
							$wlarray = mysqli_fetch_row($wlpicquery);
							$wlthumbpic = $wlarray[0];
							$firstwlname = $wlarray[1];
							$lastwlname = $wlarray[2];
							$userwlname = $wlarray[3];
							$qeachtime = "SELECT to_be_deleted FROM posts WHERE user_posted_to='$userwlname' AND date_time>'$each_time_value'";
							$execq_eachtime = mysqli_query($db_conx, $qeachtime);
							if($execq_eachtime == TRUE){}
							else{echo "unable to get number of new posts";}
							$countnew = mysqli_num_rows($execq_eachtime);
							if (!isset($wlthumbpic)) {
							echo '<a id="wla" href="'.$wlname.'"><div id="wllisting"><div id="wllistpic"><img  src="userdata/default_pic/default.gif" height="37" width="52" alt=" '.$wlname.'s Profile" title=" '.$wlname.' " /></div>
							<div id="watchlistname">
							'.$firstwlname.'&nbsp;'.$lastwlname.'
							</div>';
							/*if($countnew ==0){
							echo '	<div id="countnew">
							99+&nbsp;new
							</div>';}else{}
							if($countleaving!=0){echo '
							<div id="countleaving">
							exit
							</div>';}*/
							echo'</div></a>';
							}else{
							echo '<a id="wla" href="'.$wlname.'"><div id="wllisting"><div id="wllistpic"><img  src="userdata/profile_pics/'.$wlthumbpic.'" height="37" width="52" alt=" '.$wlname.'s Profile" title=" '.$wlname.' " /></div>
							<div id="watchlistname">
							'.$firstwlname.'&nbsp;'.$lastwlname.'
							</div>';
							/*<div id="countnew">
							'.$countnew.'
							</div>
							<div id="countleaving">
							exit
							</div>*/
							echo '</div></a>';

						}
							}
            			?>
            	</div>

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
      
			<div id="wrapperprofile">
<div id="profilepagecontent">
<div id="textheader"><?php echo $firstname. ' ' .$lastname;?></div>

	
	<?php 
	if ($username != $usersession) {
	$queryperm = "SELECT id, user1, user2, accepted FROM permission WHERE user1='$usersession' AND user2='$username' OR user1='$username' AND user2='$usersession'";
	$execqueryperm = mysqli_query($db_conx, $queryperm);
	$ifperm = mysqli_num_rows($execqueryperm);
	$permstatus = mysqli_fetch_row($execqueryperm);
	$reqID1 = $permstatus[0];
	$permuser1 = $permstatus[1];
	$permuser2 = $permstatus[2];
	$pstatus = $permstatus[3];
	
	if($ifperm < 1){echo'<div id="reqperm">You must first send a permission request to write on '.$firstname.'&#39;s mug  </div>';}
	else if($ifperm ==1 && $pstatus ==0){
	if($permuser1 == $usersession && $permuser2 == $username){
	echo '<div id="reqpermsent">Permission request sent. You will be able to<br /> write on  '.$firstname.'&#39;s mug as soon as it&#39;s accepted </div>';}
	else if($permuser1 == $username && $permuser2 == $usersession){
	echo '<div id="reqpermsent">'.$firstname.' has sent you a permission request. You both will have the permission<br /> as soon as it&#39;s accepted by you</div>';
	}}else{}
	if($execqueryperm == true){
	if($pstatus ==1 ){
	
		echo '<div class="postform">
<form action="'.$username.'" method="POST" ><textarea id="post" name="post" rows="5" cols="82"></textarea><input type="submit" name="send"  value="Post" style="background-color: #eee; color: 
   				#007f7f; font-weight: bold; float: right; border: 3px inset #00B9ED; margin-top: 20px; padding: 6px;
   				 margin-right: 8px; cursor: pointer; cursor: hand;">   </form>
</div>';
	}

	}}else{ }
   	 ?>
<?php
if($username != $usersession){
$q = "SELECT accepted FROM permission WHERE user1='$usersession' AND user2='$username' OR user1='$username' AND user2='$usersession' LIMIT 1";
$eq = mysqli_query($db_conx, $q);
$rowstatus = mysqli_fetch_row($eq);
$status = $rowstatus[0];
$ceq = mysqli_num_rows($eq);
if($ceq == 1){
if($status == 0){
if($permuser1 == $username && $permuser2 == $usersession){
echo '<div id="permission">
<br />
<div class ="grant_user" id="permlisting_'.$reqID1.'"><button class="grantbtn_user" onclick="friendReqHandler(\'accept\',\''.$reqID1.'\',\''.$username.'\',\'permlisting_'.$reqID1.'\')">Allow</button></div>
  <div id="blockBtn">'.$block_button.'</div>
</div>';
}else{
echo '<div id="permission">
<br />
<div id="sentrequest">Request sent</div>
<div id="blockBtn">'.$block_button.'</div>
</div>';
}}
else if($status == 1){
echo '<div id="permission">
<br />
 <div id="acceptedrequest">Request Accpeted</div>
  <div id="blockBtn">'.$block_button.'</div>
</div>';
}
}else{
echo '<div id="permission">
<br />
 <div id="friendBtn">'.$friend_button.'</div>
  <div id="blockBtn">'.$block_button.'</div>
</div>'; }}else{}
?> 

<?php 
$getposts = mysqli_query($db_conx,"SELECT * FROM posts WHERE user_posted_to='$username' ORDER BY id DESC") OR die(mysqli_error());
$getpostsleavingq = mysqli_query($db_conx,"SELECT id FROM posts WHERE user_posted_to='$username' AND to_be_deleted='1' ORDER BY id DESC");
$getpostsleaving = mysqli_num_rows($getpostsleavingq);
if($username == $usersession){
$totalposts = mysqli_num_rows($getposts);
$qeachtime1 = mysqli_query($db_conx, "SELECT to_be_deleted FROM posts WHERE user_posted_to='$log_username' AND date_time>'$each_time_value'");
$countnew1 = mysqli_num_rows($qeachtime1);
echo '<div id="myposts">Posts on your mug&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$countnew1.' new&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  | &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$getpostsleaving.' leaving&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  | &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$totalposts.' total</div>';
}
echo '<div class="profileposts">'; 
while($row = mysqli_fetch_assoc($getposts)){
	$id = $row['id'];
	$body = $row['body'];
	$user_posted_to = $row['user_posted_to'];
	$date_added = $row['date_time'];
	$dateshow = strftime("%b %d, %Y", $date_added);
	$deleted = $row['to_be_deleted'];
	echo "<div id='whole'><div class='postsonprofile'><div id='date_added'>$dateshow &nbsp;</div>";
	if($username == $usersession && $date_added>$each_time_value){
	echo "<div id='ifnew'>new</div>";}
	echo "
	<div id='bodyposts'>&nbsp;$body&nbsp;</div></div>"; 
	if($username != $usersession){
	if ($deleted == 1) {
		echo '<div id="poststatus">&nbsp;Leaving </div>';
		
	}
else{
	echo '<div id="poststatuspresent">Present</div>';
	echo '<button id="reply">Reply</button>';
}}
	if ($username == $usersession) {
			if ($deleted == 1) {
		echo '<div id="poststatus">Leaving in 2hrs</div>';

	}
	else{
		echo '<div class="userinfo" id="userinfo_'.$id.'"><button id="remove"  onclick="postdeletehandler(\'delete\',\''.$id.'\',\'userinfo_'.$id.'\')">Remove</button>
		</div><div class="likebtninfo" id="likebtninfo_'.$id.'"><button id="linkebtn" onclick="likeclick(\''.$ensession.'\', \'likepost\',\''.$id.'\',\'likebtninfo_'.$id.'\')">Like</button></div>
		<div id="numberoflikes">';
		$getlikequery = "SELECT COUNT(id) FROM likes WHERE postid='$id'";
		$execlikequery = mysqli_query($db_conx, $getlikequery);
		$getlikecount = mysqli_num_rows($execlikequery);
		echo ''.$getlikecount.'</div>';
		}}
	echo "</div><br />";
	
	 echo '<div id="replybox"><textarea id="reply_post" name="reply" rows="5" cols="82"></textarea></div>';
}
 ?>
 </div>
 <?php
 if($username == $usersession){
 date_default_timezone_set('Asia/Kolkata');
                $seteach_time = date("Y-m-d H:i:s");
                $seteach_time = strtotime($seteach_time);
               // echo "$seteach_time";
		$eachtimequery = "UPDATE users SET each_time='$seteach_time' WHERE username='$usersession' AND activated='1' LIMIT 1";
		$execeachtime = mysqli_query($db_conx, $eachtimequery);
		if($execeachtime === TRUE){}
		else{echo" unable to get new posts notifications";}
 }
 else{} 
 ?>
<div id="profile_photo">
<?php 
if (!isset($profilepic)) {
	echo '<img  src="userdata/default_pic/default.gif" height="220" width="330" alt=" '.$username.'s Profile" title="<?php echo $username; s Profile " /></div>';
}
else{

echo '<img  src="userdata/profile_pics/'.$profilepic.'" height="220" width="330" alt="'.$username.' Profile" title="'.$username.' Profile "/></div>';


}
?><?php
if($username == $usersession){
echo '<div id="permissionsection">
<div id="permheading"><p id="permheadingtext" ><a id="permheadingtext" href="permission.php">Permissions</a></p></div>';
$perm_requests = "";
$sqlperm = "SELECT * FROM permission WHERE user2='$usersession' AND accepted='0' ORDER BY datemade ASC";
if($sqlperm == true){}else{echo"unable to  execute. There might be some problems Please try again later";}
$perm_query = mysqli_query($db_conx, $sqlperm);
$numrows = mysqli_num_rows($perm_query);
if($numrows < 1){
	echo "<h2 id='norequest'> Currently there are no permission requests for you</h2>";
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
							<div id="permlistname"><a id="permlistnameid" href="'.$user1.'">
							'.$permfname.'&nbsp;'.$permlname.'</a>
							</div><div id="grant"><button class="grantbtn" onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'permlisting_'.$reqID.'\')">Allow</button></div><div id="deny"><button  class="ignorebtn" onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'permlisting_'.$reqID.'\')">Ignore</button></div></div>';}
							else{echo '<div class="permlisting" id="permlisting_'.$reqID.'"><div id="permlistpic"><img  src="userdata/profile_pics/'.$permpic.'" height="37" width="52" alt="'.$user1.'s Profile" title="'.$user1.'" /></div>
							<div id="permlistname"><a id="permlistnameid" href="'.$user1.'">
							'.$permfname.'&nbsp;'.$permlname.'</a>
							</div><div id="grant"><button class="grantbtn" onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'permlisting_'.$reqID.'\')">Allow</button></div><div id="deny"><button class="ignorebtn" onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'permlisting_'.$reqID.'\')">Ignore</button></div></div>';
							}
}}}else{}
?></div>
</div>
</div>
</div>                           
<?php include("footer.inc.php");?>