<?php
# Script: complexform.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "complexform.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $q_string  = "select r_sprite_level ";
  $q_string .= "from r_sprite ";
  $q_string .= "where r_sprite_character = " . $formVars['id'] . " ";
  $q_r_sprite = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_r_sprite = mysqli_fetch_array($q_r_sprite);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Complex Forms</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Complex Form</th>\n";
  $output .= "  <th class=\"ui-state-default\">Target</th>\n";
  $output .= "  <th class=\"ui-state-default\">Duration</th>\n";
  $output .= "  <th class=\"ui-state-default\">Fading</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select r_form_id,r_form_number,form_name,form_target,form_duration,form_fading ";
  $q_string .= "from r_complexform ";
  $q_string .= "left join complexform on complexform.form_id = r_complexform.r_form_number ";
  $q_string .= "where r_form_character = " . $formVars['id'] . " ";
  $q_string .= "order by form_name ";
  $q_r_complexform = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_complexform) > 0) {
    while ($a_r_complexform = mysqli_fetch_array($q_r_complexform)) {

      $target = "Device";
      if ($a_r_complexform['form_target'] == 1) {
        $target = "File";
      }
      if ($a_r_complexform['form_target'] == 2) {
        $target = "Persona";
      }
      if ($a_r_complexform['form_target'] == 3) {
        $target = "Self";
      }
      if ($a_r_complexform['form_target'] == 4) {
        $target = "Sprite";
      }

      $duration = "Immediate";
      if ($a_r_complexform['form_duration'] == 1) {
        $duration = "Permanent";
      }
      if ($a_r_complexform['form_duration'] == 2) {
        $duration = "Sustained";
      }

#      $fading = 'L';
#      if ($a_r_complexform['form_fading'] > 0) {
#        $fading = "L+" . $a_r_complexform['form_fading'];
#      }
#      if ($a_r_complexform['form_fading'] < 0) {
#        $fading = "L" . $a_r_complexform['form_fading'];
#      }
      $fading = ($a_r_sprite['r_sprite_level'] + $a_r_complexform['form_fading']);

      $class = "ui-widget-content";

      $output .= "<tr>\n";
      $output .= "  <td class=\"" . $class . "\">"        . $a_r_complexform['form_name'] . "</td>\n";
      $output .= "  <td class=\"" . $class . "\">"        . $target                       . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $duration                     . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $fading                       . "</td>\n";
      $output .= "</tr>\n";
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('complexform_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
