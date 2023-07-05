<?php
# Script: complexform.mysql.php
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
    $package = "complexform.mysql.php";
    if (isset($_GET['update'])) {
      $formVars['update'] = clean($_GET['update'], 10);
    } else {
      $formVars['update'] = -1;
    }
    if (isset($_GET['r_form_character'])) {
      $formVars['r_form_character'] = clean($_GET['r_form_character'], 10);
    } else {
      $formVars['r_form_character'] = 0;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0) {
        $formVars['r_form_number']      = clean($_GET['r_form_number'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_form_number'] == '') {
          $formVars['r_form_number'] = 1;
        }

        if ($formVars['r_form_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_form_character   =   " . $formVars['r_form_character']   . "," .
            "r_form_number      =   " . $formVars['r_form_number'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_complexform set r_form_id = NULL," . $q_string;
            $message = "Complex Form added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_form_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -2) {
        $formVars['copyfrom'] = clean($_GET['r_form_copyfrom'], 10);

        if ($formVars['copyfrom'] > 0) {
          $q_string  = "select r_form_number ";
          $q_string .= "from r_complexform ";
          $q_string .= "where r_form_character = " . $formVars['copyfrom'];
          $q_r_complexform = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          while ($a_r_complexform = mysql_fetch_array($q_r_complexform)) {

            $q_string =
              "r_form_character     =   " . $formVars['r_form_character']   . "," .
              "r_form_number        =   " . $a_r_complexform['r_form_number'];
  
            $query = "insert into r_complexform set r_bio_id = NULL, " . $q_string;
            mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
          }
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      if ($formVars['update'] == -3) {

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Complex Forms</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('complexform-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"complexform-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Weapon Listing</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li><strong>Remove</strong> - Click here to delete this Weapon from the Mooks Database.</li>\n";
        $output .= "    <li><strong>Editing</strong> - Click on a Weapon to toggle the form and edit the Weapon.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Notes</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li>Click the <strong>Firearm Management</strong> title bar to toggle the <strong>Firearm Form</strong>.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Complex Form</th>\n";
        $output .=   "<th class=\"ui-state-default\">Target</th>\n";
        $output .=   "<th class=\"ui-state-default\">Duration</th>\n";
        $output .=   "<th class=\"ui-state-default\">Fading</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select form_id,form_name,form_target,form_duration,form_fading,form_level,ver_book,form_page ";
        $q_string .= "from complexform ";
        $q_string .= "left join versions on versions.ver_id = complexform.form_book ";
        $q_string .= "where ver_active = 1 ";
        $q_string .= "order by form_name,ver_version ";
        $q_complexform = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_complexform) > 0) {
          while ($a_complexform = mysql_fetch_array($q_complexform)) {

# this adds the bio_id to the r_bio_character
            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('complexform.mysql.php?update=0&r_form_character=" . $formVars['r_form_character'] . "&r_form_number=" . $a_complexform['form_id'] . "');\">";
            $linkend = "</a>";

            $form_target = "Device";
            if ($a_complexform['form_target'] == 1) {
              $form_target = "File";
            }
            if ($a_complexform['form_target'] == 2) {
              $form_target = "Persona";
            }
            if ($a_complexform['form_target'] == 3) {
              $form_target = "Self";
            }
            if ($a_complexform['form_target'] == 4) {
              $form_target = "Sprite";
            }

            $form_duration = "Immediate";
            if ($a_complexform['form_duration'] == 1) {
              $form_duration = "Permanent";
            }
            if ($a_complexform['form_duration'] == 2) {
              $form_duration = "Sustained";
            }

            $form_fading = return_Complex($a_complexform['form_fading'], $a_complexform['form_level']);

            $form_book = return_Book($a_complexform['ver_book'], $a_complexform['form_page']);

            $class = "ui-widget-content";

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_complexform['form_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $form_target                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $form_duration                         . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $form_fading                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $form_book                             . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('complexform_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">My Complex Forms</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('my-complexform-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"my-complexform-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Complex Form</th>\n";
      $output .=   "<th class=\"ui-state-default\">Target</th>\n";
      $output .=   "<th class=\"ui-state-default\">Duration</th>\n";
      $output .=   "<th class=\"ui-state-default\">Fading</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_form_id,form_id,form_name,form_target,form_duration,form_fading,ver_book,form_page ";
      $q_string .= "from r_complexform ";
      $q_string .= "left join complexform on complexform.form_id = r_complexform.r_form_number ";
      $q_string .= "left join versions on versions.ver_id = complexform.form_book ";
      $q_string .= "where r_form_character = " . $formVars['r_form_character'] . " ";
      $q_string .= "order by form_name,ver_version ";
      $q_r_complexform = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_complexform) > 0) {
        while ($a_r_complexform = mysql_fetch_array($q_r_complexform)) {

          $linkdel = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_complexform('complexform.del.php?id="  . $a_r_complexform['r_form_id'] . "');\">";
          $linkend = "</a>";

          $form_target = "Device";
          if ($a_r_complexform['form_target'] == 1) {
            $form_target = "File";
          }
          if ($a_r_complexform['form_target'] == 2) {
            $form_target = "Persona";
          }
          if ($a_r_complexform['form_target'] == 3) {
            $form_target = "Self";
          }
          if ($a_r_complexform['form_target'] == 4) {
            $form_target = "Sprite";
          }

          $form_duration = "Immediate";
          if ($a_r_complexform['form_duration'] == 1) {
            $form_duration = "Permanent";
          }
          if ($a_r_complexform['form_duration'] == 2) {
            $form_duration = "Sustained";
          }

          $form_fading = 'L';
          if ($a_r_complexform['form_fading'] > 0) {
            $form_fading = "L+" . $a_r_complexform['form_fading'];
          }
          if ($a_r_complexform['form_fading'] < 0) {
            $form_fading = "L" . $a_r_complexform['form_fading'];
          }

          $form_book = return_Book($a_r_complexform['ver_book'], $a_r_complexform['form_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel         . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_r_complexform['form_name'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $form_target                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $form_duration                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $form_fading                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $form_book                    . "</td>\n";
          $output .= "</tr>\n";

        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No Complex Forms added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_complexform);

      print "document.getElementById('my_complexform_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
