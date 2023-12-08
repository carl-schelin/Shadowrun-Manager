<?php
# Script: members.checked.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "members.checked.php";
    $formVars['mem_id']    = clean($_GET['mem_id'], 10);

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Building the query.");

      $q_string  = "select mem_visible ";
      $q_string .= "from members ";
      $q_string .= "where mem_id = " . $formVars['mem_id'] . " ";
      $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_members = mysqli_fetch_array($q_members);

      if ($a_members['mem_visible'] == 1) {
        $q_string = "mem_visible = 0";
        $message = "Visibility disabled.";
      }
      if ($a_members['mem_visible'] == 0) {
        $q_string = "mem_visible = 1";
        $message = "Visibility enabled.";
      }

      $query = "update members set " . $q_string . " where mem_id = " . $formVars['mem_id'];

      logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['mem_id']);

      mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

      print "alert('" . $message . "');\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
