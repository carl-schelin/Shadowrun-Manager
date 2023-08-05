<?php
# Script: spells.mysql.php
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
    $package = "spells.mysql.php";
    $formVars['r_spell_character']   = clean($_GET['r_spell_character'],    10);

    if (isset($_GET['update'])) {
      $formVars['update']              = clean($_GET['update'],               10);
    } else {
      $formVars['update'] = -1;
    }
    if (isset($_GET['spell_group'])) {
      $formVars['spell_group']         = clean($_GET['spell_group'],          10);
    } else {
      $formVars['spell_group'] = 0;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_spell_id']          = clean($_GET['id'],                   10);
        $formVars['r_spell_number']      = clean($_GET['r_spell_number'],       10);
        $formVars['r_spell_special']     = clean($_GET['r_spell_special'],      60);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if ($formVars['r_spell_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_spell_character   =   " . $formVars['r_spell_character']   . "," .
            "r_spell_number      =   " . $formVars['r_spell_number']      . "," .
            "r_spell_special     = \"" . $formVars['r_spell_special']     . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_spells set r_spell_id = NULL," . $q_string;
            $message = "Spell added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update r_spells set " . $q_string . " where r_spell_id = " . $formVars['r_spell_id'];
            $message = "Spell updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_spell_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_spell_refresh\" value=\"Refresh Spell Listing\" onClick=\"javascript:attach_spells('spells.mysql.php', -3);\">\n";
        $output .= "<input type=\"button\" name=\"r_spell_update\"  value=\"Update Spell\"          onClick=\"javascript:attach_spells('spells.mysql.php', 1);hideDiv('spells-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_spell_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_spell_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Spell Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Spell: <span id=\"r_spell_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Special: <input type=\"text\" name=\"r_spell_special\" size=\"30\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('spells_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Spell Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('spells-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"spells-listing-help\" style=\"display: none\">\n";

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
        $output .=   "<th class=\"ui-state-default\">Group</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Class</th>\n";
        $output .=   "<th class=\"ui-state-default\">Type</th>\n";
        $output .=   "<th class=\"ui-state-default\">Test</th>\n";
        $output .=   "<th class=\"ui-state-default\">Range</th>\n";
        $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
        $output .=   "<th class=\"ui-state-default\">Duration</th>\n";
        $output .=   "<th class=\"ui-state-default\">Drain</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select spell_id,spell_name,spell_group,class_name,spell_class,spell_type,spell_test,spell_range,";
        $q_string .= "spell_damage,spell_duration,spell_force,spell_drain,ver_book,spell_page ";
        $q_string .= "from spells ";
        $q_string .= "left join class on class.class_id = spells.spell_group ";
        $q_string .= "left join versions on versions.ver_id = spells.spell_book ";
        $q_string .= "where ver_active = 1 ";
        if ($formVars['spell_group'] > 0) {
          $q_string .= "and spell_group = " . $formVars['spell_group'] . " ";
        }
        $q_string .= "order by spell_name,ver_version ";
        $q_spells = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_spells) > 0) {
          while ($a_spells = mysql_fetch_array($q_spells)) {

            $filterstart = "<a href=\"#\" onclick=\"javascript:show_file('spells.mysql.php?update=-3&r_spell_character=" . $formVars['r_spell_character'] . "&spell_group=" . $a_spells['spell_group'] . "');\">";
            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('spells.mysql.php?update=0&r_spell_character=" . $formVars['r_spell_character'] . "&r_spell_number=" . $a_spells['spell_id'] . "');return false;\">";
            $linkend   = "</a>";

            $spell_drain = return_Drain($a_spells['spell_drain'], $a_spells['spell_force']);

            $spell_book = return_Book($a_spells['ver_book'], $a_spells['spell_page']);

            $class = "ui-widget-content";

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"      . $filterstart . $a_spells['class_name']  . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_spells['spell_name']  . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"                     . $a_spells['spell_class']            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_spells['spell_type']             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_spells['spell_test']             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_spells['spell_range']            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_spells['spell_damage']           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_spells['spell_duration']         . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $spell_drain                        . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $spell_book                         . "</td>\n";
            $output .= "</tr>\n";

          }
        } else {
          $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No Spells added.</td>\n";
        }
        $output .= "</table>\n";

        mysql_free_result($q_r_spells);

        print "document.getElementById('spell_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Spell Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('spells-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"spells-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">Group</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Class</th>\n";
      $output .=   "<th class=\"ui-state-default\">Type</th>\n";
      $output .=   "<th class=\"ui-state-default\">Test</th>\n";
      $output .=   "<th class=\"ui-state-default\">Range</th>\n";
      $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
      $output .=   "<th class=\"ui-state-default\">Duration</th>\n";
      $output .=   "<th class=\"ui-state-default\">Drain</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_spell_id,r_spell_special,spell_id,spell_name,spell_group,class_name,spell_class,spell_type,spell_test,spell_range,";
      $q_string .= "spell_damage,spell_duration,spell_force,spell_drain,ver_book,spell_page ";
      $q_string .= "from r_spells ";
      $q_string .= "left join spells on spells.spell_id = r_spells.r_spell_number ";
      $q_string .= "left join class on class.class_id = spells.spell_group ";
      $q_string .= "left join versions on versions.ver_id = spells.spell_book ";
      $q_string .= "where r_spell_character = " . $formVars['r_spell_character'] . " ";
      $q_string .= "order by spell_group,spell_name ";
      $q_r_spells = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_spells) > 0) {
        while ($a_r_spells = mysql_fetch_array($q_r_spells)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('spells.fill.php?id=" . $a_r_spells['r_spell_id'] . "');showDiv('spells-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_spells('spells.del.php?id="  . $a_r_spells['r_spell_id'] . "');\">";
          $linkend   = "</a>";

          $spell_name = $a_r_spells['spell_name'];
          if (strlen($a_r_spells['r_spell_special']) > 0) {
            $spell_name = $a_r_spells['spell_name'] . " (" . $a_r_spells['r_spell_special'] . ")";
          }

          $spell_drain = return_Drain($a_r_spells['spell_drain'], $a_r_spells['spell_force']);

          $spell_book = return_Book($a_r_spells['ver_book'], $a_r_spells['spell_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_spell_number']) && $formVars['r_spell_number'] == $a_r_spells['spell_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                              . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_spells['class_name']             . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $spell_name                . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_spells['spell_class']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_spells['spell_type']             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_spells['spell_test']             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_spells['spell_range']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_spells['spell_damage']           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_spells['spell_duration']         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $spell_drain                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $spell_book                           . "</td>\n";
          $output .= "</tr>\n";

        }
      } else {
        $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No Spells added.</td>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_spells);

      print "document.getElementById('my_spells_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.r_spell_update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
