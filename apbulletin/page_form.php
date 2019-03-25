<?php require_once("includes/session.php");?>
<?php confirm_logged_in(); ?>
<?php // This page is included by new_page.php and edit_page.php ?>
<?php if (!isset($new_page)) {$new_page = false;} ?>

<P>Page name:
<input type="text" name="menu_name" value="<?php
if (!$new_page) {
      echo htmlentities($sel_page['menu_name']) ;
   } else {
      echo htmlentities("");
    } ?>" id="menu_name" /></P>
<p>Position:
<select name="position">
  <?php
    if (!$new_page) {
      $page_set = get_pages_for_subject($sel_page["subject_id"]);
      $page_count = mysql_num_rows($page_set);
    } else {
      $page_set = get_pages_for_subject($sel_subject["id"]);
      // $page + 1 because we are adding a page.
      $page_count = mysql_num_rows($page_set) + 1;
    }
    for ($count=1; $count <= $page_count; $count++) { 
      echo "<option value=\"{$count}\" ";
      if ($sel_page['position'] == $count) {echo " selected"; }
          echo ">{$count}</option>";
    }
  ?>
</select>
</p>
<p>Visible
  <input type="radio" name="visible" value="1" <?php 
    if ($sel_page['visible'] == 1) { echo " checked"; }
  ?> /> No
  &nbsp;
 <input type="radio" name="visible" value="2" <?php
    if ($sel_page['visible'] == 2) { echo " checked"; }
 ?>/> Yes
</p>
<p>Date of publication
    <input type="Date" name="date_published" value="<?php
    if (!$new_page) {
       echo $sel_page['dop'] ;
     } else{
    echo "" ; }?>" >
</p>
<p>Content:<br/>
  <textarea name="content" cols="120" rows="200" maxlength="300000" id="comment">
    <?php 
      if (!$new_page) {
        echo $sel_page['content'];
      }?>
 </textarea>
 <script>
    CKEDITOR.replace('comment');
  </script>
</p><br/>