<?php
# Script: condition.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "condition.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $q_string  = "select runr_body,runr_willpower,runr_physicalcon,runr_stuncon ";
  $q_string .= "from runners ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_runners = mysqli_fetch_array($q_runners);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Condition Monitor</th>";
  $output .= "</tr>\n";

  $output .= "<tr>\n";
  $physical_damage = ceil(($a_runners['runr_body'] / 2) + 8);
  $output .= "  <td class=\"ui-widget-content\">" . "Physical Damage: (" . $physical_damage . "): ";
  for ($i = 1; $i <= 18; $i++) {
    if ($physical_damage >= $i) {
      $checked = '';
      if ($i <= $a_runners['runr_physicalcon']) {
        $checked = 'checked=\"true\"';
      }

      $output .= "<input type=\"checkbox\" " . $checked . " id=\"physcon" . $i . "\" onclick=\"edit_RunnerCondition(" . $i . ", 'physical');\">\n";
    }
  }
  $output .= "</td>\n";
  $output .= "</tr>\n";

  $output .= "<tr>\n";
  $stun_damage = ceil(($a_runners['runr_willpower'] / 2) + 8);
  $output .= "  <td class=\"ui-widget-content\">" . "Stun Damage: (" . $stun_damage . "): ";
  for ($i = 1; $i <= 12; $i++) {
    if ($stun_damage >= $i) {
      $checked = '';
      if ($i <= $a_runners['runr_stuncon']) {
        $checked = 'checked=\"true\"';
      }

      $output .= "<input type=\"checkbox\" " . $checked . " id=\"stuncon" . $i . "\" onclick=\"edit_RunnerCondition(" . $i . ", 'stun');\">\n";
    }
  }
  $output .= "</td>\n";
  $output .= "</tr>\n";

  $output .= "</table>\n";

  print "document.getElementById('condition_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";

?>
