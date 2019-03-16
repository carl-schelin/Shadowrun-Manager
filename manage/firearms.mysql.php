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
  $output .=   "<th class=\"ui-state-default\">Accuracy</th>\n";
  $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
  $output .=   "<th class=\"ui-state-default\">AP</th>\n";
  $output .=   "<th class=\"ui-state-default\">Mode</th>\n";
  $output .=   "<th class=\"ui-state-default\">RC</th>\n";
  $output .=   "<th class=\"ui-state-default\">Ammo</th>\n";
  $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
  $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
  $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
  $output .= "</tr>\n";

  $nuyen = '&yen;';
  $q_string  = "select r_fa_id,fa_class,fa_name,fa_acc,fa_damage,fa_type,fa_flag,";
  $q_string .= "fa_ap,fa_mode1,fa_mode2,fa_mode3,fa_rc,fa_fullrc,fa_ammo1,";
  $q_string .= "fa_clip1,fa_ammo2,fa_clip2,fa_avail,fa_perm,fa_cost,ver_book,fa_page ";
  $q_string .= "from r_firearms ";
  $q_string .= "left join firearms on firearms.fa_id = r_firearms.r_fa_number ";
  $q_string .= "left join versions on versions.ver_id = firearms.fa_book ";
  $q_string .= "where r_fa_character = " . $formVars['id'] . " ";
  $q_string .= "order by fa_class,fa_name ";
  $q_r_firearms = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_firearms) > 0) {
    while ($a_r_firearms = mysql_fetch_array($q_r_firearms)) {

      $fa_mode = return_Mode($a_r_firearms['fa_mode1'], $a_r_firearms['fa_mode2'], $a_r_firearms['fa_mode3']);

      $fa_damage = return_Damage($a_r_firearms['fa_damage'], $a_r_firearms['fa_type'], $a_r_firearms['fa_flag']);

      $fa_rc = return_Recoil($a_r_firearms['fa_rc'], $a_r_firearms['fa_fullrc']);

      $fa_ap = return_Penetrate($a_r_firearms['fa_ap']);

      $fa_ammo = return_Ammo($a_r_firearms['fa_ammo1'], $a_r_firearms['fa_clip1'], $a_r_firearms['fa_ammo2'], $a_r_firearms['fa_clip2']);

      $fa_avail = return_Avail($a_r_firearms['fa_avail'], $a_r_firearms['fa_perm']);

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_firearms['fa_class']                                     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_firearms['fa_name']                                      . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_firearms['fa_acc']                                       . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_damage                                                    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_ap                                                        . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_mode                                                      . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_rc                                                        . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_ammo                                                      . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $fa_avail                                                     . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . number_format($a_r_firearms['fa_cost'], 0, '.', ',') . $nuyen . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_firearms['ver_book'] . " " . $a_r_firearms['fa_page']    . "</td>\n";
      $output .= "</tr>\n";

    }
  } else {
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"11\">No Firearms added.</td>\n";
    $output .= "</tr>\n";
  }

  $output .= "</table>\n";

  mysql_free_result($q_r_firearms);

  print "document.getElementById('firearms_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

?>
