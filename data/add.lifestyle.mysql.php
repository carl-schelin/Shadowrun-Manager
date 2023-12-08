<?php
# Script: add.lifestyle.mysql.php
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
    $package = "add.lifestyle.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                   = clean($_GET['id'],                  10);
        $formVars['life_style']           = clean($_GET['life_style'],          60);
        $formVars['life_mincost']         = clean($_GET['life_mincost'],        10);
        $formVars['life_maxcost']         = clean($_GET['life_maxcost'],        10);
        $formVars['life_book']            = clean($_GET['life_book'],           10);
        $formVars['life_page']            = clean($_GET['life_page'],           10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['life_page'] == '') {
          $formVars['life_page'] = 0;
        }
        if ($formVars['life_mincost'] == '') {
          $formVars['life_mincost'] = 0;
        }
        if ($formVars['life_mincost'] == '') {
          $formVars['life_maxcost'] = 0;
        }

        if (strlen($formVars['life_style']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "life_style            = \"" . $formVars['life_style']           . "\"," .
            "life_mincost          =   " . $formVars['life_mincost']         . "," .
            "life_maxcost          =   " . $formVars['life_maxcost']         . "," .
            "life_book             = \"" . $formVars['life_book']            . "\"," .
            "life_page             =   " . $formVars['life_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into lifestyle set life_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update lifestyle set " . $q_string . " where life_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['life_style']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Lifestyle Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('lifestyle-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"lifestyle-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<p><strong>Lifestyle Listing</strong></p>\n";
      $output .= "<p>The extended information are from the extended character books.\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
      $output .=   "<th class=\"ui-state-default\">Lifestyle</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select life_id,life_style,life_mincost,life_maxcost,ver_book,life_page ";
      $q_string .= "from lifestyle ";
      $q_string .= "left join versions on versions.ver_id = lifestyle.life_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by life_style,ver_version ";
      $q_lifestyle = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_lifestyle) > 0) {
        while ($a_lifestyle = mysqli_fetch_array($q_lifestyle)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.lifestyle.fill.php?id="  . $a_lifestyle['life_id'] . "');jQuery('#dialogLifestyle').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_lifestyle('add.lifestyle.del.php?id=" . $a_lifestyle['life_id'] . "');\">";
          $linkend = "</a>";

          $life_cost = return_Cost($a_lifestyle['life_mincost'], $a_lifestyle['life_maxcost']);

          $life_book = return_Book($a_lifestyle['ver_book'], $a_lifestyle['life_page']);

          $class = "ui-widget-content";

          $total = 0;
          $q_string  = "select r_life_id ";
          $q_string .= "from r_lifestyle ";
          $q_string .= "where r_life_number = " . $a_lifestyle['life_id'] . " ";
          $q_r_lifestyle = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_lifestyle) > 0) {
            while ($a_r_lifestyle = mysqli_fetch_array($q_r_lifestyle)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                         . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_lifestyle['life_id']               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                                . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_lifestyle['life_style'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $life_cost                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $life_book                            . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.life_style.value = '';\n";
      print "document.dialog.life_mincost.value = '';\n";
      print "document.dialog.life_maxcost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
