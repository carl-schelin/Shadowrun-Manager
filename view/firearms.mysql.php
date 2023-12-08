<?php
# Script: firearms.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "firearms.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"8\">Firearms</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .=   "<th class=\"ui-state-default\">Class</th>\n";
  $output .=   "<th class=\"ui-state-default\">Name</th>\n";
  $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
  $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
  $output .=   "<th class=\"ui-state-default\">AP</th>\n";
  $output .=   "<th class=\"ui-state-default\">Mode</th>\n";
  $output .=   "<th class=\"ui-state-default\">RC</th>\n";
  $output .=   "<th class=\"ui-state-default\">Ammo</th>\n";
  $output .= "</tr>\n";

  $nuyen = '&yen;';
  $q_string  = "select r_fa_id,class_name,fa_name,fa_acc,fa_damage,fa_type,fa_flag,";
  $q_string .= "fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_rc,fa_fullrc,fa_ammo1,";
  $q_string .= "fa_clip1,fa_ammo2,fa_clip2 ";
  $q_string .= "from r_firearms ";
  $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
  $q_string .= "left join class on class.class_id = firearms.fa_class ";
  $q_string .= "where r_fa_character = " . $formVars['id'] . " ";
  $q_string .= "order by class_name,fa_name ";
  $q_r_firearms = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_firearms) > 0) {
    while ($a_r_firearms = mysqli_fetch_array($q_r_firearms)) {

      $fa_mode = return_Mode($a_r_firearms['fa_mode1'], $a_r_firearms['fa_mode2'], $a_r_firearms['fa_mode3']);

      $fa_damage = return_Damage($a_r_firearms['fa_damage'], $a_r_firearms['fa_type'], $a_r_firearms['fa_flag']);

      $fa_rc = return_Recoil($a_r_firearms['fa_rc'], $a_r_firearms['fa_fullrc']);

      $fa_ap = return_Penetrate($a_r_firearms['fa_ap']);

      $fa_ammo = return_Ammo($a_r_firearms['fa_ammo1'], $a_r_firearms['fa_clip1'], $a_r_firearms['fa_ammo2'], $a_r_firearms['fa_clip2']);

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_firearms['class_name'] . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_firearms['fa_name']    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_firearms['fa_acc']     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_damage                  . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_ap                      . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_mode                    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_rc                      . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_ammo                    . "</td>\n";
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
      $q_string .= "where sub_name = \"Firearms\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_firearms['r_fa_id'] . " ";
      $q_string .= "order by acc_name,acc_rating,ver_version ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $acc_name = $a_r_accessory['acc_name'];
          if ($a_r_accessory['acc_mount'] != '') {
            $acc_name = $a_r_accessory['acc_name'] . " (" . $a_r_accessory['acc_mount'] . ")";
          }

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $acc_name                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
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
      $q_string .= "where r_ammo_character = " . $formVars['id'] . " and r_ammo_parentid = " . $a_r_firearms['r_fa_id'] . " ";
      $q_string .= "order by ammo_name,class_name ";
      $q_r_ammo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_ammo) > 0) {
        while ($a_r_ammo = mysqli_fetch_array($q_r_ammo)) {

          $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_ammo['ammo_mod']                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_ap                                                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                            . "</td>\n";
          $output .= "</tr>\n";

        }
      }
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('firearms_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
