<?php
# Script: knowledge.mysql.php
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
    $package = "knowledge.mysql.php";
    $formVars['update']              = clean($_GET['update'],               10);
    $formVars['r_know_character']    = clean($_GET['r_know_character'],     10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_know_id']          = clean($_GET['id'],                  10);
        $formVars['r_know_number']      = clean($_GET['r_know_number'],       10);
        $formVars['r_know_rank']        = clean($_GET['r_know_rank'],         10);
        $formVars['r_know_specialize']  = clean($_GET['r_know_specialize'],   60);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_know_rank'] == '') {
          $formVars['r_know_rank'] = 0;
        }

        if ($formVars['r_know_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_know_character   =   " . $formVars['r_know_character']   . "," .
            "r_know_number      =   " . $formVars['r_know_number']      . "," .
            "r_know_rank        =   " . $formVars['r_know_rank']        . "," .
            "r_know_specialize  = \"" . $formVars['r_know_specialize']  . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_knowledge set r_know_id = NULL," . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update r_knowledge set " . $q_string . " where r_know_id = " . $formVars['r_know_id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_know_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -2) {
        $formVars['copyfrom'] = clean($_GET['r_know_copyfrom'], 10);

        if ($formVars['copyfrom'] > 0) {
          $q_string  = "select r_know_number,r_know_specialize ";
          $q_string .= "from r_knowledge ";
          $q_string .= "where r_know_character = " . $formVars['copyfrom'];
          $q_r_knowledge = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          while ($a_r_knowledge = mysql_fetch_array($q_r_knowledge)) {

            $q_string =
              "r_know_character     =   " . $formVars['r_know_character']      . "," .
              "r_know_number        =   " . $a_r_knowledge['r_know_number']       . "," .
              "r_know_specialize    =   " . $a_r_knowledge['r_know_specialize']   . "\"";
  
            $query = "insert into r_knowledge set r_know_id = NULL, " . $q_string;
            mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
          }
        }
      }


      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_know_refresh\" value=\"Refresh Knowledge Skill Listing\" onClick=\"javascript:attach_knowledge('knowledge.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_know_update\"  value=\"Update Knowledge Skill\"          onClick=\"javascript:attach_knowledge('knowledge.mysql.php', 1);hideDiv('knowledge-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_know_id\"      value=\"0\">\n";
        $output .= "<input type=\"button\" name=\"r_know_addbtn\"  value=\"Add Knowledge Skill\"             onClick=\"javascript:attach_knowledge('knowledge.mysql.php', 0);\">\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"copyitem\"  value=\"Copy Knowledge Table From:\" onClick=\"javascript:attach_knowledge('knowledge.mysql.php', -2);\">\n";
        $output .= "<select name=\"r_know_copyfrom\">\n";
        $output .= "<option value=\"0\">None</option>\n";

        $q_string  = "select runr_id,runr_aliases ";
        $q_string .= "from runners ";
        $q_string .= "order by runr_aliases ";
        $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_runners = mysql_fetch_array($q_runners)) {
          $q_string  = "select r_know_id ";
          $q_string .= "from r_knowledge ";
          $q_string .= "where r_know_character = " . $a_runners['runr_id'] . " ";
          $q_r_knowledge = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $r_know_total = mysql_num_rows($q_r_knowledge);

          if ($r_know_total > 0) {
            $output .= "<option value=\"" . $a_runners['runr_id'] . "\">" . $a_runners['runr_aliases'] . " (" . $r_know_total . ")</option>\n";
          }
        }

        $output .= "</select></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Knowledge Skill Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Knowledge Skill: <select name=\"r_know_number\">\n";
        $output .= "<option value=\"0\">None</option>\n";

        $q_string  = "select know_id,know_name ";
        $q_string .= "from knowledge ";
        $q_string .= "order by know_name ";
        $q_knowledge = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_knowledge = mysql_fetch_array($q_knowledge)) {
          $output .= "<option value=\"" . $a_knowledge['know_id'] . "\">" . $a_knowledge['know_name'] . "</option>\n";
        }

        $output .= "</select></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Rank: <input type=\"text\" name=\"r_know_rank\" size=\"10\">\n";
        $output .= "  <td class=\"ui-widget-content\">Specialize: <input type=\"text\" name=\"r_know_specialize\" size=\"30\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Knowledge Add Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">New Knowledge Skill: <input type=\"text\" name=\"know_name\" size=\"40\">\n";
        $output .= "  <td class=\"ui-widget-content\">Skill Type: <select name=\"know_attribute\">\n";

        $q_string  = "select s_know_id,s_know_name ";
        $q_string .= "from s_knowledge ";
        $q_string .= "order by s_know_name ";
        $q_s_knowledge = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_s_knowledge = mysql_fetch_array($q_s_knowledge)) {
          $output .= "<option value=\"" . $a_s_knowledge['s_know_id'] . "\">" . $a_s_knowledge['s_know_name'] . "</option>\n";
        }

        $output .= "</select></td>\n";
        $output .= "  <td class=\"button ui-widget-content\"><input type=\"button\" name=\"know_skill\" value=\"Add New Knowledge Skill\" onClick=\"javascript:attach_knowledge_dialog('add.knowledge.mysql.php', 0);\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('knowledge_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Knowledge Skill Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('knowledge-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"knowledge-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Knowledge Type</th>\n";
      $output .=   "<th class=\"ui-state-default\">Knowledge Skill</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rank</th>\n";
      $output .=   "<th class=\"ui-state-default\">Associated Attribute</th>\n";
      $output .=   "<th class=\"ui-state-default\">Score</th>\n";
      $output .=   "<th class=\"ui-state-default\">Dice Pool</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_know_id,r_know_rank,r_know_specialize,know_id,know_name,att_name,att_column,s_know_name,ver_book,s_know_page ";
      $q_string .= "from r_knowledge ";
      $q_string .= "left join knowledge on knowledge.know_id = r_knowledge.r_know_number ";
      $q_string .= "left join s_knowledge on s_knowledge.s_know_id = knowledge.know_attribute ";
      $q_string .= "left join attributes on attributes.att_id = s_knowledge.s_know_attribute ";
      $q_string .= "left join versions on versions.ver_id = s_knowledge.s_know_book ";
      $q_string .= "where r_know_character = " . $formVars['r_know_character'] . " ";
      $q_string .= "order by know_name ";
      $q_r_knowledge = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_knowledge) > 0) {
        while ($a_r_knowledge = mysql_fetch_array($q_r_knowledge)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('knowledge.fill.php?id=" . $a_r_knowledge['r_know_id'] . "');showDiv('knowledge-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_knowledge('knowledge.del.php?id="  . $a_r_knowledge['r_know_id'] . "');\">";
          $linkend   = "</a>";

          $q_string  = "select " . $a_r_knowledge['att_column'] . " ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $formVars['r_know_character'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $a_runners = mysql_fetch_array($q_runners);

          $know_book = return_Book($a_r_knowledge['ver_book'], $a_r_knowledge['s_know_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_know_number']) && $formVars['r_know_number'] == $a_r_knowledge['know_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $linkdel                                                                                . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_knowledge['s_know_name']                                              . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_knowledge['know_name']                                     . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_knowledge['r_know_rank']                                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_knowledge['att_name']                                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_runners[$a_r_knowledge['att_column']]                                   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . ($a_r_knowledge['r_know_rank'] + $a_runners[$a_r_knowledge['att_column']]) . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $know_book                                                                 . "</td>\n";
          $output .= "</tr>\n";

          if (strlen($a_r_knowledge['r_know_specialize']) > 0) {
            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                                                    . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                                                    . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . "&gt; " . $a_r_knowledge['r_know_specialize']                                  . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_knowledge['r_know_rank'] . " + 2"                                         . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_knowledge['att_name']                                                     . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_runners[$a_r_knowledge['att_column']]                                       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . ($a_r_knowledge['r_know_rank'] + $a_runners[$a_r_knowledge['att_column']] + 2) . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $know_book                                                                     . "</td>\n";
            $output .= "</tr>\n";
          }

        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">No Knowledge Skills added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_knowledge);

      print "document.getElementById('knowledge_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.r_know_update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
