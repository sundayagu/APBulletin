<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	if(intval($_GET['userid']) == 0){
    	redirect_to("new_user.php");
	}
	
	$id = mysql_prep($_GET['userid']);

	if ($user = get_user_by_id($id)) {
			
		$query = "DELETE FROM users WHERE id = {$id} LIMIT 1";
		$result = mysql_query($query, $connection);
		if (mysql_affected_rows() == 1) {
			redirect_to("new_user.php");
		} else {
			// Deletion failed
			echo "<p>Page deletion failed.</p>";
			echo "<p>" . mysql_error() . "</p>";
			echo "<a href=\"ap-admin.php\">Return to Main page</a>";
		}

	} else {
		// Page didn't exist in database
		redirect_to("new_user.php");
	}

?>
<?php mysql_close($connection);?>