<?php
# Script: identity.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "identity.mysql.php";
    $formVars['id'] = clean($_GET['id'], 10);

    if (check_userlevel(3)) {

      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">";
      if (check_userlevel('1') || check_owner($formVars['id'])) {
        $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#identity\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
      }
      $output .= "Identity and License Information";
      if (check_userlevel('1') || check_owner($formVars['id'])) {
        $output .= "</a>";
      }
      $output .= "</th>";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('identity-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"identity-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Knowledge Skill Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Delete (x)</strong> - Clicking the <strong>x</strong> will delete this association from this server.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on an association to edit it.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Association Management</strong> title bar to toggle the <strong>Association Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Type</th>\n";
      $output .=   "<th class=\"ui-state-default\">Identity</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
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
          $output .= "  <td class=\"ui-widget-content delete\">"         . "Shadowrun 5th Core: 24"    . "</td>\n";
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
              $output .= "  <td class=\"ui-widget-content delete\">" . "Shadowrun 5th Core: 419"   . "</td>\n";
              $output .= "</tr>\n";

            }
          } else {
            $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">No Licenses added.</td>\n";
          }
          mysql_free_result($q_r_license);

        }
      } else {
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">No Identities added.</td>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_identity);

      print "document.getElementById('identity_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
