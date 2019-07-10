<?php include_once("php_includes/check_login_status.php");?>
<?php 
if($user_ok ==false) {
 echo "<meta http-equiv=\"refresh\" content=\"0; url=http://www.truthmug.com\">";
 exit();
 } ?>
<!doctype html>
<html>
<head>
  <title>Search Truthmug</title>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
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
      <div id="searchwrapper">
<div id="searchresulttext">Search Results..
<div id="searchinsearch">     <form id="searchForm" method="GET" action="search.php">
   
                        <input type="text" name="usersearch"  placeholder="Search Truthmug..." />
                        <input type="submit" id="searchSubmit" value="Search" />
                    
                </form></div></div>
<div id="searchresults">
<?php
  // This function builds a search query from the search keywords and sort setting
  function build_query($user_search) {
    $search_query = "SELECT  first_name, last_name, username, profile_pic FROM users";

    // Extract the search keywords into an array
    $clean_search = str_replace(',', ' ', $user_search);
    $search_words = explode(' ', $clean_search);
    $final_search_words = array();
    if (count($search_words) > 0) {
      foreach ($search_words as $word) {
        if (!empty($word)) {
          $final_search_words[] = $word;
        }
      }
    }

    // Generate a WHERE clause using all of the search keywords
    $where_list = array();
    if (count($final_search_words) > 0) {
      foreach($final_search_words as $word) {
        $where_list[] = "first_name LIKE '%$word%'";
      }
    }
    $where_clause = implode(' OR ', $where_list);

    // Add the keyword WHERE clause to the search query
    if (!empty($where_clause)) {
      $search_query .= " WHERE $where_clause";
      $search_query .= "AND activated='1'";
    }
    return $search_query;
  }




  // Grab the sort setting and search keywords from the URL using GET

$user_search = preg_replace('#[^a-z0-9]#i', '', $_GET['usersearch']);


  // Query to get the total results
  if ($user_search== ""){
    echo "<div id='emptysearch'>Please enter something</div>";
  }else{
  $query = build_query($user_search);
  $result = mysqli_query($db_conx, $query);
  $total = mysqli_num_rows($result);


  // Query again to get just the subset of results
  $query =  $query . " ORDER BY first_name ASC";
  $result = mysqli_query($db_conx, $query);
  if($total ==0 ){
    echo "<div id='nosearchresult'>No results were found. Try entering something different</div> ";
  }else{
  while ($row = mysqli_fetch_row($result)) {
        $searchfname = $row[0];
        $searchlname = $row[1];
        $searchuname = $row[2];
        $searchpic =   $row[3];
      if (!isset($searchpic)) {
                echo '<a href="'.$searchuname.'"><div id="searchlisting"><div id="wllistpic"><img  src="userdata/default_pic/default.gif" height="37" width="52" alt=" '.$searchfname.'" title=" '.$searchfname.' " /></div>
              <div id="searchlistname">
              '.$searchfname.'&nbsp;'.$searchlname.'
              </div>
              </div></a>';
              }else{
              echo '<a href="'.$searchuname.'"><div id="searchlisting"><div id="wllistpic"><img  src="userdata/profile_pics/'.$searchpic.'" height="37" width="52" alt=" '.$searchfname.'" title=" '.$searchfname.' " /></div>
              <div id="searchlistname">
              '.$searchfname.'&nbsp;'.$searchlname.'
              </div>
              </div></a>';

  

    } }}}


  // Generate navigational page links if we have more than one page
  mysqli_close($db_conx);
  ?>
  </div>
  </div>
  <br />
<?php include("footer.inc.php"); ?>



