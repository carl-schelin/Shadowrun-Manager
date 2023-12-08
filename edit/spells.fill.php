<?php
# Script: spells.fill.php
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
    $package = "spells.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_spells");

      $q_string  = "select r_spell_number,spell_name,r_spell_special ";
      $q_string .= "from r_spells ";
      $q_string .= "left join spells on spells.spell_id = r_spells.r_spell_number ";
      $q_string .= "where r_spell_id = " . $formVars['id'];
      $q_r_spells = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_spells = mysqli_fetch_array($q_r_spells);
      mysql_free_result($q_r_spells);

      print "document.getElementById('r_spell_item').innerHTML = '" . mysql_real_escape_string($a_r_spells['spell_name']) . "';\n\n";
      print "document.edit.r_spell_number.value = '" . mysql_real_escape_string($a_r_spells['r_spell_number'])     . "';\n";
      print "document.edit.r_spell_special.value = '" . mysql_real_escape_string($a_r_spells['r_spell_special'])     . "';\n";

      print "document.edit.r_spell_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_spell_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
