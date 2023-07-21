<?php
# Script: active.mysql.php
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
    $package = "active.mysql.php";
    if (isset($_GET['update'])) {
      $formVars['update'] = clean($_GET['update'], 10);
    } else {
      $formVars['update'] = -1;
    }
    if (isset($_GET['r_act_character'])) {
      $formVars['r_act_character'] = clean($_GET['r_act_character'], 10);
    } else {
      $formVars['r_act_character'] = 0;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_act_id']          = clean($_GET['id'],                 10);
        $formVars['r_act_number']      = clean($_GET['r_act_number'],       10);
        $formVars['r_act_group']       = clean($_GET['r_act_group'],        10);
        $formVars['r_act_rank']        = clean($_GET['r_act_rank'],         10);
        $formVars['r_act_specialize']  = clean($_GET['r_act_specialize'],   60);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
# group is reset each time so if group is > 0 then it's purposefully selected; ignore what's in the active skill drop down.
        if ($formVars['r_act_group'] > 0) {
          $formVars['r_act_number'] = 0;
        }
        if ($formVars['r_act_rank'] == '') {
          $formVars['r_act_rank'] = 0;
        }

        if ($formVars['r_act_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_act_character   =   " . $formVars['r_act_character']   . "," .
            "r_act_number      =   " . $formVars['r_act_number']      . "," .
            "r_act_rank        =   " . $formVars['r_act_rank']        . "," .
            "r_act_specialize  = \"" . $formVars['r_act_specialize']  . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_active set r_act_id = NULL," . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update r_active set " . $q_string . " where r_act_id = " . $formVars['r_act_id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_act_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        }

        if ($formVars['r_act_group'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string  = "select act_group ";
          $q_string .= "from active ";
          $q_string .= "where act_id = " . $formVars['r_act_group'] . " ";
          $q_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $a_active = mysql_fetch_array($q_active);

# for the comparison below for highlighting
          $group_highlight = $a_active['act_group'];

          $q_string  = "select act_id ";
          $q_string .= "from active ";
          $q_string .= "where act_group = \"" . $a_active['act_group'] . "\" ";
          $q_skill = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          while ($a_skill = mysql_fetch_array($q_skill)) {

            $q_string =
              "r_act_character   =   " . $formVars['r_act_character']   . "," .
              "r_act_number      =   " . $a_skill['act_id']             . "," .
              "r_act_group       =   " . "1"                            . "," .
              "r_act_rank        =   " . $formVars['r_act_rank'];

            if ($formVars['update'] == 0) {
              $query = "insert into r_active set r_act_id = NULL," . $q_string;
            }

            logaccess($_SESSION['username'], $package, "Saving Changes to: " . $a_skill['act_id']);

            mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
          }

          $message = "Active Skill Group added.";

          print "alert('" . $message . "');\n";

        }

        if ($formVars['r_act_number'] == 0 && $formVars['r_act_group'] == 0) {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_act_refresh\" value=\"Refresh Active Skill Listing\" onClick=\"javascript:attach_active('active.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_act_update\"  value=\"Update Active Skill\"          onClick=\"javascript:attach_active('active.mysql.php', 1);hideDiv('active-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_act_id\"      value=\"0\">\n";
        $output .= "<input type=\"button\" name=\"r_act_addbtn\"  value=\"Add Active Skill\"             onClick=\"javascript:attach_active('active.mysql.php', 0);\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Active Skill Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Skill: <select name=\"r_act_number\">\n";
        $output .= "<option value=\"0\">None</option>\n";

        $q_string  = "select act_id,act_name ";
        $q_string .= "from active ";
        $q_string .= "left join versions on versions.ver_id = active.act_book ";
        $q_string .= "where ver_active = 1 ";
        $q_string .= "order by act_name ";
        $q_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_active = mysql_fetch_array($q_active)) {
          $output .= "<option value=\"" . $a_active['act_id'] . "\">" . $a_active['act_name'] . "</option>\n";
        }

        $output .= "</select></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Skill Group: <select name=\"r_act_group\">\n";
        $output .= "<option value=\"0\">None</option>\n";

        $q_string  = "select act_id,act_group ";
        $q_string .= "from active ";
        $q_string .= "left join versions on versions.ver_id = active.act_book ";
        $q_string .= "where act_group != '' and ver_active = 1 ";
        $q_string .= "group by act_group ";
        $q_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_active = mysql_fetch_array($q_active)) {
          $output .= "<option value=\"" . $a_active['act_id'] . "\">" . $a_active['act_group'] . "</option>\n";
        }

        $output .= "</select></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Rank: <input type=\"text\" name=\"r_act_rank\" size=\"10\">\n";
        $output .= "  <td class=\"ui-widget-content\">Specialize: <input type=\"text\" name=\"r_act_specialize\" size=\"30\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('active_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Active Skill Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('active-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"active-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Spell Listing</strong>\n";
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
      $output .=   "<th class=\"ui-state-default\">Group</th>\n";
      $output .=   "<th class=\"ui-state-default\">Active Skill</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rank</th>\n";
      $output .=   "<th class=\"ui-state-default\">Associated Attribute</th>\n";
      $output .=   "<th class=\"ui-state-default\">Score</th>\n";
      $output .=   "<th class=\"ui-state-default\">Dice Pool</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_act_id,r_act_rank,r_act_group,r_act_specialize,act_id,act_name,act_group,att_name,att_column,ver_book,act_page ";
      $q_string .= "from r_active ";
      $q_string .= "left join active on active.act_id = r_active.r_act_number ";
      $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
      $q_string .= "left join versions on versions.ver_id = active.act_book ";
      $q_string .= "where r_act_character = " . $formVars['r_act_character'] . " ";
      $q_string .= "order by act_name ";
      $q_r_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_active) > 0) {
        while ($a_r_active = mysql_fetch_array($q_r_active)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('active.fill.php?id=" . $a_r_active['r_act_id'] . "');showDiv('active-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_active('active.del.php?id="  . $a_r_active['r_act_id'] . "');\">";
          $linkend   = "</a>";

          $group = "";
          if ($a_r_active['r_act_group']) {
            $group = " *";
          }

# since we can select groups, highlight all members of a group.
# group_highlight will be set to the group or will be unset if no group was selected
          $class = "ui-widget-content";
          if (isset($group_highlight) && $group_highlight == $a_r_active['act_group']) {
            $class = "ui-state-error";
          } else {
            if (isset($formVars['r_act_number']) && $formVars['r_act_number'] == $a_r_active['act_id']) {
              $class = "ui-state-error";
            }
          }

          $act_book = return_Book($a_r_active['ver_book'], $a_r_active['act_page']);

# old 4a characters have deleted active skills which breaks this. This block lets you delete the bad skill.
          if ($a_r_active['att_column'] == '') {
            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $linkdel                                          . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"                     . $a_r_active['act_group']             . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_active['act_name']   . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_active['r_act_rank']            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_active['att_name']              . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . "Blank"                              . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . "--"                                 . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $act_book                            . "</td>\n";
            $output .= "</tr>\n";
          } else {
            $q_string  = "select " . $a_r_active['att_column'] . " ";
            $q_string .= "from runners ";
            $q_string .= "where runr_id = " . $formVars['r_act_character'] . " ";
            $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            $a_runners = mysql_fetch_array($q_runners);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $linkdel                                                                         . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"                     . $a_r_active['act_group']                                            . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_active['act_name'] . $group                         . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_active['r_act_rank']                                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_active['att_name']                                             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_runners[$a_r_active['att_column']]                               . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . ($a_runners[$a_r_active['att_column']] + $a_r_active['r_act_rank']) . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $act_book                                                           . "</td>\n";
            $output .= "</tr>\n";

            if (strlen($a_r_active['r_act_specialize']) > 0) {
              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . "&nbsp;"                                                                               . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . "&nbsp;"                                                                               . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $linkstart . "&gt; " . $a_r_active['r_act_specialize']                      . $linkend . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_active['r_act_rank'] . " + 2"                                        . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_active['att_name']                                                   . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $a_runners[$a_r_active['att_column']]                                     . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . ($a_runners[$a_r_active['att_column']]  + $a_r_active['r_act_rank'] + 2)  . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $act_book                                                                 . "</td>\n";
              $output .= "</tr>\n";
            }
          }

        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">No Active Skills added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";
      $output .= "<p class=\"ui-widget-content\">* Identifies a managed skill group.</p>\n";

      mysql_free_result($q_r_active);

      print "document.getElementById('active_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.r_act_group[0].selected = true;\n";
      print "document.edit.r_act_group.disabled = false;\n";
      print "document.edit.r_act_specialize.value = '';\n";
      print "document.edit.r_act_update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
