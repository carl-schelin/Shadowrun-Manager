<?php
# Script: add.cyberjack.mysql.php
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
    $package = "add.cyberjack.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],            10);
        $formVars['jack_class']     = clean($_GET['jack_class'],    10);
        $formVars['jack_name']      = clean($_GET['jack_name'],     40);
        $formVars['jack_rating']    = clean($_GET['jack_rating'],   10);
        $formVars['jack_data']      = clean($_GET['jack_data'],     10);
        $formVars['jack_firewall']  = clean($_GET['jack_firewall'], 10);
        $formVars['jack_matrix']    = clean($_GET['jack_matrix'],   10);
        $formVars['jack_essence']   = clean($_GET['jack_essence'],  10);
        $formVars['jack_access']    = clean($_GET['jack_access'],   16);
        $formVars['jack_avail']     = clean($_GET['jack_avail'],    10);
        $formVars['jack_perm']      = clean($_GET['jack_perm'],     10);
        $formVars['jack_cost']      = clean($_GET['jack_cost'],     10);
        $formVars['jack_book']      = clean($_GET['jack_book'],     10);
        $formVars['jack_page']      = clean($_GET['jack_page'],     10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['jack_rating'] == '') {
          $formVars['jack_rating'] = 0;
        }
        if ($formVars['jack_data'] == '') {
          $formVars['jack_data'] = 0;
        }
        if ($formVars['jack_firewall'] == '') {
          $formVars['jack_firewall'] = 0;
        }
        if ($formVars['jack_matrix'] == '') {
          $formVars['jack_matrix'] = 0;
        }
        if ($formVars['jack_essence'] == '') {
          $formVars['jack_essence'] = 0.00;
        }
        if ($formVars['jack_avail'] == '') {
          $formVars['jack_avail'] = 0;
        }
        if ($formVars['jack_cost'] == '') {
          $formVars['jack_cost'] = 0;
        }
        if ($formVars['jack_page'] == '') {
          $formVars['jack_page'] = 0;
        }

        if (strlen($formVars['jack_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "jack_class      =   " . $formVars['jack_class']      . "," .
            "jack_name       = \"" . $formVars['jack_name']       . "\"," .
            "jack_rating     =   " . $formVars['jack_rating']     . "," .
            "jack_data       =   " . $formVars['jack_data']       . "," .
            "jack_firewall   =   " . $formVars['jack_firewall']   . "," .
            "jack_matrix     =   " . $formVars['jack_matrix']     . "," .
            "jack_essence    =   " . $formVars['jack_essence']    . "," .
            "jack_access     = \"" . $formVars['jack_access']     . "\"," .
            "jack_avail      =   " . $formVars['jack_avail']      . "," .
            "jack_perm       = \"" . $formVars['jack_perm']       . "\"," .
            "jack_cost       =   " . $formVars['jack_cost']       . "," .
            "jack_book       = \"" . $formVars['jack_book']       . "\"," .
            "jack_page       =   " . $formVars['jack_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into cyberjack set jack_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update cyberjack set " . $q_string . " where jack_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['jack_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Cyberjack Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('cyberjack-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"cyberjack-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Cyberjack Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Vehicle from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Vehicle to toggle the form and edit the Vehicle.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Vehicles Management</strong> title bar to toggle the <strong>Vehicles Form</strong>.</li>\n";
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
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
      $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
      $output .=   "<th class=\"ui-state-default\">Matrix Bonus</th>\n";
      $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
      $output .=   "<th class=\"ui-state-default\">Company ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book</th>\n";
      $output .= "</tr>\n";

      $nuyen = '&yen;';
      $q_string  = "select jack_id,jack_name,jack_rating,jack_data,jack_firewall,jack_matrix,jack_essence,";
      $q_string .= "jack_access,jack_avail,jack_perm,jack_cost,ver_book,jack_page ";
      $q_string .= "from cyberjack ";
      $q_string .= "left join versions on versions.ver_id = cyberjack.jack_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by jack_name,jack_rating,ver_version ";
      $q_cyberjack = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_cyberjack) > 0) {
        while ($a_cyberjack = mysql_fetch_array($q_cyberjack)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.cyberjack.fill.php?id="  . $a_cyberjack['jack_id'] . "');jQuery('#dialogCyberjack').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_cyberjack('add.cyberjack.del.php?id=" . $a_cyberjack['jack_id'] . "');\">";
          $linkend = "</a>";

          $rating = return_Rating($a_cyberjack['jack_rating']);

          $jack = return_Cyberjack($a_cyberjack['jack_data'], $a_cyberjack['jack_firewall']);

          $essence = return_Essence($a_cyberjack['jack_essence']);

          $avail = return_Avail($a_cyberjack['jack_avail'], $a_cyberjack['jack_perm'], 0, 0);

          $cost = return_Cost($a_cyberjack['jack_cost']);

          $book = return_Book($a_cyberjack['ver_book'], $a_cyberjack['jack_page']);

          $class = return_Class($a_cyberjack['jack_perm']);

          $total = 0;
          $q_string  = "select r_jack_id ";
          $q_string .= "from r_cyberjack ";
          $q_string .= "where r_jack_number = " . $a_cyberjack['jack_id'] . " ";
          $q_r_cyberjack = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_cyberjack) > 0) {
            while ($a_r_cyberjack = mysql_fetch_array($q_r_cyberjack)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_cyberjack['jack_id']              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                               . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_cyberjack['jack_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $rating                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberjack['jack_data']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberjack['jack_firewall']        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberjack['jack_matrix']          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $essence                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberjack['jack_access']          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $avail                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $cost                                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $book                                . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"15\">No Cyberjacks found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.jack_name.value = '';\n";
      print "document.dialog.jack_rating.value = '';\n";
      print "document.dialog.jack_data.value = '';\n";
      print "document.dialog.jack_firewall.value = '';\n";
      print "document.dialog.jack_matrix.value = '';\n";
      print "document.dialog.jack_essence.value = '';\n";
      print "document.dialog.jack_access.value = '';\n";
      print "document.dialog.jack_avail.value = '';\n";
      print "document.dialog.jack_perm.value = '';\n";
      print "document.dialog.jack_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
