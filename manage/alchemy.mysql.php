<?php
# Script: alchemy.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  header('Content-Type: text/javascript');

  if (isset($_SESSION['username'])) {
    $package = "alchemy.mysql.php";

    $formVars['id'] = clean($_GET['id'], 10);

    if (check_userlevel($db, $AL_Shadowrunner)) {

      $q_string  = "select ver_version ";
      $q_string .= "from versions ";
      $q_string .= "left join runners on runners.runr_version = versions.ver_id ";
      $q_string .= "where runr_id = " . $formVars['id'] . " ";
      $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_versions = mysqli_fetch_array($q_versions);

      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Group</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Class</th>\n";
      $output .=   "<th class=\"ui-state-default\">Type</th>\n";
      $output .=   "<th class=\"ui-state-default\">Test</th>\n";
      $output .=   "<th class=\"ui-state-default\">Range</th>\n";
      $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
      $output .=   "<th class=\"ui-state-default\">Duration</th>\n";
      $output .=   "<th class=\"ui-state-default\">Drain</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_alc_id,r_alc_special,spell_id,spell_name,spell_group,class_name,spell_class,spell_type,spell_test,spell_range,";
      $q_string .= "spell_damage,spell_force,spell_duration,spell_drain,ver_book,spell_page ";
      $q_string .= "from r_alchemy ";
      $q_string .= "left join spells on spells.spell_id = r_alchemy.r_alc_number ";
      $q_string .= "left join class on class.class_id = spells.spell_group ";
      $q_string .= "left join versions on versions.ver_id = spells.spell_book ";
      $q_string .= "where r_alc_character = " . $formVars['id'] . " ";
      $q_string .= "order by spell_group,spell_name ";
      $q_r_alchemy = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_alchemy) > 0) {
        while ($a_r_alchemy = mysqli_fetch_array($q_r_alchemy)) {

          $spell_name = $a_r_alchemy['spell_name'];
          if (strlen($a_r_alchemy['r_alc_special']) > 0) {
            $spell_name = $a_r_alchemy['spell_name'] . " (" . $a_r_alchemy['r_alc_special'] . ")";
          }

          $spell_drain = return_Drain($a_r_alchemy['spell_drain'], $a_r_alchemy['spell_force']);

          $spell_book = return_Book($a_r_alchemy['ver_book'], $a_r_alchemy['spell_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_alc_number']) && $formVars['r_alc_number'] == $a_r_alchemy['spell_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_alchemy['class_name']             . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $spell_name                . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_alchemy['spell_class']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_alchemy['spell_type']             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_alchemy['spell_test']             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_alchemy['spell_range']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_alchemy['spell_damage']           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_alchemy['spell_duration']         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $spell_drain                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $spell_book                           . "</td>\n";
          $output .= "</tr>\n";

        }
      } else {
       $output .= "<tr>";
       $output .= "<td class=\"ui-widget-content\" colspan=\"10\">No Alchemical Devices found</td>";
       $output .= "</tr>";
      }

      $output .= "</table>\n";

      mysqli_free_result($q_r_alchemy);
    }
  }
?>

document.getElementById('alchemy_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

