<?php include_once("php_includes/check_login_status.php");?>
<?php 
if($user_ok ==false) {
 echo "<meta http-equiv=\"refresh\" content=\"0; url=http://www.truthmug.com\">";
 exit();
 } ?>
<?php
  $senddata = @$_POST['senddata'];

  //Password variables
  $old_password = strip_tags(@$_POST['oldpassword']);
  $new_password = strip_tags(@$_POST['newpassword']);
  $repeat_password = strip_tags(@$_POST['newpassword2']);

  if ($senddata) {
  //If the form has been submitted ...

  $password_query = mysqli_query($db_conx,"SELECT * FROM users WHERE username='$log_username'");
  while ($row = mysqli_fetch_assoc($password_query)) {
        $db_password = $row['password'];
        
        //md5 the old password before we check if it matches
        $old_password_md5 = md5($old_password);
        
        //Check whether old password equals $db_password
        if ($old_password_md5 == $db_password) {
         //Continue Changing the users password ...
         //Check whether the 2 new passwords match
         if ($new_password == $repeat_password) {
            if (strlen($new_password) <= 4) {
             echo "Sorry! But your password must be more than 4 character long!";
            }
            else
            {

            //md5 the new password before we add it to the database
            $new_password_md5 = md5($new_password);
           //Great! Update the users passwords!
           $password_update_query = mysqli_query($db_conx,"UPDATE users SET password='$new_password_md5' WHERE username='$log_username'");
           echo "Success! Your password has been updated!";

            }
         }
         else
         {
          echo "Your two new passwords don't match!";
         }
        }
        else
        {
         echo "The old password is incorrect!";
        }
  }
   }
  else
  {
   echo "";
  }
  ?>
  <?php
  //First Name, Last Name and About the user query
  $get_info = mysqli_query($db_conx,"SELECT username,first_name, last_name FROM users WHERE username='$log_username'");
  $get_row = mysqli_fetch_assoc($get_info);
  $db_username = $get_row['username'];
  $db_firstname = $get_row['first_name'];
  $db_last_name = $get_row['last_name'];


  //Submit what the user types into the database
  if (isset($_POST['updateinfo'])) {
   $firstname = strip_tags(@$_POST['fname']);
   $lastname = strip_tags(@$_POST['lname']);
   $bio = @$_POST['bio'];


   if (strlen($firstname) < 3) {
    echo "Your first name must be 3 more than characters long.";
   }
   else
   if (strlen($lastname) < 5) {
    echo "Your last name must be  more than 5 characters long.";
   }
   else
   {
    //Submit the form to the database
    $info_submit_query = mysqli_query($db_conx,"UPDATE users SET first_name='$firstname', last_name='$lastname' WHERE username='$log_username'");
    echo "Your profile info has been updated!";
 echo "<meta http-equiv=\"refresh\" content=\"0; url=http://www.truthmug.com/".$db_username."\">";
   }
  }
  else
  {
   //Do nothing
  }
  ?>
  <?php
  //Check whether the user has uploaded a profile pic or not
  $check_pic = mysqli_query($db_conx,"SELECT profile_pic FROM users WHERE username='$log_username'");
  $get_pic_row = mysqli_fetch_assoc($check_pic);
  $profile_pic_db = $get_pic_row['profile_pic'];
  if ($profile_pic_db == "") {
  $profile_pic = "userdata/default_pic/default.gif";
  }
  else
  {
  $profile_pic = "userdata/profile_pics/".$profile_pic_db;
  }
  //Profile Image upload script
  if (isset($_FILES['profilepic'])) {
   if (((@$_FILES["profilepic"]["type"]=="image/jpeg") || (@$_FILES["profilepic"]["type"]=="image/png") || (@$_FILES["profilepic"]["type"]=="image/jpg") || (@$_FILES["profilepic"]["type"]=="image/gif"))&&(@$_FILES["profilepic"]["size"] < 5242880)) //1 Megabyte
  {
   $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   $rand_dir_name = substr(str_shuffle($chars), 0, 15);
   mkdir("userdata/profile_pics/$rand_dir_name");

   if (file_exists("userdata/profile_pics/$rand_dir_name/".@$_FILES["profilepic"]["name"]))
   {
    echo @$_FILES["profilepic"]["name"]." Already exists";
   }
   else
   {
    move_uploaded_file(@$_FILES["profilepic"]["tmp_name"],"userdata/profile_pics/$rand_dir_name/".@$_FILES["profilepic"]["name"]);
    echo "Uploaded and stored in: userdata/profile_pics/$rand_dir_name/".@$_FILES["profilepic"]["name"];
    $profile_pic_name = @$_FILES["profilepic"]["name"];
    $profile_pic_query = mysqli_query($db_conx,"UPDATE users SET profile_pic='$rand_dir_name/$profile_pic_name' WHERE username='$log_username'");
 echo "<meta http-equiv=\"refresh\" content=\"0; url=http://www.truthmug.com/".$db_username."\">";
    
   }
  }  
  else   
  {
      echo "Choose and image and make sure that the image must not be larger than 5MB and it must be either a .jpg, .jpeg, .png or .gif";
  }
  }

?>
<!doctype html>
<html>
<head>
  <title><?php echo $db_firstname; ?></title>
    <link rel="stylesheet" type="text/css" href="./css/style.css"/>
  <script src="js/main.js"></script>
  <script src="js/jquery.js"></script>
  <script src="js/ajax.js"></script>
  </head>
  <body>

<div id="header_wrapper_login">


    <div id="menu">
    <a href="<?php echo $log_username; ?>">Home</a>&nbsp;&nbsp;|
    <a href="account_settings.php">Settings</a>&nbsp;&nbsp;|
    <a href="logout.php">Logout</a>&nbsp;
    </div>
      <div id="loginlogo"><img src="./img/newlogologin.gif" /></div>
      </div>
      </div>
      </div>

<br />
<div id="wrapperaccount">
<div id="profilecontent">
<h2>Edit your Account Settings below</h2>

<p>UPLOAD YOUR PROFILE PHOTO:</p>
<p >Note: PLease use landscape images for efficient results. Further features will be soon embeded.</p>
<form action="" method="POST" enctype="multipart/form-data">
<img src="<?php echo "$profile_pic" ?>" width="330" height="220" /><br />
<input type="file" name="profilepic"  value="Choose file" >
<input type="submit" name="uploadpic" value="Upload Image">
</form>

<br />
<form action="account_settings.php" method="post">
<table><tr><td>
<p>CHANGE PASSWORD:</p> </td></tr>

<tr><td>
<font class="info">Your Old Password:</font></td><td> <input type="text" name="oldpassword" id="oldpassword" size="40"></td></tr>
<tr><td><font class="info">Your New Password:</td><td> <input type="text" name="newpassword" id="newpassword" size="40"></td></tr>
<tr><td><font class="info">Repeat Password  :</td><td> <input type="text" name="newpassword2" id="newpassword2" size="40"></td></tr>
<tr><td><input type="submit" name="senddata" id="senddata" value="Update Password"></td></tr>


</form>

<br />
<form action="account_settings.php" method="post">
<tr><td><p>UPDATE PROFILE INFO:</p></td></tr>

<tr><td><font class="info">
First Name:</td><td> <input type="text" name="fname" id="fname" size="40" value="<?php echo "$db_firstname" ?>"></td></tr>
<tr><td><font class="info">Last Name:</td><td> <input type="text" name="lname" id="lname" size="40" value="<?php echo "$db_last_name" ?>"></td></tr>

<tr><td><input type="submit" name="updateinfo" id="updateinfo" value="Update Profile">
</td></tr></table></form>
<form action="close_account.php" method="post">

<br />
<br />
<br />
</div>


</div>

<?php include("footer.inc.php"); ?>