<?php
# Script: commlink.del.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Delete association entries

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "commlink.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_commlink");

      $q_string  = "select r_link_character ";
      $q_string .= "from r_commlink ";
      $q_string .= "where r_link_id = " . $formVars['id'] . " ";
      $q_r_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_commlink = mysql_fetch_array($q_r_commlink);

      $q_string  = "delete ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "where sub_name = \"Commlinks\" and r_acc_character = " . $a_r_commlink['r_link_character'] . " and r_acc_parentid = " . $formVars['id'] . " ";
      $result = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      $q_string  = "delete ";
      $q_string .= "from r_commlink ";
      $q_string .= "where r_link_id= " . $formVars['id'] . " ";
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      print "alert('Commlink deleted.');\n";
    } else {
      logaccess($_SESSION['username'], $package, "Access denied");
    }
  }
?>
