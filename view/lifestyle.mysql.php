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

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"7\">Lifestyle</th>";
  $output .= "</tr>\n";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Lifestyle</th>";
  $output .= "  <th class=\"ui-state-default\">Description</th>";
  $output .= "  <th class=\"ui-state-default\">Months Paid Up</th>";
  $output .= "  <th class=\"ui-state-default\">Comforts & Necessities</th>";
  $output .= "  <th class=\"ui-state-default\">Security</th>";
  $output .= "  <th class=\"ui-state-default\">Neighborhood</th>";
  $output .= "  <th class=\"ui-state-default\">Entertainment</th>";
  $output .= "</tr>";

  $nuyen = '&yen;';
  $q_string  = "select r_life_id,r_life_desc,r_life_months,life_style,r_life_comforts,r_life_security,r_life_neighborhood,r_life_entertainment ";
  $q_string .= "from r_lifestyle ";
  $q_string .= "left join lifestyle on lifestyle.life_id = r_lifestyle.r_life_number ";
  $q_string .= "where r_life_character = " . $formVars['id'] . " ";
  $q_string .= "order by life_style ";
  $q_r_lifestyle = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_lifestyle) > 0) {
    while ($a_r_lifestyle = mysqli_fetch_array($q_r_lifestyle)) {

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_lifestyle['life_style']            . "</td>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_lifestyle['r_life_desc']           . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_lifestyle['r_life_months']         . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_lifestyle['r_life_comforts']       . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_lifestyle['r_life_security']       . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_lifestyle['r_life_neighborhood']   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_lifestyle['r_life_entertainment']  . "</td>";
      $output .= "</tr>";

    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }


  print "document.getElementById('lifestyle_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
