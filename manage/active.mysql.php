<?php
# Script: active.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  header('Content-Type: text/javascript');

  if (isset($_SESSION['username'])) {
    $package = "active.mysql.php";

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
      $output .= "  <th class=\"ui-state-default\">Active Group</th>\n";
      $output .= "  <th class=\"ui-state-default\">Active Skill</th>\n";
      $output .= "  <th class=\"ui-state-default\">Skill Rank</th>\n";
      $output .= "  <th class=\"ui-state-default\">Associated Attribute</th>\n";
      $output .= "  <th class=\"ui-state-default\">Attribute Score</th>\n";
      $output .= "  <th class=\"ui-state-default\">Dice Pool</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select act_id,act_name,act_group,att_name,att_column,act_default,ver_book,act_page ";
      $q_string .= "from active ";
      $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
      $q_string .= "left join versions on versions.ver_id = active.act_book ";
      $q_string .= "where ver_active = 1 ";
      $q_string .= "order by act_name,act_group ";
      $q_active = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ($a_active = mysqli_fetch_array($q_active)) {

        if ($a_active['act_default']) {
          $italicstart = "<i>";
          $italicend = "</i>";
        }

        $class = "ui-widget-content";

        $q_string  = "select " . $a_active['att_column'] . " ";
        $q_string .= "from runners ";
        $q_string .= "where runr_id = " . $formVars['id'] . " ";
        $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        $a_runners = mysqli_fetch_array($q_runners);
        $attribute = $a_runners[$a_active['att_column']];

        $q_string  = "select r_act_rank,r_act_specialize ";
        $q_string .= "from r_active ";
        $q_string .= "where r_act_character = " . $formVars['id'] . " and r_act_number = " . $a_active['act_id'] . " ";
        $q_r_active = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        if (mysqli_num_rows($q_r_active) > 0) {
          $a_r_active = mysqli_fetch_array($q_r_active);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " button\">" . $a_active['act_group']                               . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $italicstart . $a_active['act_name'] . $italicend    . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_r_active['r_act_rank']                            . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_active['att_name']                                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $attribute                                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . ($attribute + $a_r_active['r_act_rank'])             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $a_active['ver_book'] . ": " . $a_active['act_page'] . "</td>\n";
          $output .= "</tr>\n";

          if (strlen($a_r_active['r_act_specialize']) > 0) {
            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                             . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">&gt;"    . $a_r_active['r_act_specialize']                      . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $a_r_active['r_act_rank']                            . " + 2</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $a_active['att_name']                                . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $attribute                                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . ($attribute + $a_r_active['r_act_rank'] + 2)         . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $a_active['ver_book'] . ": " . $a_active['act_page'] . "</td>\n";
            $output .= "</tr>\n";
          }

        } else {

          if ($a_active['act_default']) {
            $active = $attribute - 1;
            $a_r_active['r_act_rank'] = '-1';

            $class = "ui-state-highlight";

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . " button\">" . $a_active['act_group']                               . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $italicstart . $a_active['act_name'] . $italicend    . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $a_r_active['r_act_rank']                            . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $a_active['att_name']                                . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $attribute                                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $active                                              . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $a_active['ver_book'] . ": " . $a_active['act_page'] . "</td>\n";
            $output .= "</tr>\n";

          } else {
            $active = 'Unavailable';
            $a_r_active['r_act_rank'] = 'Unavailable';

            $class = "ui-state-error";

#            $output .= "<tr>\n";
#            $output .= "  <td class=\"" . $class . " button\">" . $a_active['act_group']                               . "</td>\n";
#            $output .= "  <td class=\"" . $class . "\">"        . $a_active['act_name']                                . "</td>\n";
#            $output .= "  <td class=\"" . $class . " delete\">" . $a_r_active['r_act_rank']                            . "</td>\n";
#            $output .= "  <td class=\"" . $class . "\">"        . $a_active['att_name']                                . "</td>\n";
#            $output .= "  <td class=\"" . $class . " delete\">" . $attribute                                           . "</td>\n";
#            $output .= "  <td class=\"" . $class . " delete\">" . $active                                              . "</td>\n";
#            $output .= "  <td class=\"" . $class . " delete\">" . $a_active['ver_book'] . ": " . $a_active['act_page'] . "</td>\n";
#            $output .= "</tr>\n";
          }
        }
      }

      $output .= "</table>\n";
      $output .= "<p><i>Italic Skills</i> indicate skills you can default on.</p>\n";
    }
  }
?>

document.getElementById('active_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

