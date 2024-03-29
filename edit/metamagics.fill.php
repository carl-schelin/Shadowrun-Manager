<?php
# Script: metamagics.fill.php
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
    $package = "metamagics.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_metamagics");

      $q_string  = "select r_meta_number,meta_name ";
      $q_string .= "from r_metamagics ";
      $q_string .= "left join metamagics on metamagics.meta_id = r_metamagics.r_meta_number ";
      $q_string .= "where r_meta_id = " . $formVars['id'];
      $q_r_metamagics = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_r_metamagics = mysqli_fetch_array($q_r_metamagics);
      mysqli_free_result($q_r_metamagics);

      print "document.getElementById('r_meta_item').innerHTML = '" . mysqli_real_escape_string($db, $a_r_metamagics['meta_name']) . "';\n\n";

      print "document.edit.r_meta_number.value = '" . mysqli_real_escape_string($db, $a_r_adept['r_meta_number'])     . "';\n";

      print "document.edit.r_meta_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_meta_update.disabled = false;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
