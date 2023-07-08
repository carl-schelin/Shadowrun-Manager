<?php
# Script: firearms.mysql.php
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
    $package = "firearms.mysql.php";
    $formVars['update']             = clean($_GET['update'],              10);
    $formVars['r_fa_character']     = clean($_GET['r_fa_character'],      10);
    $formVars['fa_class']           = clean($_GET['fa_class'],            10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_fa_character'] == '') {
      $formVars['r_fa_character'] = -1;
    }
    if ($formVars['fa_class'] == '') {
      $formVars['fa_class'] = 0;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0) {
        $formVars['r_fa_number']      = clean($_GET['r_fa_number'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_fa_number'] == '') {
          $formVars['r_fa_number'] = 1;
        }

        if ($formVars['r_fa_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_fa_character   =   " . $formVars['r_fa_character']   . "," .
            "r_fa_number      =   " . $formVars['r_fa_number'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_firearms set r_fa_id = NULL," . $q_string;
            $message = "Firearm added.";
          }

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

# here let's go through any firearms accessories and anything with a -1 is added automatically

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_fa_number']);

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -2) {
        $formVars['copyfrom'] = clean($_GET['r_fa_copyfrom'], 10);

        if ($formVars['copyfrom'] > 0) {
          $q_string  = "select r_fa_number ";
          $q_string .= "from r_firearms ";
          $q_string .= "where r_fa_character = " . $formVars['copyfrom'];
          $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          while ($a_r_firearms = mysql_fetch_array($q_r_firearms)) {

            $q_string =
              "r_fa_character     =   " . $formVars['r_fa_character']   . "," .
              "r_fa_number        =   " . $a_r_firearms['r_fa_number'];
  
            $query = "insert into r_firearms set r_fa_id = NULL, " . $q_string;
            mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
          }
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      if ($formVars['update'] == -3) {

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_fa_refresh\" value=\"Refresh My Firearm Listing\" onClick=\"javascript:attach_firearms('firearms.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_fa_update\"  value=\"Update Firearm\"          onClick=\"javascript:attach_firearms('firearms.mysql.php', 1);hideDiv('firearms-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_fa_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_fa_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Active Weapons Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Weapon: <span id=\"r_fa_item\">None Selected</span></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('firearms_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Firearms</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('firearm-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"firearm-listing-help\" style=\"display: none\">\n";

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
        $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
        $output .=   "<th class=\"ui-state-default\">AP</th>\n";
        $output .=   "<th class=\"ui-state-default\">Mode</th>\n";
        $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
        $output .=   "<th class=\"ui-state-default\">RC</th>\n";
        $output .=   "<th class=\"ui-state-default\">Ammo</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select fa_id,class_name,fa_class,fa_name,fa_acc,fa_damage,fa_type,fa_flag,";
        $q_string .= "fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_rc,fa_fullrc,fa_ammo1,";
        $q_string .= "fa_ar1,fa_ar2,fa_ar3,fa_ar4,fa_ar5,";
        $q_string .= "fa_clip1,fa_ammo2,fa_clip2,fa_avail,fa_perm,fa_cost,ver_book,fa_page ";
        $q_string .= "from firearms ";
        $q_string .= "left join class on class.class_id = firearms.fa_class ";
        $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
        $q_string .= "where ver_active = 1 ";
        if ($formVars['fa_class'] > 0) {
          $q_string .= "and fa_class = " . $formVars['fa_class'] . " ";
        }
        $q_string .= "order by fa_name,fa_class ";
        $q_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_firearms) > 0) {
          while ($a_firearms = mysql_fetch_array($q_firearms)) {

# this adds the fa_id to the r_fa_character
            $filterstart = "<a href=\"#\" onclick=\"javascript:show_file('firearms.mysql.php?update=-3&r_fa_character=" . $formVars['r_fa_character'] . "&fa_class=" . $a_firearms['fa_class'] . "');\">";
            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('firearms.mysql.php?update=0&r_fa_character=" . $formVars['r_fa_character'] . "&r_fa_number=" . $a_firearms['fa_id'] . "');\">";
            $linkend = "</a>";

            $fa_mode = return_Mode($a_firearms['fa_mode1'], $a_firearms['fa_mode2'], $a_firearms['fa_mode3']);

            $fa_damage = return_Damage($a_firearms['fa_damage'], $a_firearms['fa_type'], $a_firearms['fa_flag']);

            $fa_rc = return_Recoil($a_firearms['fa_rc'], $a_firearms['fa_fullrc']);

            $fa_ap = return_Penetrate($a_firearms['fa_ap']);

            $fa_attack = return_Attack($a_firearms['fa_ar1'], $a_firearms['fa_ar2'], $a_firearms['fa_ar3'], $a_firearms['fa_ar4'], $a_firearms['fa_ar5']);

            $fa_ammo = return_Ammo($a_firearms['fa_ammo1'], $a_firearms['fa_clip1'], $a_firearms['fa_ammo2'], $a_firearms['fa_clip2']);

            $fa_avail = return_Avail($a_firearms['fa_avail'], $a_firearms['fa_perm']);

            $fa_cost = return_Cost($a_firearms['fa_cost']);

            $fa_book = return_Book($a_firearms['ver_book'], $a_firearms['fa_page']);

            $class = return_Class($a_firearms['fa_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $filterstart . $a_firearms['class_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart   . $a_firearms['fa_name']    . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                . $a_firearms['fa_acc']                . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                . $fa_damage                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                . $fa_ap                               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                . $fa_mode                             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                . $fa_attack                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                . $fa_rc                               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                . $fa_ammo                             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                . $fa_avail                            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                . $fa_cost                             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                . $fa_book                             . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('arms_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">My Firearms</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('firearms-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"firearms-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
      $output .=   "<th class=\"ui-state-default\">AP</th>\n";
      $output .=   "<th class=\"ui-state-default\">Mode</th>\n";
      $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
      $output .=   "<th class=\"ui-state-default\">RC</th>\n";
      $output .=   "<th class=\"ui-state-default\">Ammo</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $costtotal = 0;
      $q_string  = "select r_fa_id,fa_id,class_name,fa_name,fa_acc,fa_damage,fa_type,fa_flag,";
      $q_string .= "fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_rc,fa_fullrc,fa_ammo1,";
      $q_string .= "fa_ar1,fa_ar2,fa_ar3,fa_ar4,fa_ar5,";
      $q_string .= "fa_clip1,fa_ammo2,fa_clip2,fa_avail,fa_perm,fa_cost,ver_book,fa_page ";
      $q_string .= "from r_firearms ";
      $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
      $q_string .= "left join class on class.class_id = firearms.fa_class ";
      $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
      $q_string .= "where r_fa_character = " . $formVars['r_fa_character'] . " and r_fa_faid = 0 ";
      $q_string .= "order by fa_name,fa_class ";
      $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_firearms) > 0) {
        while ($a_r_firearms = mysql_fetch_array($q_r_firearms)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:attach_fireacc(" . $a_r_firearms['r_fa_id'] . ");showDiv('firearms-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_firearms('firearms.del.php?id="  . $a_r_firearms['r_fa_id'] . "');\">";
          $linkend   = "</a>";

          $fa_mode = return_Mode($a_r_firearms['fa_mode1'], $a_r_firearms['fa_mode2'], $a_r_firearms['fa_mode3']);

          $fa_attack = return_Attack($a_r_firearms['fa_ar1'], $a_r_firearms['fa_ar2'], $a_r_firearms['fa_ar3'], $a_r_firearms['fa_ar4'], $a_r_firearms['fa_ar5']);

          $fa_damage = return_Damage($a_r_firearms['fa_damage'], $a_r_firearms['fa_type'], $a_r_firearms['fa_flag']);

          $fa_rc = return_Recoil($a_r_firearms['fa_rc'], $a_r_firearms['fa_fullrc']);

          $fa_ap = return_Penetrate($a_r_firearms['fa_ap']);

          $fa_ammo = return_Ammo($a_r_firearms['fa_ammo1'], $a_r_firearms['fa_clip1'], $a_r_firearms['fa_ammo2'], $a_r_firearms['fa_clip2']);

          $costtotal += $a_r_firearms['fa_cost'];

          $fa_avail = return_Avail($a_r_firearms['fa_avail'], $a_r_firearms['fa_perm']);

          $fa_cost = return_Cost($a_r_firearms['fa_cost']);

          $fa_book = return_Book($a_r_firearms['ver_book'], $a_r_firearms['fa_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_fa_number']) && $formVars['r_fa_number'] == $a_r_firearms['fa_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                            . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_r_firearms['class_name']                      . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_firearms['fa_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_firearms['fa_acc']                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_damage                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_ap                                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_mode                                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_attack                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_rc                                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_ammo                                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_avail                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_cost                                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $fa_book                                         . "</td>\n";
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
          $q_string .= "where sub_name = \"Firearms\" and r_acc_character = " . $formVars['r_fa_character'] . " and r_acc_parentid = " . $a_r_firearms['r_fa_id'] . " ";
          $q_string .= "order by acc_name,acc_rating,ver_version ";
          $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_accessory) > 0) {
            while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_fireacc('fireacc.del.php?id="  . $a_r_accessory['r_acc_id'] . "');\">";
              $linkend   = "</a>";

              $acc_name = $a_r_accessory['acc_name'];
              if ($a_r_accessory['acc_mount'] != '') {
                $acc_name = $a_r_accessory['acc_name'] . " (" . $a_r_accessory['acc_mount'] . ")";
              }

              $acc_cost = return_Cost($a_r_accessory['acc_cost']);

# if the cost is '-1' then the cost is included
              if ($a_r_accessory['acc_cost'] == -1) {
                $a_r_accessory['acc_cost'] = 0;
              }
              $costtotal += $a_r_accessory['acc_cost'];

              $acc_avail = return_Avail($a_r_accessory['acc_avail'], $a_r_accessory['acc_perm']);

              $acc_book = return_Book($a_r_accessory['ver_book'], $a_r_accessory['acc_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_acc_number']) && $formVars['r_acc_number'] == $a_r_accessory['acc_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $acc_name   . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                  . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                  . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                  . "</td>\n";
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


# associate any ammo with the weapon
          $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,class_name,ammo_name,ammo_rounds,ammo_mod,ammo_ap,";
          $q_string .= "ammo_avail,ammo_perm,ver_book,ammo_page ";
          $q_string .= "from r_ammo ";
          $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
          $q_string .= "left join class on class.class_id = ammo.ammo_class ";
          $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
          $q_string .= "where r_ammo_character = " . $formVars['r_fa_character'] . " and r_ammo_parentid = " . $a_r_firearms['r_fa_id'] . " ";
          $q_string .= "order by ammo_name,class_name ";
          $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_ammo) > 0) {
            while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_fireammo('fireammo.del.php?id="  . $a_r_ammo['r_ammo_id'] . "');\">";

              $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

              $ammo_avail = return_Avail($a_r_ammo['ammo_avail'], $a_r_ammo['ammo_perm']);

              $ammo_book = return_Book($a_r_ammo['ver_book'], $a_r_ammo['ammo_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_ammo_number']) && $formVars['r_ammo_number'] == $a_r_ammo['ammo_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                                   . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                    . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $a_r_ammo['ammo_mod']                                   . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $ammo_ap                                                . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                    . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                    . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                    . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $ammo_avail                                             . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                    . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $ammo_book                                              . "</td>\n";
              $output .= "</tr>\n";

            }
          }
        }
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"13\">Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"13\">No Firearms added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_firearms);

      print "document.getElementById('my_firearms_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
