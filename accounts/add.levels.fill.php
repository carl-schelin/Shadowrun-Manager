<?php
# Script: add.levels.fill.php
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
    $package = "add.levels.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Johnson)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from levels");

      $q_string  = "select lvl_name,lvl_level,lvl_disabled ";
      $q_string .= "from levels ";
      $q_string .= "where lvl_id = " . $formVars['id'];
      $q_levels = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_levels = mysqli_fetch_array($q_levels);
      mysqli_free_result($q_levels);

      print "document.levels.lvl_name.value = '"  . mysqli_real_escape_string($db, $a_levels['lvl_name'])  . "';\n";
      print "document.levels.lvl_level.value = '" . mysqli_real_escape_string($db, $a_levels['lvl_level']) . "';\n";

      print "document.levels.lvl_disabled['" . $a_levels['lvl_disabled'] . "'].selected = 'true';\n";

      print "document.levels.id.value = " . $formVars['id'] . ";\n";

      print "document.levels.update.disabled = false;\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
