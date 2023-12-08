<?php
# Script: add.users.mysql.php
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
    $package = "add.users.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],             10);
        $formVars['usr_first']      = clean($_GET['usr_first'],     255);
        $formVars['usr_last']       = clean($_GET['usr_last'],      255);
        $formVars['usr_name']       = clean($_GET['usr_name'],      120);
        $formVars['usr_disabled']   = clean($_GET['usr_disabled'],   10);
        $formVars['usr_level']      = clean($_GET['usr_level'],      10);
        $formVars['usr_email']      = clean($_GET['usr_email'],     255);
        $formVars['usr_theme']      = clean($_GET['usr_theme'],      10);
        $formVars['usr_passwd']     = clean($_GET['usr_passwd'],     32);
        $formVars['usr_reenter']    = clean($_GET['usr_reenter'],    32);
        $formVars['usr_reset']      = clean($_GET['usr_reset'],      10);
        $formVars['usr_phone']      = clean($_GET['usr_phone'],      15);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['usr_reset'] == 'true') {
          $formVars['usr_reset'] = 1;
        } else {
          $formVars['usr_reset'] = 0;
        }

        if (strlen($formVars['usr_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "usr_first       = \"" . $formVars['usr_first']     . "\"," .
            "usr_last        = \"" . $formVars['usr_last']      . "\"," .
            "usr_name        = \"" . $formVars['usr_name']      . "\"," .
            "usr_disabled    =   " . $formVars['usr_disabled']  . "," .
            "usr_level       =   " . $formVars['usr_level']     . "," .
            "usr_email       = \"" . $formVars['usr_email']     . "\"," .
            "usr_phone       = \"" . $formVars['usr_phone']     . "\"," .
            "usr_theme       =   " . $formVars['usr_theme'];

          if (strlen($formVars['usr_passwd']) > 0 && $formVars['usr_passwd'] === $formVars['usr_reenter']) {
            logaccess($_SESSION['username'], $package, "Resetting user " . $formVars['usr_name'] . " password.");
            $q_string .= ",usr_passwd = '" . MD5($formVars['usr_passwd']) . "' ";
          }

          if ($formVars['update'] == 0) {
            $query = "insert into users set usr_id = NULL, " . $q_string;
            $message = "User added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update users set " . $q_string . " where usr_id = " . $formVars['id'];
            $message = "User updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['usr_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

######
# New User Listing
######

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">New User Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('newuser-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"newuser-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>New User Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Delete (x)</strong> - Click here to delete this user from the Inventory. It's better to disable the user.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a user to toggle the form and edit the user.</li>\n";
      $output .= "    <li><strong>Highlight</strong> - If a user is <span class=\"ui-state-highlight\">highlighted</span>, then the user's Reset Password on Next Login flag has been set.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>User Management</strong> title bar to toggle the <strong>User Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" colspan=\"13\">New Users</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Level</th>\n";
      $output .=   "<th class=\"ui-state-default\">Login</th>\n";
      $output .=   "<th class=\"ui-state-default\">First Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Last Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">E-Mail</th>\n";
      $output .=   "<th class=\"ui-state-default\">Reset</th>\n";
      $output .=   "<th class=\"ui-state-default\">Registered Date</th>\n";
      $output .=   "<th class=\"ui-state-default\">Last Login</th>\n";
      $output .=   "<th class=\"ui-state-default\">IP Address</th>\n";
      $output .=   "<th class=\"ui-state-default\">Theme</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select usr_id,lvl_name,usr_disabled,usr_name,usr_first,";
      $q_string .= "usr_last,usr_email,usr_reset,usr_timestamp,usr_lastlogin,";
      $q_string .= "usr_ipaddress,theme_title ";
      $q_string .= "from users ";
      $q_string .= "left join levels on levels.lvl_id = users.usr_level ";
      $q_string .= "left join themes on themes.theme_id = users.usr_theme ";
      $q_string .= "where usr_disabled = 0 and usr_level = 0 ";
      $q_string .= "order by usr_last,usr_first";
      $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_users) > 0) {
        while ($a_users = mysqli_fetch_array($q_users)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.users.fill.php?id="  . $a_users['usr_id'] . "');showDiv('user-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_user('add.users.del.php?id="  . $a_users['usr_id'] . "');\">";
          $linkend = "</a>";

          if ($a_users['usr_reset']) {
            $default = " class=\"ui-state-highlight\"";
            $defaultdel = " class=\"ui-state-highlight delete\"";
          } else {
            if ($a_users['usr_disabled']) {
              $default = " class=\"ui-state-error\"";
              $defaultdel = " class=\"ui-state-error delete\"";
            } else {
              $default = " class=\"ui-widget-content\"";
              $defaultdel = " class=\"ui-widget-content delete\"";
            }
          }

          $timestamp = strtotime($a_users['usr_timestamp']);
          $reg_date = date('d M y @ H:i' ,$timestamp);

          if ($a_users['usr_reset']) {
            $pwreset = 'Yes';
          } else {
            $pwreset = 'No';
          }

          $output .= "<tr>\n";
          $output .=   "<td" . $defaultdel . ">" . $linkdel                                                   . "</td>\n";
          $output .= "  <td" . $defaultdel . ">" . $linkstart . $a_users['usr_id']                 . $linkend . "</td>\n";
          $output .= "  <td" . $default    . ">" . $linkstart . $a_users['lvl_name']               . $linkend . "</td>\n";
          $output .= "  <td" . $default    . ">" . $linkstart . $a_users['usr_name']               . $linkend . "</td>\n";
          $output .= "  <td" . $default    . ">" . $linkstart . $a_users['usr_first']              . $linkend . "</td>\n";
          $output .= "  <td" . $default    . ">" . $linkstart . $a_users['usr_last']               . $linkend . "</td>\n";
          $output .= "  <td" . $default    . ">" . $linkstart . $a_users['usr_email']              . $linkend . "</td>\n";
          $output .= "  <td" . $defaultdel . ">" . $linkstart . $pwreset                           . $linkend . "</td>\n";
          $output .= "  <td" . $default    . ">" . $linkstart . $a_users['grp_name']               . $linkend . "</td>\n";
          $output .= "  <td" . $defaultdel . ">" . $linkstart . $reg_date                          . $linkend . "</td>\n";
          $output .= "  <td" . $defaultdel . ">" . $linkstart . $a_users['usr_lastlogin']          . $linkend . "</td>\n";
          $output .= "  <td" . $defaultdel . ">" . $linkstart . $a_users['usr_ipaddress']          . $linkend . "</td>\n";
          $output .= "  <td" . $defaultdel . ">" . $linkstart . $a_users['theme_title']            . $linkend . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"13\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('newuser_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


      display_User("Mr. Johnson",  "mrjohnson",    " usr_disabled = 0 and usr_level = 1 ");
      display_User("Fixer",        "fixer",        " usr_disabled = 0 and usr_level = 2 ");
      display_User("Shadowrunner", "shadowrunner", " usr_disabled = 0 and usr_level = 3 ");
      display_User("Chummer",      "chummer",      " usr_disabled = 0 and usr_level = 4 ");
      display_User("Guest",        "guest",        " usr_disabled = 0 and usr_level = 5 ");
      display_User("Disabled",     "disabled",     " usr_disabled = 1 ");

      print "document.user.usr_level[0].selected = true;\n";
      print "document.user.usr_name.value = '';\n";
      print "document.user.usr_first.value = '';\n";
      print "document.user.usr_last.value = '';\n";
      print "document.user.usr_email.value = '';\n";
      print "document.user.usr_reset.checked = false;\n";
      print "document.user.usr_theme[0].selected = true;\n";

      print "document.user.update.disabled = true;\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

function display_user( $p_title, $p_toggle, $p_query ) {

  $output  = "<p></p>\n";
  $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
  $output .= "<tr>\n";
  $output .=   "<th class=\"ui-state-default\">" . $p_title . " User Listing</th>\n";
  $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $p_toggle . "-user-help');\">Help</a></th>\n";
  $output .= "</tr>\n";
  $output .= "</table>\n";

  $output .= "<div id=\"" . $p_toggle . "-user-help\" style=\"display: none\">\n";

  $output .= "<div class=\"main-help ui-widget-content\">\n";

  $output .= "<ul>\n";
  $output .= "  <li><strong>Disabled User Listing</strong>\n";
  $output .= "  <ul>\n";
  $output .= "    <li><strong>Delete (x)</strong> - Click here to delete this user from the Inventory. It's better to disable the user.</li>\n";
  $output .= "    <li><strong>Editing</strong> - Click on a user to toggle the form and edit the user.</li>\n";
  $output .= "    <li><strong>Highlight</strong> - If a user is <span class=\"ui-state-error\">highlighted</span>, then the user has been disabled.</li>\n";
  $output .= "  </ul></li>\n";
  $output .= "</ul>\n";

  $output .= "<ul>\n";
  $output .= "  <li><strong>Notes</strong>\n";
  $output .= "  <ul>\n";
  $output .= "    <li>Click the <strong>User Management</strong> title bar to toggle the <strong>User Form</strong>.</li>\n";
  $output .= "  </ul></li>\n";
  $output .= "</ul>\n";

  $output .= "</div>\n";

  $output .= "</div>\n";

  $q_string  = "select grp_id,grp_name ";
  $q_string .= "from groups ";
  $q_string .= "where grp_disabled = 0 ";
  $q_string .= "order by grp_name";
  $q_groups = mysql_query($q_string);
  while ($a_groups = mysqli_fetch_array($q_groups)) {

    $group  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
    $group .= "<tr>\n";
    $group .=   "<th class=\"ui-state-default\" colspan=\"13\">" . $a_groups['grp_name'] . "</th>\n";
    $group .= "</tr>\n";
    $group .= "<tr>\n";
    $group .=   "<th class=\"ui-state-default\">Del</th>\n";
    $group .=   "<th class=\"ui-state-default\">ID</th>\n";
    $group .=   "<th class=\"ui-state-default\">Login</th>\n";
    $group .=   "<th class=\"ui-state-default\">First Name</th>\n";
    $group .=   "<th class=\"ui-state-default\">Last Name</th>\n";
    $group .=   "<th class=\"ui-state-default\">E-Mail</th>\n";
    $group .=   "<th class=\"ui-state-default\">Force Password Change</th>\n";
    $group .=   "<th class=\"ui-state-default\">Registered Date</th>\n";
    $group .=   "<th class=\"ui-state-default\">Last Login</th>\n";
    $group .=   "<th class=\"ui-state-default\">IP Address</th>\n";
    $group .=   "<th class=\"ui-state-default\">Theme</th>\n";
    $group .= "</tr>\n";

    $count = 0;
    $q_string  = "select usr_id,usr_disabled,usr_name,usr_first,usr_last,";
    $q_string .= "usr_email,usr_reset,usr_timestamp,usr_lastlogin,usr_ipaddress,theme_title ";
    $q_string .= "from users ";
    $q_string .= "left join themes on themes.theme_id = users.usr_theme ";
    $q_string .= "where " . $p_query;
    $q_string .= "order by usr_last,usr_first";
    $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_users) > 0) {
      while ($a_users = mysqli_fetch_array($q_users)) {

        $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_user('add.users.del.php?id="  . $a_users['usr_id'] . "');\">";
        $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.users.fill.php?id=" . $a_users['usr_id'] . "');showDiv('user-hide');\">";
        $linkend = "</a>";

        if ($a_users['usr_reset']) {
          $default = " class=\"ui-state-highlight\"";
          $defaultdel = " class=\"ui-state-highlight delete\"";
        } else {
          if ($a_users['usr_disabled']) {
            $default = " class=\"ui-state-error\"";
            $defaultdel = " class=\"ui-state-error delete\"";
          } else {
            $default = " class=\"ui-widget-content\"";
            $defaultdel = " class=\"ui-widget-content delete\"";
          }
        }

        $timestamp = strtotime($a_users['usr_timestamp']);
        $reg_date = date('d M y @ H:i' ,$timestamp);

        $timestamp = strtotime($a_users['usr_lastlogin']);
        $lastlogin = date('d M y @ H:i' ,$timestamp);

        if ($a_users['usr_reset']) {
          $pwreset = 'Yes';
        } else {
          $pwreset = 'No';
        }

        $group .= "<tr>\n";
        $group .=   "<td" . $defaultdel . ">" . $linkdel                                                   . "</td>\n";
        $group .= "  <td" . $defaultdel . ">" . $linkstart . $a_users['usr_id']                 . $linkend . "</td>\n";
        $group .= "  <td" . $default    . ">" . $linkstart . $a_users['usr_name']               . $linkend . "</td>\n";
        $group .= "  <td" . $default    . ">" . $linkstart . $a_users['usr_first']              . $linkend . "</td>\n";
        $group .= "  <td" . $default    . ">" . $linkstart . $a_users['usr_last']               . $linkend . "</td>\n";
        $group .= "  <td" . $default    . ">" . $linkstart . $a_users['usr_email']              . $linkend . "</td>\n";
        $group .= "  <td" . $defaultdel . ">" . $linkstart . $pwreset                           . $linkend . "</td>\n";
        $group .= "  <td" . $defaultdel . ">" . $linkstart . $reg_date                          . $linkend . "</td>\n";
        $group .= "  <td" . $defaultdel . ">" . $linkstart . $lastlogin            . $linkend . "</td>\n";
        $group .= "  <td" . $defaultdel . ">" . $linkstart . $a_users['usr_ipaddress']          . $linkend . "</td>\n";
        $group .= "  <td" . $defaultdel . ">" . $linkstart . $a_users['theme_title']            . $linkend . "</td>\n";
        $group .= "</tr>\n";
        $count++;
      }
    } else {
      $group .= "<tr>\n";
      $group .= "  <td class=\"ui-widget-content\" colspan=\"13\">No records found.</td>\n";
      $group .= "</tr>\n";
    }

    $group .= "</table>\n";

  }

  print "document.getElementById('" . $p_toggle . "_table').innerHTML = '" . mysql_real_escape_string($group) . "';\n\n";

}

?>
