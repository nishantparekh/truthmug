<?php 
 $db_conx=mysqli_connect("localhost", "","") or die("Couldn't connect to sql server");
mysqli_select_db ($db_conx,'truthmug_truthmug') or die(" couldnt select DB");
?>
