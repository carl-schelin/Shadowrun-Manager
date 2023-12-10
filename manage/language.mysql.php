<?php
# Script: language.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "language.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
  $output .= "<tr>";
  $output .= "  <th class=\"ui-state-default\">";
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    $output .= "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#language\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
  $output .= "Language Information";
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    $output .= "</a>";
  }
  $output .= "</th>";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('language-help');\">Help</a></th>";
  $output .= "</tr>";
  $output .= "</table>";

  $output .= "<div id=\"language-help\" style=\"display: none\">";

  $output .= "<div class=\"main-help ui-widget-content\">";

  $output .= "<ul>";
  $output .= "  <li><strong>Language</strong> - .</li>";
  $output .= "</ul>";

  $output .= "</div>";

  $output .= "</div>";


  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Language</th>\n";
  $output .= "  <th class=\"ui-state-default\">Skill Rank</th>\n";
  $output .= "  <th class=\"ui-state-default\">Associated Attribute</th>\n";
  $output .= "  <th class=\"ui-state-default\">Attribute Score</th>\n";
  $output .= "  <th class=\"ui-state-default\">Dice Pool</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select lang_name,lang_attribute,r_lang_rank,r_lang_specialize,r_lang_expert ";
  $q_string .= "from r_language ";
  $q_string .= "left join language on language.lang_id = r_language.r_lang_number ";
  $q_string .= "where r_lang_character = " . $formVars['id'] . " ";
  $q_string .= "order by lang_name ";
  $q_r_language = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_language) > 0) {
    while ($a_r_language = mysqli_fetch_array($q_r_language)) {

      $q_string  = "select s_lang_name,s_lang_attribute,ver_book,s_lang_page ";
      $q_string .= "from s_language ";
      $q_string .= "left join versions on versions.ver_id = s_language.s_lang_book ";
      $q_string .= "where s_lang_id = " . $a_r_language['lang_attribute'];
      $q_s_language = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_s_language = mysqli_fetch_array($q_s_language);

      $q_string  = "select att_name,att_column ";
      $q_string .= "from attributes ";
      $q_string .= "where att_id = " . $a_s_language['s_lang_attribute'] . " ";
      $q_attributes = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_attributes = mysqli_fetch_array($q_attributes);

      $q_string  = "select " . $a_attributes['att_column'] . " ";
      $q_string .= "from runners ";
      $q_string .= "where runr_id = " . $formVars['id'] . " ";
      $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_runners = mysqli_fetch_array($q_runners);

      if ($a_r_language['r_lang_rank'] == 0) {
        $r_lang_rank = "Native Speaker";
        $r_lang_dicepool = "Native Speaker";
      } else {
        $r_lang_rank = $a_r_language['r_lang_rank'];
        $r_lang_dicepool = $a_r_language['r_lang_rank'] + $a_runners[$a_attributes['att_column']];
      }

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_language['lang_name']                                      . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $r_lang_rank                                                    . "</td>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_attributes['att_name']                                       . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners[$a_attributes['att_column']]                         . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $r_lang_dicepool                                                . "</td>\n";
      $output .= "</tr>\n";

      if (strlen($a_r_language['r_lang_specialize']) > 0) {
        $expert = "";
        $increase = 2;
        if ($a_r_language['r_lang_expert']) {
          $expert = " *";
          $increase = 3;
        }

        if ($a_r_language['r_lang_rank'] == 0) {
          $r_lang_rank = "Native Speaker";
          $r_lang_dicepool = "Native Speaker";
        } else {
          $r_lang_rank = $a_r_language['r_lang_rank'] + $increase;
          $r_lang_dicepool = $a_r_language['r_lang_rank'] + $a_runners[$a_attributes['att_column']] + $increase;
        }

        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">"        . "&gt; " . $a_r_language['r_lang_specialize'] . $expert          . "</td>\n";
        $output .= "  <td class=\"ui-widget-content delete\">" . $r_lang_rank                                                    . "</td>\n";
        $output .= "  <td class=\"ui-widget-content\">"        . $a_attributes['att_name']                                       . "</td>\n";
        $output .= "  <td class=\"ui-widget-content delete\">" . $a_runners[$a_attributes['att_column']]                         . "</td>\n";
        $output .= "  <td class=\"ui-widget-content delete\">" . $r_lang_dicepool                                                . "</td>\n";
        $output .= "</tr>\n";
      }
    }
  } else {
    $output .= "<tr>";
    $output .= "<td class=\"ui-widget-content\" colspan=\"6\">No Languages found</td>";
    $output .= "</tr>";
  }

  $output .= "</table>\n";
?>

document.getElementById('language_mysql').innerHTML = '<?php print mysqli_real_escape_string($db, $output); ?>';

