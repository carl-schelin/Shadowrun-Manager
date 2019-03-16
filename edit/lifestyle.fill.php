<?php
# Script: lifestyle.fill.php
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
    $package = "lifestyle.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_lifestyle");

      $q_string  = "select r_life_number,r_life_desc,r_life_months ";
      $q_string .= "from r_lifestyle ";
      $q_string .= "where r_life_id = " . $formVars['id'];
      $q_r_lifestyle = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_lifestyle = mysql_fetch_array($q_r_lifestyle);
      mysql_free_result($q_r_lifestyle);

      print "document.edit.r_life_number.value = '" . mysql_real_escape_string($a_r_lifestyle['r_life_number']) . "';\n";
      print "document.edit.r_life_desc.value = '"   . mysql_real_escape_string($a_r_lifestyle['r_life_desc'])   . "';\n";
      print "document.edit.r_life_months.value = '" . mysql_real_escape_string($a_r_lifestyle['r_life_months']) . "';\n";

      print "document.edit.r_life_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_life_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
