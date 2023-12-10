<?php
# Script: add.class.fill.php
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
    $package = "add.class.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from class");

      $q_string  = "select class_subjectid,class_name ";
      $q_string .= "from class ";
      $q_string .= "where class_id = " . $formVars['id'];
      $q_class = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_class = mysqli_fetch_array($q_class);
      mysqli_free_result($q_class);

      print "document.dialog.class_subjectid.value = '" . mysqli_real_escape_string($db, $a_class['class_subjectid']) . "';\n";
      print "document.dialog.class_name.value = '"      . mysqli_real_escape_string($db, $a_class['class_name'])      . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
