<?php
# Script: language.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Retrieve data and update the database with the new info. Prepare and display the table

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "language.mysql.php";
    $formVars['update']              = clean($_GET['update'],               10);
    $formVars['r_lang_character']    = clean($_GET['r_lang_character'],     10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_lang_id']          = clean($_GET['id'],                  10);
        $formVars['r_lang_number']      = clean($_GET['r_lang_number'],       10);
        $formVars['r_lang_rank']        = clean($_GET['r_lang_rank'],         10);
        $formVars['r_lang_specialize']  = clean($_GET['r_lang_specialize'],   60);
        $formVars['r_lang_expert']      = clean($_GET['r_lang_expert'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_lang_rank'] == '') {
          $formVars['r_lang_rank'] = 0;
        }
        if ($formVars['r_lang_expert'] == 'true') {
          $formVars['r_lang_expert'] = 1;
        } else {
          $formVars['r_lang_expert'] = 0;
        }

        if ($formVars['r_lang_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_lang_character   =   " . $formVars['r_lang_character']   . "," .
            "r_lang_number      =   " . $formVars['r_lang_number']      . "," .
            "r_lang_rank        =   " . $formVars['r_lang_rank']        . "," .
            "r_lang_specialize  = \"" . $formVars['r_lang_specialize']  . "\"," .
            "r_lang_expert      =   " . $formVars['r_lang_expert'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_language set r_lang_id = NULL," . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update r_language set " . $q_string . " where r_lang_id = " . $formVars['r_lang_id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_lang_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_lang_refresh\" value=\"Refresh Language Listing\" onClick=\"javascript:attach_language('language.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_lang_update\"  value=\"Update Language\"          onClick=\"javascript:attach_language('language.mysql.php', 1);hideDiv('language-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_lang_id\"      value=\"0\">\n";
        $output .= "<input type=\"button\" name=\"r_lang_addbtn\"  value=\"Add Language\"             onClick=\"javascript:attach_language('language.mysql.php', 0);\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Language Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Language Skill: <select name=\"r_lang_number\">\n";
        $output .= "<option value=\"0\">None</option>\n";

        $q_string  = "select lang_id,lang_name ";
        $q_string .= "from language ";
        $q_string .= "order by lang_name ";
        $q_language = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_language = mysqli_fetch_array($q_language)) {
          $output .= "<option value=\"" . $a_language['lang_id'] . "\">" . $a_language['lang_name'] . "</option>\n";
        }

        $output .= "</select></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Rank: <input type=\"text\" name=\"r_lang_rank\" size=\"10\"> Zero for Native Speaker\n";
        $output .= "  <td class=\"ui-widget-content\">Specialize: <input type=\"text\" name=\"r_lang_specialize\" size=\"30\"> Expert? <input type=\"checkbox\" name=\"r_lang_expert\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Language Add Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">New Language: <input type=\"text\" name=\"lang_name\" size=\"40\">\n";
        $output .= "  <td class=\"button ui-widget-content\"><input type=\"button\" name=\"lang_skill\" value=\"Add New Language\" onClick=\"javascript:attach_language_dialog('add.language.mysql.php', 0);\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('language_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Language Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('language-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"language-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Knowledge Skill Listing</strong>\n";
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
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">Language</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rank</th>\n";
      $output .=   "<th class=\"ui-state-default\">Associated Attribute</th>\n";
      $output .=   "<th class=\"ui-state-default\">Score</th>\n";
      $output .=   "<th class=\"ui-state-default\">Dice Pool</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_lang_id,r_lang_rank,r_lang_specialize,r_lang_expert,lang_id,lang_name,att_name,att_column,ver_book,s_lang_page ";
      $q_string .= "from r_language ";
      $q_string .= "left join language on language.lang_id = r_language.r_lang_number ";
      $q_string .= "left join s_language on s_language.s_lang_id = language.lang_attribute ";
      $q_string .= "left join attributes on attributes.att_id = s_language.s_lang_attribute ";
      $q_string .= "left join versions on versions.ver_id = s_language.s_lang_book ";
      $q_string .= "where r_lang_character = " . $formVars['r_lang_character'] . " ";
      $q_string .= "order by lang_name ";
      $q_r_language = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_language) > 0) {
        while ($a_r_language = mysqli_fetch_array($q_r_language)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('language.fill.php?id=" . $a_r_language['r_lang_id'] . "');showDiv('language-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_language('language.del.php?id="  . $a_r_language['r_lang_id'] . "');\">";
          $linkend   = "</a>";

          $q_string  = "select " . $a_r_language['att_column'] . " ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $formVars['r_lang_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $a_runners = mysqli_fetch_array($q_runners);

          if ($a_r_language['r_lang_rank'] > 0) {
            $r_lang_rank = $a_r_language['r_lang_rank'];
            $r_dice_pool = ($a_r_language['r_lang_rank'] + $a_runners[$a_r_language['att_column']]);
          } else {
            $r_lang_rank = "Native Speaker";
            $r_dice_pool = "Native Speaker";
          }

          $class = "ui-widget-content";
          if (isset($formVars['r_lang_number']) && $formVars['r_lang_number'] == $a_r_language['lang_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $linkdel                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_language['lang_name']              . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $r_lang_rank                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_language['att_name']                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_runners[$a_r_language['att_column']]            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $r_dice_pool                                       . "</td>\n";
          $output .= "</tr>\n";

          if (strlen($a_r_language['r_lang_specialize']) > 0) {

            $expert = "";
            $increase = 2;
            if ($a_r_language['r_lang_expert']) {
              $expert = " *";
              $increase = 3;
            }

            if ($a_r_language['r_lang_rank'] > 0) {
              $r_lang_rank = $a_r_language['r_lang_rank'] . " + " . $increase;
              $r_dice_pool = ($a_r_language['r_lang_rank'] + $a_runners[$a_r_language['att_column']] + $increase);
            } else {
              $r_lang_rank = "Native Speaker";
              $r_dice_pool = "Native Speaker";
            }

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                             . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . "&gt; " . $a_r_language['r_lang_specialize'] . $expert . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $r_lang_rank                                            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_language['att_name']                               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_runners[$a_r_language['att_column']]                 . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $r_dice_pool                                            . "</td>\n";
            $output .= "</tr>\n";
          }

        }
      } else {
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">No Languages added.</td>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_language);

      print "document.getElementById('language_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.r_lang_update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
