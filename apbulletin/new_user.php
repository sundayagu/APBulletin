<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php confirm_role(); ?>
<?php find_selected_user(); ?>
<?php
	include_once("includes/form_functions.php");

	// START FORM PROCESSING
	if (isset($_POST['submit'])) {  // Form has been submitted.
		$errors = array();

	// perform validations on the form data
	$required_fields = array('username', 'password');
	$errors = array_merge($errors, check_required_fields($required_fields, $_POST));

	$fields_with_lengths = array('username' => 30);
	$errors = array_merge($errors, check_max_field_lengths($fields_with_lengths, $_POST));

	$username = trim(mysql_prep($_POST['username']));
    $password = trim(mysql_prep($_POST['password']));
    $role = trim(mysql_prep($_POST['role']));
    $hashed_password = sha1($password);

  	if (empty($errors)) {
			$query = "SELECT * ";
    	$query .= "FROM users ";
    	$query .= "WHERE username = '{$username}' ";
    	$query .= "LIMIT 1";
    	$result_set = mysql_query($query);
			confirm_query($result_set);
			// Checks if username already exits
    	if (mysql_num_rows($result_set) == 1) {
				$message = '<span style = "color:red;"><strong>*</strong></span>The username already exits';
			}else{
				$query = "INSERT INTO users ( 
					username, hashed_password, role_id 
				) VALUES (
						'{$username}', '{$hashed_password}', '{$role}' 
					)";
				$result = mysql_query($query, $connection);
				if ($result) {
				$message = "The user was successfully created.";
				}else{
				$message = "The user could not be created.";
				$message .= "<br/>" . mysql_error();
				}
			}
  	}else{
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
<?php include("includes/header.php"); ?>
<table id="structure">
  <tr>
    <td id="navigation">
    	+ Edit Users
      <?php echo user_navigation($sel_user) ;  ?>
      <a href="ap-admin.php">Return to Menu</a>
      <br/>
    </td>
    <td id="page">
        <h2>Create New User</h2>
      	<?php if(!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";}?>
		<?php if(!empty($errors)) { display_errors($errors); } ?>
		<form action="new_user.php" method="post">
			<p>Username:
			<input type="text" name="username" maxlength="30" 
					value="<?php echo htmlentities($username); ?>" /></p>

			<p>User Role:  
				<select name="role">
					<?php
						$result = get_roles();
						while ($row = mysql_fetch_array($result)) {
								echo "<option value='" . $row['id'] ."'>" . $row['name'] ."</option>";
						}
					?>
				</select>
			</p>

			<p>Password:
			<input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" /></p><br/>
			
			<input type="submit" name="submit" value="Create User" />&nbsp;&nbsp;&nbsp;<a href="ap-admin.php">Cancel</a>
		</form>
	</td>
</tr>
</table>
<?php require("includes/footer.php");?>