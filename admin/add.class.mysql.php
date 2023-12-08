<?php
# Script: add.class.mysql.php
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
    $package = "add.class.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                = clean($_GET['id'],                10);
        $formVars['class_subjectid']   = clean($_GET['class_subjectid'],   10);
        $formVars['class_name']        = clean($_GET['class_name'],        60);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if (strlen($formVars['class_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "class_subjectid    =   " . $formVars['class_subjectid']   . "," .
            "class_name         = \"" . $formVars['class_name']        . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into class set class_id = NULL, " . $q_string;
            $message = "Class added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update class set " . $q_string . " where class_id = " . $formVars['id'];
            $message = "Class updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['class_name']);

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
      $output .= "  <th class=\"ui-state-default\">Book Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $title . "-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"" . $title . "-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Subject</th>\n";
      $output .=   "<th class=\"ui-state-default\">Class</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select class_id,sub_name,class_name ";
      $q_string .= "from class ";
      $q_string .= "left join subjects on subjects.sub_id = class.class_subjectid ";
      $q_string .= "order by sub_name,class_name ";
      $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_class) > 0) {
        while ($a_class = mysqli_fetch_array($q_class)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.class.fill.php?id="  . $a_class['class_id'] . "');jQuery('#dialogClass').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_class('add.class.del.php?id=" . $a_class['class_id'] . "');\">";
          $linkend = "</a>";

          $output .= "<tr>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                                        . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">"              . $a_class['class_id']               . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"                                  . $a_class['sub_name']               . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"                     . $linkstart . $a_class['class_name']  . $linkend . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('class_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.class_subjectid.value = '';\n";
      print "document.dialog.class_name.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
