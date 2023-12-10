<?php
# Script: gear.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "gear.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Gear</th>";
  $output .= "</tr>\n";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Class</th>";
  $output .= "  <th class=\"ui-state-default\">Name</th>";
  $output .= "  <th class=\"ui-state-default\">Number</th>";
  $output .= "  <th class=\"ui-state-default\">Rating</th>";
  $output .= "  <th class=\"ui-state-default\">Capacity</th>";
  $output .= "</tr>";

  $nuyen = '&yen;';
  $q_string  = "select r_gear_id,r_gear_details,r_gear_active,r_gear_amount,";
  $q_string .= "class_name,gear_name,gear_rating,gear_capacity ";
  $q_string .= "from r_gear ";
  $q_string .= "left join gear on gear.gear_id = r_gear.r_gear_number ";
  $q_string .= "left join class on class.class_id = gear.gear_class ";
  $q_string .= "where r_gear_character = " . $formVars['id'] . " ";
  $q_string .= "order by gear_name,gear_rating ";
  $q_r_gear = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_gear) > 0) {
    while ($a_r_gear = mysqli_fetch_array($q_r_gear)) {

      $gear_name = $a_r_gear['gear_name'];
      if ($a_r_gear['r_gear_details'] != '') {
        $gear_name = $a_r_gear['gear_name'] . " (" . $a_r_gear['r_gear_details'] . ")";
      }

      $rating = return_Rating($a_r_gear['gear_rating']);

      $capacity = return_Capacity($a_r_gear['gear_capacity']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_gear['class_name']    . "</td>";
      $output .= "<td class=\"ui-widget-content\">"        . $gear_name                 . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_gear['r_gear_amount'] . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $rating                    . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $capacity                  . "</td>";
      $output .= "</tr>";

# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
      $q_string  = "select r_acc_id,acc_id,acc_class,class_name,acc_name,acc_rating,acc_essence,acc_capacity ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "left join class on class.class_id = accessory.acc_class ";
      $q_string .= "where sub_name = \"Gear\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_gear['r_gear_id'] . " ";
      $q_string .= "order by acc_name,acc_rating ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $rating = return_Rating($a_r_accessory['acc_rating']);

          $essence = return_Essence($a_r_accessory['acc_essence']);

          $capacity = return_Capacity($a_r_accessory['acc_capacity']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                           . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_accessory['acc_name']                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $rating                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $capacity                                                          . "</td>\n";
          $output .= "</tr>\n";
        }
      }
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }


  print "document.getElementById('gear_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";

?>
