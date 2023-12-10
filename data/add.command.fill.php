<?php
# Script: add.command.fill.php
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
    $package = "add.command.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from command");

      $q_string  = "select cmd_brand,cmd_model,cmd_rating,cmd_data,cmd_firewall,cmd_programs,cmd_access,cmd_cost,cmd_avail,cmd_perm,cmd_book,cmd_page ";
      $q_string .= "from command ";
      $q_string .= "where cmd_id = " . $formVars['id'];
      $q_command = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_command = mysqli_fetch_array($q_command);
      mysqli_free_result($q_command);

      print "document.dialog.cmd_brand.value = '"    . mysqli_real_escape_string($db, $a_command['cmd_brand'])     . "';\n";
      print "document.dialog.cmd_model.value = '"    . mysqli_real_escape_string($db, $a_command['cmd_model'])     . "';\n";
      print "document.dialog.cmd_rating.value = '"   . mysqli_real_escape_string($db, $a_command['cmd_rating'])    . "';\n";
      print "document.dialog.cmd_data.value = '"     . mysqli_real_escape_string($db, $a_command['cmd_data'])      . "';\n";
      print "document.dialog.cmd_firewall.value = '" . mysqli_real_escape_string($db, $a_command['cmd_firewall'])  . "';\n";
      print "document.dialog.cmd_programs.value = '" . mysqli_real_escape_string($db, $a_command['cmd_programs'])  . "';\n";
      print "document.dialog.cmd_access.value = '"   . mysqli_real_escape_string($db, $a_command['cmd_access'])    . "';\n";
      print "document.dialog.cmd_cost.value = '"     . mysqli_real_escape_string($db, $a_command['cmd_cost'])      . "';\n";
      print "document.dialog.cmd_avail.value = '"    . mysqli_real_escape_string($db, $a_command['cmd_avail'])     . "';\n";
      print "document.dialog.cmd_perm.value = '"     . mysqli_real_escape_string($db, $a_command['cmd_perm'])      . "';\n";
      print "document.dialog.cmd_book.value = '"     . mysqli_real_escape_string($db, $a_command['cmd_book'])      . "';\n";
      print "document.dialog.cmd_page.value = '"     . mysqli_real_escape_string($db, $a_command['cmd_page'])      . "';\n";

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
