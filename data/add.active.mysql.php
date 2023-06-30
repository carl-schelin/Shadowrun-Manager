<?php
# Script: add.active.mysql.php
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
    $package = "add.active.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']            = clean($_GET['id'],            10);
        $formVars['act_type']      = clean($_GET['act_type'],      40);
        $formVars['act_name']      = clean($_GET['act_name'],      50);
        $formVars['act_group']     = clean($_GET['act_group'],     50);
        $formVars['act_attribute'] = clean($_GET['act_attribute'],  5);
        $formVars['act_default']   = clean($_GET['act_default'],   10);
        $formVars['act_book']      = clean($_GET['act_book'],      10);
        $formVars['act_page']      = clean($_GET['act_page'],      10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['act_default'] == 'true') {
          $formVars['act_default'] = 1;
        } else {
          $formVars['act_default'] = 0;
        }
        if ($formVars['act_page'] == '') {
          $formVars['act_page'] = 0;
        }

        if (strlen($formVars['act_type']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "act_type       = \"" . $formVars['act_type']      . "\"," .
            "act_name       = \"" . $formVars['act_name']      . "\"," .
            "act_group      = \"" . $formVars['act_group']     . "\"," .
            "act_attribute  = \"" . $formVars['act_attribute'] . "\"," .
            "act_default    =   " . $formVars['act_default']   . "," .
            "act_book       = \"" . $formVars['act_book']      . "\"," .
            "act_page       =   " . $formVars['act_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into active set act_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update active set " . $q_string . " where act_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['act_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $active_list = array("combat", "magical", "physical", "resonance", "social", "technical", "vehicle");

      foreach ($active_list as &$active) {

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Active Skill Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $active . "-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"" . $active . "-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Active Skill Listing</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li><strong>Remove</strong> - Click here to delete this Active Skill from the Mooks Database.</li>\n";
        $output .= "    <li><strong>Editing</strong> - Click on an Active Skill to toggle the form and edit the Active Skill.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Notes</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li>Click the <strong>Active Skill Management</strong> title bar to toggle the <strong>Active Skill Form</strong>.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
        $output .=   "<th class=\"ui-state-default\">ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Group</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Attribute</th>\n";
        $output .=   "<th class=\"ui-state-default\">Default</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select act_id,act_name,act_group,att_name,act_default,ver_book,act_page ";
        $q_string .= "from active ";
        $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
        $q_string .= "left join versions on versions.ver_id = active.act_book ";
        $q_string .= "where act_type = \"" . $active . "\" and ver_admin = 1 ";
        $q_string .= "order by act_type,act_name,ver_version ";
        $q_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_active) > 0) {
          while ($a_active = mysql_fetch_array($q_active)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.active.fill.php?id="  . $a_active['act_id'] . "');jQuery('#dialogActive').dialog('open');return false;\">";
            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_active('add.active.del.php?id=" . $a_active['act_id'] . "');\">";
            $linkend = "</a>";

            if ($a_active['act_default']) {
              $default = 'Yes';
            } else {
              $default = 'No';
            }

            $class = "ui-widget-content";

            $total = 0;
            $q_string  = "select r_act_id ";
            $q_string .= "from r_active ";
            $q_string .= "where r_act_number = " . $a_active['act_id'] . " ";
            $q_r_active = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_r_active) > 0) {
              while ($a_r_active = mysql_fetch_array($q_r_active)) {
                $total++;
              }
            }

            $output .= "<tr>\n";
            if ($total > 0) {
              $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
            } else {
              $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
            }
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_active['act_id']                                       . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"                     . $a_active['act_group']                                    . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_active['act_name']                          . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_active['att_name']                                     . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $default                                                  . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . return_Book($a_active['ver_book'], $a_active['act_page']) . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('" . $active . "_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      }

      print "document.dialog.act_name.value = '';\n";
      print "document.dialog.act_group.value = '';\n";
      print "document.dialog.act_attribute.value = '';\n";
      print "document.dialog.act_default.checked = false;\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
