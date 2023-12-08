<?php
# Script: cyberdeck.del.php
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
    $package = "cyberdeck.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_cyberdeck");

      $q_string  = "select r_deck_character ";
      $q_string .= "from r_cyberdeck ";
      $q_string .= "where r_deck_id = " . $formVars['id'] . " ";
      $q_r_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_cyberdeck = mysqli_fetch_array($q_r_commlink);

      $q_string  = "delete ";
      $q_string .= "from r_accessory ";
      $q_string .= "where r_acc_character = " . $a_r_cyberdeck['r_deck_character'] . " and r_acc_parentid = " . $formVars['id'] . " ";
      $result = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

# delete all the agents associated with the deleted deck
      $q_string  = "delete ";
      $q_string .= "from r_agents ";
      $q_string .= "where r_agt_cyberdeck = " . $formVars['id'] . " ";
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      print "alert('Cyberdeck agents deleted.');\n";

# delete all the programs associated with the deleted deck
      $q_string  = "delete ";
      $q_string .= "from r_program ";
      $q_string .= "where r_pgm_cyberdeck = " . $formVars['id'] . " ";
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      print "alert('Cyberdeck programs deleted.');\n";

# need to delete the deck plus all associated programs and accessories
      $q_string  = "delete ";
      $q_string .= "from r_cyberdeck ";
      $q_string .= "where r_deck_id = " . $formVars['id'] . " ";
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      print "alert('Cyberdeck deleted.');\n";

    } else {
      logaccess($_SESSION['username'], $package, "Access denied");
    }
  }
?>
