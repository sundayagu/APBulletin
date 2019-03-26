<?php require_once("includes/connection.php");?>
<?php require_once("includes/functions.php"); ?>
<?php find_selected_page(); ?>
<?php include("includes/header.php"); ?>
<style>
  * {
    line-height: 1.5;
  }
</style>
  <table id="structure">
    <tr>
      <td id="navigation">
        <?php echo public_navigation($sel_subject, $sel_page) ;  ?>
      </td>
      <td id="page">
        <?php if($sel_page) { ?>
        <h2><?php echo htmlentities($sel_page["menu_name"]);?></h2>
        <div class = "page-content">
        <?php 
          //echo strip_tags(nl2br($sel_page["content"]), "<b><br><p><a><hr><i><u><h1><h2><h3><h4><h5><h6><center>");
          echo $sel_page["content"];
        ?>
        </div>
        <?php } else{ ?>
         <h2> Welcome to A. A. Mortins</h2>
       <?php }?>
      </td>
    </tr>
  </table>
<?php require("includes/footer.php");?>
