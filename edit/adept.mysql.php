<?php
# Script: adept.mysql.php
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
    $package = "adept.mysql.php";
    $formVars['update']              = clean($_GET['update'],             10);
    $formVars['r_adp_character']     = clean($_GET['r_adp_character'],    10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_adp_id']          = clean($_GET['id'],                 10);
        $formVars['r_adp_number']      = clean($_GET['r_adp_number'],       10);
        $formVars['r_adp_level']       = clean($_GET['r_adp_level'],        10);
        $formVars['r_adp_specialize']  = clean($_GET['r_adp_specialize'],   60);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_adp_level'] == '') {
          $formVars['r_adp_level'] = 0;
        }

        if ($formVars['r_adp_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_adp_character   =   " . $formVars['r_adp_character']   . "," .
            "r_adp_number      =   " . $formVars['r_adp_number']      . "," .
            "r_adp_level       =   " . $formVars['r_adp_level']       . "," .
            "r_adp_specialize  = \"" . $formVars['r_adp_specialize']  . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_adept set r_adp_id = NULL," . $q_string;
            $message = "Adept Power added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update r_adept set " . $q_string . " where r_adp_id = " . $formVars['r_adp_id'];
            $message = "Adept Power updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_adp_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -2) {
        $formVars['copyfrom'] = clean($_GET['r_adp_copyfrom'], 10);

        if ($formVars['copyfrom'] > 0) {
          $q_string  = "select r_adp_number,r_adp_specialize ";
          $q_string .= "from r_adept ";
          $q_string .= "where r_adp_character = " . $formVars['copyfrom'];
          $q_r_adept = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          while ($a_r_adept = mysql_fetch_array($q_r_adept)) {

            $q_string =
              "r_adp_character     =   " . $formVars['r_adp_character']      . "," .
              "r_adp_number        =   " . $a_r_adept['r_adp_number']       . "," .
              "r_adp_specialize    =   " . $a_r_adept['r_adp_specialize']   . "\"";
  
            $query = "insert into r_adept set r_adp_id = NULL, " . $q_string;
            mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
          }
        }
      }


      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_adp_refresh\" value=\"Refresh Adept Power Listing\" onClick=\"javascript:attach_adept('adept.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_adp_update\"  value=\"Update Adept Power\"          onClick=\"javascript:attach_adept('adept.mysql.php', 1);hideDiv('adept-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_adp_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_adp_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"copyitem\"  value=\"Copy Adept Table From:\" onClick=\"javascript:attach_adept('adept.mysql.php', -2);\">\n";
        $output .= "<select name=\"r_adp_copyfrom\">\n";
        $output .= "<option value=\"0\">None</option>\n";

        $q_string  = "select runr_id,runr_aliases ";
        $q_string .= "from runners ";
        $q_string .= "order by runr_aliases ";
        $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_runners = mysql_fetch_array($q_runners)) {
          $q_string  = "select r_adp_id ";
          $q_string .= "from r_adept ";
          $q_string .= "where r_adp_character = " . $a_runners['runr_id'] . " ";
          $q_r_adept = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $r_adp_total = mysql_num_rows($q_r_adept);

          if ($r_adp_total > 0) {
            $output .= "<option value=\"" . $a_runners['runr_id'] . "\">" . $a_runners['runr_aliases'] . " (" . $r_adp_total . ")</option>\n";
          }
        }

        $output .= "</select></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Adept Power Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Adept Power: <span id=\"r_adp_item\"></span></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Level: <input type=\"text\" name=\"r_adp_level\" size=\"10\">\n";
        $output .= "  <td class=\"ui-widget-content\">Specialize: <input type=\"text\" name=\"r_adp_specialize\" size=\"30\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('adept_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Adept Power Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('adept-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"adept-listing-help\" style=\"display: none\">\n";

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
        $output .=   "<th class=\"ui-state-default\">Adept Power</th>\n";
        $output .=   "<th class=\"ui-state-default\">Description</th>\n";
        $output .=   "<th class=\"ui-state-default\">Power Points</th>\n";
        $output .=   "<th class=\"ui-state-default\">Level</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select adp_id,adp_name,adp_desc,adp_power,adp_level,ver_book,adp_page ";
        $q_string .= "from adept ";
        $q_string .= "left join versions on versions.ver_id = adept.adp_book ";
        $q_string .= "where ver_active = 1 ";
        $q_string .= "order by adp_name ";
        $q_adept = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_adept) > 0) {
          while ($a_adept = mysql_fetch_array($q_adept)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('adept.mysql.php?update=0&r_adp_character=" . $formVars['r_adp_character'] . "&r_adp_number=" . $a_adept['adp_id'] . "');\">";
            $linkend   = "</a>";

            $maxlevel = $a_adept['adp_level'];
            if ($a_adept['adp_level'] == 0) {
              $maxlevel = "Limited by Magic";
            }

            $adp_book = return_Book($a_adept['ver_book'], $a_adept['adp_page']);

            $class = "ui-widget-content";

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_adept['adp_name']  . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"                     . $a_adept['adp_desc']             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_adept['adp_power']            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $maxlevel                        . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $adp_book                        . "</td>\n";
            $output .= "</tr>\n";

          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No Adept Powers available.</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "</table>\n";

        mysql_free_result($q_adept);

        print "document.getElementById('powers_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Adept Power Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('adept-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"adept-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Adept Power</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Level</th>\n";
      $output .=   "<th class=\"ui-state-default\">Power Points</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $adepttotal = 0;
      $q_string  = "select r_adp_id,adp_id,adp_name,adp_desc,adp_power,adp_level,r_adp_level,r_adp_specialize,ver_book,adp_page ";
      $q_string .= "from r_adept ";
      $q_string .= "left join adept on adept.adp_id = r_adept.r_adp_number ";
      $q_string .= "left join versions on versions.ver_id = adept.adp_book ";
      $q_string .= "where r_adp_character = " . $formVars['r_adp_character'] . " ";
      $q_string .= "order by adp_name ";
      $q_r_adept = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_adept) > 0) {
        while ($a_r_adept = mysql_fetch_array($q_r_adept)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('adept.fill.php?id=" . $a_r_adept['r_adp_id'] . "');showDiv('adept-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_adept('adept.del.php?id="  . $a_r_adept['r_adp_id'] . "');\">";
          $linkend   = "</a>";

# if == 0, then skill is by level.
          if ($a_r_adept['adp_level'] == 0) {
            $level = " per level";
          } else {
            $level = "";
          }

          if (strlen($a_r_adept['r_adp_specialize']) > 0) {
            $specialize = " (" . $a_r_adept['r_adp_specialize'] . ")";
          } else {
            $specialize = "";
          }

          if ($a_r_adept['adp_level'] == 0) {
            $powerpoints = ($a_r_adept['adp_power'] * $a_r_adept['r_adp_level']);
          } else {
            $powerpoints = $a_r_adept['adp_power'];
          }

          $adepttotal += $powerpoints;

          $adp_book = return_Book($a_r_adept['ver_book'], $a_r_adept['adp_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_adp_number']) && $formVars['r_adp_number'] == $a_r_adept['adp_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $linkdel                                                                                . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . "<strong>" . $a_r_adept['adp_name'] . "</strong>" . $specialize . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_adept['adp_desc']                                          . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_adept['adp_power'] . $level                                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_adept['r_adp_level']                                                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . number_format($powerpoints, 2, '.', ',')                                   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $adp_book                                                                  . "</td>\n";
          $output .= "</tr>\n";

        }

        $q_string  = "select runr_magic ";
        $q_string .= "from runners ";
        $q_string .= "where runr_id = " . $formVars['r_adp_character'] . " ";
        $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        $a_runners = mysql_fetch_array($q_runners);

        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">Power Points: " . $adepttotal . " of " . $a_runners['runr_magic'] . " Magic Points</td>\n";
        $output .= "</tr>\n";

      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No Adept Powers added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_adept);

      print "document.getElementById('my_powers_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.r_adp_update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
