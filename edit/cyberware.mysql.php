<?php
# Script: cyberware.mysql.php
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
    $package = "cyberware.mysql.php";
    $formVars['update']               = clean($_GET['update'],                10);
    $formVars['r_ware_id']            = clean($_GET['id'],                    10);
    $formVars['r_ware_character']     = clean($_GET['r_ware_character'],      10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_ware_character'] == '') {
      $formVars['r_ware_character'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_ware_number']      = clean($_GET['r_ware_number'],       10);
        $formVars['r_ware_specialize']  = clean($_GET['r_ware_specialize'],   60);
        $formVars['r_ware_grade']       = clean($_GET['r_ware_grade'],        10);

        if ($formVars['r_ware_number'] == '') {
          $formVars['r_ware_number'] = 1;
        }
# default is Standard; id == 1
        if ($formVars['r_ware_grade'] == '') {
          $formVars['r_ware_grade'] = 1;
        }

        if ($formVars['r_ware_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_ware_character   =   " . $formVars['r_ware_character']   . "," .
            "r_ware_number      =   " . $formVars['r_ware_number']      . "," .
            "r_ware_grade       =   " . $formVars['r_ware_grade']       . "," .
            "r_ware_specialize  = \"" . $formVars['r_ware_specialize']  . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_cyberware set r_ware_id = NULL," . $q_string;
            $message = "Cyberware added.";
          }

          if ($formVars['update'] == 1) {
            $query = "update r_cyberware set " . $q_string . " where r_ware_id = " . $formVars['r_ware_id'];
            $message = "Cyberware updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_ware_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_ware_refresh\" value=\"Refresh My Cyberware Listing\" onClick=\"javascript:attach_cyberware('cyberware.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_ware_update\"  value=\"Update Cyberware\"          onClick=\"javascript:attach_cyberware('cyberware.mysql.php', 1);hideDiv('cyberware-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_ware_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_ware_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Active Cyberware Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Cyberware: <span id=\"r_ware_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Description: <input name=\"r_ware_specialize\" size=\"30\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Grade: <select name=\"r_ware_grade\">\n";

        $q_string  = "select grade_id,grade_name ";
        $q_string .= "from grades ";
        $q_string .= "order by grade_essence desc ";
        $q_grades = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_grades = mysql_fetch_array($q_grades)) {
          $output .= "<option value=\"" . $a_grades['grade_id'] . "\">" . $a_grades['grade_name'] . "</option>\n";
        }
        $output .= "</select></td>\n";

        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('cyberware_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

# now list all the items to select

        $cyberware_list = array("earware", "eyeware", "headware", "bodyware", "cyberjack", "cyberlimbs", "cosmetic");

        foreach ($cyberware_list as &$cyberware) {

          $output  = "<p></p>\n";
          $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
          $output .= "<tr>\n";
          $output .=   "<th class=\"ui-state-default\">Cyberware Dealer</th>\n";
          $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $cyberware . "-listing-help');\">Help</a></th>\n";
          $output .= "</tr>\n";
          $output .= "</table>\n";

          $output .= "<div id=\"" . $cyberware . "-listing-help\" style=\"display: none\">\n";

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
          $output .=   "<th class=\"ui-state-default\">Name</th>\n";
          $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
          $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
          $output .=   "<th class=\"ui-state-default\">Capacity</th>\n";
          $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
          $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
          $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
          $output .= "</tr>\n";

          $q_string  = "select ware_id,ware_class,ware_name,ware_rating,ware_essence,ware_capacity,";
          $q_string .= "ware_avail,ware_perm,ware_cost,ver_book,ware_page ";
          $q_string .= "from cyberware ";
          $q_string .= "left join versions on versions.ver_id = cyberware.ware_book ";
          $q_string .= "left join class on class.class_id = cyberware.ware_class ";
          $q_string .= "where class_name like \"" . $cyberware . "%\" and ver_active = 1 ";
          $q_string .= "order by ware_name,ware_rating,ware_class,ver_version ";
          $q_cyberware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_cyberware) > 0) {
            while ($a_cyberware = mysql_fetch_array($q_cyberware)) {

# this adds the ware_id to the r_ware_character
              $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('cyberware.mysql.php?update=0&r_ware_character=" . $formVars['r_ware_character'] . "&r_ware_number=" . $a_cyberware['ware_id'] . "');\">";
              $linkend = "</a>";

              $ware_rating = return_Rating($a_cyberware['ware_rating']);

              $ware_essence = return_Essence($a_cyberware['ware_essence']);
     
              $ware_capacity = return_Capacity($a_cyberware['ware_capacity']);
              
              $ware_avail = return_Avail($a_cyberware['ware_avail'], $a_cyberware['ware_perm']);

              $ware_cost = return_Cost($a_cyberware['ware_cost']);

              $ware_book = return_Book($a_cyberware['ver_book'], $a_cyberware['ware_page']);

              $class = return_Class($a_cyberware['ware_perm']);
  
              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_cyberware['ware_name'] . $linkend . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $ware_rating                         . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $ware_essence                        . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $ware_capacity                       . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $ware_avail                          . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $ware_cost                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $ware_book                           . "</td>\n";
              $output .= "</tr>\n";
            }
          } else {
            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No records found.</td>\n";
            $output .= "</tr>\n";
          }

          $output .= "</table>\n";

          print "document.getElementById('" . $cyberware . "_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">My Cyberware</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('my-cyberware-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"my-cyberware-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
      $output .=   "<th class=\"ui-state-default\">Capacity</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $costtotal = 0;
      $costgrade = 0;
      $essensetotal = 0;
      $essensegrade = 0;
      $q_string  = "select r_ware_id,r_ware_specialize,ware_id,ware_class,class_name,ware_name,ware_rating,ware_essence,ware_capacity,";
      $q_string .= "ware_avail,ware_perm,ware_cost,ver_book,ware_page,grade_name,grade_essence,grade_avail,grade_cost ";
      $q_string .= "from r_cyberware ";
      $q_string .= "left join cyberware on cyberware.ware_id = r_cyberware.r_ware_number ";
      $q_string .= "left join class on class.class_id = cyberware.ware_class ";
      $q_string .= "left join grades on grades.grade_id = r_cyberware.r_ware_grade ";
      $q_string .= "left join versions on versions.ver_id = cyberware.ware_book ";
      $q_string .= "where r_ware_character = " . $formVars['r_ware_character'] . " ";
      $q_string .= "order by ware_name,ware_rating,ware_name,ver_version ";
      $q_r_cyberware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_cyberware) > 0) {
        while ($a_r_cyberware = mysql_fetch_array($q_r_cyberware)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:attach_cyberacc(" . $a_r_cyberware['r_ware_id'] . ");showDiv('cyberware-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_cyberware('cyberware.del.php?id="  . $a_r_cyberware['r_ware_id'] . "');\">";
          $linkend   = "</a>";

          $ware_name = $a_r_cyberware['ware_name'];
          if ($a_r_cyberware['r_ware_specialize'] != '') {
            $ware_name .= " (" . $a_r_cyberware['r_ware_specialize'] . ")";
          }

          $grade = '';
          if ($a_r_cyberware['grade_essence'] != 1.00) {
            $grade = " (" . $a_r_cyberware['grade_name'] . ")";
          }

          $ware_rating = return_Rating($a_r_cyberware['ware_rating']);

          $essencegrade = ($a_r_cyberware['ware_essence'] * $a_r_cyberware['grade_essence']);
          $ware_essence = return_Essence($essencegrade);
          $essencetotal += $essencegrade;
     
          $ware_capacity = return_Capacity($a_r_cyberware['ware_capacity']);
              
          $ware_avail = return_Avail(($a_r_cyberware['ware_avail'] + $a_r_cyberware['grade_avail']), $a_r_cyberware['ware_perm']);

          $costgrade = ($a_r_cyberware['ware_cost'] * $a_r_cyberware['grade_cost']);
          $costtotal += $costgrade;

          $ware_cost = return_Cost($costgrade);

          $ware_book = return_Book($a_r_cyberware['ver_book'], $a_r_cyberware['ware_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_ware_number']) && $formVars['r_ware_number'] == $a_r_cyberware['ware_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                       . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_r_cyberware['class_name']                . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $ware_name . $grade . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_rating                                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_essence                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_capacity                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_avail                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_cost                                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ware_book                                  . "</td>\n";
          $output .= "</tr>\n";


# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
          $q_string  = "select r_acc_id,acc_id,acc_class,acc_name,acc_rating,acc_essence,acc_capacity,";
          $q_string .= "acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
          $q_string .= "from r_accessory ";
          $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
          $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
          $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
          $q_string .= "where sub_name = \"Cyberware\" and r_acc_character = " . $formVars['r_ware_character'] . " and r_acc_parentid = " . $a_r_cyberware['r_ware_id'] . " ";
          $q_string .= "order by acc_name,acc_rating,ver_version ";
          $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_accessory) > 0) {
            while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_cyberacc('cyberacc.del.php?id="  . $a_r_accessory['r_acc_id'] . "');\">";
              $linkend   = "</a>";

              $acc_rating = return_Rating($a_r_accessory['acc_rating']);

              $essencegrade = ($a_r_accessory['acc_essence'] * $a_r_cyberware['grade_essence']);
              $acc_essence = return_Essence($essencegrade);
              $essencetotal += $essencegrade;
     
              $acc_capacity = return_Capacity($a_r_accessory['acc_capacity']);
              
              $acc_avail = return_Avail(($a_r_accessory['acc_avail'] + $a_r_cyberware['grade_avail']), $a_r_accessory['acc_perm']);

              $costgrade = ($a_r_accessory['acc_cost'] * $a_r_cyberware['grade_cost']);
              $costtotal += $costgrade;

              $acc_cost = return_Cost($costgrade);

              $acc_book = return_Book($a_r_accessory['ver_book'], $a_r_accessory['acc_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_acc_number']) && $formVars['r_acc_number'] == $a_r_accessory['acc_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                         . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                      . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_accessory['acc_name'] . $grade . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_rating                                   . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_essence                                  . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_capacity                                 . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail                                    . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost                                     . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_book                                     . "</td>\n";
              $output .= "</tr>\n";
            }
          }


# now display the attached melee weapons
          $q_string  = "select r_fa_id,fa_id,class_name,fa_name,fa_acc,fa_damage,fa_type,fa_flag,";
          $q_string .= "fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_rc,fa_fullrc,fa_ammo1,";
          $q_string .= "fa_clip1,fa_ammo2,fa_clip2,fa_avail,fa_perm,fa_cost,ver_book,fa_page ";
          $q_string .= "from r_firearms ";
          $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
          $q_string .= "left join class on class.class_id = firearms.fa_class ";
          $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
          $q_string .= "where r_fa_character = " . $formVars['r_ware_character'] . " and r_fa_parentid = " . $a_r_cyberware['r_ware_id'] . " ";
          $q_string .= "order by fa_name,fa_class ";
          $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_firearms) > 0) {
            while ($a_r_firearms = mysql_fetch_array($q_r_firearms)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_cyberfire('cyberfire.del.php?id="  . $a_r_firearms['r_fa_id'] . "');\">";

              $fa_avail = return_Avail($a_r_firearms['fa_avail'], $a_r_firearms['fa_perm']);

              $fa_book = return_Book($a_r_firearms['ver_book'], $a_r_firearms['fa_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_fa_number']) && $formVars['r_fa_number'] == $a_r_firearms['fa_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel              . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_firearms['fa_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $fa_avail                          . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $fa_book                           . "</td>\n";
              $output .= "</tr>\n";



# associate any ammo with the weapon
              $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,class_name,ammo_name,ammo_rounds,ammo_mod,ammo_ap,";
              $q_string .= "ammo_avail,ammo_perm,ver_book,ammo_page ";
              $q_string .= "from r_ammo ";
              $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
              $q_string .= "left join class on class.class_id = ammo.ammo_class ";
              $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
              $q_string .= "where r_ammo_character = " . $formVars['r_ware_character'] . " and r_ammo_parentid = " . $a_r_firearms['r_fa_id'] . " ";
              $q_string .= "order by ammo_name,class_name ";
              $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
              if (mysql_num_rows($q_r_ammo) > 0) {
                while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

                  $ammo_avail = return_Avail($a_r_ammo['ammo_avail'], $a_r_ammo['ammo_perm']);

                  $ammo_book = return_Book($a_r_ammo['ver_book'], $a_r_ammo['ammo_page']);

                  $class = "ui-widget-content";
                  if (isset($formVars['r_ammo_number']) && $formVars['r_ammo_number'] == $a_r_ammo['ammo_id']) {
                    $class = "ui-state-error";
                  }

                  $output .= "<tr>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . "\">"        . "&gt;&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . $ammo_avail                                                         . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
                  $output .= "  <td class=\"" . $class . " delete\">" . $ammo_book                                                          . "</td>\n";
                  $output .= "</tr>\n";

                }
              }
            }
          }
        }
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">Total Essence: " . $essencetotal . ", Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";

      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No Cyberware added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_cyberware);

      print "document.getElementById('my_cyberware_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
