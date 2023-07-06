<?php
# Script: add.adept.mysql.php
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
    $package = "add.adept.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],            10);
        $formVars['adp_name']      = clean($_GET['adp_name'],      60);
        $formVars['adp_desc']      = clean($_GET['adp_desc'],     128);
        $formVars['adp_power']     = clean($_GET['adp_power'],     10);
        $formVars['adp_level']     = clean($_GET['adp_level'],     10);
        $formVars['adp_book']      = clean($_GET['adp_book'],      10);
        $formVars['adp_page']      = clean($_GET['adp_page'],      10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['adp_power'] == '') {
          $formVars['adp_power'] = 0.00;
        }
        if ($formVars['adp_page'] == '') {
          $formVars['adp_page'] = 0;
        }

        if (strlen($formVars['adp_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "adp_name       = \"" . $formVars['adp_name']   . "\"," .
            "adp_desc       = \"" . $formVars['adp_desc']   . "\"," .
            "adp_power      =   " . $formVars['adp_power']  . "," .
            "adp_level      =   " . $formVars['adp_level']  . "," .
            "adp_book       = \"" . $formVars['adp_book']   . "\"," .
            "adp_page       =   " . $formVars['adp_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into adept set adp_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update adept set " . $q_string . " where adp_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['adp_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
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
      $output .= "  <li><strong>Adept Power Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Adept Power from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on an Adept Power to toggle the form and edit the Adept Power.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Adept Power Management</strong> title bar to toggle the <strong>Adept Power Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Power Points</th>\n";
      $output .=   "<th class=\"ui-state-default\">Level</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select adp_id,adp_name,adp_desc,adp_power,adp_level,ver_book,adp_page ";
      $q_string .= "from adept ";
      $q_string .= "left join versions on versions.ver_id = adept.adp_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by adp_name,ver_version ";
      $q_adept = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_adept) > 0) {
        while ($a_adept = mysql_fetch_array($q_adept)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.adept.fill.php?id="  . $a_adept['adp_id'] . "');jQuery('#dialogAdeptPower').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_adept('add.adept.del.php?id=" . $a_adept['adp_id'] . "');\">";
          $linkend = "</a>";

          $maxlevel = $a_adept['adp_level'];
          if ($a_adept['adp_level'] == 0) {
            $maxlevel = "Limited by Magic";
          }

          $class = "ui-widget-content";

          $total = 0;
          $q_string  = "select r_adp_id ";
          $q_string .= "from r_adept ";
          $q_string .= "where r_adp_number = " . $a_adept['adp_id'] . " ";
          $q_r_adept = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_adept) > 0) {
            while ($a_r_adept = mysql_fetch_array($q_r_adept)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_adept['adp_id']                                      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                                                  . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_adept['adp_name']                         . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_adept['adp_desc']                                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_adept['adp_power']                                   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $maxlevel                                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . return_Book($a_adept['ver_book'], $a_adept['adp_page']) . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.adp_name.value = '';\n";
      print "document.dialog.adp_desc.value = '';\n";
      print "document.dialog.adp_power.value = '';\n";
      print "document.dialog.adp_level.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
