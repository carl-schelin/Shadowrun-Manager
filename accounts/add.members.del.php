<?php
# Script: add.members.del.php
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
    $package = "add.members.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Fixer)) {
      logaccess($db, $_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from members");

      $q_string  = "delete ";
      $q_string .= "from members ";
      $q_string .= "where mem_id = " . $formVars['id'];
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      print "alert('Group Member deleted.');\n";
    } else {
      logaccess($db, $_SESSION['username'], $package, "Access denied");
    }
  }
?>
