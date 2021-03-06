<?php
# Script: add.armor.mysql.php
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
    $package = "add.armor.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']           = clean($_GET['id'],            10);
        $formVars['arm_class']    = clean($_GET['arm_class'],     10);
        $formVars['arm_name']     = clean($_GET['arm_name'],     100);
        $formVars['arm_rating']   = clean($_GET['arm_rating'],    10);
        $formVars['arm_capacity'] = clean($_GET['arm_capacity'],  10);
        $formVars['arm_avail']    = clean($_GET['arm_avail'],     10);
        $formVars['arm_perm']     = clean($_GET['arm_perm'],       5);
        $formVars['arm_cost']     = clean($_GET['arm_cost'],      10);
        $formVars['arm_book']     = clean($_GET['arm_book'],      10);
        $formVars['arm_page']     = clean($_GET['arm_page'],      10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['arm_rating'] == '') {
          $formVars['arm_rating'] = 0;
        }
        if ($formVars['arm_capacity'] == '') {
          $formVars['arm_capacity'] = 0;
        }
        if ($formVars['arm_avail'] == '') {
          $formVars['arm_avail'] = 0;
        }
        if ($formVars['arm_cost'] == '') {
          $formVars['arm_cost'] = 0;
        }
        if ($formVars['arm_page'] == '') {
          $formVars['arm_page'] = 0;
        }

        if (strlen($formVars['arm_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "arm_class      =   " . $formVars['arm_class']      . "," .
            "arm_name       = \"" . $formVars['arm_name']       . "\"," .
            "arm_rating     =   " . $formVars['arm_rating']     . "," .
            "arm_capacity   =   " . $formVars['arm_capacity']   . "," .
            "arm_avail      =   " . $formVars['arm_avail']      . "," .
            "arm_perm       = \"" . $formVars['arm_perm']       . "\"," .
            "arm_cost       =   " . $formVars['arm_cost']       . "," .
            "arm_book       = \"" . $formVars['arm_book']       . "\"," .
            "arm_page       =   " . $formVars['arm_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into armor set arm_id = NULL, " . $q_string;
            $message = "Armor added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update armor set " . $q_string . " where arm_id = " . $formVars['id'];
            $message = "Armor updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['arm_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Armor Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('armor-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"armor-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Armor Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Weapon from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Weapon to toggle the form and edit the Weapon.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Firearm Management</strong> title bar to toggle the <strong>Firearm Form</strong>.</li>\n";
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

      $q_string  = "select arm_id,class_name,arm_name,arm_rating,arm_capacity,";
      $q_string .= "arm_avail,arm_perm,arm_cost,ver_book,arm_page ";
      $q_string .= "from armor ";
      $q_string .= "left join class on class.class_id = armor.arm_class ";
      $q_string .= "left join versions on versions.ver_id = armor.arm_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by arm_name,arm_rating,ver_version ";
      $q_armor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_armor) > 0) {
        while ($a_armor = mysql_fetch_array($q_armor)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.armor.fill.php?id="  . $a_armor['arm_id'] . "');jQuery('#dialogArmor').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_armor('add.armor.del.php?id=" . $a_armor['arm_id'] . "');\">";
          $linkend = "</a>";

          $arm_rating = return_Rating($a_armor['arm_rating']);

          $arm_capacity = return_Capacity($a_armor['arm_capacity']);

          $arm_avail = return_Avail($a_armor['arm_avail'], $a_armor['arm_perm']);

          $arm_cost = return_Cost($a_armor['arm_cost']);

          $arm_book = return_Book($a_armor['ver_book'], $a_armor['arm_page']);

          $class = return_Class($a_armor['arm_perm']);

          $output .= "<tr>\n";
          $output .=   "<td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_armor['arm_id']                . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_armor['class_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_armor['arm_name']   . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $arm_rating                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $arm_capacity                     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $arm_avail                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $arm_cost                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $arm_book                         . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.arm_class.value = '';\n";
      print "document.dialog.arm_name.value = '';\n";
      print "document.dialog.arm_rating.value = '';\n";
      print "document.dialog.arm_capacity.value = '';\n";
      print "document.dialog.arm_avail.value = '';\n";
      print "document.dialog.arm_perm.value = '';\n";
      print "document.dialog.arm_cost.value = '';\n";

      print "document.getElementById('arm_name').focus();\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
