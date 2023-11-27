<?php
# Script: mooks.mysql.php
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
    $package = "mooks.mysql.php";
    $formVars['group'] = clean($_GET['group'], 10);

    $where = '';
    if ($formVars['group'] > 0) {
      $where = " and mem_group = " . $formVars['group'] . " ";
    }

    if (check_userlevel(3)) {

      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Mooks Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"mygear-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Spell Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Delete (x)</strong> - Clicking the <strong>x</strong> will delete this association from this server.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on an association to edit it.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Association Management</strong> title bar to toggle the <strong>Association Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Delete</th>\n";
      $output .= "  <th class=\"ui-state-default\">View</th>\n";
      $output .= "  <th class=\"ui-state-default\">Manage</th>\n";
      $output .= "  <th class=\"ui-state-default\">Edit</th>\n";
      $output .= "  <th class=\"ui-state-default\">Owner</th>\n";
      $output .= "  <th class=\"ui-state-default\">Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Archetype</th>\n";
      $output .= "  <th class=\"ui-state-default\">Metatype</th>\n";
      $output .= "  <th class=\"ui-state-default\">Release</th>\n";
      $output .= "  <th class=\"ui-state-default\">BOD</th>\n";
      $output .= "  <th class=\"ui-state-default\">AGI</th>\n";
      $output .= "  <th class=\"ui-state-default\">REA</th>\n";
      $output .= "  <th class=\"ui-state-default\">STR</th>\n";
      $output .= "  <th class=\"ui-state-default\">WIL</th>\n";
      $output .= "  <th class=\"ui-state-default\">LOG</th>\n";
      $output .= "  <th class=\"ui-state-default\">INT</th>\n";
      $output .= "  <th class=\"ui-state-default\">CHA</th>\n";
      $output .= "  <th class=\"ui-state-default\">EDG</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select runr_id,usr_first,usr_last,runr_name,runr_archetype,meta_name,runr_agility,runr_body, ";
      $q_string .= "runr_reaction,runr_strength,runr_charisma,runr_intuition,runr_logic,runr_willpower, ";
      $q_string .= "runr_totaledge,runr_version,ver_book,runr_available ";
      $q_string .= "from runners ";
      $q_string .= "left join members   on members.mem_runner = runners.runr_id ";
      $q_string .= "left join users     on users.usr_id       = runners.runr_owner ";
      $q_string .= "left join metatypes on metatypes.meta_id  = runners.runr_metatype ";
      $q_string .= "left join versions  on versions.ver_id    = runners.runr_version ";
      $q_string .= "where (ver_active = 1 or runr_version = 0) " . $where;
      $q_string .= "order by runr_owner,runr_archetype ";
      $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_runners) > 0) {
        while ($a_runners = mysql_fetch_array($q_runners)) {

          $linkdel     = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_character('mooks.del.php?id="  . $a_runners['runr_id'] . "');\">";
          $viewstart   = "<a href=\"" . $Viewroot   . "/mooks.php?id=" . $a_runners['runr_id'] . "\" target=\"_blank\">";
          $managestart = "<a href=\"" . $Manageroot . "/mooks.php?id=" . $a_runners['runr_id'] . "\" target=\"_blank\">";
          $editstart   = "<a href=\"" . $Editroot   . "/mooks.php?id=" . $a_runners['runr_id'] . "\" target=\"_blank\">";
          $linkend     = "</a>";


          $display = 'No';

# are we looking at just one group and is the character a member of that group?
          if ($formVars['group'] > 0) {
            $q_string  = "select mem_id ";
            $q_string .= "from members ";
            $q_string .= "where mem_group = " . $formVars['group'] . " and mem_runner = " . $a_runners['runr_id'] . " ";
            $q_members = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_members) > 0) {
              $display = 'Yes';
            }
          } else {
# I'm a Johnson, show me everyone in the group
            if (check_userlevel(1)) {
              $display = 'Yes';
            }
# it's my character so show me no matter what
            if (check_owner($a_runners['runr_id'])) {
              $display = 'Yes';
            }
# are we a gm and the character is available for running?
            if (check_userlevel(2) && check_available($a_runners['runr_id'])) {
              $display = 'Yes';
            }
          }

          $available = "";
          if ($a_runners['runr_available']) {
            $available = "*";
          }

          $release = $a_runners['ver_book'];
          if ($a_runners['runr_version'] < 1) {
            $release = "Any Version";
          }

          if ($display == 'Yes') {
            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content\" width=\"60\">" . $linkdel                                                                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">"       . $viewstart . "View"                                    . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">"       . $managestart . "Manage"                                . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">"       . $editstart . "Edit"                                    . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"                           . $a_runners['usr_first'] . " " . $a_runners['usr_last']            . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"                           . $a_runners['runr_name'] . $available                              . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"                           . $a_runners['runr_archetype']                                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"                           . $a_runners['meta_name']                                           . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"                           . $release                                                          . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_body']                                           . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_agility']                                        . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_reaction']                                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_strength']                                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_willpower']                                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_logic']                                          . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_intuition']                                      . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_charisma']                                       . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"                    . $a_runners['runr_totaledge']                                      . "</td>\n";
            $output .= "</tr>\n";
          }
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"18\">No Runners to display.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('group_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
