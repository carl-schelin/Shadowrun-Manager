<?php
# Script: add.groups.mysql.php
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
    $package = "add.groups.mysql.php";
    $formVars['update'] = clean($_GET['update'], 10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(2)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],            10);
        $formVars['grp_name']      = clean($_GET['grp_name'],     100);
        $formVars['grp_email']     = clean($_GET['grp_email'],    255);
        $formVars['grp_disabled']  = clean($_GET['grp_disabled'], 255);
        $formVars['grp_owner']     = clean($_GET['grp_owner'],     10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if (strlen($formVars['grp_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "grp_name      = \"" . $formVars['grp_name']      . "\"," . 
            "grp_email     = \"" . $formVars['grp_email']     . "\"," . 
            "grp_disabled  =   " . $formVars['grp_disabled']  . "," . 
            "grp_owner     =   " . $formVars['grp_owner'];

          if ($formVars['update'] == 0) {
            $query = "insert into groups set grp_id = NULL," . $q_string;
            $message = "Group added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update groups set " . $q_string . " where grp_id = " . $formVars['id'];
            $message = "Group updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['grp_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Group Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('group-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"group-listing-help\" style=\"display: none\">\n";

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
      if (check_userlevel(1)) {
        $output .= "  <th class=\"ui-state-default\">Id</th>";
      }
      $output .= "  <th class=\"ui-state-default\">Del</th>";
      $output .= "  <th class=\"ui-state-default\">Manage Users</th>";
      $output .= "  <th class=\"ui-state-default\">Group Name</th>";
      $output .= "  <th class=\"ui-state-default\">Group Email</th>";
      $output .= "  <th class=\"ui-state-default\">Group Owner</th>";
      $output .= "</tr>";

      $q_string  = "select grp_id,grp_disabled,grp_name,grp_email,usr_last ";
      $q_string .= "from groups ";
      $q_string .= "left join users on users.usr_id = groups.grp_owner ";
      $q_string .= "where grp_owner = " . $_SESSION['uid'] . " or usr_level = 1 ";
      $q_string .= "order by grp_name";
      $q_groups = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_groups) > 0) {
        while ($a_groups = mysql_fetch_array($q_groups)) {

          $linkdel   = "<input type=\"button\" value=\"Remove\" onclick=\"delete_line('add.groups.del.php?id=" . $a_groups['grp_id'] . "');\">";
          $linkstart = "<a href=\"#\" onclick=\"show_file('add.groups.fill.php?id="  . $a_groups['grp_id'] . "');showDiv('group-hide');\">";
          $manage    = "<a href=\"add.members.php?id=" . $a_groups['grp_id'] . "\">";
          $linkend = "</a>";

          $class = "ui-widget-content";
          if ($a_groups['grp_disabled']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>";
          if (check_userlevel(1)) {
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_id']        . $linkend . "</td>";
          }
          $output .= "  <td class=\"" . $class . " delete\">" . $linkdel                                           . "</td>";
          $output .= "  <td class=\"" . $class . " delete\">" . $manage . "Manage"                      . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_name']      . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['grp_email']     . $linkend . "</td>";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_groups['usr_last']      . $linkend . "</td>";
          $output .= "</tr>";
        }
      } else {
        $output .= "<tr>";
        $output .= "  <td class=\"" . $class . "\" colspan=\"6\">No Groups found.</td>";
        $output .= "</tr>";
      }

      mysql_free_result($q_groups);

      $output .= "</table>";

      print "document.getElementById('table_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.groups.grp_disabled[0].selected = true;\n";
      print "document.groups.grp_name.value = '';\n";
      print "document.groups.grp_email.value = '';\n";

      print "document.groups.update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
