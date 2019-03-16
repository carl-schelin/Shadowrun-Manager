<?php
# Script: tradition.mysql.php
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
    $package = "tradition.mysql.php";
    $formVars['update']              = clean($_GET['update'],           10);
    $formVars['r_trad_character']    = clean($_GET['r_trad_character'], 10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_trad_id']          = clean($_GET['r_trad_id'],           10);
        $formVars['r_trad_number']      = clean($_GET['r_trad_number'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if ($formVars['r_trad_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_trad_character   =   " . $formVars['r_trad_character']   . "," .
            "r_trad_number      =   " . $formVars['r_trad_number'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_tradition set r_trad_id = NULL," . $q_string;
            $message = "Tradition added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update r_tradition set " . $q_string . " where r_trad_id = " . $formVars['r_trad_id'];
            $message = "Tradition updated.";
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

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Tradition Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('tradition-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"tradition-listing-help\" style=\"display: none\">\n";

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

        $q_string  = "select s_trad_id,s_trad_name ";
        $q_string .= "from s_tradition ";
        $q_s_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_s_tradition = mysql_fetch_array($q_s_tradition)) {
          $tradition_name[$a_s_tradition['s_trad_id']] = $a_s_tradition['s_trad_name'];
        }

        $q_string  = "select att_id,att_name ";
        $q_string .= "from attributes ";
        $q_attributes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_attributes = mysql_fetch_array($q_attributes)) {
          $attribute_name[$a_attributes['att_id']] = $a_attributes['att_name'];
        }

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Tradition</th>\n";
        $output .=   "<th class=\"ui-state-default\">Description</th>\n";
        $output .=   "<th class=\"ui-state-default\">Combat</th>\n";
        $output .=   "<th class=\"ui-state-default\">Detection</th>\n";
        $output .=   "<th class=\"ui-state-default\">Health</th>\n";
        $output .=   "<th class=\"ui-state-default\">Illusion</th>\n";
        $output .=   "<th class=\"ui-state-default\">Manipulation</th>\n";
        $output .=   "<th class=\"ui-state-default\">Drain</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select trad_id,trad_name,trad_description,trad_combat,trad_detection,trad_health,";
        $q_string .= "trad_illusion,trad_manipulation,trad_drainleft,trad_drainright,ver_book,trad_page ";
        $q_string .= "from tradition ";
        $q_string .= "left join versions on versions.ver_id = tradition.trad_book ";
        $q_string .= "where ver_active = 1 ";
        $q_string .= "order by trad_name ";
        $q_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_tradition) > 0) {
          while ($a_tradition = mysql_fetch_array($q_tradition)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('tradition.mysql.php?update=0&r_trad_character=" . $formVars['r_trad_character'] . "&r_trad_number=" . $a_tradition['trad_id'] . "');\">";
            $linkend   = "</a>";

            $trad_book = return_Book($a_tradition['ver_book'], $a_tradition['trad_page']);

            $class = "ui-widget-content";

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">" . $linkstart . $a_tradition['trad_name']                         . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"              . $a_tradition['trad_description']                             . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"              . $tradition_name[$a_tradition['trad_combat']]                 . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"              . $tradition_name[$a_tradition['trad_detection']]              . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"              . $tradition_name[$a_tradition['trad_health']]                 . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"              . $tradition_name[$a_tradition['trad_illusion']]               . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"              . $tradition_name[$a_tradition['trad_manipulation']]           . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"              . $attribute_name[$a_tradition['trad_drainleft']] . " + " . $attribute_name[$a_tradition['trad_drainright']] . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"       . $trad_book                                                   . "</td>\n";
            $output .= "</tr>\n";

          }
        } else {
          $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">No available Traditions.</td>\n";
        }
        $output .= "</table>\n";

        mysql_free_result($q_r_tradition);

        print "document.getElementById('traditions_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">My Tradition</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('tradition-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"tradition-listing-help\" style=\"display: none\">\n";

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

      $q_string  = "select s_trad_id,s_trad_name ";
      $q_string .= "from s_tradition ";
      $q_s_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      while ($a_s_tradition = mysql_fetch_array($q_s_tradition)) {
        $tradition_name[$a_s_tradition['s_trad_id']] = $a_s_tradition['s_trad_name'];
      }

      $q_string  = "select att_id,att_name ";
      $q_string .= "from attributes ";
      $q_attributes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      while ($a_attributes = mysql_fetch_array($q_attributes)) {
        $attribute_name[$a_attributes['att_id']] = $a_attributes['att_name'];
      }

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">Tradition</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Combat</th>\n";
      $output .=   "<th class=\"ui-state-default\">Detection</th>\n";
      $output .=   "<th class=\"ui-state-default\">Health</th>\n";
      $output .=   "<th class=\"ui-state-default\">Illusion</th>\n";
      $output .=   "<th class=\"ui-state-default\">Manipulation</th>\n";
      $output .=   "<th class=\"ui-state-default\">Drain</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_trad_id,trad_id,trad_name,trad_description,trad_combat,trad_detection,trad_health,";
      $q_string .= "trad_illusion,trad_manipulation,trad_drainleft,trad_drainright,ver_book,trad_page ";
      $q_string .= "from r_tradition ";
      $q_string .= "left join tradition on tradition.trad_id = r_tradition.r_trad_number ";
      $q_string .= "left join versions on versions.ver_id = tradition.trad_book ";
      $q_string .= "where r_trad_character = " . $formVars['r_trad_character'] . " ";
      $q_string .= "order by trad_name ";
      $q_r_tradition = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_tradition) > 0) {
        while ($a_r_tradition = mysql_fetch_array($q_r_tradition)) {

          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_tradition('tradition.del.php?id="  . $a_r_tradition['r_trad_id'] . "');\">";

          $trad_book = return_Book($a_r_tradition['ver_book'], $a_r_tradition['trad_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_trad_number']) && $formVars['r_trad_number'] == $a_r_tradition['trad_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_tradition['trad_name']                                    . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_tradition['trad_description']                             . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $tradition_name[$a_r_tradition['trad_combat']]                 . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $tradition_name[$a_r_tradition['trad_detection']]              . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $tradition_name[$a_r_tradition['trad_health']]                 . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $tradition_name[$a_r_tradition['trad_illusion']]               . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $tradition_name[$a_r_tradition['trad_manipulation']]           . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $attribute_name[$a_r_tradition['trad_drainleft']] . " + " . $attribute_name[$a_r_tradition['trad_drainright']] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $trad_book                                                     . "</td>\n";
          $output .= "</tr>\n";

        }
      } else {
        $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">No Tradition selected.</td>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_tradition);

      print "document.getElementById('my_traditions_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
