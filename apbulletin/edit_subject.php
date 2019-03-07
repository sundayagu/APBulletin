<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php"); ?>
<?php include_once("includes/form_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
  if(intval($_GET['subj']) == 0){
    redirect_to("content.php");
  }
  if (isset($_POST['submit'])) {
    $errors = array();

  $required_fields = array('menu_name', 'position', 'visible');
    $errors = array_merge($errors, check_required_fields($required_fields));

    $fields_with_lengths = array('menu_name' => 50);
    $errors = array_merge($errors, check_max_field_lengths($fields_with_lengths));

  if (empty($errors)) {
    $id = mysql_prep($_GET['subj']);
    $menu_name = mysql_prep($_POST['menu_name']);
    $position = mysql_prep($_POST['position']);
    $visible = mysql_prep($_POST['visible']);

    $query = "UPDATE subjects SET 
                menu_name = '{$menu_name}', 
                position = {$position},
                visible = {$visible}
              WHERE id = {$id}";
    $result = mysql_query($query, $connection);
    if (mysql_affected_rows() == 1) {
      // Success
      $message = "The subject was successfully updated.";
    } else{
      // Failed
      $message = "The subject updated failed";
      $message .= "<br/>" . mysql_error();
    }
  
  } else{   // else of: if (empty($errors))
    // Errors occurred
    if (count($errors) == 1) {
      $message = "There was 1 error in the form.";
    } else{
      $message = "There were " . count($errors) . "errors in the form.";
    }

  } // end of: if (empty($errors))

  } // end:if(isset($_POST['submit'])).Currently there it has no "Else"
?>
<?php find_selected_page(); ?>
<?php include("includes/header.php"); ?>
  <table id="structure">
    <tr>
      <td id="navigation">
        <?php echo navigation($sel_subject, $sel_page); ?>
      </td>
      <td id="page">
        <h2>Edit Subject: <?php echo $sel_subject['menu_name']; ?></h2>
        <?php if (!empty($message)) {
                echo "<p class=\"message\">" . $message . "</p>";
        } ?>
        <?php
        // output a list of the fields that had errors
        if (!empty($errors)) {
          echo "<p class=\"errors\">";
          echo "Please review the following fields:<br />";
          foreach ($errors as $error) {
            echo "-" . $error . "<br />";
          }
          echo "</p>";
        }
        ?>
        <form action="edit_subject.php?subj=<?php 
                    echo urlencode($sel_subject["id"]); 
                    ?>" method="POST">
          <P>Subject name:
              <input type="text" name="menu_name" value="<?php
               echo htmlentities($sel_subject['menu_name']);?>" id="menu_name" />
          </P>
          <p>Position:
             <select name="position">
              <?php
                $subjet_set = get_all_subjects();
                $subject_count = mysql_num_rows($subjet_set);
                // $subject + 1 because we are adding a subject.
                for ($count=1; $count <= $subject_count + 1; $count++) { 
                  echo "<option value=\"{$count}\" ";
                  if ($sel_subject['position'] == $count) {
                    echo " selected";
                  }
                  echo ">{$count}</option>";
                }
              ?>
            </select>
          </p>
          <p>Visible
            <input type="radio" name="visible" value="1" <?php 
            if ($sel_subject['visible'] == 1) { echo " checked"; }
             ?> /> No
            &nbsp;
            <input type="radio" name="visible" value="2" <?php
             if ($sel_subject['visible'] == 2) { echo " checked"; }
            ?>/> Yes
          </p>
          <input type="submit" name="submit" value="Edit subject" />
          &nbsp;&nbsp;
          <a href="delete_subject.php?subj=<?php
            echo urlencode($sel_subject["id"]) ; ?>
          " onclick="return confirm('Are you sure');">Delete Subject</a>
        </form>
        <br/>
        <a href="content.php">Cancel</a>
        <br/><br/>
        <hr>
        <h3>Pages in this subject:</h3>
        <?php
        echo "<ul class=\"pages\">";
          $pages = get_pages_for_subject($sel_subject["id"]);
           while ($page = mysql_fetch_array($pages)) {
              echo "<li><a href=\"content.php?page=" .urlencode($page["id"]) ;
              echo "\">{$page["menu_name"]}</a></li>";
            }
        echo "</ul>";
        ?>
        <br/><br/>+
        <a href="new_page.php?subj=<?php
         echo urlencode($sel_subject["id"]) ;?>
         "> Add a new page to this subject.</a>
      </td>
    </tr>
  </table>
  
<?php require("includes/footer.php");?>
