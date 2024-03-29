<?php
# Script: add.groups.fill.php
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
    $package = "add.groups.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Fixer)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from groups");

      $q_string  = "select grp_disabled,grp_name,grp_email,grp_owner ";
      $q_string .= "from groups ";
      $q_string .= "where grp_id = " . $formVars['id'];
      $q_groups = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_groups = mysqli_fetch_array($q_groups);
      mysqli_free_result($q_groups);

      $user = return_Index($db, $a_groups['grp_owner'], "select usr_id from users order by usr_last,usr_first");

      print "document.groups.grp_name.value = '"      . mysqli_real_escape_string($db, $a_groups['grp_name'])      . "';\n";
      print "document.groups.grp_email.value = '"     . mysqli_real_escape_string($db, $a_groups['grp_email'])     . "';\n";

      print "document.groups.grp_disabled['" . $a_groups['grp_disabled'] . "'].selected = true;\n";
      print "document.groups.grp_owner['"    . $user                     . "'].selected = true;\n";

      print "document.groups.id.value = " . $formVars['id'] . ";\n";

      print "document.groups.update.disabled = false;\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
