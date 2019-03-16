<?php
# Script: ammo.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "ammo.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"7\">Ammunition</th>";
  $output .= "</tr>\n";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Class</th>";
  $output .= "  <th class=\"ui-state-default\">Name</th>";
  $output .= "  <th class=\"ui-state-default\">Rounds</th>";
  $output .= "  <th class=\"ui-state-default\">Rating</th>";
  $output .= "  <th class=\"ui-state-default\">Damage Modifier</th>";
  $output .= "  <th class=\"ui-state-default\">AP Modifier</th>";
  $output .= "  <th class=\"ui-state-default\">Blast Radius</th>";
  $output .= "</tr>";

  $nuyen = '&yen;';
  $q_string  = "select r_ammo_rounds,ammo_rounds,class_name,ammo_name,ammo_rating,ammo_mod,ammo_ap,ammo_blast ";
  $q_string .= "from r_ammo ";
  $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
  $q_string .= "left join class on class.class_id = ammo.ammo_class ";
  $q_string .= "where r_ammo_character = " . $formVars['id'] . " and r_ammo_parentid = 0 ";
  $q_string .= "order by class_name,ammo_name ";
  $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_ammo) > 0) {
    while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

      $ammo_rating = return_Rating($a_r_ammo['ammo_rating']);
      $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_ammo['class_name']                                 . "</td>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_ammo['ammo_name']                                  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $ammo_rating                                            . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_ammo['ammo_mod']                                   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $ammo_ap                                                . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_ammo['ammo_blast']                                 . "</td>";
      $output .= "</tr>";

    }
    $output .= "</table>";
  } else {
    $output = "";
  }

  print "document.getElementById('ammo_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
