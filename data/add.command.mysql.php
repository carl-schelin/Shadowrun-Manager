<?php
# Script: add.command.mysql.php
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
    $package = "add.command.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],            10);
        $formVars['cmd_brand']     = clean($_GET['cmd_brand'],     30);
        $formVars['cmd_model']     = clean($_GET['cmd_model'],     30);
        $formVars['cmd_rating']    = clean($_GET['cmd_rating'],    10);
        $formVars['cmd_data']      = clean($_GET['cmd_data'],      10);
        $formVars['cmd_firewall']  = clean($_GET['cmd_firewall'],  10);
        $formVars['cmd_programs']  = clean($_GET['cmd_programs'],  10);
        $formVars['cmd_access']    = clean($_GET['cmd_access'],    15);
        $formVars['cmd_cost']      = clean($_GET['cmd_cost'],      10);
        $formVars['cmd_avail']     = clean($_GET['cmd_avail'],     10);
        $formVars['cmd_perm']      = clean($_GET['cmd_perm'],      10);
        $formVars['cmd_book']      = clean($_GET['cmd_book'],      10);
        $formVars['cmd_page']      = clean($_GET['cmd_page'],      10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['cmd_rating'] == '') {
          $formVars['cmd_rating'] = 0;
        }
        if ($formVars['cmd_data'] == '') {
          $formVars['cmd_data'] = 0;
        }
        if ($formVars['cmd_firewall'] == '') {
          $formVars['cmd_firewall'] = 0;
        }
        if ($formVars['cmd_programs'] == '') {
          $formVars['cmd_programs'] = 0;
        }
        if ($formVars['cmd_cost'] == '') {
          $formVars['cmd_cost'] = 0;
        }
        if ($formVars['cmd_page'] == '') {
          $formVars['cmd_page'] = 0;
        }

        if (strlen($formVars['cmd_brand']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "cmd_brand     = \"" . $formVars['cmd_brand']    . "\"," .
            "cmd_model     = \"" . $formVars['cmd_model']    . "\"," .
            "cmd_rating    =   " . $formVars['cmd_rating']   . "," .
            "cmd_data      =   " . $formVars['cmd_data']     . "," .
            "cmd_firewall  =   " . $formVars['cmd_firewall'] . "," .
            "cmd_programs  =   " . $formVars['cmd_programs'] . "," .
            "cmd_access    = \"" . $formVars['cmd_access']   . "\"," .
            "cmd_cost      =   " . $formVars['cmd_cost']     . "," .
            "cmd_avail     =   " . $formVars['cmd_avail']    . "," .
            "cmd_perm      = \"" . $formVars['cmd_perm']     . "\"," .
            "cmd_book      =   " . $formVars['cmd_book']     . "," .
            "cmd_page      =   " . $formVars['cmd_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into command set cmd_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update command set " . $q_string . " where cmd_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['cmd_brand']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Rigger Command Console Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('command-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"command-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Commlink Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Commlink from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Commlink to toggle the form and edit the Commlink.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Commlink Management</strong> title bar to toggle the <strong>Commlink Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $nuyen = '&yen;';
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
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
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by cmd_rating,cmd_cost,ver_version ";
      $q_command = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_command) > 0) {
        while ($a_command = mysql_fetch_array($q_command)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.command.fill.php?id="  . $a_command['cmd_id'] . "');jQuery('#dialogCommand').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_command('add.command.del.php?id=" . $a_command['cmd_id'] . "');\">";
          $linkend = "</a>";

          $rating = return_Rating($a_command['cmd_rating']);

          $avail = return_Avail($a_command['cmd_avail'], $a_command['cmd_perm'], 0, 0);

          $cost = return_Cost($a_command['cmd_cost']);

          $book = return_Book($a_command['ver_book'], $a_command['cmd_page']);

          $class = return_Class($a_command['cmd_perm']);

          $total = 0;
          $q_string  = "select r_cmd_id ";
          $q_string .= "from r_command ";
          $q_string .= "where r_cmd_number = " . $a_command['cmd_id'] . " ";
          $q_r_command = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_command) > 0) {
            while ($a_r_command = mysql_fetch_array($q_r_command)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"ui-widget-content delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"ui-widget-content delete\">" . $linkdel                                                  . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_command['cmd_id']                                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_command['cmd_brand'] . " " . $a_command['cmd_model'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $rating                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_command['cmd_data']                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_command['cmd_firewall']                                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_command['cmd_programs']                                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_command['cmd_access']                                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $avail                                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $cost                                                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $book                                                              . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.cmd_brand.value = '';\n";
      print "document.dialog.cmd_model.value = '';\n";
      print "document.dialog.cmd_rating.value = '';\n";
      print "document.dialog.cmd_data.value = '';\n";
      print "document.dialog.cmd_firewall.value = '';\n";
      print "document.dialog.cmd_programs.value = '';\n";
      print "document.dialog.cmd_access.value = '';\n";
      print "document.dialog.cmd_avail.value = '';\n";
      print "document.dialog.cmd_perm.value = '';\n";
      print "document.dialog.cmd_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
