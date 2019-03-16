<?php
# Script: weaknesses.dialog.php
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
    $package = "weaknesses.dialog.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from sp_weaknesses");

      $q_string  = "select sp_weak_specialize ";
      $q_string .= "from sp_weaknesses ";
      $q_string .= "where sp_weak_id = " . $formVars['id'];
      $q_sp_weaknesses = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_sp_weaknesses = mysql_fetch_array($q_sp_weaknesses);
      mysql_free_result($q_sp_weaknesses);

      print "document.weakness.sp_weak_specialize.value = '" . mysql_real_escape_string($a_sp_weaknesses['sp_weak_specialize']) . "';\n";

      print "document.weakness.id.value = " . $formVars['id'] . ";\n";
      print "$(\"#weakness-update\").button(\"enable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
