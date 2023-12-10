<?php
# Script: add.actions.fill.php
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
    $package = "add.actions.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from actions");

      $q_string  = "select action_name,action_type,action_level,action_attack,action_defense,";
      $q_string .= "action_outsider,action_user,action_admin,action_book,action_page ";
      $q_string .= "from actions ";
      $q_string .= "where action_id = " . $formVars['id'];
      $q_actions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_actions = mysqli_fetch_array($q_actions);
      mysqli_free_result($q_actions);

      print "document.dialog.action_name.value = '"      . mysqli_real_escape_string($db, $a_actions['action_name'])      . "';\n";
      print "document.dialog.action_attack.value = '"    . mysqli_real_escape_string($db, $a_actions['action_attack'])    . "';\n";
      print "document.dialog.action_defense.value = '"   . mysqli_real_escape_string($db, $a_actions['action_defense'])   . "';\n";
      print "document.dialog.action_book.value = '"      . mysqli_real_escape_string($db, $a_actions['action_book'])      . "';\n";
      print "document.dialog.action_page.value = '"      . mysqli_real_escape_string($db, $a_actions['action_page'])      . "';\n";

      print "document.dialog.action_type['"  . $a_actions['action_type']  . "'].checked = true;\n";
      print "document.dialog.action_level['" . $a_actions['action_level'] . "'].checked = true;\n";

      if ($a_actions['action_outsider']) {
        print "document.dialog.action_outsider.checked = true;\n";
      } else {
        print "document.dialog.action_outsider.checked = false;\n";
      }
      if ($a_actions['action_user']) {
        print "document.dialog.action_user.checked = true;\n";
      } else {
        print "document.dialog.action_user.checked = false;\n";
      }
      if ($a_actions['action_admin']) {
        print "document.dialog.action_admin.checked = true;\n";
      } else {
        print "document.dialog.action_admin.checked = false;\n";
      }

      print "document.dialog.id.value = '" . $formVars['id'] . "'\n";
      print "$(\"#button-update\").button(\"enable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
