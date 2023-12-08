<?php
# Script: complex.mysql.php
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
    $package = "complex.mysql.php";

    $formVars['id'] = clean($_GET['id'], 10);

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
    $q_string .= "where r_form_character = " . $formVars['id'] . " ";
    $q_string .= "order by form_name,ver_version ";
    $q_r_complexform = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
    if (mysql_num_rows($q_r_complexform) > 0) {
      while ($a_r_complexform = mysqli_fetch_array($q_r_complexform)) {

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
        $output .= "  <td class=\"" . $class . "\">"        . $a_r_complexform['form_name'] . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $form_target                  . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $form_duration                . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $form_fading                  . "</td>\n";
        $output .= "  <td class=\"" . $class . " delete\">" . $form_book                    . "</td>\n";
        $output .= "</tr>\n";

      }
    } else {
     $output .= "<tr>";
     $output .= "<td class=\"ui-widget-content\" colspan=\"5\">No Complex Forms found</td>";
     $output .= "</tr>";

    }

    $output .= "</table>\n";

    mysql_free_result($q_r_complexform);

    print "document.getElementById('complex_mysql').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

  }
?>
