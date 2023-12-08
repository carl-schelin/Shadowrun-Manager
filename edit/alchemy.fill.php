<?php
# Script: alchemy.fill.php
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
    $package = "alchemy.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_alchemy");

      $q_string  = "select r_alc_number,spell_name,r_alc_special ";
      $q_string .= "from r_alchemy ";
      $q_string .= "left join spells on spells.spell_id = r_alchemy.r_alc_number ";
      $q_string .= "where r_alc_id = " . $formVars['id'];
      $q_r_alchemy = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_alchemy = mysqli_fetch_array($q_r_alchemy);
      mysql_free_result($q_r_alchemy);

      print "document.getElementById('r_alc_item').innerHTML = '" . mysql_real_escape_string($a_r_alchemy['spell_name']) . "';\n\n";
      print "document.edit.r_alc_number.value = '" . mysql_real_escape_string($a_r_alchemy['r_alc_number'])     . "';\n";
      print "document.edit.r_alc_special.value = '" . mysql_real_escape_string($a_r_alchemy['r_alc_special'])     . "';\n";

      print "document.edit.r_alc_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_alc_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
