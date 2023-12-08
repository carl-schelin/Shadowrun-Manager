<?php
# Script: firearms.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  $package = "firearms.mysql.php";
  $formVars['id'] = clean($_GET['id'], 10);

  logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

  $q_string  = "select ver_version ";
  $q_string .= "from versions ";
  $q_string .= "left join runners on runners.runr_version = versions.ver_id ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_versions = mysqli_fetch_array($q_versions);

  $output  = "<p></p>\n";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";

  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#weapons\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Firearms Information";
  if (check_userlevel('1') || check_owner($formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('firearms-listing-help');\">Help</a></th>\n";
  $output .= "</tr>\n";
  $output .= "</table>\n";

  $output .= "<div id=\"firearms-listing-help\" style=\"display: none\">\n";

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
  }
  $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
  if ($a_versions['ver_version'] == 5.0) {
    $output .=   "<th class=\"ui-state-default\">AP</th>\n";
  }
  $output .=   "<th class=\"ui-state-default\">Mode</th>\n";
  if ($a_versions['ver_version'] == 5.0) {
    $output .=   "<th class=\"ui-state-default\">RC</th>\n";
  }
  if ($a_versions['ver_version'] == 6.0) {
    $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
  }
  $output .=   "<th class=\"ui-state-default\">Ammo</th>\n";
  $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
  $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
  $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
  $output .= "</tr>\n";

  $totalcost = 0;
  $q_string  = "select r_fa_id,class_name,fa_name,fa_acc,fa_damage,fa_type,fa_flag,";
  $q_string .= "fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_rc,fa_fullrc,fa_ammo1,";
  $q_string .= "fa_ar1,fa_ar2,fa_ar3,fa_ar4,fa_ar5,";
  $q_string .= "fa_clip1,fa_ammo2,fa_clip2,fa_avail,fa_perm,fa_cost,ver_book,fa_page ";
  $q_string .= "from r_firearms ";
  $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
  $q_string .= "left join class on class.class_id = firearms.fa_class ";
  $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
  $q_string .= "where r_fa_character = " . $formVars['id'] . " ";
  $q_string .= "order by fa_class,fa_name ";
  $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_firearms) > 0) {
    while ($a_r_firearms = mysqli_fetch_array($q_r_firearms)) {

      $fa_damage = return_Damage($a_r_firearms['fa_damage'], $a_r_firearms['fa_type'], $a_r_firearms['fa_flag']);

      $fa_ap = return_Penetrate($a_r_firearms['fa_ap']);

      $fa_mode = return_Mode($a_r_firearms['fa_mode1'], $a_r_firearms['fa_mode2'], $a_r_firearms['fa_mode3']);

      $fa_attack = return_Attack($a_r_firearms['fa_ar1'], $a_r_firearms['fa_ar2'], $a_r_firearms['fa_ar3'], $a_r_firearms['fa_ar4'], $a_r_firearms['fa_ar5']);

      $fa_rc = return_Recoil($a_r_firearms['fa_rc'], $a_r_firearms['fa_fullrc']);

      $totalcost += $a_r_firearms['fa_cost'];
      $fa_cost = return_Cost($a_r_firearms['fa_cost']);

      $fa_book = return_Book($a_r_firearms['ver_book'], $a_r_firearms['fa_page']);

      $fa_ammo = return_Ammo($a_r_firearms['fa_ammo1'], $a_r_firearms['fa_clip1'], $a_r_firearms['fa_ammo2'], $a_r_firearms['fa_clip2']);

      $fa_avail = return_Avail($a_r_firearms['fa_avail'], $a_r_firearms['fa_perm']);

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_firearms['class_name']                                   . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_firearms['fa_name']                                      . "</td>\n";
      if ($a_versions['ver_version'] == 5.0) {
        $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_firearms['fa_acc']                                       . "</td>\n";
      }
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_damage                                                    . "</td>\n";
      if ($a_versions['ver_version'] == 5.0) {
        $output .= "  <td class=\"ui-widget-content delete\">" . $fa_ap                                                        . "</td>\n";
      }
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_mode                                                      . "</td>\n";
      if ($a_versions['ver_version'] == 6.0) {
        $output .= "  <td class=\"ui-widget-content delete\">" . $fa_attack                                                    . "</td>\n";
      }
      if ($a_versions['ver_version'] == 5.0) {
        $output .= "  <td class=\"ui-widget-content delete\">" . $fa_rc                                                        . "</td>\n";
      }
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_ammo                                                      . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_avail                                                     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_cost                               . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_book                                 . "</td>\n";
      $output .= "</tr>\n";

    }
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">Total Cost: " . return_Cost($totalcost) . "</td>\n";
    $output .= "</tr>\n";
  } else {
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No Firearms added.</td>\n";
    $output .= "</tr>\n";
  }

  $output .= "</table>\n";

  mysql_free_result($q_r_firearms);

  print "document.getElementById('firearms_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

?>
