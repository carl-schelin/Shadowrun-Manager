<?php
# Script: sprites.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  header('Content-Type: text/javascript');

  if (isset($_SESSION['username'])) {
    $package = "sprites.mysql.php";

    $formVars['id'] = clean($_GET['id'], 10);

    if (check_userlevel($db, $AL_Shadowrunner)) {

      $q_string  = "select ver_version ";
      $q_string .= "from versions ";
      $q_string .= "left join runners on runners.runr_version = versions.ver_id ";
      $q_string .= "where runr_id = " . $formVars['id'] . " ";
      $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_versions = mysqli_fetch_array($q_versions);

      logaccess($db, $formVars['username'], $package, "Accessing the script.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\">Sprite</th>";
      $output .= "  <th class=\"ui-state-default\">Level</th>";
      $output .= "  <th class=\"ui-state-default\">Tasks</th>";
      $output .= "  <th class=\"ui-state-default\">Registered</th>";
      $output .= "  <th class=\"ui-state-default\">Attack</th>";
      $output .= "  <th class=\"ui-state-default\">Sleaze</th>";
      $output .= "  <th class=\"ui-state-default\">Data</th>";
      $output .= "  <th class=\"ui-state-default\">Firewall</th>";
      $output .= "  <th class=\"ui-state-default\">Initiative</th>";
      $output .= "</tr>";

      $q_string  = "select r_sprite_id,sprite_name,r_sprite_level,r_sprite_tasks,r_sprite_registered,";
      $q_string .= "sprite_attack,sprite_sleaze,sprite_data,sprite_firewall,sprite_initiative ";
      $q_string .= "from r_sprite ";
      $q_string .= "left join sprites on sprites.sprite_id = r_sprite.r_sprite_number ";
      $q_string .= "where r_sprite_character = " . $formVars['id'] . " ";
      $q_r_sprite = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_sprite) > 0) {
        while ($a_r_sprite = mysqli_fetch_array($q_r_sprite)) {

          $registered = 'No';
          if ($a_r_sprite['r_sprite_registered']) {
            $registered = 'Yes';
          }

          $output .= "<tr>";
          $output .= "<td class=\"ui-widget-content\">"        . $a_r_sprite['sprite_name']     . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $a_r_sprite['r_sprite_level']    . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $a_r_sprite['r_sprite_tasks'] . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $bound                         . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $a_r_sprite['sprite_attack'] . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $a_r_sprite['sprite_sleaze'] . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $a_r_sprite['sprite_data'] . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $a_r_sprite['sprite_firewall'] . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $a_r_sprite['sprite_initiative'] . "</td>";
          $output .= "</tr>";

        }
      } else {
         $output .= "<tr>";
         $output .= "<td class=\"ui-widget-content\" colspan=\"9\">No Sprites found</td>";
         $output .= "</tr>";
      }

      $output .= "</table>";
    }
  }
?>

document.getElementById('sprites_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

