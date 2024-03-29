<?php
# Script: cyberdeck.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "cyberdeck.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output = '';

  $q_string  = "select r_deck_id,deck_brand,deck_model,deck_rating,deck_programs,r_deck_attack,r_deck_sleaze,";
  $q_string .= "r_deck_data,r_deck_firewall,r_deck_access,r_deck_conmon ";
  $q_string .= "from r_cyberdeck ";
  $q_string .= "left join cyberdeck on cyberdeck.deck_id = r_cyberdeck.r_deck_number ";
  $q_string .= "where r_deck_character = " . $formVars['id'] . " ";
  $q_string .= "order by deck_brand,deck_model,deck_rating ";
  $q_r_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_cyberdeck) > 0) {

    while ($a_r_cyberdeck = mysqli_fetch_array($q_r_cyberdeck)) {

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\" colspan=\"10\">Cyberdeck ID: " . $a_r_cyberdeck['r_deck_access'] . "</th>";
      $output .= "</tr>";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\">Cyberdeck</th>";
      $output .= "  <th class=\"ui-state-default\">Rating</th>";
      $output .= "  <th class=\"ui-state-default\">Attack</th>";
      $output .= "  <th class=\"ui-state-default\">Sleaze</th>";
      $output .= "  <th class=\"ui-state-default\">Data Processing</th>";
      $output .= "  <th class=\"ui-state-default\">Firewall</th>";
      $output .= "  <th class=\"ui-state-default\">Programs</th>";
      $output .= "</tr>";

      $rating = return_Rating($a_r_cyberdeck['deck_rating']);

      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_cyberdeck['deck_brand'] . " " . $a_r_cyberdeck['deck_model'] . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $rating                                                           . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberdeck['r_deck_attack']                                   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberdeck['r_deck_sleaze']                                   . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberdeck['r_deck_data']                                     . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberdeck['r_deck_firewall']                                 . "</td>";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberdeck['deck_programs']                                   . "</td>";
      $output .= "</tr>";

      $output .= "<tr>\n";
      $matrix_damage = ceil(($a_r_cyberdeck['deck_rating'] / 2) + 8);
      $output .= "  <td class=\"ui-widget-content\" colspan=\"15\">" . "Matrix Damage: (" . $matrix_damage . "): ";
      for ($i = 1; $i <= 12; $i++) {
        if ($matrix_damage >= $i) {
          $checked = '';
          if ($i <= $a_r_cyberdeck['r_deck_conmon']) {
            $checked = 'checked=\"true\"';
          }

          $output .= "<input type=\"checkbox\" " . $checked . " id=\"deckcon" . ${i} . "\"  onclick=\"edit_CyberdeckCondition(" . ${i} . ", " . $a_r_cyberdeck['r_deck_id'] . ", 'cyberdeck');\">\n";
        }
      }
      $output .= "</td>\n";
      $output .= "</tr>\n";

      $output .= "</table>";


      $q_string  = "select r_acc_id,acc_name,acc_rating ";
      $q_string .= "from r_accessory ";
      $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
      $q_string .= "where r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_cyberdeck['r_deck_id'] . " ";
      $q_string .= "order by acc_name,acc_rating ";
      $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql =" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_accessory) > 0) {

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" colspan=\"12\">Cyberdeck Accessories</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .= "</tr>\n";

        while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

          $acc_rating = return_Rating($a_r_accessory['acc_rating']);

          $costtotal += $a_r_accessory['acc_cost'];

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
      $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " and pgm_type = 0 ";
      $q_string .= "order by pgm_name ";
      $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_program) > 0) {

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
        $output .= "<tr>";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Common Programs</th>";
        $output .= "</tr>";
        $output .= "<tr>";
        $output .= "  <th class=\"ui-state-default\">Program</th>";
        $output .= "  <th class=\"ui-state-default\">Description</th>";
        $output .= "</tr>";

        while ($a_r_program = mysqli_fetch_array($q_r_program)) {
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
      $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " and pgm_type = 1 ";
      $q_string .= "order by pgm_name ";
      $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_program) > 0) {

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
        $output .= "<tr>";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Hacking Programs</th>";
        $output .= "</tr>";
        $output .= "<tr>";
        $output .= "  <th class=\"ui-state-default\">Program</th>";
        $output .= "  <th class=\"ui-state-default\">Description</th>";
        $output .= "</tr>";

        while ($a_r_program = mysqli_fetch_array($q_r_program)) {
          $output .= "<tr>";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_program['pgm_name']                                      . "</td>";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_program['pgm_desc']                                      . "</td>";
          $output .= "</tr>";

        }
        $output .= "</table>";

      }

# now Agents
      $q_string  = "select agt_name,agt_rating,r_agt_active ";
      $q_string .= "from r_agents ";
      $q_string .= "left join agents on agents.agt_id = r_agents.r_agt_number ";
      $q_string .= "where r_agt_character = " . $formVars['id'] . " and r_agt_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " ";
      $q_string .= "order by agt_name ";
      $q_r_agents = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_agents) > 0) {

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
        $output .= "<tr>";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Agents</th>";
        $output .= "</tr>";
        $output .= "<tr>";
        $output .= "  <th class=\"ui-state-default\">Agent</th>";
        $output .= "  <th class=\"ui-state-default\">Rating</th>";
        $output .= "</tr>";

        while ($a_r_agents = mysqli_fetch_array($q_r_agents)) {

          $rating = return_Rating($a_r_agents['agt_rating']);

          $output .= "<tr>";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_agents['agt_name']                                      . "</td>";
          $output .= "  <td class=\"ui-widget-content\">"        . $rating                                                      . "</td>";
          $output .= "</tr>";

        }
        $output .= "</table>";

      }
    }
  } else {
    $output  = "";
  }

  print "document.getElementById('cyberdeck_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";

?>
