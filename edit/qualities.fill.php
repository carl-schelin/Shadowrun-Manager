<?php
# Script: qualities.fill.php
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
    $package = "qualities.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      logaccess($db, $_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_qualities");

      $q_string  = "select r_qual_number,r_qual_details ";
      $q_string .= "from r_qualities ";
      $q_string .= "where r_qual_id = " . $formVars['id'];
      $q_r_qualities = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_r_qualities = mysqli_fetch_array($q_r_qualities);
      mysqli_free_result($q_r_qualities);

      print "document.edit.r_qual_number.value = '"  . mysqli_real_escape_string($db, $a_r_qualities['r_qual_number'])  . "';\n";
      print "document.edit.r_qual_details.value = '" . mysqli_real_escape_string($db, $a_r_qualities['r_qual_details']) . "';\n";

      print "document.edit.r_qual_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_qual_update.disabled = false;\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
