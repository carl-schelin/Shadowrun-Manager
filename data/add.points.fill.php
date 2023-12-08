<?php
# Script: add.points.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "add.points.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from points");

      $q_string  = "select point_number,point_cost,point_level,point_book,point_page ";
      $q_string .= "from points ";
      $q_string .= "where point_id = " . $formVars['id'];
      $q_points = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_points = mysqli_fetch_array($q_points);
      mysql_free_result($q_points);

      print "document.dialog.point_number.value = '"  . mysql_real_escape_string($a_points['point_number'])   . "';\n";
      print "document.dialog.point_cost.value = '"    . mysql_real_escape_string($a_points['point_cost'])     . "';\n";
      print "document.dialog.point_level.value = '"   . mysql_real_escape_string($a_points['point_level'])    . "';\n";
      print "document.dialog.point_book.value = '"    . mysql_real_escape_string($a_points['point_book'])     . "';\n";
      print "document.dialog.point_page.value = '"    . mysql_real_escape_string($a_points['point_page'])     . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
