<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php confirm_role(); ?>
<?php
	include_once("includes/form_functions.php");

	// START FORM PROCESSING
	
	if(intval($_GET['userid']) == 0){
    redirect_to("ap-admin.php");
    }
	
	if (isset($_POST['submit'])) {  // Form has been submitted.
		$errors = array();

	// perform validations on the form data
	$required_fields = array('username', 'password');
	$errors = array_merge($errors, check_required_fields($required_fields, $_POST));

	$fields_with_lengths = array('username' => 30);
	$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths, $_POST));

	$id = mysql_prep($_GET['userid']);
	$username = trim(mysql_prep($_POST['username']));
    $password = trim(mysql_prep($_POST['password']));
    $role = trim(mysql_prep($_POST['role']));
    $hashed_password = $password;

    if (empty($errors)) {
     $query = "UPDATE users SET 
                username = '{$username}', 
                hashed_password = '{$hashed_password}',
                role_id = '{$role}' 
                WHERE id = '{$id}' ";
    $result = mysql_query($query, $connection);
    if (mysql_affected_rows() == 1) {
      // Success
      $message = "The user was successfully updated.";
    } else{
      // Failed
      $message = "The user updated failed";
      $message .= "<br/>" . mysql_error();
    }
  
   } else{
      if (count($errors) == 1) {
           $message = "There was 1 error in the form.";
      } else {
           $message = "There were " . count($errors) . "errors in the form.";
     }
   }   // end of: if (empty($errors))

		
} else {  // Form has not been submitted.
		$username = "";
		$password = "";
}

?>
<?php find_selected_user(); ?>
<?php include("includes/header.php"); ?>
<table id="structure">
  <tr>
    <td id="navigation">
      + Edit Users
      <?php echo user_navigation($sel_user) ;  ?>
      <br/>
      <a href="ap-admin.php">Return to Menu</a>
      <br/>
    </td>
    <td id="page">
        <h2>Edit  User</h2>
      	<?php if(!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";}?>
		<?php if(!empty($errors)) { display_errors($errors); } ?>
		<form action="edit_user.php?userid=<?php 
			echo urldecode($sel_user["id"]);
		?>" method="post">
		<table>
			<tr>
				<td>Username:</td>
				<td><input type="text" name="username" maxlength="30" 
					value="<?php echo htmlentities($sel_user['username']); ?>" /></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password" maxlength="40" 
					value="<?php echo htmlentities($sel_user['hashed_password']); ?>" /></td>
			</tr>
      <tr>
        <td>User Role:</td>
          <td><select name="role">
            <?php
              $result = get_roles();
              while ($row = mysql_fetch_array($result)) {
                  echo "<option value='" . $row['id'] ."'>" . $row['name'] ."</option>";
              }
            ?>
          </select></td>
      </tr>
			<tr>
				<td colspan="2" ><input type="submit" name="submit" value="Update User" />&nbsp;&nbsp;
          
          <a href="delete_user.php?userid=<?php echo urlencode($sel_user["id"]) ; ?>
          " onclick="return confirm('Are you sure');">Delete User</a>


				</td>
			</tr>	
		</table>
		</form>
	</td>
</tr>
</table>
<?php require("includes/footer.php");?>