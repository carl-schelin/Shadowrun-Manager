<?php
# Script: gear.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "gear.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);
  $output = '';

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#gear\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Gear Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('gear-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"gear-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<p>Help</p>";

  $output .= "</div>";

  $output .= "</div>";


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Class</th>";
  $output .= "  <th class=\"ui-state-default\">Name</th>";
  $output .= "  <th class=\"ui-state-default\">Rating</th>";
  $output .= "  <th class=\"ui-state-default\">Capacity</th>";
  $output .= "  <th class=\"ui-state-default\">Availability</th>";
  $output .= "  <th class=\"ui-state-default\">Cost</th>";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
  $output .= "</tr>";

  $nuyen = '&yen;';
  $q_string  = "select r_gear_id,gear_class,gear_name,gear_rating,gear_capacity,gear_avail,gear_perm,ver_book,gear_page,gear_cost ";
  $q_string .= "from r_gear ";
  $q_string .= "left join gear on gear.gear_id = r_gear.r_gear_number ";
  $q_string .= "left join versions on versions.ver_id = gear.gear_book ";
  $q_string .= "where r_gear_character = " . $formVars['id'] . " ";
  $q_string .= "order by gear_rating,ver_version ";
  $q_r_gear = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_gear) > 0) {
    while ($a_r_gear = mysql_fetch_array($q_r_gear)) {

      $rating = return_Rating($a_r_gear['gear_rating']);

      $capacity = return_Capacity($a_r_gear['gear_capacity']);

      $avail = return_Avail($a_r_gear['gear_avail'], $a_r_gear['gear_perm']);

      $cost = number_format($a_r_gear['gear_cost'], 0, '.', ',') . $nuyen;

      $book = $a_r_gear['ver_book'] . ": " . $a_r_gear['gear_page'];

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_gear['gear_class'] . "</td>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_gear['gear_name']  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $rating                 . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $capacity               . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $avail                  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cost                   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $book                   . "</td>";
      $output .= "</tr>";

    }
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"7\">No Gear selected.</td>";
    $output .= "</tr>";
  }

  $output .= "</table>";
     
  print "document.getElementById('gear_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
