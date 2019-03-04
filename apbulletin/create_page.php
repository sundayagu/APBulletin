<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php

	$errors = array();
	// Form Validation
	
	$required_fields = array('menu_name', 'position', 'visible', 'date_published');
    foreach($required_fields as $fieldname) {
    if(!isset($_POST[$fieldname])||empty($_POST[$fieldname])) {
      $errors[] = $fieldname;
    	}
  	}

  	$fields_with_lengths = array('menu_name' => 50);
  	foreach($fields_with_lengths as $fieldname => $maxlength) {
    if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength) {
      $errors[] = $fieldname;
    	}
  	}

	
	if(!empty($errors)){
		redirect_to("new_subject.php");
	}
?>

<?php
	$subject_id = mysql_prep($_GET['subj']);
	$menu_name = trim(mysql_prep($_POST['menu_name']));
	$position = mysql_prep($_POST['position']);
	$visible = mysql_prep($_POST['visible']);
	$date_published = mysql_prep($_POST['date_published']);
	$content = mysql_prep($_POST['content']);
?>
<?php
	$query = "INSERT INTO pages (
				subject_id, menu_name, position, visible, dop, content
			 ) VALUES (
			    {$subject_id},'{$menu_name}', {$position},
			     {$visible}, '{$date_published}', '{$content}'
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