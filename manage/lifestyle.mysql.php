<?php
# Script: lifestyle.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "lifestyle.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);
  $output = '';

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#lifestyle\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Lifestyle Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('lifestyle-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"lifestyle-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<p>Help</p>";

  $output .= "</div>";

  $output .= "</div>";

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Lifestyle</th>";
  $output .= "  <th class=\"ui-state-default\">Description</th>";
  $output .= "  <th class=\"ui-state-default\">Months Paid</th>";
  $output .= "  <th class=\"ui-state-default\">Comforts & Necessities</th>";
  $output .= "  <th class=\"ui-state-default\">Security</th>";
  $output .= "  <th class=\"ui-state-default\">Neighborhood</th>";
  $output .= "  <th class=\"ui-state-default\">Entertainment</th>";
  $output .= "  <th class=\"ui-state-default\">Cost</th>";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
  $output .= "</tr>";

  $totalcost = 0;
  $q_string  = "select r_life_id,r_life_desc,r_life_months,life_style,life_mincost,r_life_comforts,";
  $q_string .= "r_life_security,r_life_neighborhood,r_life_entertainment,ver_book,life_page ";
  $q_string .= "from r_lifestyle ";
  $q_string .= "left join lifestyle on lifestyle.life_id = r_lifestyle.r_life_number ";
  $q_string .= "left join versions on versions.ver_id = lifestyle.life_book ";
  $q_string .= "where r_life_character = " . $formVars['id'] . " ";
  $q_string .= "order by life_style,ver_version ";
  $q_r_lifestyle = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_lifestyle) > 0) {
    while ($a_r_lifestyle = mysqli_fetch_array($q_r_lifestyle)) {

      $totalcost += ($a_r_lifestyle['life_mincost'] * $a_r_lifestyle['r_life_months']);
      $cost = return_Cost($a_r_lifestyle['life_mincost'] * $a_r_lifestyle['r_life_months']);

      $book = return_Book($a_r_lifestyle['ver_book'], $a_r_lifestyle['life_page']);

      $output .= "<tr>\n";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_lifestyle['life_style']           . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_lifestyle['r_life_desc']          . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_lifestyle['r_life_months']        . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_lifestyle['r_life_comforts']      . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_lifestyle['r_life_security']      . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_lifestyle['r_life_neighborhood']  . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_lifestyle['r_life_entertainment'] . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $cost                                  . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $book                                  . "</td>\n";
      $output .= "</tr>\n";

    }
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">Total Cost: " . return_Cost($totalcost) . "</td>\n";
    $output .= "</tr>\n";

  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"9\">No Lifestyles indentified.</td>";
    $output .= "</tr>";
  }

  $output .= "</table>";
     
  print "document.getElementById('lifestyle_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
