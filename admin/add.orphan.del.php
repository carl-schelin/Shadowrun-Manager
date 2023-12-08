<?php
# Script: add.orphan.del.php
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
    $package = "add.orphan.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }
    $formVars['table'] = '';
    if (isset($_GET['table'])) {
      $formVars['table'] = clean($_GET['table'], 40);
    }
    $formVars['index'] = '';
    if (isset($_GET['index'])) {
      $formVars['index'] = clean($_GET['index'], 40);
    }

    if (check_userlevel(1)) {

      if ($formVars['table'] != '' && $formVars['index'] != '') {
        $q_string  = "delete ";
        $q_string .= "from " . $formVars['table'] . " ";
        $q_string .= "where " . $formVars['index'] . " = " . $formVars['id'];
        $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

        print "alert('Record deleted from " . $formVars['table'] . ".');\n";
      }
    } else {
      logaccess($_SESSION['username'], $package, "Access denied");
    }
  }
?>
