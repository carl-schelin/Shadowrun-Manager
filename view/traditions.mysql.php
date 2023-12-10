<?php
# Script: traditions.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "traditions.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Traditions</th>";
  $output .= "</tr>\n";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Tradition</th>";
  $output .= "  <th class=\"ui-state-default\">Description</th>";
  $output .= "</tr>";

  $nuyen = '&yen;';
  $q_string  = "select r_trad_id,trad_name,trad_description ";
  $q_string .= "from r_tradition ";
  $q_string .= "left join tradition on tradition.trad_id = r_tradition.r_trad_number ";
  $q_string .= "where r_trad_character = " . $formVars['id'] . " ";
  $q_r_tradition = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_tradition) > 0) {
    while ($a_r_tradition = mysqli_fetch_array($q_r_tradition)) {

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_tradition['trad_name']     . "</td>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_tradition['trad_description']    . "</td>";
      $output .= "</tr>";

    }
    $output .= "</table>";
  } else {
     $output = "";
  }

  print "document.getElementById('traditions_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";

?>
