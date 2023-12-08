<?php
# Script: fireacc.mysql.php
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
    $package = "fireacc.mysql.php";
    $formVars['update']        = clean($_GET['update'],     10);
    $formVars['r_fa_id']       = clean($_GET['r_fa_id'],    10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_fa_id'] == '') {
      $formVars['r_fa_id'] = 0;
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
            "r_acc_parentid    =   " . $formVars['r_fa_id'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_accessory set r_acc_id = NULL," . $q_string;
            $message = "Firearm Accessory added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_acc_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";

        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }

# Associate ammo with a weapon
      if ($formVars['update'] == 2) {
        $formVars['r_ammo_id']        = clean($_GET['r_ammo_id'],        10);
        $formVars['r_ammo_parentid']  = clean($_GET['r_ammo_parentid'],  10);

        if ($formVars['r_ammo_id'] == '') {
          $formVars['r_ammo_id'] = 0;
        }
        if ($formVars['r_ammo_parentid'] == '') {
          $formVars['r_ammo_parentid'] = 1;
        }

        if ($formVars['r_ammo_parentid'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_ammo_parentid =   " . $formVars['r_ammo_parentid'];

          if ($formVars['update'] == 2) {
            $query = "update r_ammo set " . $q_string . " where r_ammo_id = " . $formVars['r_ammo_id'];
            $message = "Firearm Ammunition added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_ammo_id']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";

        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Accessories (" . $formVars['r_fa_id'] . ")</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('firearms-accessories-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"firearms-accessories-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Mount</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

# on new load of data only
      if ($formVars['r_fa_id'] == 0) {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">Select one Firearm in order to purchase accessories.</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('fireacc_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      } else {

# r_fire_id == the id of the firearm owned/selected. If zero, then no firearm has been selected and no accessories presented.
# then r_fire_number == the id of actual firearm that was selected which gives me the class id.
# show the accessories associated with 'Firearms' (get from accessory table (acc_type) linked to subjects)
# also where the class == the accessory class or zero for all classes
# also where the item itself is called out in the accessory table; acc_accessory.
# basically building the where clause.

# so Firearms by default
        $where = "where sub_name = \"Firearms\" ";

# get the purchased firearm id
# got the number, now get the fa_class and gear_name from the gear table
# now get the class name from the class table
        $q_string  = "select fa_class,fa_name,fa_cost,r_fa_character ";
        $q_string .= "from r_firearms ";
        $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
        $q_string .= "where r_fa_id = " . $formVars['r_fa_id'] . " ";
        $q_r_firearms = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        $a_r_firearms = mysqli_fetch_array($q_r_firearms);

# for that class or something that works for all; numbers because both acc_class and fa_class are numeric. no need to convert to text
        $where .= "and (acc_class = " . $a_r_firearms['fa_class'] . " or acc_class = 0) ";

# for that item or something that works for all
        $where .= "and (acc_accessory = \"" . $a_r_firearms['fa_name'] . "\" or acc_accessory = \"\") ";

        $q_string  = "select acc_id,acc_name,acc_mount,acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
        $q_string .= "from accessory ";
        $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
        $q_string .= "left join class on class.class_id = accessory.acc_class ";
        $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
        $q_string .= $where . " and ver_active = 1 ";
        $q_string .= "order by acc_name,acc_rating,ver_version ";
        $q_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_accessory) > 0) {
          while ($a_accessory = mysqli_fetch_array($q_accessory)) {

            $linkstart  = "<a href=\"#\" onclick=\"javascript:show_file('fireacc.mysql.php";
            $linkstart .= "?update=0";
            $linkstart .= "&r_fa_id="         . $formVars['r_fa_id'];
            $linkstart .= "&r_acc_character=" . $a_r_firearms['r_fa_character'];
            $linkstart .= "&r_acc_number="    . $a_accessory['acc_id'];
            $linkstart .= "');";
            $linkstart .= "show_file('firearms.mysql.php";
            $linkstart .= "?update=-1";
            $linkstart .= "&r_fa_character=" . $a_r_firearms['r_fa_character'];
            $linkstart .= "');\">";
 
            $linkend   = "</a>";

            $acc_mount = $a_accessory['acc_mount'];

            $acc_avail = return_Avail($a_accessory['acc_avail'], $a_accessory['acc_perm']);

            $acc_cost = return_Cost($a_accessory['acc_cost']);
#            if ($a_accessory['acc_cost'] == -1) {
#              $acc_cost = return_Cost($a_r_firearms['fa_cost']);
#            }

            $acc_book = return_Book($a_accessory['ver_book'], $a_accessory['acc_page']);

            $class = return_Class($a_accessory['acc_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_accessory['acc_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_mount                                       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail                                       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost                                        . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_book                                        . "</td>\n";
            $output .= "</tr>\n";

          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">There are no appropriate Accessories for this item.</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "</table>\n";

        mysql_free_result($q_accessory);

# now display the available ammo
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Ammunition</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('ammoacc-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Class</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rounds</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Damage Modifier</th>\n";
        $output .=   "<th class=\"ui-state-default\">AP Modifier</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,class_name,ammo_name,ammo_rounds,ammo_rating,ammo_mod,ammo_ap,";
        $q_string .= "ammo_avail,ammo_perm,ver_book,ammo_page ";
        $q_string .= "from r_ammo ";
        $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
        $q_string .= "left join class on class.class_id = ammo.ammo_class ";
        $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
        $q_string .= "where r_ammo_character = " . $a_r_firearms['r_fa_character'] . " and r_ammo_parentid = 0 ";
        $q_string .= "order by ammo_name,class_name ";
        $q_r_ammo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_r_ammo) > 0) {
          while ($a_r_ammo = mysqli_fetch_array($q_r_ammo)) {

            $linkstart  = "<a href=\"#\" onclick=\"javascript:show_file('fireacc.mysql.php";
            $linkstart .= "?update=2";
            $linkstart .= "&r_fa_id="         . $formVars['r_fa_id'];
            $linkstart .= "&r_ammo_id="       . $a_r_ammo['r_ammo_id'];
            $linkstart .= "&r_ammo_parentid=" . $formVars['r_fa_id'];
            $linkstart .= "');";
            $linkstart .= "show_file('firearms.mysql.php";
            $linkstart .= "?update=-1";
            $linkstart .= "&r_fa_character=" . $a_r_firearms['r_fa_character'];
            $linkstart .= "');\">";
            $linkend   = "</a>";

            $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

            $ammo_rating = return_Rating($a_r_ammo['ammo_rating']);

            $ammo_avail = return_Avail($a_r_ammo['ammo_avail'], $a_r_ammo['ammo_perm']);

            $costgrade = ($a_r_ammo['ammo_cost'] * $a_r_ammo['r_ammo_rounds']);
            $costtotal += $costgrade;

            $ammo_book = return_Book($a_r_ammo['ver_book'], $a_r_ammo['ammo_page']);

            $class = return_Class($a_r_ammo['ammo_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $a_r_ammo['class_name']                                     . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_ammo['ammo_name']              . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds'])     . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $ammo_rating                                                . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $a_r_ammo['ammo_mod']                                       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $ammo_ap                                                    . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $ammo_avail                                                 . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $ammo_book                                                  . "</td>\n";
            $output .= "</tr>\n";

          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">You need to purchase Ammunition in order to associate it with a weapon.</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "</table>\n";

        mysql_free_result($q_r_ammo);

        print "document.getElementById('fireacc_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      }
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
