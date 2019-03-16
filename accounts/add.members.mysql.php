<?php
# Script: add.members.mysql.php
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
    $package = "add.members.mysql.php";
    $formVars['update']    = clean($_GET['update'],    10);
    $formVars['mem_group'] = clean($_GET['mem_group'], 10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(2)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],            10);
        $formVars['mem_runner']    = clean($_GET['mem_runner'],    10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if ($formVars['mem_runner'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string  = "select runr_owner ";
          $q_string .= "from runners ";
          $q_string .= "where runr_id = " . $formVars['mem_runner'] . " ";
          $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $a_runners = mysql_fetch_array($q_runners);

          $q_string =
            "mem_owner     =   " . $a_runners['runr_owner']    . "," . 
            "mem_runner    =   " . $formVars['mem_runner']     . "," . 
            "mem_group     =   " . $formVars['mem_group'];

          if ($formVars['update'] == 0) {
            $q_string .= ",mem_invite = 2";
          }

          if ($formVars['update'] == 0) {
            $query = "insert into members set mem_id = NULL," . $q_string;
            $message = "Group Member added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update members set " . $q_string . " where mem_id = " . $formVars['id'];
            $message = "Group Member updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['mem_group']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Group Member Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('members-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"members-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Group Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Delete (x)</strong> - Click here to delete this group from the Inventory. It's better to disable the user.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a group to toggle the form and edit the group.</li>\n";
      $output .= "    <li><strong>Highlight</strong> - If a group is <span class=\"ui-state-error\">highlighted</span>, then the group has been disabled and will not be visible in any selection menus.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Group Management</strong> title bar to toggle the <strong>Group Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\">Id</th>";
      $output .= "  <th class=\"ui-state-default\">Del</th>";
      $output .= "  <th class=\"ui-state-default\">Owner</th>";
      $output .= "  <th class=\"ui-state-default\">Runner</th>";
      $output .= "  <th class=\"ui-state-default\">Status</th>";
      $output .= "  <th class=\"ui-state-default\">Active Date</th>";
      $output .= "</tr>";

      $q_string  = "select mem_id,runr_name,usr_last,usr_first,mem_invite,mem_active ";
      $q_string .= "from members ";
      $q_string .= "left join runners on runners.runr_id = members.mem_runner ";
      $q_string .= "left join users on users.usr_id = runners.runr_owner ";
      $q_string .= "where mem_group = " . $formVars['mem_group'] . " ";
      $q_string .= "order by usr_last,usr_first,runr_name ";
      $q_members = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_members) > 0) {
        while ($a_members = mysql_fetch_array($q_members)) {

          $linkdel   = "<input type=\"button\" value=\"Remove\" onclick=\"delete_line('add.members.del.php?id=" . $a_members['mem_id'] . "');\">";
          $linkstart = "<a href=\"#\" onclick=\"show_file('add.members.fill.php?id="  . $a_members['mem_id'] . "');showDiv('members-hide');\">";
          $linkend = "</a>";

          $class = "ui-widget-content";
          if ($a_members['mem_invite'] == 0) {
            $class = "ui-state-error";
          }

          $status = "Declined";
          if ($a_members['mem_invite'] == 1) {
            $status = "Accepted";
          }
          if ($a_members['mem_invite'] == 2) {
            $status = "Pending";
          }

          $output .= "<tr>";
          $output .= "  <td class=\"" . $class . " delete\">" . $linkstart . $a_members['mem_id'] . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . " delete\">" . $linkdel                                           . "</td>";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_members['usr_first'] . " " . $a_members['usr_last'] . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_members['runr_name']     . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $status     . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_members['runr_active']      . $linkend . "</td>";
          $output .= "</tr>";
        }
      } else {
        $output .= "<tr>";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">No Members found.</td>";
        $output .= "</tr>";
      }

      mysql_free_result($q_members);

      $output .= "</table>";

      print "document.getElementById('table_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.members.mem_runner[0].selected = true;\n";
      print "document.members.mem_invite[0].selected = true;\n";
      print "document.members.mem_active.value = '';\n";

      print "document.members.update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
