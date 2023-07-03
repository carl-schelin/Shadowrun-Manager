<?php
# Script: add.complexform.mysql.php
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
    $package = "add.complexform.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],             10);
        $formVars['form_name']      = clean($_GET['form_name'],      40);
        $formVars['form_target']    = clean($_GET['form_target'],    10);
        $formVars['form_duration']  = clean($_GET['form_duration'],  10);
        $formVars['form_level']     = clean($_GET['form_level'],     10);
        $formVars['form_fading']    = clean($_GET['form_fading'],    10);
        $formVars['form_book']      = clean($_GET['form_book'],      10);
        $formVars['form_page']      = clean($_GET['form_page'],      10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['form_level'] == 'true') {
          $formVars['form_level'] = 1;
        } else {
          $formVars['form_level'] = 0;
        }
        if ($formVars['form_fading'] == '') {
          $formVars['form_fading'] = 0;
        }

        if (strlen($formVars['form_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "form_name        = \"" . $formVars['form_name']      . "\"," .
            "form_target      = \"" . $formVars['form_target']    . "\"," .
            "form_duration    = \"" . $formVars['form_duration']  . "\"," .
            "form_level       =   " . $formVars['form_level']     . "," .
            "form_fading      =   " . $formVars['form_fading']    . "," .
            "form_book        = \"" . $formVars['form_book']      . "\"," .
            "form_page        =   " . $formVars['form_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into complexform set form_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update complexform set " . $q_string . " where form_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['form_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Complex Form Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('complexform-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"complexform-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Language Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Language from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Language to toggle the form and edit the Language.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Language Management</strong> title bar to toggle the <strong>Language Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
      $output .=   "<th class=\"ui-state-default\">Complex Form</th>\n";
      $output .=   "<th class=\"ui-state-default\">Target</th>\n";
      $output .=   "<th class=\"ui-state-default\">Duration</th>\n";
      $output .=   "<th class=\"ui-state-default\">Fading Value</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select form_id,form_name,form_target,form_duration,form_fading,ver_book,form_page ";
      $q_string .= "from complexform ";
      $q_string .= "left join versions on versions.ver_id = complexform.form_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by form_name,ver_version ";
      $q_complexform = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_complexform) > 0) {
        while ($a_complexform = mysql_fetch_array($q_complexform)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.complexform.fill.php?id="  . $a_complexform['form_id'] . "');jQuery('#dialogForm').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_complexform('add.complexform.del.php?id=" . $a_complexform['form_id'] . "');\">";
          $linkend = "</a>";

          $target = "Device";
          if ($a_complexform['form_target'] == 1) {
            $target = "File";
          }
          if ($a_complexform['form_target'] == 2) {
            $target = "Persona";
          }
          if ($a_complexform['form_target'] == 3) {
            $target = "Self";
          }
          if ($a_complexform['form_target'] == 4) {
            $target = "Sprite";
          }

          $duration = "Immediate";
          if ($a_complexform['form_duration'] == 1) {
            $duration = "Permanent";
          }
          if ($a_complexform['form_duration'] == 2) {
            $duration = "Sustained";
          }

          $fading = return_Complex($a_complexform['form_fading'], $a_complexform['form_level']);

          $form_book = return_Book($a_complexform['ver_book'], $a_complexform['form_page']);

          $class = "ui-widget-content";

          $total = 0;
          $q_string  = "select r_form_id ";
          $q_string .= "from r_complexform ";
          $q_string .= "where r_form_number = " . $a_complexform['form_id'] . " ";
          $q_r_complexform = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_complexform) > 0) {
            while ($a_r_complexform = mysql_fetch_array($q_r_complexform)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                          . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_complexform['form_id']              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_complexform['form_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $target                                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $duration                              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $fading                                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $form_book                             . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('complexform_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.form_name.value = '';\n";
      print "document.dialog.form_target.value = '';\n";
      print "document.dialog.form_duration.value = '';\n";
      print "document.dialog.form_level.value = '';\n";
      print "document.dialog.form_fading.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
