<?php
# Script: commlink.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "commlink.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Commlinks</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .=   "<th class=\"ui-state-default\">Commlink</th>\n";
  $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
  $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
  $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
  $output .=   "<th class=\"ui-state-default\">Company ID:Unit ID</th>\n";
  $output .= "</tr>\n";

  $costtotal = 0;
  $nuyen = '&yen;';
  $q_string  = "select r_link_id,r_link_conmon,link_id,link_brand,link_model,link_rating,link_data,link_firewall,r_link_access,r_link_active ";
  $q_string .= "from r_commlink ";
  $q_string .= "left join commlink on commlink.link_id = r_commlink.r_link_number ";
  $q_string .= "where r_link_character = " . $formVars['id'] . " ";
  $q_string .= "order by link_rating,link_cost ";
  $q_r_commlink = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_commlink) > 0) {
    while ($a_r_commlink = mysqli_fetch_array($q_r_commlink)) {

      $rating = return_Rating($a_r_commlink['link_rating']);

      $class = "ui-widget-content";

      $output .= "<tr>\n";
      $output .= "  <td class=\"" . $class . "\">"        . $a_r_commlink['link_brand'] . " " . $a_r_commlink['link_model'] . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $rating                                                         . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $a_r_commlink['link_data']                                      . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $a_r_commlink['link_firewall']                                  . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $a_r_commlink['r_link_access']                                  . "</td>\n";
      $output .= "</tr>\n";

      $output .= "<tr>\n";
      $matrix_damage = ceil(($a_r_commlink['link_rating'] / 2) + 8);
      $output .= "  <td class=\"ui-widget-content\" colspan=\"15\">" . "Matrix Damage: (" . $matrix_damage . "): ";
      for ($i = 1; $i <= 18; $i++) {
        if ($matrix_damage >= $i) {
          $checked = '';
          if ($i <= $a_r_commlink['r_link_conmon']) {
            $checked = 'checked=\"true\"';
          }

          $output .= "<input type=\"checkbox\" " . $checked . " id=\"linkcon" . ${i} . "\"  onclick=\"edit_CommlinkCondition(" . ${i} . ", " . $a_r_commlink['r_link_id'] . ", 'commlink');\">\n";
        }
      }
      $output .= "</td>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_acc_id,acc_id,acc_class,acc_name,acc_rating ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "where sub_name = \"Commlinks\" and r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_commlink['r_link_id'] . " ";
      $q_string .= "order by acc_name,acc_rating ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_accessory) > 0) {
        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $rating = return_Rating($a_r_accessory['acc_rating']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"               . "&gt; " . $a_r_accessory['acc_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"        . $rating                              . "</td>\n";
          $output .= "  <td class=\"" . $class . "\" colspan=\"3\">" . "&nbsp;"                             . "</td>\n";
          $output .= "</tr>\n";
        }
      }
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('commlink_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
