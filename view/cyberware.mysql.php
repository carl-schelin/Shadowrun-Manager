<?php
# Script: cyberware.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "cyberware.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Cyberware</th>";
  $output .= "</tr>\n";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Class</th>";
  $output .= "  <th class=\"ui-state-default\">Cyberware</th>";
  $output .= "  <th class=\"ui-state-default\">Rating</th>";
  $output .= "  <th class=\"ui-state-default\">Essence</th>";
  $output .= "  <th class=\"ui-state-default\">Capacity</th>";
  $output .= "</tr>";

  $nuyen = '&yen;';
  $q_string  = "select r_ware_id,r_ware_specialize,class_name,ware_name,ware_rating,ware_multiply,ware_essence,ware_capacity, ";
  $q_string .= "grade_name,grade_essence ";
  $q_string .= "from r_cyberware ";
  $q_string .= "left join cyberware on cyberware.ware_id = r_cyberware.r_ware_number ";
  $q_string .= "left join class on class.class_id = cyberware.ware_class ";
  $q_string .= "left join grades on grades.grade_id = r_cyberware.r_ware_grade ";
  $q_string .= "where r_ware_character = " . $formVars['id'] . " ";
  $q_string .= "order by ware_name,ware_rating,class_name ";
  $q_r_cyberware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_cyberware) > 0) {
    while ($a_r_cyberware = mysqli_fetch_array($q_r_cyberware)) {

      $ware_name = $a_r_cyberware['ware_name'];
      if ($a_r_cyberware['r_ware_specialize'] != '') {
        $ware_name .= " (" . $a_r_cyberware['r_ware_specialize'] . ")";
      }

      $grade = '';
      if ($a_r_cyberware['grade_essence'] != 1.00) {
        $grade = " (" . $a_r_cyberware['grade_name'] . ")";
      }

      $rating = return_Rating($a_r_cyberware['ware_rating']);

      $essencegrade = ($a_r_cyberware['ware_essence'] * $a_r_cyberware['grade_essence']);
      $essence = return_Essence($essencegrade);

      $capacity = return_Capacity($a_r_cyberware['ware_capacity']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_cyberware['class_name'] . "</td>";
      $output .= "<td class=\"ui-widget-content\">"        . $ware_name . $grade          . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $rating                      . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $essence                     . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $capacity                    . "</td>";
      $output .= "</tr>";

# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
      $q_string  = "select r_acc_id,acc_id,acc_name,acc_rating,acc_essence,acc_capacity ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "where sub_name = \"Cyberware\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_cyberware['r_ware_id'] . " ";
      $q_string .= "order by acc_name,acc_rating ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $rating = return_Rating($a_r_accessory['acc_rating']);

          $essencegrade = ($a_r_accessory['acc_essence'] * $a_r_cyberware['grade_essence']);
          $essence = return_Essence($essencegrade);

          $capacity = return_Capacity($a_r_accessory['acc_capacity']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                      . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_accessory['acc_name'] . $grade . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $rating                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $essence                                      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $capacity                                     . "</td>\n";
          $output .= "</tr>\n";
        }
      }


# now display the attached melee weapons
      $q_string  = "select r_fa_id,fa_name ";
      $q_string .= "from r_firearms ";
      $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
      $q_string .= "where r_fa_character = " . $formVars['id'] . " and r_fa_parentid = " . $a_r_cyberware['r_ware_id'] . " ";
      $q_string .= "order by fa_name ";
      $q_r_firearms = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_firearms) > 0) {
        while ($a_r_firearms = mysqli_fetch_array($q_r_firearms)) {

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_firearms['fa_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                           . "</td>\n";
          $output .= "</tr>\n";


# associate any ammo with the weapon
          $q_string  = "select r_ammo_rounds,ammo_name,ammo_rounds ";
          $q_string .= "from r_ammo ";
          $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
          $q_string .= "where r_ammo_character = " . $formVars['id'] . " and r_ammo_parentid = " . $a_r_firearms['r_fa_id'] . " ";
          $q_string .= "order by ammo_name ";
          $q_r_ammo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_ammo) > 0) {
            while ($a_r_ammo = mysqli_fetch_array($q_r_ammo)) {

              $class = "ui-widget-content";

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&gt;&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                            . "</td>\n";
              $output .= "</tr>\n";

            }
          }
        }
      }
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('cyberware_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";

?>
