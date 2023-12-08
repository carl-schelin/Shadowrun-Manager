<?php
# Script: history.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "history.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#game\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Game Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('history-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"history-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<ul>";
  $output .= "  <li><strong>History</strong> - .</li>";
  $output .= "</ul>";

  $output .= "</div>";

  $output .= "</div>";

  $q_string  = "select runr_desc,runr_sop,runr_available ";
  $q_string .= "from runners ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_runners = mysqli_fetch_array($q_runners);

  if ($a_runners['runr_available']) {
    $not = '';
  } else {
    $not = 'not ';
  }

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Character Availability</th>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\">Character <strong>is " . $not . "</strong>available to Referees</td>\n";
  $output .= "</tr>\n";
  $output .= "</table>\n";

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Character Description</th>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\">" . $a_runners['runr_desc'] . "</td>\n";
  $output .= "</tr>\n";
  $output .= "</table>\n";

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Character Standard Operating Procedure</th>\n";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <td class=\"ui-widget-content\">" . $a_runners['runr_sop'] . "</td>\n";
  $output .= "</tr>\n";
  $output .= "</table>\n";

?>

document.getElementById('character_mysql').innerHTML = '<?php print mysql_real_escape_string($output); ?>';

