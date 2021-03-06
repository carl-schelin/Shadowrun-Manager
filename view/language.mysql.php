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

  logaccess($formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);

  $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Language Skills</th>";
  $output .= "</tr>\n";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .= "  <th class=\"ui-state-default\">Language</th>\n";
  $output .= "  <th class=\"ui-state-default\">Dice Pool</th>\n";
  $output .= "</tr>\n";

  $q_string  = "select lang_name,lang_attribute,r_lang_rank,r_lang_specialize ";
  $q_string .= "from r_language ";
  $q_string .= "left join language on language.lang_id = r_language.r_lang_number ";
  $q_string .= "where r_lang_character = " . $formVars['id'] . " ";
  $q_string .= "order by lang_name ";
  $q_r_language = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_language) > 0) {
    while ($a_r_language = mysql_fetch_array($q_r_language)) {

      $q_string  = "select s_lang_name,s_lang_attribute,ver_book,s_lang_page ";
      $q_string .= "from s_language ";
      $q_string .= "left join versions on versions.ver_id = s_language.s_lang_book ";
      $q_string .= "where s_lang_id = " . $a_r_language['lang_attribute'];
      $q_s_language = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_s_language = mysql_fetch_array($q_s_language);

      $q_string  = "select att_name,att_column ";
      $q_string .= "from attributes ";
      $q_string .= "where att_id = " . $a_s_language['s_lang_attribute'] . " ";
      $q_attributes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_attributes = mysql_fetch_array($q_attributes);

      $q_string  = "select " . $a_attributes['att_column'] . " ";
      $q_string .= "from runners ";
      $q_string .= "where runr_id = " . $formVars['id'] . " ";
      $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_runners = mysql_fetch_array($q_runners);

      if ($a_r_language['r_lang_rank'] == 0) {
        $r_lang_rank = "Native Speaker";
        $r_lang_dicepool = "Native Speaker";
      } else {
        $r_lang_rank = $a_r_language['r_lang_rank'];
        $r_lang_dicepool = $a_r_language['r_lang_rank'] + $a_runners[$a_attributes['att_column']];
      }

      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">"        . $a_r_language['lang_name']                                      . "</td>\n";
      $output .= "  <td class=\"ui-widget-content delete\">" . $r_lang_dicepool                                                . "</td>\n";
      $output .= "</tr>\n";

      if (strlen($a_r_language['r_lang_specialize']) > 0) {
        if ($a_r_language['r_lang_rank'] == 0) {
          $r_lang_rank = "Native Speaker";
          $r_lang_dicepool = "Native Speaker";
        } else {
          $r_lang_rank = $a_r_language['r_lang_rank'] + 2;
          $r_lang_dicepool = $a_r_language['r_lang_rank'] + 2 + $a_runners[$a_attributes['att_column']];
        }

        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">"        . "&gt; " . $a_r_language['r_lang_specialize']                    . "</td>\n";
        $output .= "  <td class=\"ui-widget-content delete\">" . $r_lang_dicepool                                                . "</td>\n";
        $output .= "</tr>\n";
      }
    }
  } else {
    $output = "";
  }

  $output .= "</table>\n";

  print "document.getElementById('language_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n";

?>
