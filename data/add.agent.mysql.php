<?php
# Script: add.agent.mysql.php
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
    $package = "add.agent.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],             10);
        $formVars['agt_name']       = clean($_GET['agt_name'],       30);
        $formVars['agt_rating']     = clean($_GET['agt_rating'],     10);
        $formVars['agt_avail']      = clean($_GET['agt_avail'],      10);
        $formVars['agt_perm']       = clean($_GET['agt_perm'],       10);
        $formVars['agt_cost']       = clean($_GET['agt_cost'],       10);
        $formVars['agt_book']       = clean($_GET['agt_book'],       10);
        $formVars['agt_page']       = clean($_GET['agt_page'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['agt_rating'] == '') {
          $formVars['agt_rating'] = 0;
        }
        if ($formVars['agt_avail'] == '') {
          $formVars['agt_avail'] = 0;
        }
        if ($formVars['agt_cost'] == '') {
          $formVars['agt_cost'] = 0;
        }
        if ($formVars['agt_page'] == '') {
          $formVars['agt_page'] = 0;
        }

        if (strlen($formVars['agt_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "agt_name        = \"" . $formVars['agt_name']       . "\"," .
            "agt_rating      =   " . $formVars['agt_rating']     . "," .
            "agt_avail       =   " . $formVars['agt_avail']      . "," .
            "agt_perm        = \"" . $formVars['agt_perm']       . "\"," .
            "agt_cost        =   " . $formVars['agt_cost']       . "," .
            "agt_book        = \"" . $formVars['agt_book']       . "\"," .
            "agt_page        =   " . $formVars['agt_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into agents set agt_id = NULL, " . $q_string;
            $message = "Agents added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update agents set " . $q_string . " where agt_id = " . $formVars['id'];
            $message = "Agents updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['agt_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Agent Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('agent-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"agent-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>AGent Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Program from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Program to toggle the form and edit the Program.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Program Management</strong> title bar to toggle the <strong>Program Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book</th>\n";
      $output .= "</tr>\n";

      $nuyen = '&yen;';
      $q_string  = "select agt_id,agt_name,agt_rating,agt_avail,agt_perm,agt_cost,ver_book,agt_page ";
      $q_string .= "from agents ";
      $q_string .= "left join versions on versions.ver_id = agents.agt_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by agt_name,agt_rating,ver_version ";
      $q_agents = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_agents) > 0) {
        while ($a_agents = mysql_fetch_array($q_agents)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.agent.fill.php?id="  . $a_agents['agt_id'] . "');jQuery('#dialogAgent').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_agent('add.agent.del.php?id=" . $a_agents['agt_id'] . "');\">";
          $linkend = "</a>";

          $class = "ui-widget-content";
          if ($a_agents['agt_perm'] == 'R') {
            $class = "ui-state-highlight";
          }
          if ($a_agents['agt_perm'] == 'F') {
            $class = "ui-state-error";
          }

          $agt_avail = "-";
          if ($a_agents['agt_avail'] != 0) {
            $agt_avail = $a_agents['agt_avail'] . $a_agents['agt_perm'];
          }

          $output .= "<tr>\n";
          $output .=   "<td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">"              . $a_agents['agt_id']                                                   . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $linkstart . $a_agents['agt_name']                                      . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"                           . $a_agents['agt_rating']                                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"                           . $agt_avail                                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"                           . number_format($a_agents['agt_cost'], 0, '.', ',') . $nuyen            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"                           . $a_agents['ver_book'] . ": " . $a_agents['agt_page']                 . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('agent_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
    }

    print "document.dialog.agt_name.value = '';\n";
    print "document.dialog.agt_rating.value = '';\n";
    print "document.dialog.agt_avail.value = '';\n";
    print "document.dialog.agt_perm.value = '';\n";
    print "document.dialog.agt_cost.value = '';\n";

    print "$(\"#button-update\").button(\"disable\");\n";

  } else {
    logaccess($_SESSION['username'], $package, "Unauthorized access.");
  }

?>
