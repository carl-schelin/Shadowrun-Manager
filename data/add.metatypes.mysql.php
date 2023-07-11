<?php
# Script: add.metatypes.mysql.php
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
    $package = "add.metatypes.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],             10);
        $formVars['meta_name']      = clean($_GET['meta_name'],      60);
        $formVars['meta_walk']      = clean($_GET['meta_walk'],      10);
        $formVars['meta_run']       = clean($_GET['meta_run'],       10);
        $formVars['meta_swim']      = clean($_GET['meta_swim'],      10);
        $formVars['meta_notes']     = clean($_GET['meta_notes'],    100);
        $formVars['meta_book']      = clean($_GET['meta_book'],      10);
        $formVars['meta_page']      = clean($_GET['meta_page'],      10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['meta_walk'] == '') {
          $formVars['meta_walk'] = 0;
        }
        if ($formVars['meta_run'] == '') {
          $formVars['meta_run'] = 0;
        }
        if ($formVars['meta_swim'] == '') {
          $formVars['meta_swim'] = 0;
        }
        if ($formVars['meta_page'] == '') {
          $formVars['meta_page'] = 0;
        }

        if (strlen($formVars['meta_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "meta_name    = \"" . $formVars['meta_name'] . "\"," .
            "meta_walk    =   " . $formVars['meta_walk'] . "," .
            "meta_run     =   " . $formVars['meta_run']  . "," .
            "meta_swim    =   " . $formVars['meta_swim'] . "," .
            "meta_notes   = \"" . $formVars['meta_notes'] . "\"," .
            "meta_book    = \"" . $formVars['meta_book'] . "\"," .
            "meta_page    =   " . $formVars['meta_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into metatypes set meta_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update metatypes set " . $q_string . " where meta_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['meta_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Metatype Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('metatype-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"metatype-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Metatype Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Metatype from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Metatype to toggle the form and edit the Metatype.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Metatype Management</strong> title bar to toggle the <strong>Metatype Form</strong>.</li>\n";
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
      $output .=   "<th class=\"ui-state-default\">Walk</th>\n";
      $output .=   "<th class=\"ui-state-default\">Run</th>\n";
      $output .=   "<th class=\"ui-state-default\">Swim</th>\n";
      $output .=   "<th class=\"ui-state-default\">Notes</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select meta_id,meta_name,meta_walk,meta_run,meta_swim,meta_notes,ver_book,meta_page ";
      $q_string .= "from metatypes ";
      $q_string .= "left join versions on versions.ver_id = metatypes.meta_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by meta_name,ver_version ";
      $q_metatypes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_metatypes) > 0) {
        while ($a_metatypes = mysql_fetch_array($q_metatypes)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.metatypes.fill.php?id="  . $a_metatypes['meta_id'] . "');jQuery('#dialogMetatype').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_metatypes('add.metatypes.del.php?id=" . $a_metatypes['meta_id'] . "');\">";
          $linkend = "</a>";

          $book = return_Book($a_metatypes['ver_book'], $a_metatypes['meta_page']);

          $class = "ui-widget-content";

          $total = 0;
          $q_string  = "select runr_metatype ";
          $q_string .= "from runners ";
          $q_string .= "where runr_metatype = " . $a_metatypes['meta_id'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_runners) > 0) {
            while ($a_runners = mysql_fetch_array($q_runners)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_metatypes['meta_id']              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                               . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_metatypes['meta_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_metatypes['meta_walk']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_metatypes['meta_run']             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_metatypes['meta_swim']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_metatypes['meta_notes']           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $book                                . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.meta_name.value = '';\n";
      print "document.dialog.meta_walk.value = '';\n";
      print "document.dialog.meta_run.value = '';\n";
      print "document.dialog.meta_swim.value = '';\n";
      print "document.dialog.meta_notes.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
