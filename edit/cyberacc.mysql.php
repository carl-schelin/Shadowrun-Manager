<?php
# Script: cyberacc.mysql.php
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
    $package = "cyberacc.mysql.php";
    $formVars['update']          = clean($_GET['update'],       10);
    $formVars['r_ware_id']       = clean($_GET['r_ware_id'],    10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_ware_id'] == '') {
      $formVars['r_ware_id'] = 0;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0) {
        $formVars['r_acc_character']    = clean($_GET['r_acc_character'],    10);
        $formVars['r_acc_number']       = clean($_GET['r_acc_number'],       10);

        if ($formVars['r_acc_character'] == '') {
          $formVars['r_acc_character'] = 0;
        }
        if ($formVars['r_acc_number'] == '') {
          $formVars['r_acc_number'] = 1;
        }

        if ($formVars['r_acc_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_acc_character   =   " . $formVars['r_acc_character']   . "," .
            "r_acc_number      =   " . $formVars['r_acc_number']      . "," . 
            "r_acc_parentid    =   " . $formVars['r_ware_id'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_accessory set r_acc_id = NULL," . $q_string;
            $message = "Cyberware Accessory added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_acc_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";

        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }

# Associate vehicle with a weapon
      if ($formVars['update'] == 2) {
        $formVars['r_fa_id']        = clean($_GET['r_fa_id'],        10);
        $formVars['r_fa_parentid']  = clean($_GET['r_fa_parentid'],  10);

        if ($formVars['r_fa_id'] == '') {
          $formVars['r_fa_id'] = 0;
        }
        if ($formVars['r_fa_parentid'] == '') {
          $formVars['r_fa_parentid'] = 1;
        }

        if ($formVars['r_fa_parentid'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_fa_parentid =   " . $formVars['r_fa_parentid'];

          if ($formVars['update'] == 2) {
            $query = "update r_firearms set " . $q_string . " where r_fa_id = " . $formVars['r_fa_id'];
            $message = "Cyberware Weapon added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_fa_id']);

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
      $output .= "  <th class=\"ui-state-default\">Accessories (" . $formVars['r_ware_id'] . ")</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('cyberware-accessories-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"cyberware-accessories-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
      $output .=   "<th class=\"ui-state-default\">Capacity</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

# on new load of data only
      if ($formVars['r_ware_id'] == 0) {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">Select one of your Cyberware items in order to purchase accessories.</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('cyberacc_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      } else {

# r_ware_id == the id of the cyberware owned/selected. If zero, then no cyberware has been selected and no accessories presented.
# then r_ware_number == the id of actual cyberware that was selected which gives me the class id.
# show the accessories associated with 'Cyberware' (get from accessory table (acc_type) linked to subjects)
# also where the class == the accessory class or zero for all classes
# also where the item itself is called out in the accessory table; acc_accessory.
# basically building the where clause.

# so Cyberware by default
        $where = "where sub_name = \"Cyberware\" ";

# get the purchased cyberware id
# got the number, now get the ware_class and ware_name from the cyberware table
# now get the class name from the class table
        $q_string  = "select ware_id,ware_class,ware_name,ware_capacity,r_ware_character,r_ware_number ";
        $q_string .= "from r_cyberware ";
        $q_string .= "left join cyberware on cyberware.ware_id = r_cyberware.r_ware_number ";
        $q_string .= "left join class on class.class_id = cyberware.ware_class ";
        $q_string .= "where r_ware_id = " . $formVars['r_ware_id'] . " ";
        $q_r_cyberware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        $a_r_cyberware = mysql_fetch_array($q_r_cyberware);

# for that class or something that works for all; numbers because both acc_class and ware_class are numeric. no need to convert to text
        $where .= "and (acc_class = " . $a_r_cyberware['ware_class'] . " or acc_class = 0) ";

# for that item or something that works for all
        $where .= "and (acc_accessory = \"" . $a_r_cyberware['ware_name'] . "\" or acc_accessory = \"\") ";


# this one has capacity. we have the r_arm_number so we know which accessories have been purchased.
# get a total of all acc_capacity and subtract it from arm_capacity. if the total is less than 0, don't display it.

        $totalcapacity = 0;
        $q_string  = "select acc_capacity ";
        $q_string .= "from r_accessory ";
        $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
        $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
        $q_string .= "where sub_name = \"Cyberware\" and r_acc_character = " . $a_r_cyberware['r_ware_character'] . " and r_acc_parentid = " . $formVars['r_ware_id'] . " ";
        $q_string .= "order by acc_name,acc_rating ";
        $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_r_accessory) > 0) {
          while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {
           $totalcapacity += $a_r_accessory['acc_capacity'];
          }
        }


# this should give me the total used capacity of all accessories for this piece of armor
        $availablecapacity = -1 * abs($a_r_cyberware['ware_capacity'] + $totalcapacity);
# now I have a negative available capacity. just return any accessories that have an equal or greater value.
        $where .= "and acc_capacity >= " . $availablecapacity . " ";


# now display the available accessories:
        $q_string  = "select acc_id,acc_name,acc_essence,acc_rating,acc_capacity,acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
        $q_string .= "from accessory ";
        $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
        $q_string .= "left join class on class.class_id = accessory.acc_class ";
        $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
        $q_string .= $where . " and ver_active = 1 ";
        $q_string .= "order by acc_name,acc_rating,ver_version ";
        $q_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_accessory) > 0) {
          while ($a_accessory = mysql_fetch_array($q_accessory)) {

            $linkstart  = "<a href=\"#\" onclick=\"javascript:show_file('cyberacc.mysql.php";
            $linkstart .= "?update=0";
            $linkstart .= "&r_ware_id="          . $formVars['r_ware_id'];
            $linkstart .= "&r_acc_character="    . $a_r_cyberware['r_ware_character'];
            $linkstart .= "&r_acc_number="       . $a_accessory['acc_id'];
            $linkstart .= "');";
            $linkstart .= "show_file('cyberware.mysql.php";
            $linkstart .= "?update=-1";
            $linkstart .= "&r_ware_character=" . $a_r_cyberware['r_ware_character'];
            $linkstart .= "');\">";

            $linkend   = "</a>";

            $acc_rating = return_Rating($a_accessory['acc_rating']);

            $acc_essence = return_Essence($a_accessory['acc_essence']);
     
            $acc_capacity = return_Capacity($a_accessory['acc_capacity']);
              
            $acc_avail = return_Avail($a_accessory['acc_avail'], $a_accessory['acc_perm']);

            $acc_cost = return_Cost($a_accessory['acc_cost']);

            $acc_book = return_Book($a_accessory['ver_book'], $a_accessory['acc_page']);

            $class = return_Class($a_accessory['acc_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_accessory['acc_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_rating                                      . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_essence                                     . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_capacity                                    . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail                                       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost                                        . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_book                                        . "</td>\n";
            $output .= "</tr>\n";

          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">There are no appropriate Accessories for this item.</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "</table>\n";

        mysql_free_result($q_accessory);


# now display the available weapons
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Weapons</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('cyberacc-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Class</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
        $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
        $output .=   "<th class=\"ui-state-default\">AP</th>\n";
        $output .=   "<th class=\"ui-state-default\">Mode</th>\n";
        $output .=   "<th class=\"ui-state-default\">RC</th>\n";
        $output .=   "<th class=\"ui-state-default\">Ammo</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select r_fa_id,fa_id,class_name,fa_name,fa_acc,fa_damage,fa_type,fa_flag,";
        $q_string .= "fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_rc,fa_fullrc,fa_ammo1,fa_clip1,fa_ammo2,";
        $q_string .= "fa_clip2,fa_avail,fa_perm,fa_cost,ver_book,fa_page ";
        $q_string .= "from r_firearms ";
        $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
        $q_string .= "left join class on class.class_id = firearms.fa_class ";
        $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
        $q_string .= "where r_fa_character = " . $a_r_cyberware['r_ware_character'] . " and r_fa_parentid = 0 ";
        $q_string .= "order by fa_name,class_name ";
        $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_r_firearms) > 0) {
          while ($a_r_firearms = mysql_fetch_array($q_r_firearms)) {

            $linkstart  = "<a href=\"#\" onclick=\"javascript:show_file('cyberacc.mysql.php";
            $linkstart .= "?update=2";
            $linkstart .= "&r_ware_id="      . $formVars['r_ware_id'];
            $linkstart .= "&r_fa_id="       . $a_r_firearms['r_fa_id'];
            $linkstart .= "&r_fa_parentid=" . $formVars['r_ware_id'];
            $linkstart .= "');";
            $linkstart .= "show_file('cyberware.mysql.php";
            $linkstart .= "?update=-1";
            $linkstart .= "&r_ware_character=" . $a_r_cyberware['r_ware_character'];
            $linkstart .= "');\">";

            $linkend   = "</a>";

            $fa_mode = return_Mode($a_r_firearms['fa_mode1'], $a_r_firearms['fa_mode2'], $a_r_firearms['fa_mode3']);

            $fa_damage = return_Damage($a_r_firearms['fa_damage'], $a_r_firearms['fa_type'], $a_r_firearms['fa_flag']);

            $fa_rc = return_Recoil($a_r_firearms['fa_rc'], $a_r_firearms['fa_fullrc']);

            $fa_ap = return_Penetrate($a_r_firearms['fa_ap']);

            $fa_ammo = return_Ammo($a_r_firearms['fa_ammo1'], $a_r_firearms['fa_clip1'], $a_r_firearms['fa_ammo2'], $a_r_firearms['fa_clip2']);

            $fa_avail = return_Avail($a_r_firearms['fa_avail'], $a_r_firearms['fa_perm']);

            $fa_book = return_Book($a_r_firearms['ver_book'], $a_r_firearms['fa_page']);

            $class = return_Class($a_r_firearms['fa_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $a_r_firearms['class_name']                      . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_firearms['fa_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $a_r_firearms['fa_acc']                          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $fa_damage                                       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $fa_ap                                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $fa_mode                                         . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $fa_rc                                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $fa_ammo                                         . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $fa_avail                                        . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $fa_book                                         . "</td>\n";
            $output .= "</tr>\n";


# associate any ammo with the weapon
            $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,class_name,ammo_name,ammo_rounds,ammo_mod,ammo_ap,";
            $q_string .= "ammo_avail,ammo_perm,ver_book,ammo_page ";
            $q_string .= "from r_ammo ";
            $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
            $q_string .= "left join class on class.class_id = ammo.ammo_class ";
            $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
            $q_string .= "where r_ammo_character = " . $a_r_cyberware['r_ware_character'] . " and r_ammo_parentid = " . $a_r_firearms['r_fa_id'] . " ";
            $q_string .= "order by ammo_name,class_name ";
            $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_r_ammo) > 0) {
              while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

                $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

                $ammo_avail = return_Avail($a_r_ammo['ammo_avail'], $a_r_ammo['ammo_perm']);

                $ammo_book = return_Book($a_r_ammo['ver_book'], $a_r_ammo['ammo_page']);

                $class = return_Class($a_r_ammo['ammo_perm']);

                $output .= "<tr>\n";
                $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                        . "</td>\n";
                $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
                $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                        . "</td>\n";
                $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                        . "</td>\n";
                $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                        . "</td>\n";
                $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                        . "</td>\n";
                $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                        . "</td>\n";
                $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                        . "</td>\n";
                $output .= "  <td class=\"" . $class . " delete\">" . $ammo_avail                                                 . "</td>\n";
                $output .= "  <td class=\"" . $class . " delete\">" . $ammo_book                                                  . "</td>\n";
                $output .= "</tr>\n";
              }
            }

          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">You need to purchase Weapons in order to associate it with cyberware.</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "</table>\n";




        print "document.getElementById('cyberacc_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      }
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
