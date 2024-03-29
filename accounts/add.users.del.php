<?php
# Script: add.users.del.php
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
    $package = "add.users.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      if ($formVars['id'] == $_SESSION['uid']) {
        print "alert('You cannot delete yourself.');\n";
        exit;
      }
      if ($formVars['id'] == 1) {
        print "alert('You cannot delete the Administrator account.');\n";
        exit;
      }

      logaccess($db, $_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from users");

      $q_string  = "delete ";
      $q_string .= "from users ";
      $q_string .= "where usr_id = " . $formVars['id'];
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      print "alert('User deleted.');\n";
    } else {
      logaccess($db, $_SESSION['username'], $package, "Access denied");
    }
  }
?>
