<?php
# Script: active.fill.php
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
    $package = "active.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_active");

      $q_string  = "select r_act_number,r_act_rank,r_act_specialize ";
      $q_string .= "from r_active ";
      $q_string .= "where r_act_id = " . $formVars['id'];
      $q_r_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_active = mysql_fetch_array($q_r_active);
      mysql_free_result($q_r_active);

      print "document.edit.r_act_number.value = '"     . mysql_real_escape_string($a_r_active['r_act_number'])     . "';\n";
      print "document.edit.r_act_rank.value = '"       . mysql_real_escape_string($a_r_active['r_act_rank'])       . "';\n";
      print "document.edit.r_act_specialize.value = '" . mysql_real_escape_string($a_r_active['r_act_specialize']) . "';\n";

      print "document.edit.r_act_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_act_group.disabled = true;\n\n";
      print "document.edit.r_act_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
