<?php
# Script: alchemy.mysql.php
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
    $package = "alchemy.mysql.php";
    $formVars['update']              = clean($_GET['update'],               10);
    $formVars['r_alc_character']     = clean($_GET['r_alc_character'],      10);
    $formVars['spell_group']         = clean($_GET['spell_group'],          10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['spell_group'] == '') {
      $formVars['spell_group'] = 0;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_alc_id']          = clean($_GET['r_alc_id'],           10);
        $formVars['r_alc_number']      = clean($_GET['r_alc_number'],       10);
        $formVars['r_alc_special']     = clean($_GET['r_alc_special'],      60);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if ($formVars['r_alc_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_alc_character   =   " . $formVars['r_alc_character']   . "," .
            "r_alc_number      =   " . $formVars['r_alc_number']      . "," .
            "r_alc_special     = \"" . $formVars['r_alc_special']     . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_alchemy set r_alc_id = NULL," . $q_string;
            $message = "Alchemical Preparation added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update r_alchemy set " . $q_string . " where r_alc_id = " . $formVars['r_alc_id'];
            $message = "Alchemical Preparation updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_alc_number']);

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
        $output .= "<input type=\"button\" name=\"r_alc_refresh\" value=\"Refresh Alchemy Listing\" onClick=\"javascript:attach_alchemy('alchemy.mysql.php', -3);\">\n";
        $output .= "<input type=\"button\" name=\"r_alc_update\"  value=\"Update Alchemy\"          onClick=\"javascript:attach_alchemy('alchemy.mysql.php', 1);hideDiv('alchemy-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_alc_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_alc_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Alchemical Preparation Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Alchemical Preparation: <span id=\"r_alc_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Special: <input type=\"text\" name=\"r_alc_special\" size=\"30\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('alchemy_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Alchemical Prepration Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('alchemy-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"alchemy-listing-help\" style=\"display: none\">\n";

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
        $output .=   "<th class=\"ui-state-default\">Class</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Type</th>\n";
        $output .=   "<th class=\"ui-state-default\">Test</th>\n";
        $output .=   "<th class=\"ui-state-default\">Range</th>\n";
        $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
        $output .=   "<th class=\"ui-state-default\">Duration</th>\n";
        $output .=   "<th class=\"ui-state-default\">Drain</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select spell_id,spell_name,spell_group,class_name,spell_class,spell_type,spell_test,spell_range,";
        $q_string .= "spell_damage,spell_duration,spell_drain,ver_book,spell_page ";
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

            $filterstart = "<a href=\"#\" onclick=\"javascript:show_file('alchemy.mysql.php?update=-3&r_alc_character=" . $formVars['r_alc_character'] . "&spell_group=" . $a_spells['spell_group'] . "');\">";
            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('alchemy.mysql.php?update=0&r_alc_character=" . $formVars['r_alc_character'] . "&r_alc_number=" . $a_spells['spell_id'] . "');\">";
            $linkend   = "</a>";

            $spell_drain = "F" . $a_spells['spell_drain'];
            if ($a_spells['spell_drain'] == 0) {
              $spell_drain = "F";
            }

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

        print "document.getElementById('preps_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

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

      $q_string  = "select r_alc_id,r_alc_special,spell_id,spell_name,spell_group,class_name,spell_class,spell_type,spell_test,spell_range,";
      $q_string .= "spell_damage,spell_duration,spell_drain,ver_book,spell_page ";
      $q_string .= "from r_alchemy ";
      $q_string .= "left join spells on spells.spell_id = r_alchemy.r_alc_number ";
      $q_string .= "left join class on class.class_id = spells.spell_group ";
      $q_string .= "left join versions on versions.ver_id = spells.spell_book ";
      $q_string .= "where r_alc_character = " . $formVars['r_alc_character'] . " ";
      $q_string .= "order by spell_group,spell_name ";
      $q_r_alchemy = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_alchemy) > 0) {
        while ($a_r_alchemy = mysql_fetch_array($q_r_alchemy)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('alchemy.fill.php?id=" . $a_r_alchemy['r_alc_id'] . "');showDiv('alchemy-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_alchemy('alchemy.del.php?id="  . $a_r_alchemy['r_alc_id'] . "');\">";
          $linkend   = "</a>";

          $spell_name = $a_r_alchemy['spell_name'];
          if (strlen($a_r_alchemy['r_alc_special']) > 0) {
            $spell_name = $a_r_alchemy['spell_name'] . " (" . $a_r_alchemy['r_alc_special'] . ")";
          }

          $spell_drain = "F" . $a_r_alchemy['spell_drain'];
          if ($a_r_alchemy['spell_drain'] == 0) {
            $spell_drain = "F";
          }

          $spell_book = return_Book($a_r_alchemy['ver_book'], $a_r_alchemy['spell_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_alc_number']) && $formVars['r_alc_number'] == $a_r_alchemy['spell_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                              . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_alchemy['class_name']             . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $spell_name                . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_alchemy['spell_class']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_alchemy['spell_type']             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_alchemy['spell_test']             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_alchemy['spell_range']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_alchemy['spell_damage']           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_alchemy['spell_duration']         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $spell_drain                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $spell_book                           . "</td>\n";
          $output .= "</tr>\n";

        }
      } else {
        $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No Preparations added.</td>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_alchemy);

      print "document.getElementById('my_preps_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.r_alc_update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
