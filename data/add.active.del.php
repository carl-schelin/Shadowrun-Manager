<?php
# Script: add.active.del.php
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
    $package = "add.active.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(1)) {

      $q_string  = "delete ";
      $q_string .= "from active ";
      $q_string .= "where act_id = " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      $q_string  = "delete ";
      $q_string .= "from r_active ";
      $q_string .= "where r_act_number = " . $formVars['id'];
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      print "alert('Active Skill deleted.');\n";
    } else {
      logaccess($_SESSION['username'], $package, "Access denied");
    }
  }
?>
