<?php
# Script: add.grades.fill.php
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
    $package = "add.grades.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from grades");

      $q_string  = "select grade_name,grade_essence,grade_avail,grade_cost,grade_book,grade_page ";
      $q_string .= "from grades ";
      $q_string .= "where grade_id = " . $formVars['id'];
      $q_grades = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_grades = mysqli_fetch_array($q_grades);
      mysql_free_result($q_grades);

      print "document.dialog.grade_name.value = '"    . mysql_real_escape_string($a_grades['grade_name'])    . "';\n";
      print "document.dialog.grade_essence.value = '" . mysql_real_escape_string($a_grades['grade_essence']) . "';\n";
      print "document.dialog.grade_avail.value = '"   . mysql_real_escape_string($a_grades['grade_avail'])   . "';\n";
      print "document.dialog.grade_cost.value = '"    . mysql_real_escape_string($a_grades['grade_cost'])    . "';\n";
      print "document.dialog.grade_book.value = '"    . mysql_real_escape_string($a_grades['grade_book'])    . "';\n";
      print "document.dialog.grade_page.value = '"    . mysql_real_escape_string($a_grades['grade_page'])    . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
