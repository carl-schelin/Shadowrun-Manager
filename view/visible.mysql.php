<?php
# Script: visible.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "visible.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $q_string  = "select runr_name,runr_aliases,meta_name,runr_archetype,runr_archetype,";
  $q_string .= "runr_age,runr_sex,runr_height,runr_weight,";
  $q_string .= "runr_currentedge,runr_totaledge,runr_currentkarma,runr_totalkarma ";
  $q_string .= "from runners ";
  $q_string .= "left join metatypes on metatypes.meta_id = runners.runr_metatype ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_runners = mysql_fetch_array($q_runners);

  $sex = "Female";
  if ($a_runners['runr_sex']) {
    $sex = "Male";
  }

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Personal Data</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">NAME/PRIMARY ALIAS: " . $a_runners['runr_name'] . "/" . $a_runners['runr_aliases'] . "</td>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\" colspan=\"2\">Metatype: " . $a_runners['meta_name'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Ethnicity: " . $a_runners['runr_ethnicity'] . "</td>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\">Age: " . $a_runners['runr_age'] . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Sex: " . $sex . "</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Height: " . $a_runners['runr_height'] . "cm</td>\n";
  $output .= "  <td class=\"ui-widget-content\">Weight: " . $a_runners['runr_weight'] . "kg</td>\n";
  $output .= "</tr>\n";
  $output .= "</table>\n";


  $q_string  = "select runr_body,runr_agility,runr_reaction,runr_strength,runr_willpower,runr_logic,";
  $q_string .= "runr_intuition,runr_charisma,runr_essence ";
  $q_string .= "from runners ";
  $q_string .= "left join metatypes on metatypes.meta_id = runners.runr_metatype ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_runners = mysql_fetch_array($q_runners);

  print "document.getElementById('visible_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
