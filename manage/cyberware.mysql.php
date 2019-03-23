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

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);
  $output = '';

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#cyberware\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Cyberware Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('cyberware-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"cyberware-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<p>Help</p>";

  $output .= "</div>";

  $output .= "</div>";


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Class</th>";
  $output .= "  <th class=\"ui-state-default\">Cyberware</th>";
  $output .= "  <th class=\"ui-state-default\">Rating</th>";
  $output .= "  <th class=\"ui-state-default\">Essence</th>";
  $output .= "  <th class=\"ui-state-default\">Capacity</th>";
  $output .= "  <th class=\"ui-state-default\">Availability</th>";
  $output .= "  <th class=\"ui-state-default\">Cost</th>";
  $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
  $output .= "</tr>";

  $nuyen = '&yen;';
  $q_string  = "select r_ware_id,class_name,ware_class,ware_name,ware_rating,ware_multiply,ware_essence,ware_capacity,ware_avail,ware_perm,ware_cost,ver_book,ware_page ";
  $q_string .= "from r_cyberware ";
  $q_string .= "left join cyberware on cyberware.ware_id = r_cyberware.r_ware_number ";
  $q_string .= "left join versions on versions.ver_id = cyberware.ware_book ";
  $q_string .= "left join class on class.class_id = cyberware.ware_class ";
  $q_string .= "where r_ware_character = " . $formVars['id'] . " ";
  $q_string .= "order by ware_name,ware_rating,ver_version ";
  $q_r_cyberware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_cyberware) > 0) {
    while ($a_r_cyberware = mysql_fetch_array($q_r_cyberware)) {

      $rating = return_Rating($a_r_cyberware['ware_rating']);

      $essence = return_Essence($a_r_cyberware['ware_essence']);

      $capacity = return_Capacity($a_r_cyberware['ware_capacity']);

      $avail = return_Avail($a_r_cyberware['ware_avail'], $a_r_cyberware['ware_perm']);

      $cost = return_Cost($a_r_cyberware['ware_cost']);

      $book = return_Book($a_r_cyberware['ver_book'], $a_r_cyberware['ware_page']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_cyberware['class_name'] . "</td>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_cyberware['ware_name']  . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $rating                      . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $essence                     . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $capacity                    . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $avail                       . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $cost                        . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $book                        . "</td>";
      $output .= "</tr>";

# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
      $q_string  = "select r_acc_id,acc_id,acc_class,class_name,acc_name,acc_rating,acc_essence,acc_capacity,";
      $q_string .= "acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join class on class.class_id = accessory.acc_class ";
      $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
      $q_string .= "where r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_cyberware['r_ware_id'] . " ";
      $q_string .= "order by acc_name,acc_rating,ver_version ";
      $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {

          $rating = return_Rating($a_r_accessory['acc_rating']);

          $essence = return_Essence($a_r_accessory['acc_essence']);

          $capacity = return_Capacity($a_r_accessory['acc_capacity']);

          $avail = return_Avail($a_r_accessory['acc_avail'], $a_r_accessory['acc_perm']);

          $class = "ui-widget-content";
          if (isset($formVars['r_acc_number']) && $formVars['r_acc_number'] == $a_r_accessory['acc_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_r_accessory['class_name']                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . $a_r_accessory['acc_name']                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $rating                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $essence                                                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $capacity                                                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $avail                                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . number_format($a_r_accessory['acc_cost'], 0, '.', ',') . $nuyen   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_accessory['ver_book']    . ": " . $a_r_accessory['acc_page'] . "</td>\n";
          $output .= "</tr>\n";
        }
      }
    }
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"8\">No Cyberware selected.</td>";
    $output .= "</tr>";
  }

  $output .= "</table>";
     
  print "document.getElementById('cyberware_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
