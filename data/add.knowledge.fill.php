<?php
# Script: add.knowledge.fill.php
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
    $package = "add.knowledge.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from knowledge");

      $q_string  = "select know_name,know_attribute ";
      $q_string .= "from knowledge ";
      $q_string .= "where know_id = " . $formVars['id'];
      $q_knowledge = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_knowledge = mysqli_fetch_array($q_knowledge);
      mysqli_free_result($q_knowledge);

      print "document.dialog.know_name.value = '"      . mysqli_real_escape_string($db, $a_knowledge['know_name'])      . "';\n";
      print "document.dialog.know_attribute.value = '" . mysqli_real_escape_string($db, $a_knowledge['know_attribute']) . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
