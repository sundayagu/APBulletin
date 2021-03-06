<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php"); ?>
<?php include_once("includes/form_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	$errors = array();
	// Form Validation
	$required_fields = array('menu_name', 'position', 'visible');
    $errors = array_merge($errors, check_required_fields($required_fields));

    $fields_with_lengths = array('menu_name' => 50);
    $errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));

	if(!empty($errors)){
		redirect_to("new_subject.php");
	}
?>

<?php
	$menu_name = mysql_prep($_POST['menu_name']);
	$position = mysql_prep($_POST['position']);
	$visible = mysql_prep($_POST['visible']);
?>
<?php
	$query = "INSERT INTO subjects (
				menu_name, position, visible
			 ) VALUES (
			    '{$menu_name}', {$position}, {$visible}
			 )";
	
	$result = mysql_query($query, $connection);
	if($result){
		redirect_to("content.php");
	} else {
		echo "<p>  Subject creation failed </p>";
		echo "<p>" . mysql_error() . "</p>";
	}
?>
<?php mysql_close($connection);?>