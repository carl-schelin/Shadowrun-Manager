<?php
# Script: persona.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "persona.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output = '';

  $q_string  = "select runr_resonance,runr_charisma,runr_intuition,runr_logic,runr_willpower ";
  $q_string .= "from runners ";
  $q_string .= "where runr_id = " . $formVars['id'] . " and runr_resonance > 0 ";
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_runners) > 0) {
    $a_runners = mysql_fetch_array($q_runners);

    $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
    $output .= "<tr>";
    $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Persona</th>";
    $output .= "</tr>";
    $output .= "<tr>";
    $output .= "  <th class=\"ui-state-default\">Rating</th>";
    $output .= "  <th class=\"ui-state-default\">Attack</th>";
    $output .= "  <th class=\"ui-state-default\">Sleaze</th>";
    $output .= "  <th class=\"ui-state-default\">Data Processing</th>";
    $output .= "  <th class=\"ui-state-default\">Firewall</th>";
    $output .= "</tr>";

    $rating = return_Rating($a_runners['runr_resonance']);

    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content delete\">" . $rating                      . "</td>";
    $output .= "<td class=\"ui-widget-content delete\">" . $a_runners['runr_charisma']  . "</td>";
    $output .= "<td class=\"ui-widget-content delete\">" . $a_runners['runr_intuition'] . "</td>";
    $output .= "<td class=\"ui-widget-content delete\">" . $a_runners['runr_logic']     . "</td>";
    $output .= "<td class=\"ui-widget-content delete\">" . $a_runners['runr_willpower'] . "</td>";
    $output .= "</tr>";

  } else {
    $output  = "";
  }

  print "document.getElementById('persona_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
