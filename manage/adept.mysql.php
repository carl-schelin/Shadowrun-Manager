<?php
# Script: adept.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  header('Content-Type: text/javascript');

  if (isset($_SESSION['username'])) {
    $package = "adept.mysql.php";

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
      $output .= "  <th class=\"ui-state-default\">Power Name</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Level</th>\n";
      $output .= "  <th class=\"ui-state-default\">Spent</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_adp_id,r_adp_number,r_adp_level,r_adp_specialize,adp_name,adp_desc,adp_power,adp_level,ver_book,adp_page ";
      $q_string .= "from r_adept ";
      $q_string .= "left join adept on adept.adp_id = r_adept.r_adp_number ";
      $q_string .= "left join versions on versions.ver_id = adept.adp_book ";
      $q_string .= "where r_adp_character = " . $formVars['id'] . " ";
      $q_string .= "order by adp_name ";
      $q_r_adept = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_adept) > 0) {
        while ($a_r_adept = mysqli_fetch_array($q_r_adept)) {

          if ($a_r_adept['adp_level'] == 0) {
            $level = "";
          } else {
            $level = " per level";
          }

          if (strlen($a_r_adept['r_adp_specialize']) > 0) {
            $specialize = " (" . $a_r_adept['r_adp_specialize'] . ")";
          } else {
            $specialize = "";
          }

          if ($a_r_adept['r_adp_level'] == 0) {
            $powerpoints = $a_r_adept['adp_power'];
          } else {
            $powerpoints = ($a_r_adept['adp_power'] * $a_r_adept['r_adp_level']);
          }

          $adept_book = return_Book($a_r_adept['ver_book'], $a_r_adept['adp_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_r_adept['adp_name'] . $specialize . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_r_adept['adp_desc']               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_adept['adp_power'] . $level     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_adept['r_adp_level']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $adept_book                          . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">" . "No Adept Powers added" . "</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      mysqli_free_result($q_r_adept);
    }
  }
?>

document.getElementById('adept_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

