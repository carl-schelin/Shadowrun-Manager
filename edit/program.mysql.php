<?php
# Script: program.mysql.php
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
    $package = "program.mysql.php";
    $formVars['id'] = clean($_GET['id'], 10);

    if (check_userlevel(3)) {

# common programs
      if ($formVars['id'] == 0) {
        $title = "Common Programs";
        $listing = "common";
        $table = "program";
        $myprogram = "mycyberdeck.mysql.php";
        $function = "select_program";
        $message = "No common programs found.";
      }

# hacking programs
      if ($formVars['id'] == 1) {
        $title = "Hacking Programs";
        $listing = "hacking";
        $table = "hacking";
        $myprogram = "mycyberdeck.mysql.php";
        $function = "select_program";
        $message = "No hacking programs found.";
      }

# rigger common programs
      if ($formVars['id'] == 2) {
        $title = "Rigger Common Programs";
        $listing = "cmdpgm";
        $table = "cmdpgm";
        $myprogram = "mycommand.mysql.php";
        $function = "select_cmdpgm";
        $message = "No common programs found.";
      }

# rigger hacking programs
      if ($formVars['id'] == 3) {
        $title = "Rigger Hacking Programs";
        $listing = "cmdhack";
        $table = "cmdhack";
        $myprogram = "mycommand.mysql.php";
        $function = "select_cmdpgm";
        $message = "No hacking programs found.";
      }

      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

# fill in the program or hacking tab on the cyberdeck or console management table
      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">" . $title . "</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $listing . "-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"" . $listing . "-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Program</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select pgm_id,pgm_name,pgm_desc,pgm_avail,pgm_perm,pgm_cost,ver_book,pgm_page ";
      $q_string .= "from program ";
      $q_string .= "left join versions on versions.ver_id = program.pgm_book ";
      $q_string .= "where pgm_type = " . $formVars['id'] . " and ver_active = 1 ";
      $q_string .= "order by pgm_name ";
      $q_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_program) > 0) {
        while ($a_program = mysqli_fetch_array($q_program)) {

# update = 3 == add program to a console or deck
          $linkstart = "<a href=\"#\" onclick=\"javascript:" . $function . "('" . $myprogram . "?update=3&pgm_id=" . $a_program['pgm_id'] . "');\">";
          $linkend = "</a>";

          $pgm_avail = return_Avail($a_program['pgm_avail'], $a_program['pgm_perm']);

          $pgm_cost = return_Cost($a_program['pgm_cost']);

          $pgm_book = return_Book($a_program['ver_book'], $a_program['pgm_page']);

          $class = return_Class($a_program['pgm_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_program['pgm_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_program['pgm_desc']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $pgm_avail                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $pgm_cost                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $pgm_book                         . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">" . $message . "</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('" . $table . "_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
