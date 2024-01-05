<?php
# Script: traditions.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  header('Content-Type: text/javascript');

  if (isset($_SESSION['username'])) {
    $package = "traditions.mysql.php";

    $formVars['id'] = clean($_GET['id'], 10);

    if (check_userlevel($db, $AL_Shadowrunner)) {

      $q_string  = "select ver_version ";
      $q_string .= "from versions ";
      $q_string .= "left join runners on runners.runr_version = versions.ver_id ";
      $q_string .= "where runr_id = " . $formVars['id'] . " ";
      $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_versions = mysqli_fetch_array($q_versions);

      logaccess($db, $formVars['username'], $package, "Accessing the script.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\">Tradition</th>";
      $output .= "  <th class=\"ui-state-default\">Description</th>";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
      $output .= "</tr>";

      $q_string  = "select r_trad_id,trad_name,trad_description,ver_book,trad_page ";
      $q_string .= "from r_tradition ";
      $q_string .= "left join tradition on tradition.trad_id = r_tradition.r_trad_number ";
      $q_string .= "left join versions on versions.ver_id = tradition.trad_book ";
      $q_string .= "where r_trad_character = " . $formVars['id'] . " ";
      $q_r_tradition = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_tradition) > 0) {
        while ($a_r_tradition = mysqli_fetch_array($q_r_tradition)) {

          $tradition_book = return_Book($a_r_tradition['ver_book'], $a_r_tradition['trad_page']);

          $output .= "<tr>";
          $output .= "<td class=\"ui-widget-content\">"        . $a_r_tradition['trad_name']        . "</td>";
          $output .= "<td class=\"ui-widget-content\">"        . $a_r_tradition['trad_description'] . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $tradition_book                    . "</td>";
          $output .= "</tr>";

        }
      } else {
         $output .= "<tr>";
         $output .= "<td class=\"ui-widget-content\" colspan=\"3\">No Traditions found</td>";
         $output .= "</tr>";
      }

      $output .= "</table>";
    }
  }     
?>

document.getElementById('traditions_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

