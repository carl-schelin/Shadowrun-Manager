<?php
# Script: karma.fill.php
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
    $package = "karma.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from history");

      $q_string  = "select kar_karma,kar_date,kar_notes ";
      $q_string .= "from karma ";
      $q_string .= "where kar_id = " . $formVars['id'];
      $q_karma = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_karma = mysqli_fetch_array($q_karma);
      mysql_free_result($q_karma);

      print "document.edit.kar_karma.value = '"    . mysql_real_escape_string($a_karma['kar_karma'])    . "';\n";
      print "document.edit.kar_date.value = '"     . mysql_real_escape_string($a_karma['kar_date'])     . "';\n";
      print "document.edit.kar_notes.value = '"    . mysql_real_escape_string($a_karma['kar_notes'])    . "';\n";

      $value = (2000 - strlen($a_karma['kar_notes']));

      print "document.edit.kar_noteLen.value = " . $value . ";\n";
      print "document.edit.kar_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.kar_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
