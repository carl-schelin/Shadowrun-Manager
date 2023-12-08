<?php
# Script: armor.mysql.php
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
    $package = "armor.mysql.php";
    if (isset($_GET['update'])) {
      $formVars['update'] = clean($_GET['update'], 10);
    } else {
      $formVars['update'] = -1;
    }
    if (isset($_GET['update'])) {
      $formVars['r_arm_id'] = clean($_GET['r_arm_id'], 10);
    } else {
      $formVars['r_arm_id'] = 0;
    }
    if (isset($_GET['update'])) {
      $formVars['r_arm_character'] = clean($_GET['r_arm_character'], 10);
    } else {
      $formVars['r_arm_character'] = 0;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_arm_number']  = clean($_GET['r_arm_number'],  10);
        $formVars['r_arm_details'] = clean($_GET['r_arm_details'], 60);

        if ($formVars['r_arm_number'] == '') {
          $formVars['r_arm_number'] = 1;
        }

        if ($formVars['r_arm_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_arm_character   =   " . $formVars['r_arm_character']   . "," .
            "r_arm_number      =   " . $formVars['r_arm_number']      . "," .
            "r_arm_details     = \"" . $formVars['r_arm_details']     . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_armor set r_arm_id = NULL," . $q_string;
            $message = "Armor added.";
          }

          if ($formVars['update'] == 1) {
            $query = "update r_armor set " . $q_string . " where r_arm_id = " . $formVars['r_arm_id'];
            $message = "Armor updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_arm_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

      if ($formVars['update'] == -3) {

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_arm_refresh\" value=\"Refresh My Armor Listing\" onClick=\"javascript:attach_armor('armor.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_arm_update\"  value=\"Update Armor\"          onClick=\"javascript:attach_armor('armor.mysql.php', 1);hideDiv('armor-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_arm_number\"  value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_arm_id\"      value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Active Clothing and Armor Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Armor: <span id=\"r_arm_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Details: <input type=\"text\" name=\"r_arm_details\" size=\"30\"></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('armor_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        $armor_list = array("armor", "clothing");

        foreach ($armor_list as &$armor) {

# now display the information for the types of armor.
          $output  = "<p></p>\n";
          $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
          $output .= "<tr>\n";
          $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Armor Listing</th>\n";
          $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $armor . "-listing-help');\">Help</a></th>\n";
          $output .= "</tr>\n";
          $output .= "</table>\n";

          $output .= "<div id=\"" . $armor . "-listing-help\" style=\"display: none\">\n";

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
          $output .=   "<th class=\"ui-state-default\">Name</th>\n";
          $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
          $output .=   "<th class=\"ui-state-default\">Capacity</th>\n";
          $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
          $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
          $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
          $output .= "</tr>\n";

          $q_string  = "select arm_id,arm_name,arm_rating,arm_capacity,arm_cost,arm_avail,arm_perm,ver_book,arm_page ";
          $q_string .= "from armor ";
          $q_string .= "left join class on class.class_id = armor.arm_class ";
          $q_string .= "left join versions on versions.ver_id = armor.arm_book ";
          $q_string .= "where class_name = \"" . $armor . "\" and ver_active = 1 ";
          $q_string .= "order by arm_name,arm_rating,ver_version ";
          $q_armor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_armor) > 0) {
            while ($a_armor = mysqli_fetch_array($q_armor)) {

              $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('armor.mysql.php?update=0&r_arm_character=" . $formVars['r_arm_character'] . "&r_arm_number=" . $a_armor['arm_id'] . "');\">";
              $linkend   = "</a>";

              $arm_rating = return_Rating($a_armor['arm_rating']);

              $arm_capacity = return_Capacity($a_armor['arm_capacity']);

              $arm_avail = return_Avail($a_armor['arm_avail'], $a_armor['arm_perm']);

              $arm_cost = return_Cost($a_armor['arm_cost']);

              $arm_book = return_Book($a_armor['ver_book'], $a_armor['arm_page']);

              $class = return_Class($a_armor['arm_perm']);

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_armor['arm_name'] . $linkend . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $arm_rating                     . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $arm_capacity                   . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $arm_avail                      . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $arm_cost                       . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $arm_book                       . "</td>\n";
              $output .= "</tr>\n";

            }
          } else {
            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">No Armor added.</td>\n";
            $output .= "</tr>\n";
          }
          $output .= "</table>\n";

          mysql_free_result($q_armor);

          print "document.getElementById('" . $armor . "_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Armor Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('my-armor-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"my-armor-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Capacity</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $costtotal = 0;
      $q_string  = "select r_arm_id,r_arm_details,arm_id,arm_name,arm_rating,arm_capacity,arm_avail,arm_perm,arm_cost,ver_book,arm_page ";
      $q_string .= "from r_armor ";
      $q_string .= "left join armor on armor.arm_id = r_armor.r_arm_number ";
      $q_string .= "left join versions on versions.ver_id = armor.arm_book ";
      $q_string .= "where r_arm_character = " . $formVars['r_arm_character'] . " ";
      $q_string .= "order by arm_name,arm_rating,ver_version ";
      $q_r_armor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_armor) > 0) {
        while ($a_r_armor = mysqli_fetch_array($q_r_armor)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:attach_armoracc(" . $a_r_armor['r_arm_id'] . ");showDiv('armor-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_armor('armor.del.php?id="  . $a_r_armor['r_arm_id'] . "');\">";
          $linkend   = "</a>";

          $arm_name = $a_r_armor['arm_name'];
          if ($a_r_armor['r_arm_details'] != '') {
            $arm_name = $a_r_armor['arm_name'] . " (" . $a_r_armor['r_arm_details'] . ")";
          }

          $arm_rating = return_Rating($a_r_armor['arm_rating']);

          $arm_capacity = return_Capacity($a_r_armor['arm_capacity']);

          $costtotal += $a_r_armor['arm_cost'];

          $arm_avail = return_Avail($a_r_armor['arm_avail'], $a_r_armor['arm_perm']);

          $arm_cost = return_Cost($a_r_armor['arm_cost']);

          $arm_book = return_Book($a_r_armor['ver_book'], $a_r_armor['arm_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_arm_number']) && $formVars['r_arm_number'] == $a_r_armor['arm_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel               . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $arm_name   . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $arm_rating            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $arm_capacity          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $arm_avail             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $arm_cost              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $arm_book              . "</td>\n";
          $output .= "</tr>\n";


# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_acc_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
          $q_string  = "select r_acc_id,acc_id,acc_class,acc_name,acc_rating,";
          $q_string .= "acc_capacity,acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
          $q_string .= "from r_accessory ";
          $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
          $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
          $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
          $q_string .= "where sub_name = \"Clothing and Armor\" and r_acc_character = " . $formVars['r_arm_character'] . " and r_acc_parentid = " . $a_r_armor['r_arm_id'] . " ";
          $q_string .= "order by acc_name,acc_rating,ver_version ";
          $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_accessory) > 0) {
            while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_armoracc('armoracc.del.php?id="  . $a_r_accessory['r_acc_id'] . "');\">";
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
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_accessory['acc_name'] . "</td>\n";
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
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";

      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No Armor added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_armor);

      print "document.getElementById('my_armor_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
