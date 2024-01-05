<?php
# Script: qualities.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  header('Content-Type: text/javascript');

  if (isset($_SESSION['username'])) {
    $package = "qualities.mysql.php";

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
      $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Positive Qualities</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Quality</th>\n";
      $output .= "  <th class=\"ui-state-default\">Value</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select qual_name,qual_value,qual_desc,ver_book,qual_page,r_qual_details ";
      $q_string .= "from r_qualities ";
      $q_string .= "left join qualities on qualities.qual_id = r_qualities.r_qual_number ";
      $q_string .= "left join versions on versions.ver_id = qualities.qual_book ";
      $q_string .= "where r_qual_character = " . $formVars['id'] . " and qual_value > 0 ";
      $q_string .= "order by qual_name ";
      $q_r_qualities = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_qualities) > 0) {
        while ($a_r_qualities = mysqli_fetch_array($q_r_qualities)) {

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_qualities['qual_name']  . (strlen($a_r_qualities['r_qual_details']) ? " (" . $a_r_qualities['r_qual_details'] . ")" : '') . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_qualities['qual_value']                                                                                                   . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_qualities['qual_desc']                                                                                                    . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_qualities['ver_book']  . ": " . $a_r_qualities['qual_page']                                                               . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>";
        $output .= "<td class=\"ui-widget-content\" colspan=\"4\">No Positive Qualities found</td>";
        $output .= "</tr>";
      }

      $output .= "</table>\n";


      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Negative Qualities</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Quality</th>\n";
      $output .= "  <th class=\"ui-state-default\">Value</th>\n";
      $output .= "  <th class=\"ui-state-default\">Description</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select qual_name,qual_value,qual_desc,ver_book,qual_page,r_qual_details ";
      $q_string .= "from r_qualities ";
      $q_string .= "left join qualities on qualities.qual_id = r_qualities.r_qual_number ";
      $q_string .= "left join versions on versions.ver_id = qualities.qual_book ";
      $q_string .= "where r_qual_character = " . $formVars['id'] . " and qual_value < 0 ";
      $q_string .= "order by qual_name ";
      $q_r_qualities = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_qualities) > 0) {
        while ($a_r_qualities = mysqli_fetch_array($q_r_qualities)) {

          if (strlen($a_r_qualities['r_qual_details']) > 0) {
            $details = " (" . $a_r_qualities['r_qual_details'] . ")";
          } else {
            $details = '';
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_qualities['qual_name']                          . $details . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_qualities['qual_value']                                    . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_qualities['qual_desc']                                     . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $a_r_qualities['ver_book']  . ": " . $a_r_qualities['qual_page'] . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>";
        $output .= "<td class=\"ui-widget-content\" colspan=\"4\">No Negative Qualities found</td>";
        $output .= "</tr>";
      }

      $output .= "</table>\n";
    }
  }
?>

document.getElementById('qualities_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

