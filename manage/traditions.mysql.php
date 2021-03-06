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

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);
  $output = '';

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#tradition\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Tradition Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('tradition-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"tradition-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<p>Help</p>";

  $output .= "</div>";

  $output .= "</div>";


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Tradition</th>";
  $output .= "  <th class=\"ui-state-default\">Description</th>";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
  $output .= "</tr>";

  $nuyen = '&yen;';
  $q_string  = "select r_trad_id,trad_name,trad_description,ver_book,trad_page ";
  $q_string .= "from r_tradition ";
  $q_string .= "left join tradition on tradition.trad_id = r_tradition.r_trad_number ";
  $q_string .= "left join versions on versions.ver_id = tradition.trad_book ";
  $q_string .= "where r_trad_character = " . $formVars['id'] . " ";
  $q_r_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_tradition) > 0) {
    while ($a_r_tradition = mysql_fetch_array($q_r_tradition)) {

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_tradition['trad_name']     . "</td>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_tradition['trad_description']    . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_tradition['ver_book'] . ": " . $a_r_tradition['trad_page'] . "</td>";
      $output .= "</tr>";

    }
  } else {
     $output .= "<tr>";
     $output .= "<td class=\"ui-widget-content\" colspan=\"3\">No Traditions found</td>";
     $output .= "</tr>";
  }

  $output .= "</table>";
     
  print "document.getElementById('traditions_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
