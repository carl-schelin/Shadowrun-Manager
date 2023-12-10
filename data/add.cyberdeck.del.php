<?php
# Script: add.cyberdeck.del.php
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
    $package = "add.cyberdeck.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from cyberdeck");

      $q_string  = "delete ";
      $q_string .= "from cyberdeck ";
      $q_string .= "where deck_id = " . $formVars['id'];
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

# need to delete all runner owned cyberdecks plus accessories, programs, and agents.
      $q_string  = "select r_deck_id ";
      $q_string .= "from r_cyberdeck ";
      $q_string .= "where r_deck_number = " . $formVars['id'] . " ";
      $q_r_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_cyberdeck) > 0) {
        while ($a_r_cyberdeck = mysqli_fetch_array($q_r_cyberdeck)) {
# delete from accessories
          $q_string  = "delete ";
          $q_string .= "from r_programs ";
          $q_string .= "where r_pgm_cyberdeck = " . $a_r_cyberdeck['r_deck_id'];
          $input = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

          $q_string  = "delete ";
          $q_string .= "from r_agents ";
          $q_string .= "where r_agt_cyberdeck = " . $a_r_cyberdeck['r_deck_id'];
          $input = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

        }
      }
    } else {
      logaccess($db, $_SESSION['username'], $package, "Access denied");
    }
  }
?>
