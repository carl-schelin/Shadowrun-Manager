<?php
# Script: ammunition.mysql.php
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
    $package = "ammunition.mysql.php";

    $formVars['id']= clean($_GET['id'], 10);

    logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

    $q_string  = "select ver_version ";
    $q_string .= "from versions ";
    $q_string .= "left join runners on runners.runr_version = versions.ver_id ";
    $q_string .= "where runr_id = " . $formVars['id'] . " ";
    $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    $a_versions = mysqli_fetch_array($q_versions);

    $output  = "<p></p>\n";
    $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\">My Ammunition</th>\n";
    $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('ammo-listing-help');\">Help</a></th>\n";
    $output .= "</tr>\n";
    $output .= "</table>\n";

    $output .= "<div id=\"ammo-listing-help\" style=\"display: none\">\n";

    $output .= "<div class=\"main-help ui-widget-content\">\n";

    $output .= "<ul>\n";
    $output .= "  <li><strong>Spell Listing</strong>\n";
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
    $output .=   "<th class=\"ui-state-default\">Class</th>\n";
    $output .=   "<th class=\"ui-state-default\">Name</th>\n";
    $output .=   "<th class=\"ui-state-default\">Rounds</th>\n";
    $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
    $output .=   "<th class=\"ui-state-default\">Damage Modifier</th>\n";
    if ($a_versions['ver_version'] == 5.0) {
      $output .=   "<th class=\"ui-state-default\">AP Modifier</th>\n";
      $output .=   "<th class=\"ui-state-default\">Blast Radius</th>\n";
    }
    $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
    $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
    $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
    $output .= "</tr>\n";

    $costgrade = 0;
    $totalcost = 0;
    $q_string  = "select r_ammo_id,r_ammo_rounds,ammo_id,class_name,ammo_name,ammo_rounds,ammo_rating,ammo_mod,ammo_ap,";
    $q_string .= "ammo_blast,ammo_avail,ammo_perm,ammo_cost,ver_book,ammo_page ";
    $q_string .= "from r_ammo ";
    $q_string .= "left join ammo on ammo.ammo_id = r_ammo.r_ammo_number ";
    $q_string .= "left join class on class.class_id = ammo.ammo_class ";
    $q_string .= "left join versions on versions.ver_id = ammo.ammo_book ";
    $q_string .= "where r_ammo_character = " . $formVars['id'] . " ";
    $q_string .= "order by ammo_name,ammo_rating,class_name ";
    $q_r_ammo = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_r_ammo) > 0) {
      while ($a_r_ammo = mysqli_fetch_array($q_r_ammo)) {

        $ammo_rating = return_Rating($a_r_ammo['ammo_rating']);

        $ammo_ap = return_Penetrate($a_r_ammo['ammo_ap']);

        $ammo_avail = return_Avail($a_r_ammo['ammo_avail'], $a_r_ammo['ammo_perm']);

        $costgrade = ($a_r_ammo['ammo_cost'] * $a_r_ammo['r_ammo_rounds']);
        $totalcost += $costgrade;

        $ammo_cost = return_Cost($a_r_ammo['ammo_cost']);

        $ammo_book = return_Book($a_r_ammo['ver_book'], $a_r_ammo['ammo_page']);

        $class = "ui-widget-content";
        if (isset($formVars['r_ammo_number']) && $formVars['r_ammo_number'] == $a_r_ammo['ammo_id']) {
          $class = "ui-state-error";
        }

        $output .= "<tr>\n";
        $output .= "  <td class=\"" . $class . "\">"        . $a_r_ammo['class_name']                                     . "</td>\n";
        $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_ammo['ammo_name']              . $linkend . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . ($a_r_ammo['r_ammo_rounds'] * $a_r_ammo['ammo_rounds'])     . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $ammo_rating                                                . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $a_r_ammo['ammo_mod']                                       . "</td>\n";
        if ($a_versions['ver_version'] == 5.0) {
          $output .= "  <td class=\"" . $class . " delete\">" . $ammo_ap                                                    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_ammo['ammo_blast']                                     . "</td>\n";
        }
        $output .= "  <td class=\"" . $class . " delete\">" . $ammo_avail                                                 . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $ammo_cost                                                  . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $ammo_book                                                  . "</td>\n";
        $output .= "</tr>\n";

      }
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">Total Cost: " . return_Cost($totalcost) . ".</td>\n";
      $output .= "</tr>\n";

    } else {
      $output .= "<tr>";
      $output .= "<td class=\"ui-widget-content\" colspan=\"10\">No Ammunition selected.</td>";
      $output .= "</tr>";
    }

    $output .= "</table>\n";

    mysql_free_result($q_r_ammo);

    print "document.getElementById('ammunition_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

  }
?>
