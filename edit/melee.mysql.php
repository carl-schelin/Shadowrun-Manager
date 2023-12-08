<?php
# Script: melee.mysql.php
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
    $package = "melee.mysql.php";
    $formVars['update']                = clean($_GET['update'],                 10);
    $formVars['r_melee_character']     = clean($_GET['r_melee_character'],      10);
    $formVars['melee_class']           = clean($_GET['melee_class'],            10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_melee_character'] == '') {
      $formVars['r_melee_character'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0) {
        $formVars['r_melee_number'] = clean($_GET['r_melee_number'], 10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_melee_number'] == '') {
          $formVars['r_melee_number'] = 1;
        }

        if ($formVars['r_melee_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_melee_character   =   " . $formVars['r_melee_character']   . "," .
            "r_melee_number      =   " . $formVars['r_melee_number'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_melee set r_melee_id = NULL," . $q_string;
            $message = "Melee Weapon added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_melee_number']);

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
        $output .= "<input type=\"button\" name=\"r_melee_refresh\" value=\"Refresh My Melee Weapon Listing\" onClick=\"javascript:attach_melee('melee.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_melee_update\"  value=\"Update Melee Weapon\"          onClick=\"javascript:attach_melee('melee.mysql.php', 1);hideDiv('melee-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_melee_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_melee_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Active Melee Weapons Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Melee Weapon: <span id=\"r_melee_item\">None Selected</span></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('melee_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Blades and Clubs</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('melee-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"melee-listing-help\" style=\"display: none\">\n";

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
        $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
        $output .=   "<th class=\"ui-state-default\">Reach</th>\n";
        $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
        $output .=   "<th class=\"ui-state-default\">AP</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select melee_id,melee_class,class_name,melee_name,melee_acc,melee_reach,";
        $q_string .= "melee_damage,melee_type,melee_flag,melee_strength,melee_ap,melee_avail,";
        $q_string .= "melee_perm,melee_cost,ver_book,melee_page ";
        $q_string .= "from melee ";
        $q_string .= "left join class on class.class_id = melee.melee_class ";
        $q_string .= "left join versions on versions.ver_id = melee.melee_book ";
        $q_string .= "where ver_active = 1 ";
        if ($formVars['melee_class'] > 0) {
          $q_string .= "and melee_class = " . $formVars['melee_class'] . " ";
        }
        $q_string .= "order by melee_name,melee_class ";
        $q_melee = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_melee) > 0) {
          while ($a_melee = mysqli_fetch_array($q_melee)) {

# this adds the melee_id to the r_melee_character
            $filterstart = "<a href=\"#\" onclick=\"javascript:show_file('melee.mysql.php?update=-3&r_melee_character=" . $formVars['r_melee_character'] . "&melee_class=" . $a_melee['melee_class'] . "');\">";
            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('melee.mysql.php?update=0&r_melee_character=" . $formVars['r_melee_character'] . "&r_melee_number=" . $a_melee['melee_id'] . "');\">";
            $linkend = "</a>";

            $melee_reach = '--';
            if ($a_melee['melee_reach'] > 0) {
              $melee_reach = $a_melee['melee_reach'];
            }

# melee title is the (str + 1) stuff.
# melee damage is the actual score based on your stats
            $melee_title = "";
            $melee_damage = "";
            if ($a_melee['melee_strength']) {
              $melee_title = "(STR + " . $a_melee['melee_damage'] . ")";

              $q_string  = "select runr_strength ";
              $q_string .= "from runners ";
              $q_string .= "where runr_id = " . $formVars['r_melee_character'] . " ";
              $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
              $a_runners = mysqli_fetch_array($q_runners);

              $melee_damage = ($a_runners['runr_strength'] + $a_melee['melee_damage']);
            } else {
              if ($a_melee['melee_damage'] != 0) {
                $melee_damage = $a_melee['melee_damage'];
              }
            }

            if (strlen($a_melee['melee_type']) > 0) {
              $melee_damage .= $a_melee['melee_type'];
            }
            if (strlen($a_melee['melee_flag']) > 0) {
              $melee_damage .= "(" . $a_melee['melee_flag'] . ")";
            }

            $melee_ap = return_Penetrate($a_melee['melee_ap']);

            $melee_avail = return_Avail($a_melee['melee_avail'], $a_melee['melee_perm']);

            $melee_cost = return_Cost($a_melee['melee_cost']);

            $melee_book = return_Book($a_melee['ver_book'], $a_melee['melee_page']);

            $class = return_Class($a_melee['melee_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $filterstart . $a_melee['class_name']         . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_melee['melee_name']          . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_melee['melee_acc']                      . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $melee_reach                               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\" title=\"" . $melee_title . "\">" . $melee_damage            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $melee_ap                                  . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $melee_avail                               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $melee_cost                                . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $melee_book                                . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('melee_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">My Melee Weapons</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('melee-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"melee-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
      $output .=   "<th class=\"ui-state-default\">Reach</th>\n";
      $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
      $output .=   "<th class=\"ui-state-default\">AP</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $costtotal = 0;
      $q_string  = "select r_melee_id,melee_id,melee_class,class_name,melee_name,melee_acc,melee_reach,";
      $q_string .= "melee_damage,melee_type,melee_flag,melee_strength,melee_ap,melee_avail,";
      $q_string .= "melee_perm,melee_cost,ver_book,melee_page ";
      $q_string .= "from r_melee ";
      $q_string .= "left join melee on melee.melee_id = r_melee.r_melee_number ";
      $q_string .= "left join class on class.class_id = melee.melee_class ";
      $q_string .= "left join versions on versions.ver_id = melee.melee_book ";
      $q_string .= "where r_melee_character = " . $formVars['r_melee_character'] . " ";
      $q_string .= "order by melee_class,melee_name ";
      $q_r_melee = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_melee) > 0) {
        while ($a_r_melee = mysqli_fetch_array($q_r_melee)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:attach_melacc(" . $a_r_melee['r_melee_id'] . ");showDiv('melee-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_melee('melee.del.php?id="  . $a_r_melee['r_melee_id'] . "');\">";
          $linkend   = "</a>";

          $melee_reach = '--';
          if ($a_r_melee['melee_reach'] > 0) {
            $melee_reach = $a_r_melee['melee_reach'];
          }

# melee title is the (str + 1) stuff.
# melee damage is the actual score based on your stats
          $melee_title = "";
          $melee_damage = "";
          if ($a_r_melee['melee_strength']) {
            $melee_title = "(STR + " . $a_r_melee['melee_damage'] . ")";

            $q_string  = "select runr_strength ";
            $q_string .= "from runners ";
            $q_string .= "where runr_id = " . $formVars['r_melee_character'] . " ";
            $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            $a_runners = mysqli_fetch_array($q_runners);

            $melee_damage = ($a_runners['runr_strength'] + $a_r_melee['melee_damage']);
          } else {
            if ($a_r_melee['melee_damage'] != 0) {
              $melee_damage = $a_r_melee['melee_damage'];
            }
          }

          if (strlen($a_r_melee['melee_type']) > 0) {
            $melee_damage .= $a_r_melee['melee_type'];
          }
          if (strlen($a_r_melee['melee_flag']) > 0) {
            $melee_damage .= "(" . $a_r_melee['melee_flag'] . ")";
          }

          $melee_ap = return_Penetrate($a_r_melee['melee_ap']);

          $costtotal += $a_r_melee['melee_cost'];

          $melee_avail = return_Avail($a_r_melee['melee_avail'], $a_r_melee['melee_perm']);

          $melee_cost = return_Cost($a_r_melee['melee_cost']);

          $melee_book = return_Book($a_r_melee['ver_book'], $a_r_melee['melee_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_melee_number']) && $formVars['r_melee_number'] == $a_r_melee['melee_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_r_melee['class_name']                     . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_melee['melee_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_melee['melee_acc']                      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_reach                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" title=\"" . $melee_title . "\">" . $melee_damage . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_ap                                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_avail                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_cost                                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $melee_book                                  . "</td>\n";
          $output .= "</tr>\n";


# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
          $q_string  = "select r_acc_id,acc_id,acc_name,acc_mount,";
          $q_string .= "acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
          $q_string .= "from r_accessory ";
          $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
          $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
          $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
          $q_string .= "where sub_name = \"Melee\" and r_acc_character = " . $formVars['r_melee_character'] . " and r_acc_parentid = " . $a_r_melee['r_melee_id'] . " ";
          $q_string .= "order by acc_name,acc_rating,ver_version ";
          $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_accessory) > 0) {
            while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_melacc('melacc.del.php?id="  . $a_r_accessory['r_acc_id'] . "');\">";
              $linkend   = "</a>";

# if the cost is '-1' then the cost is the cost of the weapon.
              if ($a_r_accessory['acc_cost'] == -1) {
                $a_r_accessory['acc_cost'] = $a_r_firearms['fa_cost'];
              }
              $costtotal += $a_r_accessory['acc_cost'];

              $acc_avail = return_Avail($a_r_accessory['acc_avail'], $a_r_accessory['acc_perm']);

              $acc_cost = return_Cost($a_r_accessory['acc_cost']);

              $acc_book = return_Book($a_r_accessory['ver_book'], $a_r_accessory['acc_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_acc_number']) && $formVars['r_acc_number'] == $a_r_accessory['acc_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_accessory['acc_name']   . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                  . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                  . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                  . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                  . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost             . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_book             . "</td>\n";
              $output .= "</tr>\n";
            }
          }
        }
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";

      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">No Melee Weapons added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_melee);

      print "document.getElementById('my_melee_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
