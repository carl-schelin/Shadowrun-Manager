<?php
# Script: projectile.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "projectile.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Projectile Weapons</th>";
  $output .= "</tr>\n";

  $output .= "<tr>\n";
  $output .=   "<th class=\"ui-state-default\">Class</th>\n";
  $output .=   "<th class=\"ui-state-default\">Name</th>\n";
  $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
  $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
  $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
  $output .=   "<th class=\"ui-state-default\">AP</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select r_proj_id,proj_id,class_name,proj_name,proj_rating,proj_acc,proj_damage,";
  $q_string .= "proj_type,proj_strength,proj_ap ";
  $q_string .= "from r_projectile ";
  $q_string .= "left join projectile on projectile.proj_id = r_projectile.r_proj_number ";
  $q_string .= "left join class on class.class_id = projectile.proj_class ";
  $q_string .= "where r_proj_character = " . $formVars['id'] . " ";
  $q_string .= "order by class_name,proj_name ";
  $q_r_projectile = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_projectile) > 0) {
    while ($a_r_projectile = mysql_fetch_array($q_r_projectile)) {

      $proj_rating = return_Rating($a_r_projectile['proj_rating']);
      $proj_damage = return_Strength($a_r_projectile['proj_damage'], $a_r_projectile['proj_type'], "", $a_r_projectile['proj_strength']);
      $proj_ap = return_Penetrate($a_r_projectile['proj_ap']);

      $class = "ui-widget-content";

      $output .= "<tr>\n";
      $output .= "  <td class=\"" . $class . "\">"        . $a_r_projectile['class_name'] . "</td>\n";
      $output .= "  <td class=\"" . $class . "\">"        . $a_r_projectile['proj_name']  . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $proj_rating                  . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $a_r_projectile['proj_acc']   . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $proj_damage                  . "</td>\n";
      $output .= "  <td class=\"" . $class . " delete\">" . $proj_ap                      . "</td>\n";
      $output .= "</tr>\n";

# associate any ammo with the weapon
      $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,class_name,ammo_name,ammo_rounds,ammo_mod,ammo_rating,ammo_ap ";
      $q_string .= "from r_ammo ";
      $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
      $q_string .= "left join class on class.class_id = ammo.ammo_class ";
      $q_string .= "where r_ammo_character = " . $formVars['id'] . " and r_ammo_parentid = " . $a_r_projectile['r_proj_id'] . " ";
      $q_string .= "order by ammo_name,class_name ";
      $q_r_ammo = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_ammo) > 0) {
        while ($a_r_ammo = mysql_fetch_array($q_r_ammo)) {

          $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);
          $ammo_rating = return_Rating($a_r_ammo['ammo_rating']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                    . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . "&gt; " . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds']) . " rounds " . $a_r_ammo['ammo_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_rating                                                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . "--"                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_ammo['ammo_mod']                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_ap                                                    . "</td>\n";
          $output .= "</tr>\n";

        }
      }
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('projectile_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
