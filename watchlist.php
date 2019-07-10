<?php
include_once("php_includes/check_login_status.php");
if($user_ok != true || $log_username == "") {

      exit();
}else{
            $usernameprofile = $_SESSION["username"];
}
?>


  <?php     if(isset($_POST['action']) && isset($_POST['user'])){

      $user = preg_replace('#[^a-z0-9]#i', '', $_POST['user']);

            
            if ($_POST['action'] == "add") {
                 
            
            $getwlcountquery = mysqli_query($db_conx,"SELECT COUNT(id) FROM watchlist WHERE user_watchlist='$usernameprofile'");
            $getwlcount = mysqli_fetch_row($getwlcountquery);
            $addtowl = @$_POST['action'];
            $addtowl1 =  preg_replace('#[^a-z0-9]#i', '', $addtowl);
            if (isset($addtowl1)) {
            	if ($getwlcount[0]<15) {
            			$added_from = $usernameprofile;
				        $useradded = $user;
            		    $addquery =  "INSERT INTO watchlist VALUES ('', '$added_from', '$useradded', now())";
            		    $addquery1 = mysqli_query($db_conx, $addquery);
                            echo "added";
                            exit();
            		
            		}
            	
            	else{
            		echo "You currently have max of 15 members in your watchlist. Remove some members in order to add new members.";
            	}
            }

            } 
            else {
            $removefromwl = @$_POST['action'];
            $removefromwl1 = preg_replace('#[^a-z0-9]#i', '', $removefromwl);
            if (isset($removefromwl1)) {
            	$userpresent = $user;
            	$userpresentwl = $usernameprofile;
            	$deletefromwl = mysqli_query($db_conx," DELETE FROM watchlist WHERE user_in_watchlist='$userpresent' AND  user_watchlist= '$userpresentwl'");
                  echo "removed";
                 
                  }
                  }}
            ?>