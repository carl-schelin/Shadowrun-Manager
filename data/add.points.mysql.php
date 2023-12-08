<?php
# Script: add.points.mysql.php
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
    $package = "add.points.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                 = clean($_GET['id'],                10);
        $formVars['point_number']       = clean($_GET['point_number'],      10);
        $formVars['point_cost']         = clean($_GET['point_cost'],        10);
        $formVars['point_level']        = clean($_GET['point_level'],       20);
        $formVars['point_book']         = clean($_GET['point_book'],        10);
        $formVars['point_page']         = clean($_GET['point_page'],        10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['point_number'] == '') {
          $formVars['point_number'] = 0;
        }
        if ($formVars['point_cost'] == '') {
          $formVars['point_cost'] = 0;
        }
        if ($formVars['point_page'] == '') {
          $formVars['point_page'] = 0;
        }

        if ($formVars['point_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "point_number      =   " . $formVars['point_number']   . "," .
            "point_cost        =   " . $formVars['point_cost']     . "," .
            "point_level       = \"" . $formVars['point_level']    . "\"," .
            "point_book        =   " . $formVars['point_book']     . "," .
            "point_page        =   " . $formVars['point_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into points set point_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update points set " . $q_string . " where point_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['point_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Lifestyle Points Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('points-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"points-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Number</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Level</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select point_id,point_number,point_cost,point_level,ver_book,point_page ";
      $q_string .= "from points ";
      $q_string .= "left join versions on versions.ver_id = points.point_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by point_number,ver_version ";
      $q_points = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_points) > 0) {
        while ($a_points = mysqli_fetch_array($q_points)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.points.fill.php?id="  . $a_points['point_id'] . "');jQuery('#dialogPoints').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_points('add.points.del.php?id=" . $a_points['point_id'] . "');\">";
          $linkend = "</a>";

          $points_cost = return_Cost($a_points['point_cost']);

          $points_book = return_Book($a_points['ver_book'], $a_points['point_page']);

          $output .= "<tr>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                             . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $a_points['point_id']                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $linkstart . $a_points['point_number'] . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $points_cost                         . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_points['point_level']             . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $points_book                         . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.point_number.value = '';\n";
      print "document.dialog.point_cost.value = '';\n";
      print "document.dialog.point_level.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
