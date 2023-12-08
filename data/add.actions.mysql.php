<?php
# Script: add.actions.mysql.php
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
    $package = "add.actions.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                = clean($_GET['id'],              10);
        $formVars['action_name']       = clean($_GET['action_name'],     30);
        $formVars['action_type']       = clean($_GET['action_type'],     10);
        $formVars['action_level']      = clean($_GET['action_level'],    10);
        $formVars['action_attack']     = clean($_GET['action_attack'],   30);
        $formVars['action_defense']    = clean($_GET['action_defense'],  30);
        $formVars['action_outsider']   = clean($_GET['action_outsider'], 10);
        $formVars['action_user']       = clean($_GET['action_user'],     10);
        $formVars['action_admin']      = clean($_GET['action_admin'],    10);
        $formVars['action_book']       = clean($_GET['action_book'],     10);
        $formVars['action_page']       = clean($_GET['action_page'],     10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['action_outsider'] == 'true') {
          $formVars['action_outsider'] = 1;
        } else {
          $formVars['action_outsider'] = 0;
        }
        if ($formVars['action_user'] == 'true') {
          $formVars['action_user'] = 1;
        } else {
          $formVars['action_user'] = 0;
        }
        if ($formVars['action_admin'] == 'true') {
          $formVars['action_admin'] = 1;
        } else {
          $formVars['action_admin'] = 0;
        }
        if ($formVars['action_page'] == '') {
          $formVars['action_page'] = 0;
        }

        if (strlen($formVars['action_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "action_name         = \"" . $formVars['action_name']      . "\"," .
            "action_type         =   " . $formVars['action_type']      . "," .
            "action_level        =   " . $formVars['action_level']     . "," .
            "action_attack       = \"" . $formVars['action_attack']    . "\"," .
            "action_defense      = \"" . $formVars['action_defense']   . "\"," .
            "action_outsider     =   " . $formVars['action_outsider']  . "," .
            "action_user         =   " . $formVars['action_user']      . "," .
            "action_admin        =   " . $formVars['action_admin']     . "," .
            "action_book         =   " . $formVars['action_book']      . "," .
            "action_page         =   " . $formVars['action_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into actions set action_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update actions set " . $q_string . " where action_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['action_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Sprite Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('action-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"action-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Metatype Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Metatype from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Metatype to toggle the form and edit the Metatype.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Metatype Management</strong> title bar to toggle the <strong>Metatype Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Type</th>\n";
      $output .=   "<th class=\"ui-state-default\">Level</th>\n";
      $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
      $output .=   "<th class=\"ui-state-default\">Defense</th>\n";
      $output .=   "<th class=\"ui-state-default\">Access</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select action_id,action_name,action_type,action_level,action_attack,";
      $q_string .= "action_defense,action_outsider,action_user,action_admin,ver_book,action_page ";
      $q_string .= "from actions ";
      $q_string .= "left join versions on versions.ver_id = actions.action_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by action_name,ver_version ";
      $q_actions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_actions) > 0) {
        while ($a_actions = mysqli_fetch_array($q_actions)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.actions.fill.php?id="  . $a_actions['action_id'] . "');jQuery('#dialogActions').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_action('add.actions.del.php?id=" . $a_actions['action_id'] . "');\">";
          $linkend = "</a>";

          $action_type = return_Type($a_actions['action_type']);

          $action_level = return_Level($a_actions['action_level']);

          $action_access = return_Access($a_actions['action_outsider'], $a_actions['action_user'], $a_actions['action_admin']);

          $action_book = return_Book($a_actions['ver_book'], $a_actions['action_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_actions['action_id']                      . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_actions['action_name']         . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $action_type                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $action_level                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_actions['action_attack']                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_actions['action_defense']                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $action_access                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $action_book                                 . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.action_name.value = '';\n";
      print "document.dialog.action_type[0].checked = true;\n";
      print "document.dialog.action_level[0].checked = true;\n";
      print "document.dialog.action_attack.value = '';\n";
      print "document.dialog.action_defense.value = '';\n";
      print "document.dialog.action_outsider.checked = false;\n";
      print "document.dialog.action_user.checked = false;\n";
      print "document.dialog.action_admin.checked = false;\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
