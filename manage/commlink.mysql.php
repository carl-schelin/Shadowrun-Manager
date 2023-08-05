<?php
# Script: commlink.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "commlink.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);
  $output = '';

# people can have more than one commlink so get the commlinks and then check for system info and finally build the program listing and stats
# a basic commlink might have just the normal info. A decker will have programs and possibly black programs (separate listing)


  $output  = "<p></p>";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#commlink\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Commlink Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('commlink-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"commlink-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<p>Help</p>";

  $output .= "</div>";

  $output .= "</div>";


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Commlink</th>";
  $output .= "  <th class=\"ui-state-default\">Rating</th>";
  $output .= "  <th class=\"ui-state-default\">Commlink ID</th>";
  $output .= "  <th class=\"ui-state-default\">Availability</th>";
  $output .= "  <th class=\"ui-state-default\">Cost</th>";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
  $output .= "</tr>";

  $q_string  = "select r_link_id,link_brand,link_model,link_rating,link_avail,link_perm,ver_book,link_page,link_cost,r_link_access ";
  $q_string .= "from r_commlink ";
  $q_string .= "left join commlink on commlink.link_id = r_commlink.r_link_number ";
  $q_string .= "left join versions on versions.ver_id = commlink.link_book ";
  $q_string .= "where r_link_character = " . $formVars['id'] . " ";
  $q_string .= "order by link_rating,ver_version ";
  $q_r_commlink = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_commlink) > 0) {
    while ($a_r_commlink = mysql_fetch_array($q_r_commlink)) {

      $commlink_rating = return_Rating($a_r_commlink['link_rating']);

      $commlink_avail = return_Avail($a_r_commlink['link_avail'], $a_r_commlink['link_perm']);

      $commlink_cost = return_Cost($a_r_commlink['link_cost']);

      $commlink_book = return_Book($a_r_commlink['ver_book'], $a_r_commlink['link_page']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_commlink['link_brand'] . " " . $a_r_commlink['link_model'] . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $commlink_rating                                                . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_commlink['r_link_access']                                  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $commlink_avail                                                 . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $commlink_cost                                                  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $commlink_book                                                  . "</td>";
      $output .= "</tr>";

    }
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"6\">No Commlinks selected</td>";
    $output .= "</tr>";
  }

  $output .= "</table>";
     
  print "document.getElementById('commlink_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
