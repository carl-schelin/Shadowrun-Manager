<?php
# Script: metamagics.mysql.php
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
    $package = "metamagics.mysql.php";
    if (isset($_GET['update'])) {
      $formVars['update'] = clean($_GET['update'], 10);
    } else {
      $formVars['update'] = -1;
    }
    if (isset($_GET['r_meta_character'])) {
      $formVars['r_meta_character'] = clean($_GET['r_meta_character'], 10);
    } else {
      $formVars['r_meta_character'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_meta_id']          = clean($_GET['id'],                 10);
        $formVars['r_meta_number']      = clean($_GET['r_meta_number'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if ($formVars['r_meta_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_meta_character   =   " . $formVars['r_meta_character']   . "," .
            "r_meta_number      =   " . $formVars['r_meta_number'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_metamagics set r_meta_id = NULL," . $q_string;
            $message = "Metamagic added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update r_metamagics set " . $q_string . " where r_meta_id = " . $formVars['r_meta_id'];
            $message = "Metamagic updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_meta_number']);

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
        $output .= "<input type=\"button\" name=\"r_meta_refresh\" value=\"Refresh Metamagics Listing\" onClick=\"javascript:attach_metamagics('metamagics.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_meta_update\"  value=\"Update Metamagics Power\"    onClick=\"javascript:attach_metamagics('metamagics.mysql.php', 1);hideDiv('metamagics-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_meta_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_meta_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Metamagics Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Metamagic: <span id=\"r_meta_item\"></span></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('metamagics_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Metamagics Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('metamagics-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"metamagics-listing-help\" style=\"display: none\">\n";

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
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Description</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select meta_id,meta_name,meta_description,ver_book,meta_page ";
        $q_string .= "from metamagics ";
        $q_string .= "left join versions on versions.ver_id = metamagics.meta_book ";
        $q_string .= "where ver_active = 1 ";
        $q_string .= "order by meta_name ";
        $q_metamagics = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_metamagics) > 0) {
          while ($a_metamagics = mysql_fetch_array($q_metamagics)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('metamagics.mysql.php?update=0&r_meta_character=" . $formVars['r_meta_character'] . "&r_meta_number=" . $a_metamagics['meta_id'] . "');\">";
            $linkend   = "</a>";

            $meta_book = return_Book($a_metamagics['ver_book'], $a_metamagics['meta_page']);

            $class = "ui-widget-content";

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_metamagics['meta_name']  . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"                     . $a_metamagics['meta_description']             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $meta_book                        . "</td>\n";
            $output .= "</tr>\n";

          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"3\">No Metamagics available.</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "</table>\n";

        mysql_free_result($q_adept);

        print "document.getElementById('magics_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Metamagics Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('metamagics-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"metamagics-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $adepttotal = 0;
      $q_string  = "select r_meta_id,meta_id,meta_name,meta_description,ver_book,meta_page ";
      $q_string .= "from r_metamagics ";
      $q_string .= "left join metamagics on metamagics.meta_id = r_metamagics.r_meta_number ";
      $q_string .= "left join versions on versions.ver_id = metamagics.meta_book ";
      $q_string .= "where r_meta_character = " . $formVars['r_meta_character'] . " ";
      $q_string .= "order by meta_name ";
      $q_r_metamagics = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_metamagics) > 0) {
        while ($a_r_metamagics = mysql_fetch_array($q_r_metamagics)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('metamagics.fill.php?id=" . $a_r_metamagics['r_meta_id'] . "');showDiv('metamagics-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_metamagics('metamagics.del.php?id="  . $a_r_metamagics['r_meta_id'] . "');\">";
          $linkend   = "</a>";

          $meta_book = return_Book($a_r_metamagics['ver_book'], $a_r_metamagics['meta_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $linkdel                                                                                . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_metamagics['meta_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_metamagics['meta_description'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $meta_book                                                                  . "</td>\n";
          $output .= "</tr>\n";

        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">No Metamagics added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_metamagics);

      print "document.getElementById('my_magics_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.r_meta_update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
