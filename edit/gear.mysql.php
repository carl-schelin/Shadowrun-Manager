<?php
# Script: gear.mysql.php
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
    $package = "gear.mysql.php";
    $formVars['update']             = clean($_GET['update'],              10);
    $formVars['r_gear_id']          = clean($_GET['r_gear_id'],           10);
    $formVars['r_gear_character']   = clean($_GET['r_gear_character'],    10);
    $formVars['gear_class']         = clean($_GET['gear_class'],          10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_gear_character'] == '') {
      $formVars['r_gear_character'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_gear_number']      = clean($_GET['r_gear_number'],       10);
        $formVars['r_gear_details']     = clean($_GET['r_gear_details'],      60);
        $formVars['r_gear_amount']      = clean($_GET['r_gear_amount'],       10);

        if ($formVars['r_gear_number'] == '') {
          $formVars['r_gear_number'] = 1;
        }
        if ($formVars['r_gear_amount'] == '') {
          $formVars['r_gear_amount'] = 1;
        }

        if ($formVars['r_gear_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_gear_character   =   " . $formVars['r_gear_character']   . "," .
            "r_gear_number      =   " . $formVars['r_gear_number']      . "," .
            "r_gear_amount      =   " . $formVars['r_gear_amount']      . "," .
            "r_gear_details     = \"" . $formVars['r_gear_details']     . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_gear set r_gear_id = NULL," . $q_string;
            $message = "Gear added.";
          }

          if ($formVars['update'] == 1) {
            $query = "update r_gear set " . $q_string . " where r_gear_id = " . $formVars['r_gear_id'];
            $message = "Gear updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_gear_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      if ($formVars['update'] == -3) {

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_gear_refresh\" value=\"Refresh My Gear Listing\" onClick=\"javascript:attach_gear('gear.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_gear_update\"  value=\"Update Gear\"          onClick=\"javascript:attach_gear('gear.mysql.php', 1);hideDiv('gear-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_gear_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_gear_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Active Gear Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Gear: <span id=\"r_gear_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Number: <input type=\"text\" name=\"r_gear_amount\" size=\"10\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Description: <input type=\"text\" name=\"r_gear_details\" size=\"30\"></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('gear_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


# now list all the items to select

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Gear Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('gear-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"gear-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Weapon Listing</strong>\n";
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
        $output .=   "<th class=\"ui-state-default\">Class</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Capacity</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select gear_id,gear_class,class_name,gear_name,gear_rating,";
        $q_string .= "gear_capacity,gear_avail,gear_perm,gear_cost,ver_book,gear_page ";
        $q_string .= "from gear ";
        $q_string .= "left join class on class.class_id = gear.gear_class ";
        $q_string .= "left join versions on versions.ver_id = gear.gear_book ";
        $q_string .= "where ver_active = 1 ";
        if ($formVars['gear_class'] > 0) {
          $q_string .= "and gear_class = " . $formVars['gear_class'] . " ";
        }
        $q_string .= "order by gear_name,gear_rating,gear_class,ver_version ";
        $q_gear = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_gear) > 0) {
          while ($a_gear = mysql_fetch_array($q_gear)) {

# this adds the gear_id to the r_gear_character
            $filterstart = "<a href=\"#\" onclick=\"javascript:show_file('gear.mysql.php?update=-3&r_gear_character=" . $formVars['r_gear_character'] . "&gear_class=" . $a_gear['gear_class'] . "');\">";
            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('gear.mysql.php?update=0&r_gear_character=" . $formVars['r_gear_character'] . "&r_gear_number=" . $a_gear['gear_id'] . "');\">";
            $linkend = "</a>";

            $gear_rating = return_Rating($a_gear['gear_rating']);

            $gear_capacity = return_Capacity($a_gear['gear_capacity']);

            $gear_avail = return_Avail($a_gear['gear_avail'], $a_gear['gear_perm']);

            $gear_cost = return_Cost($a_gear['gear_cost']);

            $gear_book = return_Book($a_gear['ver_book'], $a_gear['gear_page']);

            $class = return_Class($a_gear['gear_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $filterstart . $a_gear['class_name'] . $linkend . "</td>\n";
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
          $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('gear_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">My Gear</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('mygear-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"mygear-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Class</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Number</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Capacity</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $costtotal = 0;
      $q_string  = "select r_gear_id,r_gear_details,r_gear_amount,gear_id,class_name,gear_name,gear_rating,";
      $q_string .= "gear_capacity,gear_avail,gear_perm,gear_cost,ver_book,gear_page ";
      $q_string .= "from r_gear ";
      $q_string .= "left join gear on gear.gear_id = r_gear.r_gear_number ";
      $q_string .= "left join class on class.class_id = gear.gear_class ";
      $q_string .= "left join versions on versions.ver_id = gear.gear_book ";
      $q_string .= "where r_gear_character = " . $formVars['r_gear_character'] . " ";
      $q_string .= "order by gear_name,gear_rating,gear_class,ver_version ";
      $q_r_gear = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_gear) > 0) {
        while ($a_r_gear = mysql_fetch_array($q_r_gear)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:attach_gearacc(" . $a_r_gear['r_gear_id'] . ");showDiv('gear-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_gear('gear.del.php?id="  . $a_r_gear['r_gear_id'] . "');\">";
          $linkend   = "</a>";

          $gear_name = $a_r_gear['gear_name'];
          if ($a_r_gear['r_gear_details'] != '') {
            $gear_name = $a_r_gear['gear_name'] . " (" . $a_r_gear['r_gear_details'] . ")";
          }

          $gear_rating = return_Rating($a_r_gear['gear_rating']);

          $gear_capacity = return_Capacity($a_r_gear['gear_capacity']);

          $gear_avail = return_Avail($a_r_gear['gear_avail'], $a_r_gear['gear_perm']);

          $costamount = ($a_r_gear['gear_cost'] * $a_r_gear['r_gear_amount']);
          $costtotal += $costamount;
          $gear_cost = return_Cost($a_r_gear['gear_cost']);

          $gear_book = return_Book($a_r_gear['ver_book'], $a_r_gear['gear_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_gear_number']) && $formVars['r_gear_number'] == $a_r_gear['gear_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel              . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_r_gear['class_name']            . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $gear_name . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_gear['r_gear_amount']         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $gear_rating                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $gear_capacity                     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $gear_avail                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $gear_cost                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $gear_book                         . "</td>\n";
          $output .= "</tr>\n";


# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
          $q_string  = "select r_acc_id,r_acc_amount,acc_id,acc_class,acc_name,acc_rating,";
          $q_string .= "acc_essence,acc_capacity,acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
          $q_string .= "from r_accessory ";
          $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
          $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
          $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
          $q_string .= "where sub_name = \"Gear\" and r_acc_character = " . $formVars['r_gear_character'] . " and r_acc_parentid = " . $a_r_gear['r_gear_id'] . " ";
          $q_string .= "order by acc_name,acc_rating,ver_version ";
          $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_accessory) > 0) {
            while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_gearacc('gearacc.del.php?id="  . $a_r_accessory['r_acc_id'] . "');\">";
              $linkend   = "</a>";

              $acc_rating = return_Rating($a_r_accessory['acc_rating']);

              $acc_capacity = return_Capacity($a_r_accessory['acc_capacity']);

              $costtotal += $a_r_accessory['acc_cost'];

              $acc_avail = return_Avail($a_r_accessory['acc_avail'], $a_r_accessory['acc_perm']);

              $acc_cost = return_Cost($a_r_accessory['acc_cost']);

              $acc_book = return_Book($a_r_accessory['ver_book'], $a_r_accessory['acc_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_acc_number']) && $formVars['r_acc_number'] == $a_r_accessory['acc_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                             . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_accessory['acc_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                             . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_rating                          . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_capacity                        . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost                            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_book                            . "</td>\n";
              $output .= "</tr>\n";
            }
          }
        }
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";

      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No Gear added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_gear);

      print "document.getElementById('my_gear_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
