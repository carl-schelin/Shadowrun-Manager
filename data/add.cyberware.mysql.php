<?php
# Script: add.cyberware.mysql.php
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
    $package = "add.cyberware.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],           10);
        $formVars['ware_class']     = clean($_GET['ware_class'],    10);
        $formVars['ware_name']      = clean($_GET['ware_name'],     40);
        $formVars['ware_rating']    = clean($_GET['ware_rating'],   10);
        $formVars['ware_multiply']  = clean($_GET['ware_multiply'], 10);
        $formVars['ware_essence']   = clean($_GET['ware_essence'],  10);
        $formVars['ware_capacity']  = clean($_GET['ware_capacity'], 10);
        $formVars['ware_avail']     = clean($_GET['ware_avail'],    10);
        $formVars['ware_perm']      = clean($_GET['ware_perm'],     10);
        $formVars['ware_basetime']  = clean($_GET['ware_basetime'], 10);
        $formVars['ware_duration']  = clean($_GET['ware_duration'], 10);
        $formVars['ware_index']     = clean($_GET['ware_index'],    10);
        $formVars['ware_legality']  = clean($_GET['ware_legality'], 10);
        $formVars['ware_cost']      = clean($_GET['ware_cost'],     10);
        $formVars['ware_book']      = clean($_GET['ware_book'],     10);
        $formVars['ware_page']      = clean($_GET['ware_page'],     10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['ware_rating'] == '') {
          $formVars['ware_rating'] = 0;
        }
        if ($formVars['ware_multiply'] == 'true') {
          $formVars['ware_multiply'] = 1;
        } else {
          $formVars['ware_multiply'] = 0;
        }
        if ($formVars['ware_essence'] == '') {
          $formVars['ware_essence'] = 0.00;
        }
        if ($formVars['ware_capacity'] == '') {
          $formVars['ware_capacity'] = 0;
        }
        if ($formVars['ware_avail'] == '') {
          $formVars['ware_avail'] = 0;
        }
        if ($formVars['ware_basetime'] == '') {
          $formVars['ware_basetime'] = 0;
        }
        if ($formVars['ware_index'] == '') {
          $formVars['ware_index'] = 0.00;
        }
        if ($formVars['ware_cost'] == '') {
          $formVars['ware_cost'] = 0;
        }
        if ($formVars['ware_page'] == '') {
          $formVars['ware_page'] = 0;
        }

        if (strlen($formVars['ware_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "ware_class      =   " . $formVars['ware_class']      . "," .
            "ware_name       = \"" . $formVars['ware_name']       . "\"," .
            "ware_rating     =   " . $formVars['ware_rating']     . "," .
            "ware_multiply   =   " . $formVars['ware_multiply']   . "," .
            "ware_essence    =   " . $formVars['ware_essence']    . "," .
            "ware_capacity   =   " . $formVars['ware_capacity']   . "," .
            "ware_avail      =   " . $formVars['ware_avail']      . "," .
            "ware_perm       = \"" . $formVars['ware_perm']       . "\"," .
            "ware_basetime   =   " . $formVars['ware_basetime']   . "," .
            "ware_duration   =   " . $formVars['ware_duration']   . "," .
            "ware_index      =   " . $formVars['ware_index']      . "," .
            "ware_legality   = \"" . $formVars['ware_legality']   . "\"," .
            "ware_cost       =   " . $formVars['ware_cost']       . "," .
            "ware_book       = \"" . $formVars['ware_book']       . "\"," .
            "ware_page       =   " . $formVars['ware_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into cyberware set ware_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update cyberware set " . $q_string . " where ware_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['ware_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $cyberware_list = array("earware", "eyeware", "headware", "bodyware", "cyberlimbs", "cosmetic");

      foreach ($cyberware_list as &$cyberware) {

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Cyberware Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $cyberware . "-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"" . $cyberware . "-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Vehicles Listing</strong>\n";
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
        $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
        $output .=   "<th class=\"ui-state-default\">Capacity</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Street Index</th>\n";
        $output .=   "<th class=\"ui-state-default\">Legality</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book</th>\n";
        $output .= "</tr>\n";

        $nuyen = '&yen;';
        $q_string  = "select ware_id,ware_name,ware_rating,ware_essence,ware_capacity,ware_avail,ware_perm,";
        $q_string .= "ware_basetime,ware_duration,ware_index,ware_legality,ware_cost,ver_book,ware_page ";
        $q_string .= "from cyberware ";
        $q_string .= "left join versions on versions.ver_id = cyberware.ware_book ";
        $q_string .= "left join class on class.class_id = cyberware.ware_class ";
        $q_string .= "where class_name like \"" . $cyberware . "%\" and ver_admin = 1 ";
        $q_string .= "order by ware_class,ware_name,ware_rating,ver_version ";
        $q_cyberware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_cyberware) > 0) {
          while ($a_cyberware = mysqli_fetch_array($q_cyberware)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.cyberware.fill.php?id="  . $a_cyberware['ware_id'] . "');jQuery('#dialogCyberware').dialog('open');return false;\">";
            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_cyberware('add.cyberware.del.php?id=" . $a_cyberware['ware_id'] . "');\">";
            $linkend = "</a>";

            $rating = return_Rating($a_cyberware['ware_rating']);

            $essence = return_Essence($a_cyberware['ware_essence']);

            $capacity = return_Capacity($a_cyberware['ware_capacity']);

            $avail = return_Avail($a_cyberware['ware_avail'], $a_cyberware['ware_perm'], $a_cyberware['ware_basetime'], $a_cyberware['ware_duration']);

            $index = return_StreetIndex($a_cyberware['ware_index']);

            $cost = return_Cost($a_cyberware['ware_cost']);

            $book = return_Book($a_cyberware['ver_book'], $a_cyberware['ware_page']);

            $class = return_Class($a_cyberware['ware_perm']);

            $total = 0;
            $q_string  = "select r_ware_id ";
            $q_string .= "from r_cyberware ";
            $q_string .= "where r_ware_number = " . $a_cyberware['ware_id'] . " ";
            $q_r_cyberware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_r_cyberware) > 0) {
              while ($a_r_cyberware = mysqli_fetch_array($q_r_cyberware)) {
                $total++;
              }
            }

            $output .= "<tr>\n";
            if ($total > 0) {
              $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
            } else {
              $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
            }
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_cyberware['ware_id']              . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                               . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_cyberware['ware_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $rating                              . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $essence                             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $capacity                            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $avail                               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $index                               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberware['ware_legality']        . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $cost                                . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $book                                . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"12\">No Cyberware found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('" . $cyberware . "_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }

      print "document.dialog.ware_name.value = '';\n";
      print "document.dialog.ware_rating.value = '';\n";
      print "document.dialog.ware_multiply.checked = false;\n";
      print "document.dialog.ware_essence.value = '';\n";
      print "document.dialog.ware_capacity.value = '';\n";
      print "document.dialog.ware_avail.value = '';\n";
      print "document.dialog.ware_perm.value = '';\n";
      print "document.dialog.ware_basetime.value = '';\n";
      print "document.dialog.ware_duration.value = 0;\n";
      print "document.dialog.ware_index.value = '';\n";
      print "document.dialog.ware_legality.value = '';\n";
      print "document.dialog.ware_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
