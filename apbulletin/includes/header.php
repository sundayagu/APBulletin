<?php require_once("functions.php"); ?>
<?php require_once("includes/session.php");?>
<?php require_once("includes/connection.php");?>
<!DOCTYPE html>
<html>
  <head>
    <title>KK Motins</title>
   <link href="styles/apstyles.css" media="all" rel="stylesheet" type="text/css">
   <script src="ckeditor/ckeditorf/ckeditor.js"></script>
  </head>
  <body>
    <div id="header">
      <?php
        $result = get_logo();
        if($result){
          while ($row = mysql_fetch_array($result)) {
            $pics = $row['logo'];
          }
        }
        if(empty($pics)){
          echo "<h1>KK Motins</h1>";
        }else{
          echo '<img src="includes/'.$pics.'" style="border-radius:10px; margin-left: 50px; margin-top: 5px; width:60px; height:60px;" alt="user-profile-picture"/>';
        }
      ?>
      <!--<img src="logos/Chib Sch 20170915_225715.jpg" style="width:100px; height:100px;" alt="user-profile-picture"/>-->
    </div>
    <div id="main">
