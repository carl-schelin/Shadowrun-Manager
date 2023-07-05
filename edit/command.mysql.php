<?php
# Script: command.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "command.mysql.php";
    if (isset($_GET['r_cmd_character'])) {
      $formVars['r_cmd_character'] = clean($_GET['r_cmd_character'], 10);
    } else {
      $formVars['r_cmd_character'] = -1;
    }

    if (check_userlevel(3)) {

# list all the available command consoles
      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Rigger Command Consoles</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('command-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"command-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Weapon Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Weapon from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Weapon to toggle the form and edit the Weapon.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Firearm Management</strong> title bar to toggle the <strong>Firearm Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Console</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
      $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
      $output .=   "<th class=\"ui-state-default\">Programs</th>\n";
      $output .=   "<th class=\"ui-state-default\">Company ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select cmd_id,cmd_brand,cmd_model,cmd_rating,cmd_data,cmd_firewall,";
      $q_string .= "cmd_programs,cmd_access,cmd_avail,cmd_perm,cmd_cost,ver_book,cmd_page ";
      $q_string .= "from command ";
      $q_string .= "left join versions on versions.ver_id = command.cmd_book ";
      $q_string .= "where ver_active = 1 ";
      $q_string .= "order by cmd_rating,cmd_cost,ver_version ";
      $q_command = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_command) > 0) {
        while ($a_command = mysql_fetch_array($q_command)) {

# this adds the cmd_id to the r_cmd_character
          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('mycommand.mysql.php?update=0&r_cmd_character=" . $formVars['r_cmd_character'] . "&cmd_id=" . $a_command['cmd_id'] . "');\">";
          $linkend = "</a>";

          $cmd_rating = return_Rating($a_command['cmd_rating']);

          $cmd_avail = return_Avail($a_command['cmd_avail'], $a_command['cmd_perm']);

          $cmd_cost = return_Cost($a_command['cmd_cost']);

          $cmd_book = return_Book($a_command['ver_book'], $a_command['cmd_page']);

          $class = return_Class($a_command['cmd_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_command['cmd_brand'] . " " . $a_command['cmd_model'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $cmd_rating                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_command['cmd_data']             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_command['cmd_firewall']         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_command['cmd_programs']         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_command['cmd_access']           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $cmd_avail                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $cmd_cost                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $cmd_book                            . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('command_consoles_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
