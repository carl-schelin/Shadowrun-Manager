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
  $output = '';

  $output  = "<p></p>\n";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#gear\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Gear Information";
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('gear-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"gear-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<p>Help</p>";

  $output .= "</div>";

  $output .= "</div>";


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Class</th>";
  $output .= "  <th class=\"ui-state-default\">Name</th>";
  $output .= "  <th class=\"ui-state-default\">Number</th>";
  $output .= "  <th class=\"ui-state-default\">Rating</th>";
  $output .= "  <th class=\"ui-state-default\">Capacity</th>";
  $output .= "  <th class=\"ui-state-default\">Availability</th>";
  $output .= "  <th class=\"ui-state-default\">Cost</th>";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
  $output .= "</tr>";

  $totalcost = 0;
  $q_string  = "select r_gear_id,r_gear_amount,class_name,gear_id,gear_class,gear_name,gear_rating,gear_capacity,";
  $q_string .= "gear_avail,gear_perm,ver_book,gear_page,gear_cost ";
  $q_string .= "from r_gear ";
  $q_string .= "left join gear on gear.gear_id = r_gear.r_gear_number ";
  $q_string .= "left join class on class.class_id = gear.gear_class ";
  $q_string .= "left join versions on versions.ver_id = gear.gear_book ";
  $q_string .= "where r_gear_character = " . $formVars['id'] . " ";
  $q_string .= "order by gear_name,gear_rating,ver_version ";
  $q_r_gear = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_gear) > 0) {
    while ($a_r_gear = mysqli_fetch_array($q_r_gear)) {

      $rating = return_Rating($a_r_gear['gear_rating']);

      $capacity = return_Capacity($a_r_gear['gear_capacity']);

      $avail = return_Avail($a_r_gear['gear_avail'], $a_r_gear['gear_perm']);

      $totalcost += $a_r_gear['gear_cost'];
      $cost = return_Cost($a_r_gear['gear_cost']);

      $book = return_Book($a_r_gear['ver_book'], $a_r_gear['gear_page']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_gear['class_name']     . "</td>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_gear['gear_name']      . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_gear['r_gear_amount']  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $rating                     . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $capacity                   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $avail                      . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cost                       . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $book                       . "</td>";
      $output .= "</tr>";


# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
      $q_string  = "select r_acc_id,r_acc_amount,acc_id,acc_class,acc_name,acc_rating,";
      $q_string .= "acc_essence,acc_capacity,acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
      $q_string .= "where sub_name = \"Gear\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_gear['r_gear_id'] . " ";
      $q_string .= "order by acc_name,acc_rating,ver_version ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $acc_rating = return_Rating($a_r_accessory['acc_rating']);

          $acc_capacity = return_Capacity($a_r_accessory['acc_capacity']);

          $totalcost += $a_r_accessory['acc_cost'];

          $acc_avail = return_Avail($a_r_accessory['acc_avail'], $a_r_accessory['acc_perm']);

          $totalcost += $a_r_accessory['acc_cost'];
          $acc_cost = return_Cost($a_r_accessory['acc_cost']);

          $acc_book = return_Book($a_r_accessory['ver_book'], $a_r_accessory['acc_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_acc_number']) && $formVars['r_acc_number'] == $a_r_accessory['acc_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                             . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_accessory['acc_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_rating                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_capacity                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_book                            . "</td>\n";
          $output .= "</tr>\n";
        }
      }
    }
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">Total Cost: " . return_Cost($totalcost) . "</td>\n";
    $output .= "</tr>\n";
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"8\">No Gear selected.</td>";
    $output .= "</tr>";
  }

  $output .= "</table>";
     
  print "document.getElementById('gear_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";

?>
