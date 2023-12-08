<?php
# Script: melee.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "melee.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Melee Weapons</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .=   "<th class=\"ui-state-default\">Class</th>\n";
  $output .=   "<th class=\"ui-state-default\">Name</th>\n";
  $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
  $output .=   "<th class=\"ui-state-default\">Reach</th>\n";
  $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
  $output .=   "<th class=\"ui-state-default\">AP</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select r_melee_id,class_name,melee_class,melee_name,melee_acc,melee_reach,melee_damage,";
  $q_string .= "melee_type,melee_flag,melee_strength,melee_ap ";
  $q_string .= "from r_melee ";
  $q_string .= "left join melee on melee.melee_id = r_melee.r_melee_number ";
  $q_string .= "left join class on class.class_id = melee.melee_class ";
  $q_string .= "where r_melee_character = " . $formVars['id'] . " ";
  $q_string .= "order by melee_class,melee_name ";
  $q_r_melee = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_melee) > 0) {
    while ($a_r_melee = mysqli_fetch_array($q_r_melee)) {

      $melee_reach = '--';
      if ($a_r_melee['melee_reach'] > 0) {
        $melee_reach = $a_r_melee['melee_reach'];
      }

# melee title is the (str/2 + 1) stuff.
# melee damage is the actual score based on your stats
      $melee_title = "";
      $melee_damage = "";
      if ($a_r_melee['melee_strength']) {
        $melee_title = "(STR + " . $a_r_melee['melee_damage'] . ")";

        $q_string  = "select runr_strength ";
        $q_string .= "from runners ";
        $q_string .= "where runr_id = " . $formVars['id'] . " ";
        $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
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

      $melee_ap = '--';
      if ($a_r_melee['melee_ap'] != 0) {
        $melee_ap = $a_r_melee['melee_ap'];
      }

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"                . $a_r_melee['class_name']                                     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"                . $a_r_melee['melee_name']                                      . "</td>\n";
      $output .= "  <td class=\"delete ui-widget-content\">"         . $a_r_melee['melee_acc']                                       . "</td>\n";
      $output .= "  <td class=\"delete ui-widget-content\">"         . $melee_reach                                                  . "</td>\n";
      $output .= "  <td class=\"delete ui-widget-content\" title=\"" . $melee_title . "\">" . $melee_damage                          . "</td>\n";
      $output .= "  <td class=\"delete ui-widget-content\">"         . $melee_ap                                                     . "</td>\n";
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
      $q_string .= "where sub_name = \"Melee\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_melee['r_melee_id'] . " ";
      $q_string .= "order by acc_name,acc_rating,ver_version ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"              . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_accessory['acc_name']   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                  . "</td>\n";
          $output .= "</tr>\n";
        }
      }
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('melee_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
