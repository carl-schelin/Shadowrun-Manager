<?php
# Script: armor.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "knowledge.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Armor</th>";
  $output .= "</tr>\n";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Armor</th>";
  $output .= "  <th class=\"ui-state-default\">Rating</th>";
  $output .= "  <th class=\"ui-state-default\">Capacity</th>";
  $output .= "</tr>";

  $nuyen = '&yen;';
  $q_string  = "select r_arm_id,r_arm_details,arm_name,arm_rating,arm_capacity ";
  $q_string .= "from r_armor ";
  $q_string .= "left join armor on armor.arm_id = r_armor.r_arm_number ";
  $q_string .= "where r_arm_character = " . $formVars['id'] . " ";
  $q_string .= "order by arm_name,arm_rating ";
  $q_r_armor = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_armor) > 0) {
    while ($a_r_armor = mysql_fetch_array($q_r_armor)) {

      $arm_name = $a_r_armor['arm_name'];
      if ($a_r_armor['r_arm_details'] != '') {
        $arm_name = $a_r_armor['arm_name'] . " (" . $a_r_armor['r_arm_details'] . ")";
      }

      $rating = return_Rating($a_r_armor['arm_rating']);
      $capacity = return_Capacity($a_r_armor['arm_capacity']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $arm_name . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $rating   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $capacity   . "</td>";
      $output .= "</tr>";

# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_acc_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
      $q_string  = "select r_acc_id,acc_id,acc_name,acc_rating,acc_capacity ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "where sub_name = \"Clothing and Armor\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_armor['r_arm_id'] . " ";
      $q_string .= "order by acc_name,acc_rating ";
      $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {

          $rating = return_Rating($a_r_accessory['acc_rating']);
          $capacity = return_Rating($a_r_accessory['acc_capacity']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_accessory['acc_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $rating                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $capacity                              . "</td>\n";
          $output .= "</tr>\n";
        }
      }
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('armor_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
