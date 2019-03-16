<?php
# Script: add.gear.mysql.php
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
    $package = "add.gear.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],            10);
        $formVars['gear_class']    = clean($_GET['gear_class'],    60);
        $formVars['gear_name']     = clean($_GET['gear_name'],    100);
        $formVars['gear_capacity'] = clean($_GET['gear_capacity'], 10);
        $formVars['gear_rating']   = clean($_GET['gear_rating'],   10);
        $formVars['gear_avail']    = clean($_GET['gear_avail'],    10);
        $formVars['gear_perm']     = clean($_GET['gear_perm'],     10);
        $formVars['gear_cost']     = clean($_GET['gear_cost'],     10);
        $formVars['gear_book']     = clean($_GET['gear_book'],     10);
        $formVars['gear_page']     = clean($_GET['gear_page'],     10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['gear_rating'] == '') {
          $formVars['gear_rating'] = 0;
        }
        if ($formVars['gear_capacity'] == '') {
          $formVars['gear_capacity'] = 0;
        }
        if ($formVars['gear_cost'] == '') {
          $formVars['gear_cost'] = 0;
        }
        if ($formVars['gear_avail'] == '') {
          $formVars['gear_avail'] = 0;
        }
        if ($formVars['gear_page'] == '') {
          $formVars['gear_page'] = 0;
        }

        if (strlen($formVars['gear_class']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "gear_class    = \"" . $formVars['gear_class']    . "\"," .
            "gear_name     = \"" . $formVars['gear_name']     . "\"," .
            "gear_capacity =   " . $formVars['gear_capacity'] . "," .
            "gear_rating   =   " . $formVars['gear_rating']   . "," .
            "gear_avail    =   " . $formVars['gear_avail']    . "," .
            "gear_perm     = \"" . $formVars['gear_perm']     . "\"," .
            "gear_cost     =   " . $formVars['gear_cost']     . "," .
            "gear_book     = \"" . $formVars['gear_book']     . "\"," .
            "gear_page     =   " . $formVars['gear_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into gear set gear_id = NULL, " . $q_string;
            $message = "Gear added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update gear set " . $q_string . " where gear_id = " . $formVars['id'];
            $message = "Gear updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['gear_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $gear_list = array("gear");

      foreach ($gear_list as &$gear) {

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">" . ucfirst($gear) . " Gear Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $gear . "-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"" . $gear . "-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Bioware Listing</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li><strong>Remove</strong> - Click here to delete this Bioware from the Mooks Database.</li>\n";
        $output .= "    <li><strong>Editing</strong> - Click on a Bioware to toggle the form and edit the Bioware.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Notes</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li>Click the <strong>Bioware Management</strong> title bar to toggle the <strong>Bioware Form</strong>.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Del</th>\n";
        $output .=   "<th class=\"ui-state-default\">ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Class</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Capacity</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $nuyen = '&yen;';
        $q_string  = "select gear_id,class_name,gear_name,gear_rating,gear_capacity,gear_avail,gear_perm,gear_cost,ver_book,gear_page ";
        $q_string .= "from gear ";
        $q_string .= "left join class on class.class_id = gear.gear_class ";
        $q_string .= "left join versions on versions.ver_id = gear.gear_book ";
        $q_string .= "where ver_admin = 1 ";
        $q_string .= "order by class_name,gear_name,gear_rating,ver_version ";
        $q_gear = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_gear) > 0) {
          while ($a_gear = mysql_fetch_array($q_gear)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.gear.fill.php?id="  . $a_gear['gear_id'] . "');jQuery('#dialogGear').dialog('open');return false;\">";
            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_gear('add.gear.del.php?id=" . $a_gear['gear_id'] . "');\">";
            $linkend = "</a>";

            $gear_rating = return_Rating($a_gear['gear_rating']);

            $gear_capacity = return_Capacity($a_gear['gear_capacity']);

            $gear_avail = return_Avail($a_gear['gear_avail'], $a_gear['gear_perm']);

            $gear_cost = return_Cost($a_gear['gear_cost']);

            $gear_book = return_Book($a_gear['ver_book'], $a_gear['gear_page']);

            $class = return_Class($a_gear['gear_perm']);

            $output .= "<tr>\n";
            $output .=   "<td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                         . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_gear['gear_id']               . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_gear['class_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_gear['gear_name']  . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $gear_rating                     . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $gear_capacity                   . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $gear_avail                      . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $gear_cost                       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $gear_book                       . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('" . $gear . "_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      }

      print "document.dialog.gear_name.value = '';\n";
      print "document.dialog.gear_rating.value = '';\n";
      print "document.dialog.gear_avail.value = '';\n";
      print "document.dialog.gear_perm.value = '';\n";
      print "document.dialog.gear_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
