<?php
# Script: sprites.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "sprites.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"10\">Sprites</th>";
  $output .= "</tr>\n";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">Sprite Name</th>";
  $output .= "  <th class=\"ui-state-default\">Level</th>";
  $output .= "  <th class=\"ui-state-default\">Tasks</th>";
  $output .= "  <th class=\"ui-state-default\">Registered</th>";
  $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
  $output .=   "<th class=\"ui-state-default\">Sleaze</th>\n";
  $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
  $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
  $output .=   "<th class=\"ui-state-default\">Initiative</th>\n";
  $output .= "</tr>";

  $q_string  = "select r_sprite_id,r_sprite_number,r_sprite_level,r_sprite_tasks,r_sprite_registered,";
  $q_string .= "sprite_name,sprite_attack,sprite_sleaze,sprite_data,sprite_firewall,sprite_initiative ";
  $q_string .= "from r_sprite ";
  $q_string .= "left join sprites on sprites.sprite_id = r_sprite.r_sprite_number ";
  $q_string .= "where r_sprite_character = " . $formVars['id'] . " ";
  $q_string .= "order by sprite_name ";
  $q_r_sprite = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_sprite) > 0) {
    while ($a_r_sprite = mysqli_fetch_array($q_r_sprite)) {

      $registered = 'No';
      if ($a_r_sprite['r_sprite_registered']) {
        $registered = 'Yes';
      }

      $sprite_attack   = return_Sprite($a_r_sprite['r_sprite_level'], $a_r_sprites['sprite_attack']);
      $sprite_sleaze   = return_Sprite($a_r_sprite['r_sprite_level'], $a_r_sprites['sprite_sleaze']);
      $sprite_data     = return_Sprite($a_r_sprite['r_sprite_level'], $a_r_sprites['sprite_data']);
      $sprite_firewall = return_Sprite($a_r_sprite['r_sprite_level'], $a_r_sprites['sprite_firewall']);

      $sprite_initiative = "(Lx2) + " . $a_r_sprite['sprite_initiative'];
      if ($a_r_sprite['r_sprite_level'] > 0) {
        $sprite_initiative = (($a_r_sprite['r_sprite_level'] * 2) + $a_r_sprite['sprite_initiative']);
      }

      $output .= "<tr>";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_sprite['sprite_name']    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_sprite['r_sprite_level'] . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_sprite['r_sprite_tasks'] . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $registered                   . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $sprite_attack                . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $sprite_sleaze                . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $sprite_data                  . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $sprite_firewall              . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $sprite_initiative . " + 4d6" . "</td>\n";
      $output .= "</tr>";

      $output .= "<tr>\n";
      $sprite_damage = ceil(($a_r_sprite['r_sprite_level'] / 2) + 8);
      $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">" . "Matrix Damage: (" . $sprite_damage . "): ";
      for ($i = 1; $i <= 18; $i++) {
        if ($sprite_damage >= $i) {
          $checked = '';
          if ($i <= $a_r_sprite['r_sprite_conmon']) {
            $checked = 'checked=\"true\"';
          }
          $output .= "<input type=\"checkbox\" " . $checked . " id=\"spritecon" . ${i} . "\"  onclick=\"edit_SpriteCondition(" . ${i} . ", " . $a_r_sprite['r_sprite_id'] . ", 'sprite');\">\n";
        }
      }
      $output .= "</td>\n";
      $output .= "</tr>\n";
    }
    $output .= "</table>\n";
  } else {
     $output = "";
  }

  print "document.getElementById('sprites_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
