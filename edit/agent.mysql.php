<?php
# Script: agent.mysql.php
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
    $package = "agent.mysql.php";
    $formVars['update']            = clean($_GET['update'],             10);
    $formVars['r_agt_character']   = clean($_GET['r_agt_character'],    10);
    $formVars['r_agt_id']          = clean($_GET['r_agt_id'],           10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_agt_character'] == '') {
      $formVars['r_agt_character'] = -1;
    }

    if (check_userlevel(3)) {

      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

# fill in the agent tab on the cyberdeck management table
      if ($formVars['update'] == -3) {
        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Agents</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('agent-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"agent-listing-help\" style=\"display: none\">\n";

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
        $output .=   "<th class=\"ui-state-default\">Agent</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select agt_id,agt_name,agt_rating,agt_avail,agt_perm,agt_cost,ver_book,agt_page ";
        $q_string .= "from agents ";
        $q_string .= "left join versions on versions.ver_id = agents.agt_book ";
        $q_string .= "order by agt_name,agt_rating,ver_version ";
        $q_agents = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_agents) > 0) {
          while ($a_agents = mysql_fetch_array($q_agents)) {

# update = 4 == add agents to a deck
            $linkstart = "<a href=\"#\" onclick=\"javascript:select_agent('mycyberdeck.mysql.php?update=4&agt_id=" . $a_agents['agt_id'] . "');\">";
            $linkend = "</a>";

            $agt_rating = return_Rating($a_agents['agt_rating']);

            $agt_avail = return_Avail($a_agents['agt_avail'], $a_agents['agt_perm']);

            $agt_cost = return_Cost($a_agents['agt_cost']);

            $agt_book = return_Book($a_agents['ver_book'], $a_agents['agt_page']);

            $class = return_Class($a_agents['agt_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_agents['agt_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $agt_rating                      . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $agt_avail                       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $agt_cost                        . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $agt_book                        . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">No agents found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('agent_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
