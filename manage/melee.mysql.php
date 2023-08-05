<?php
# Script: melee.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  $package = "melee.mysql.php";
  $formVars['id'] = clean($_GET['id'], 10);

  logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

  $q_string  = "select ver_version ";
  $q_string .= "from versions ";
  $q_string .= "left join runners on runners.runr_version = versions.ver_id ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_versions = mysql_fetch_array($q_versions);

  $output  = "<p></p>\n";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#weapons\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Melee Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('melee-listing-help');\">Help</a></th>\n";
  $output .= "</tr>\n";
  $output .= "</table>\n";

  $output .= "<div id=\"melee-listing-help\" style=\"display: none\">\n";

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
  if ($a_versions['ver_version'] == 5.0) {
    $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
    $output .=   "<th class=\"ui-state-default\">Reach</th>\n";
  }
  $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
  if ($a_versions['ver_version'] == 5.0) {
    $output .=   "<th class=\"ui-state-default\">AP</th>\n";
  }
  if ($a_versions['ver_version'] == 6.0) {
    $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
  }
  $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
  $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
  $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
  $output .= "</tr>\n";

  $totalcost = 0;
  $q_string  = "select r_melee_id,class_name,melee_name,melee_acc,melee_reach,melee_damage,";
  $q_string .= "melee_type,melee_flag,melee_strength,melee_ap,melee_avail,";
  $q_string .= "melee_perm,melee_cost,ver_book,melee_page,melee_ar1,melee_ar2,melee_ar3,melee_ar4,melee_ar5 ";
  $q_string .= "from r_melee ";
  $q_string .= "left join melee on melee.melee_id = r_melee.r_melee_number ";
  $q_string .= "left join class on class.class_id = melee.melee_class ";
  $q_string .= "left join versions on versions.ver_id = melee.melee_book ";
  $q_string .= "where r_melee_character = " . $formVars['id'] . " ";
  $q_string .= "order by class_name,melee_name ";
  $q_r_melee = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_melee) > 0) {
    while ($a_r_melee = mysql_fetch_array($q_r_melee)) {

      $melee_reach = '--';
      if ($a_r_melee['melee_reach'] > 0) {
        $melee_reach = $a_r_melee['melee_reach'];
      }

# melee title is the (str/2 + 1) stuff.
# melee damage is the actual score based on your stats
      $melee_title = "";
      $melee_damage = "";
      if ($a_r_melee['melee_strength']) {
        $melee_title = "(STR + " . $a_r_melee['melee_damage'] . ")";

        $q_string  = "select runr_strength ";
        $q_string .= "from runners ";
        $q_string .= "where runr_id = " . $formVars['id'] . " ";
        $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        $a_runners = mysql_fetch_array($q_runners);

        $melee_damage = ($a_runners['runr_strength'] + $a_r_melee['melee_damage']);
      } else {
        if ($a_r_melee['melee_damage'] != 0) {
          $melee_damage = $a_r_melee['melee_damage'];
        }
      }

      if (strlen($a_r_melee['melee_type']) > 0) {
        $melee_damage .= $a_r_melee['melee_type'];
      }
      if (strlen($a_r_melee['melee_flag']) > 0) {
        $melee_damage .= "(" . $a_r_melee['melee_flag'] . ")";
      }

      $melee_ap = '--';
      if ($a_r_melee['melee_ap'] != 0) {
        $melee_ap = $a_r_melee['melee_ap'];
      }

      $attack = return_Attack($a_r_melee['melee_ar1'], $a_r_melee['melee_ar2'], $a_r_melee['melee_ar3'], $a_r_melee['melee_ar4'], $a_r_melee['melee_ar5']);

      $avail = return_Avail($a_r_melee['melee_avail'], $a_r_melee['melee_perm']);

      $totalcost += $a_r_melee['melee_cost'];
      $cost = return_Cost($a_r_melee['melee_cost']);

      $book = return_Book($a_r_melee['ver_book'], $a_r_melee['melee_page']);

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"                . $a_r_melee['class_name']             . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"                . $a_r_melee['melee_name']             . "</td>\n";
      if ($a_versions['ver_version'] == 5.0) {
        $output .= "  <td class=\"delete ui-widget-content\">"       . $a_r_melee['melee_acc']              . "</td>\n";
        $output .= "  <td class=\"delete ui-widget-content\">"       . $melee_reach                         . "</td>\n";
      }
      $output .= "  <td class=\"delete ui-widget-content\" title=\"" . $melee_title . "\">" . $melee_damage . "</td>\n";
      if ($a_versions['ver_version'] == 5.0) {
        $output .= "  <td class=\"delete ui-widget-content\">"       . $melee_ap                            . "</td>\n";
      }
      if ($a_versions['ver_version'] == 6.0) {
        $output .= "  <td class=\"delete ui-widget-content\">"       . $attack                              . "</td>\n";
      }
      $output .= "  <td class=\"delete ui-widget-content\">"         . $avail                               . "</td>\n";
      $output .= "  <td class=\"delete ui-widget-content\">"         . $cost                                . "</td>\n";
      $output .= "  <td class=\"delete ui-widget-content\">"         . $book                                . "</td>\n";
      $output .= "</tr>\n";
    }
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">Total Cost: " . return_Cost($totalcost) . "</td>\n";
    $output .= "</tr>\n";
  } else {
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No Melee Weapons added.</td>\n";
    $output .= "</tr>\n";
  }

  $output .= "</table>\n";

  mysql_free_result($q_r_melee);

  print "document.getElementById('melee_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

?>
