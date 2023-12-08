<?php
# Script: identity.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "identity.mysql.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Identification / Lifestyles / Currencies</th>";
  $output .= "</tr>\n";
  $output .= "<tr>\n";
  $output .=   "<th class=\"ui-state-default\">Type</th>\n";
  $output .=   "<th class=\"ui-state-default\">Identity</th>\n";
  $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select id_id,id_name,id_type,id_rating,id_background ";
  $q_string .= "from r_identity ";
  $q_string .= "where id_character = " . $formVars['id'] . " ";
  $q_string .= "order by id_name ";
  $q_r_identity = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_identity) > 0) {
    while ($a_r_identity = mysqli_fetch_array($q_r_identity)) {

      if ($a_r_identity['id_type'] == 2) {
        $type = "Criminal SIN: ";
      } else {
        if ($a_r_identity['id_type'] == 1) {
          $type = "Fake SIN: ";
        } else {
          $type = "SIN: ";
        }
      }

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"                . "Identity"                  . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $type . $a_r_identity['id_name']    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">"         . $a_r_identity['id_rating']  . "</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">" . "Background: " . $a_r_identity['id_background'] . "</td>\n";
      $output .= "</tr>\n";

      $q_string  = "select lic_id,lic_type,lic_rating ";
      $q_string .= "from r_license ";
      $q_string .= "where lic_identity = " . $a_r_identity['id_id'] . " ";
      $q_string .= "order by lic_type ";
      $q_r_license = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_license) > 0) {
        while ($a_r_license = mysqli_fetch_array($q_r_license)) {

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . "License"                   . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">&gt "    . $a_r_license['lic_type']    . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_license['lic_rating']  . "</td>\n";
          $output .= "</tr>\n";

        }
      } else {
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">No Licenses added.</td>\n";
      }
    }
    $output .= "</table>\n";
  } else {
    $output = "";
  }

  print "document.getElementById('identity_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
