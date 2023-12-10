<?php
# Script: add.members.fill.php
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
    $package = "add.members.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Fixer)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from members");

      $q_string  = "select mem_runner,mem_invite,mem_active ";
      $q_string .= "from members ";
      $q_string .= "where mem_id = " . $formVars['id'];
      $q_members = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_members = mysqli_fetch_array($q_members);
      mysqli_free_result($q_members);

      $runner = return_Index($db, $a_members['mem_runner'], "select runr_id from runners where runr_available = 1 order by runr_name");

      print "document.members.mem_active.value = '" . mysqli_real_escape_string($db, $a_members['mem_active'])     . "';\n";

      print "document.members.mem_invite['" . $a_members['mem_invite'] . "'].selected = true;\n";
      print "document.members.mem_runner['" . $runner                  . "'].selected = true;\n";

      print "document.members.id.value = " . $formVars['id'] . ";\n";

      print "document.members.update.disabled = false;\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
