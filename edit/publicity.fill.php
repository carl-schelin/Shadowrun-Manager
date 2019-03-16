<?php
# Script: publicity.fill.php
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
    $package = "publicity.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from publicity");

      $q_string  = "select pub_publicity,pub_date,pub_notes ";
      $q_string .= "from publicity ";
      $q_string .= "where pub_id = " . $formVars['id'];
      $q_publicity = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_publicity = mysql_fetch_array($q_publicity);
      mysql_free_result($q_publicity);

      print "document.edit.pub_publicity.value = '"   . mysql_real_escape_string($a_publicity['pub_publicity'])  . "';\n";
      print "document.edit.pub_date.value = '"        . mysql_real_escape_string($a_publicity['pub_date'])       . "';\n";
      print "document.edit.pub_notes.value = '"       . mysql_real_escape_string($a_publicity['pub_notes'])      . "';\n";

      $value = (2000 - strlen($a_publicity['pub_notes']));

      print "document.edit.pub_noteLen.value = " . $value . ";\n";
      print "document.edit.pub_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.pub_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
