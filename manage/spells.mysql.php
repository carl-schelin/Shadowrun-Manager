<?php
# Script: spells.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  header('Content-Type: text/javascript');

  if (isset($_SESSION['username'])) {
    $package = "spells.mysql.php";

    $formVars['id'] = clean($_GET['id'], 10);

    if (check_userlevel($db, $AL_Shadowrunner)) {

      $q_string  = "select ver_version ";
      $q_string .= "from versions ";
      $q_string .= "left join runners on runners.runr_version = versions.ver_id ";
      $q_string .= "where runr_id = " . $formVars['id'] . " ";
      $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_versions = mysqli_fetch_array($q_versions);

      logaccess($db, $formVars['username'], $package, "Accessing the script.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Group</th>\n";
      $output .= "  <th class=\"ui-state-default\">Spell</th>\n";
      $output .= "  <th class=\"ui-state-default\">Class</th>\n";
      $output .= "  <th class=\"ui-state-default\">Type</th>\n";
      $output .= "  <th class=\"ui-state-default\">Test</th>\n";
      $output .= "  <th class=\"ui-state-default\">Range</th>\n";
      $output .= "  <th class=\"ui-state-default\">Damage</th>\n";
      $output .= "  <th class=\"ui-state-default\">Duration</th>\n";
      $output .= "  <th class=\"ui-state-default\">Drain</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_spell_special,spell_name,class_name,spell_class,spell_group,spell_type,spell_test,spell_range,";
      $q_string .= "spell_damage,spell_force,spell_duration,spell_drain,ver_book,spell_page ";
      $q_string .= "from r_spells ";
      $q_string .= "left join spells on spells.spell_id = r_spells.r_spell_number ";
      $q_string .= "left join class on class.class_id = spells.spell_group ";
      $q_string .= "left join versions on versions.ver_id = spells.spell_book ";
      $q_string .= "where r_spell_character = " . $formVars['id'] . " ";
      $q_string .= "order by class_name,spell_name ";
      $q_r_spells = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_spells) > 0) {
        while ($a_r_spells = mysqli_fetch_array($q_r_spells)) {

          if (strlen($a_r_spells['r_spell_special']) > 0) {
            $special = " (" . $a_r_spells['r_spell_special'] . ")";
          } else {
            $special = '';
          }

          $spell_drain = return_Drain($a_r_spells['spell_drain'], $a_r_spells['spell_force']);

          $spell_book = return_Book($a_r_spells['ver_book'], $a_r_spells['spell_page']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['class_name']                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_spells['spell_name']     . $special . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['spell_class']               . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['spell_type']                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['spell_test']                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['spell_range']               . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['spell_damage']              . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_spells['spell_duration']            . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $spell_drain                             . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $spell_book                              . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">" . "No Spells added" . "</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";
    }
  }
?>

document.getElementById('spells_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

