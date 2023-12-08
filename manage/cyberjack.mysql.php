<?php
# Script: cyberjack.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "cyberjack.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);
  $output = '';

  $output  = "<p></p>";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#cyberjack\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Cyberjack Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('cyberjack-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"cyberjack-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<p>Help</p>";

  $output .= "</div>";

  $output .= "</div>";

  $q_string  = "select r_jack_id,jack_name,jack_rating,r_jack_data,r_jack_firewall,r_jack_access,";
  $q_string .= "jack_essence,jack_avail,jack_perm,ver_book,jack_page,jack_cost ";
  $q_string .= "from r_cyberjack ";
  $q_string .= "left join cyberjack on cyberjack.jack_id = r_cyberjack.r_jack_number ";
  $q_string .= "left join versions on versions.ver_id = cyberjack.jack_book ";
  $q_string .= "where r_jack_character = " . $formVars['id'] . " ";
  $q_string .= "order by jack_rating,ver_version ";
  $q_r_cyberjack = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_cyberjack) > 0) {
    while ($a_r_cyberjack = mysqli_fetch_array($q_r_cyberjack)) {

# well, since there's only one jack to a person...
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\" colspan=\"10\">Cyberjack ID: " . $a_r_cyberjack['r_jack_access'] . "</th>\n";
      $output .= "</tr>";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\">Cyberjack</th>";
      $output .= "  <th class=\"ui-state-default\">Rating</th>";
      $output .= "  <th class=\"ui-state-default\">Data Processing</th>";
      $output .= "  <th class=\"ui-state-default\">Firewall</th>";
      $output .= "  <th class=\"ui-state-default\">Essence</th>";
      $output .= "  <th class=\"ui-state-default\">Availability</th>";
      $output .= "  <th class=\"ui-state-default\">Cost</th>";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
      $output .= "</tr>";

      $cyberjack_rating = return_Rating($a_r_cyberjack['jack_rating']);

      $cyberjack_essence = return_Essence($a_r_cyberjack['jack_essence']);

      $cyberjack_avail = return_Avail($a_r_cyberjack['jack_avail'], $a_r_cyberjack['jack_perm']);

      $cyberjack_cost = return_Cost($a_r_cyberjack['jack_cost']);

      $cyberjack_book = return_Book($a_r_cyberjack['ver_book'], $a_r_cyberjack['jack_page']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_cyberjack['jack_name']     . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cyberjack_rating               . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberjack['r_jack_data'] . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberjack['r_jack_firewall'] . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cyberjack_essence . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cyberjack_avail                . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cyberjack_cost                 . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cyberjack_book                 . "</td>";
      $output .= "</tr>";

    }
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"8\">No Cyberjacks installed</td>";
    $output .= "</tr>";
  }

  $output .= "</table>";
     
  print "document.getElementById('cyberjacks_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
