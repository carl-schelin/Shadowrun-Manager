<?php
# Script: add.knowledge.mysql.php
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
    $package = "add.knowledge.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],             10);
        $formVars['know_name']      = clean($_GET['know_name'],      50);
        $formVars['know_attribute'] = clean($_GET['know_attribute'], 10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['know_attribute'] == '') {
          $formVars['know_attribute'] = 0;
        }

        if (strlen($formVars['know_name']) > 0 && $formVars['know_attribute'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "know_name          = \"" . $formVars['know_name']       . "\"," .
            "know_attribute     =   " . $formVars['know_attribute'];

          if ($formVars['update'] == 0) {
            $query = "insert into knowledge set know_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update knowledge set " . $q_string . " where know_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['know_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $knowledge_list = array("street", "academic", "professional", "interests");

      foreach ($knowledge_list as &$knowledge) {

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Knowledge Skill Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $knowledge . "-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"" . $knowledge . "-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Knowledge Skill Listing</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li><strong>Remove</strong> - Click here to delete this Skill from the Mooks Database.</li>\n";
        $output .= "    <li><strong>Editing</strong> - Click on a Skill to toggle the form and edit the Skill.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Notes</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li>Click the <strong>Knowledge Skill Management</strong> title bar to toggle the <strong>Knowledge Skill Form</strong>.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
        $output .=   "<th class=\"ui-state-default\">ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Total</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Category</th>\n";
        $output .= "</tr>\n";

        if ($knowledge == "street") {
          $know_name = "Street Knowledge";
        }
        if ($knowledge == "academic") {
          $know_name = "Academic Knowledge";
        }
        if ($knowledge == "professional") {
          $know_name = "Professional Knowledge";
        }
        if ($knowledge == "interests") {
          $know_name = "Interests";
        }

        $q_string  = "select know_id,know_name,s_know_name ";
        $q_string .= "from knowledge ";
        $q_string .= "left join s_knowledge on s_knowledge.s_know_id = knowledge.know_attribute ";
        $q_string .= "where s_know_name = \"" . $know_name . "\" ";
        $q_string .= "order by know_name ";
        $q_knowledge = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_knowledge) > 0) {
          while ($a_knowledge = mysqli_fetch_array($q_knowledge)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.knowledge.fill.php?id="  . $a_knowledge['know_id'] . "');jQuery('#dialogKnowledge').dialog('open');return false;\">";
            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_knowledge('add.knowledge.del.php?id=" . $a_knowledge['know_id'] . "');\">";
            $linkend = "</a>";

            $class = "ui-widget-content";

            $total = 0;
            $q_string  = "select r_know_id ";
            $q_string .= "from r_knowledge ";
            $q_string .= "where r_know_number = " . $a_knowledge['know_id'] . " ";
            $q_r_knowledge = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_r_knowledge) > 0) {
              while ($a_r_knowledge = mysqli_fetch_array($q_r_knowledge)) {
                $total++;
              }
            }

            $output .= "<tr>\n";
            if ($total > 0) {
              $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
            } else {
              $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
            }
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">"              . $a_knowledge['know_id']                . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">"              . $total                                 . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"                     . $linkstart . $a_knowledge['know_name']   . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"                           . $a_knowledge['s_know_name']            . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('" . $knowledge . "_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }

      print "document.dialog.know_name.value = '';\n";
      print "document.dialog.know_attribute[1].select = true;\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
