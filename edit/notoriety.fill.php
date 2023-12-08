<?php
# Script: notoriety.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Fill in the forms for editing

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "notoriety.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from notoriety");

      $q_string  = "select not_notoriety,not_date,not_notes ";
      $q_string .= "from notoriety ";
      $q_string .= "where not_id = " . $formVars['id'];
      $q_notoriety = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_notoriety = mysqli_fetch_array($q_notoriety);
      mysql_free_result($q_notoriety);

      print "document.edit.not_notoriety.value = '"   . mysql_real_escape_string($a_notoriety['not_notoriety'])  . "';\n";
      print "document.edit.not_date.value = '"        . mysql_real_escape_string($a_notoriety['not_date'])       . "';\n";
      print "document.edit.not_notes.value = '"       . mysql_real_escape_string($a_notoriety['not_notes'])      . "';\n";

      $value = (2000 - strlen($a_notoriety['not_notes']));

      print "document.edit.not_noteLen.value = " . $value . ";\n";
      print "document.edit.not_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.not_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
