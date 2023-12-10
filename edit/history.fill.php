<?php
# Script: history.fill.php
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
    $package = "history.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from history");

      $q_string  = "select his_date,his_notes ";
      $q_string .= "from history ";
      $q_string .= "where his_id = " . $formVars['id'];
      $q_history = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_history = mysqli_fetch_array($q_history);
      mysqli_free_result($q_history);

      print "document.edit.his_date.value = '"     . mysqli_real_escape_string($db, $a_history['his_date'])     . "';\n";
      print "document.edit.his_notes.value = '"    . mysqli_real_escape_string($db, $a_history['his_notes'])    . "';\n";

      $value = (2000 - strlen($a_history['his_notes']));

      print "document.edit.his_noteLen.value = " . $value . ";\n";
      print "document.edit.his_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.his_update.disabled = false;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
