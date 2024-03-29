<?php
# Script: agent.del.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Delete agent entries

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "agent.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_agents");

      $q_string  = "delete ";
      $q_string .= "from r_agents ";
      $q_string .= "where r_agt_id= " . $formVars['id'];
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      print "alert('Agent deleted.');\n";
    } else {
      logaccess($db, $_SESSION['username'], $package, "Access denied");
    }
  }
?>
