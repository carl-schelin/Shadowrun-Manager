<?php
# Script: command.del.php
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
    $package = "command.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_command");

      $q_string  = "select r_cmd_character ";
      $q_string .= "from r_command ";
      $q_string .= "where r_cmd_id = " . $formVars['id'] . " ";
      $q_r_command = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_r_command = mysqli_fetch_array($q_r_command);

# delete accessories
      $q_string  = "delete ";
      $q_string .= "from r_accessory ";
      $q_string .= "where r_acc_character = " . $a_r_command['r_cmd_character'] . " and r_acc_parentid = " . $formVars['id'] . " ";
      $result = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

# delete all the programs associated with the deleted console
      $q_string  = "delete ";
      $q_string .= "from r_program ";
      $q_string .= "where r_pgm_command = " . $formVars['id'] . " ";
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

# need to delete the console plus all associated programs and accessories
      $q_string  = "delete ";
      $q_string .= "from r_command ";
      $q_string .= "where r_cmd_id = " . $formVars['id'] . " ";
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      print "alert('Rigger Command Console deleted.');\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Access denied");
    }
  }
?>
