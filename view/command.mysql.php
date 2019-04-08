<?php
# Script: command.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "command.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output = '';

  $q_string  = "select r_cmd_id,cmd_brand,cmd_model,cmd_rating,cmd_programs,";
  $q_string .= "cmd_data,cmd_firewall,r_cmd_noise,r_cmd_sharing,r_cmd_access ";
  $q_string .= "from r_command ";
  $q_string .= "left join command on command.cmd_id = r_command.r_cmd_number ";
  $q_string .= "where r_cmd_character = " . $formVars['id'] . " ";
  $q_string .= "order by cmd_brand,cmd_model,cmd_rating ";
  $q_r_command = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_command) > 0) {
    while ($a_r_command = mysql_fetch_array($q_r_command)) {

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\" colspan=\"10\">Rigger Command Console ID: " . $a_r_command['r_cmd_access'] . "</th>";
      $output .= "</tr>";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\">Command</th>";
      $output .= "  <th class=\"ui-state-default\">Rating</th>";
      $output .= "  <th class=\"ui-state-default\">Data Processing</th>";
      $output .= "  <th class=\"ui-state-default\">Firewall</th>";
      $output .= "  <th class=\"ui-state-default\">Noise Reduction</th>";
      $output .= "  <th class=\"ui-state-default\">Sharing</th>";
      $output .= "  <th class=\"ui-state-default\">Programs</th>";
      $output .= "</tr>";

      $rating = return_Rating($a_r_command['cmd_rating']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_command['cmd_brand'] . " " . $a_r_command['cmd_model'] . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $rating                                                           . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_command['cmd_data']                                   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_command['cmd_firewall']                                   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_command['r_cmd_noise']                                     . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_command['r_cmd_sharing']                                 . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_command['cmd_programs']                                   . "</td>";
      $output .= "</tr>";

      $output .= "<tr>\n";
      $matrix_damage = ceil(($a_r_command['cmd_rating'] / 2) + 8);
      $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">" . "Matrix Damage: (" . $matrix_damage . "): ";
      for ($i = 1; $i <= 12; $i++) {
        if ($matrix_damage >= $i) {
          $output .= "<input type=\"checkbox\" id=\"cmdcon" . ${i} . "\"  onclick=\"edit_CommandCondition(" . ${i} . ", " . $a_r_command['r_cmd_id'] . ", 'command');\">\n";
        }
      }
      $output .= "</td>\n";
      $output .= "</tr>\n";

      $output .= "</table>";

# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
      $q_string  = "select r_acc_id,acc_id,acc_class,class_name,acc_name,acc_rating,acc_essence,acc_capacity,";
      $q_string .= "acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "left join class on class.class_id = accessory.acc_class ";
      $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
      $q_string .= "where r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_command['r_cmd_id'] . " ";
      $q_string .= "order by acc_name,acc_rating,ver_version ";
      $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_accessory) > 0) {

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" colspan=\"12\">Rigger Command Console Accessories</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .= "</tr>\n";

        while ($a_r_accessory = mysql_fetch_array($q_r_accessory)) {

          $acc_rating = return_Rating($a_r_accessory['acc_rating']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_r_accessory['acc_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $acc_rating                . "</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";
      }


# legal programs first
      $q_string  = "select pgm_name,pgm_desc,r_pgm_active ";
      $q_string .= "from r_program ";
      $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
      $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_cyberdeck = " . $a_r_command['r_cmd_id'] . " and pgm_type = 2 ";
      $q_string .= "order by pgm_name ";
      $q_r_program = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_program) > 0) {

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
        $output .= "<tr>";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Rigger Command Console Common Programs</th>";
        $output .= "</tr>";
        $output .= "<tr>";
        $output .= "  <th class=\"ui-state-default\">Program</th>";
        $output .= "  <th class=\"ui-state-default\">Description</th>";
        $output .= "</tr>";

        while ($a_r_program = mysql_fetch_array($q_r_program)) {
          $output .= "<tr>";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_program['pgm_name']                                      . "</td>";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_program['pgm_desc']                                      . "</td>";
          $output .= "</tr>";
        }
        $output .= "</table>";
      }

# now Hacking programs
      $q_string  = "select pgm_name,pgm_desc,r_pgm_active ";
      $q_string .= "from r_program ";
      $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
      $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_cyberdeck = " . $a_r_command['r_cmd_id'] . " and pgm_type = 3 ";
      $q_string .= "order by pgm_name ";
      $q_r_program = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_program) > 0) {

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
        $output .= "<tr>";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Rigger Command Console Hacking Programs</th>";
        $output .= "</tr>";
        $output .= "<tr>";
        $output .= "  <th class=\"ui-state-default\">Program</th>";
        $output .= "  <th class=\"ui-state-default\">Description</th>";
        $output .= "</tr>";

        while ($a_r_program = mysql_fetch_array($q_r_program)) {
          $output .= "<tr>";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_program['pgm_name']                                      . "</td>";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_program['pgm_desc']                                      . "</td>";
          $output .= "</tr>";

        }
        $output .= "</table>";

      }
    }
  } else {
    $output  = "";
  }

  print "document.getElementById('command_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
