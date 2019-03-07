<?php
	// This file maintains all basic functions

  function mysql_prep( $value ){
  $magic_quotes_active = get_magic_quotes_gpc();
  $new_enough_php = function_exists("mysql_real_escape_string"); // i.e PHP>=V4.3.0
  if( $new_enough_php ) { // PHP V4.3.0 or higher
    // undo any magic quote effects so mysql_real_escape_string can do the work
    if( $magic_quotes_active ){ $value = stripslashes($value); }
    $value = mysql_real_escape_string($value);
  } else {  // before PHP v4.3.0
    // if magic quotes aren't already on then add slashes manually
    if( !$magic_quotes_active ) { $value = addslashes($value); }
    // if magic quotes are active, then the slashes already exist
  }
  return $value;
  }

  function redirect_to( $location = NULL ){
    if ($location != NULL) {
      header("Location: {$location}");
    exit;
    }
  }

	function confirm_query($result_set){
		if(!$result_set){
            die("Database query failed: " . mysql_error() );
          }
	}

  function get_roles($public = true){
		global $connection;
		$sql = "SELECT id,name FROM roles";
		$result = mysql_query($sql);
    confirm_query($result);
    return($result);
  }

	function get_all_subjects($public = true){
		global $connection;
		$query = "SELECT * 
            FROM subjects";
    if ($public) {
      $query .= " WHERE visible = 2";
    }
    $query .= " ORDER BY position ASC";
    $subject_set = mysql_query($query, $connection);
    confirm_query($subject_set);
    return($subject_set);
  }
  
  function get_all_pages(){
    global $connection;
    $query = "SELECT * 
                    FROM pages 
                    ORDER BY position ASC";
          $page_set = mysql_query($query, $connection);
          confirm_query($page_set);
          return($page_set);
  }

	function get_pages_for_subject($subject_id, $public = true){
		global $connection;
		$query = "SELECT * 
             FROM pages ";
    $query .= "WHERE subject_id = {$subject_id} ";
    if ($public) {
      $query .= "AND visible = 2 ";
    }
    $query .= "ORDER BY position ASC";
    $page_set = mysql_query($query, $connection);
    confirm_query($page_set);
    return ($page_set);
  }
  

  function get_subject_by_id($subject_id){
    global $connection;
    $query = "SELECT * ";
    $query .= "FROM subjects ";
    $query .= "WHERE id= " . $subject_id . " ";
    $query .= "LIMIT 1";
    $result_set = mysql_query($query, $connection);
    confirm_query($result_set);
    //Remember:
    // If no rows are returned, fetch_array will return false
    if ($subject = mysql_fetch_array($result_set)){
        return $subject;
    } else {
        return NULL;
    }
  }

  function get_page_by_id($page_id){
          global $connection;
          $query = "SELECT * ";
          $query .= "FROM pages ";
          $query .= "where id = " . $page_id . " ";
          $query .= "LIMIT 1";
          $result_set = mysql_query($query, $connection);
          confirm_query($result_set);
          if($page = mysql_fetch_array($result_set)){
               return $page;
          } else{
               return NULL;
          }
  }

  function get_default_page($subject_id){
    //Get all visible pages
    $page_set = get_pages_for_subject($subject_id, true);
    if($first_page = mysql_fetch_array($page_set)){
       return $first_page;
    } else{
       return NULL;
    }

  }

  function find_selected_page(){
          global $sel_subject, $sel_page, $new_page;
          if(isset($_GET['subj'])){
           $sel_subject = get_subject_by_id($_GET['subj']);
           $sel_page = get_default_page($sel_subject['id']);
          } elseif(isset($_GET['page'])) {
           $sel_page = get_page_by_id($_GET['page']);
           $sel_subject = Null;
           } else{
           $sel_subject = Null;
           $sel_page = Null;
          }
  }

  function navigation($sel_subject, $sel_page, $public = false){
      $output = "<ul class=\"subjects\">";
          $subject_set = get_all_subjects($public);
          while ($subject = mysql_fetch_array($subject_set)) {
            $output .= "<li ";
            if ($subject["id"] == $sel_subject['id']) {$output .= "class = \"selected\" ";}
            $output .= "> <a href=\"edit_subject.php?subj=".urlencode($subject["id"]). 
            "\">{$subject["menu_name"]}</a></li>";
            $page_set = get_pages_for_subject($subject["id"], $public);
            $output .= "<ul class=\"pages\">";
            while ($page = mysql_fetch_array($page_set)) {
              $output .= "<li ";
              if ($page["id"]==$sel_page['id']) {$output .= "class=\"selected\" ";}
              $output .= "> <a href=\"content.php?page=" .urlencode($page["id"]) .
                "\">{$page["menu_name"]}</a></li>";
            }
            $output .= "</ul><br/>";
          }
        
        $output .= "</ul>";

        return $output;
  }

function public_navigation($sel_subject, $sel_page, $public = true){
      $output = "<ul class=\"subjects\">";
          $subject_set = get_all_subjects($public);
          while ($subject = mysql_fetch_array($subject_set)) {
            $output .= "<li ";
            if ($subject["id"] == $sel_subject['id']) {$output .= "class = \"selected\" ";}
            $output .= "> <a href=\"index.php?subj=".urlencode($subject["id"]). 
            "\">{$subject["menu_name"]}</a></li><br/>";
            if ($subject["id"] == $sel_subject['id']) {
                $page_set = get_pages_for_subject($subject["id"], $public);
                $output .= "<ul class=\"pages\">";
                while ($page = mysql_fetch_array($page_set)) {
                  $output .= "<li ";
                  if ($page["id"]==$sel_page['id']) {$output .= "class=\"selected\" ";}
                  $output .= "> <a href=\"index.php?page=" .urlencode($page["id"]) .
                    "\">{$page["menu_name"]}</a></li><br/>";
                }
                $output .= "</ul>";
              }
          }
        
        $output .= "</ul>";

        return $output;
  }

//***************************************************************************
//***************************************************************************
//             Functions about the User table starts here
//***************************************************************************
//***************************************************************************

function get_all_users(){
    global $connection;
    $query = "SELECT * 
            FROM users";
    $query .= " ORDER BY id ASC";
    $user_set = mysql_query($query, $connection);
    confirm_query($user_set);
    return($user_set);
  }

  function get_user_by_id($user_id){
    global $connection;
    $query = "SELECT * ";
    $query .= "FROM users ";
    $query .= "WHERE id= " . $user_id . " ";
    $query .= "LIMIT 1";
    $result_set = mysql_query($query, $connection);
    confirm_query($result_set);
    //Remember:
    // If no rows are returned, fetch_array will return false
    if ($user = mysql_fetch_array($result_set)){
        return $user;
    } else {
        return NULL;
    }
  }

 function find_selected_user(){
          global $sel_user;
          if(isset($_GET['userid'])){
           $sel_user = get_user_by_id($_GET['userid']);
           } else{
           $sel_user = Null;
          }
  }

function user_navigation($sel_user){
      $output = "<ul class=\"subjects\">";
          $user_set = get_all_users();
          while ($user = mysql_fetch_array($user_set)) {
            $output .= "<li ";
            if ($user["id"] == $sel_user['id']) {$output .= "class = \"selected\" ";}
            $output .= "> <a href=\"edit_user.php?userid=".urlencode($user["id"]). 
            "\">{$user["username"]}</a></li>";
          }
        
        $output .= "</ul>";

        return $output;
  }

?>