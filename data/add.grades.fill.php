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

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from grades");

      $q_string  = "select grade_name,grade_essence,grade_avail,grade_cost,grade_book,grade_page ";
      $q_string .= "from grades ";
      $q_string .= "where grade_id = " . $formVars['id'];
      $q_grades = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_grades = mysqli_fetch_array($q_grades);
      mysqli_free_result($q_grades);

      print "document.dialog.grade_name.value = '"    . mysqli_real_escape_string($db, $a_grades['grade_name'])    . "';\n";
      print "document.dialog.grade_essence.value = '" . mysqli_real_escape_string($db, $a_grades['grade_essence']) . "';\n";
      print "document.dialog.grade_avail.value = '"   . mysqli_real_escape_string($db, $a_grades['grade_avail'])   . "';\n";
      print "document.dialog.grade_cost.value = '"    . mysqli_real_escape_string($db, $a_grades['grade_cost'])    . "';\n";
      print "document.dialog.grade_book.value = '"    . mysqli_real_escape_string($db, $a_grades['grade_book'])    . "';\n";
      print "document.dialog.grade_page.value = '"    . mysqli_real_escape_string($db, $a_grades['grade_page'])    . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
