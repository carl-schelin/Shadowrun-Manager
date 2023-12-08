<?php
# Script: command.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Fill in the forms for editing

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "command.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_command");

      $q_string  = "select cmd_brand,cmd_model,r_cmd_number,r_cmd_noise,r_cmd_sharing ";
      $q_string .= "from r_command ";
      $q_string .= "left join command on command.cmd_id = r_command.r_cmd_number ";
      $q_string .= "where r_cmd_id = " . $formVars['id'];
      $q_r_command = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_command = mysqli_fetch_array($q_r_command);
      mysql_free_result($q_r_command);

      print "document.getElementById('r_cmd_item').innerHTML = '" . mysql_real_escape_string($a_r_command['cmd_brand'] . " " . $a_r_command['cmd_model']) . "';\n\n";

      print "document.edit.r_cmd_noise.value = '"   . mysql_real_escape_string($a_r_command['r_cmd_noise'])   . "';\n";
      print "document.edit.r_cmd_sharing.value = '" . mysql_real_escape_string($a_r_command['r_cmd_sharing']) . "';\n";
      print "document.edit.r_cmd_number.value = '"  . mysql_real_escape_string($a_r_command['r_cmd_number']) . "';\n";

      print "document.edit.r_cmd_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_cmd_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
