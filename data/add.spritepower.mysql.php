<?php
# Script: add.spritepower.mysql.php
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
    $package = "add.spritepower.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                = clean($_GET['id'],               10);
        $formVars['pow_name']          = clean($_GET['pow_name'],         60);
        $formVars['pow_description']   = clean($_GET['pow_description'], 255);
        $formVars['pow_book']          = clean($_GET['pow_book'],         10);
        $formVars['pow_page']          = clean($_GET['pow_page'],         10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['pow_page'] == '') {
          $formVars['pow_page'] = 0;
        }

        if (strlen($formVars['pow_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "pow_name             = \"" . $formVars['pow_name']          . "\"," .
            "pow_description      = \"" . $formVars['pow_description']   . "\"," .
            "pow_book             =   " . $formVars['pow_book']          . "," .
            "pow_page             =   " . $formVars['pow_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into sprite_powers set pow_id = NULL, " . $q_string;
            $message = "Power added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update sprite_powers set " . $q_string . " where pow_id = " . $formVars['id'];
            $message = "Power updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['pow_name']);

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
      $output .= "  <th class=\"ui-state-default\">Power Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('power-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"power-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select pow_id,pow_name,pow_description,ver_book,pow_page ";
      $q_string .= "from sprite_powers ";
      $q_string .= "left join versions on versions.ver_id = sprite_powers.pow_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by pow_name,ver_version ";
      $q_sprite_powers = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_sprite_powers) > 0) {
        while ($a_sprite_powers = mysql_fetch_array($q_sprite_powers)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.spritepower.fill.php?id="  . $a_sprite_powers['pow_id'] . "');jQuery('#dialogPower').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_power('add.spritepower.del.php?id=" . $a_sprite_powers['pow_id'] . "');\">";
          $linkend = "</a>";

          $power_book = return_Book($a_sprite_powers['ver_book'], $a_sprite_powers['pow_page']);

          $output .= "<tr>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $a_sprite_powers['pow_id']                     . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_sprite_powers['pow_name']        . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"                     . $a_sprite_powers['pow_description']            . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $power_book                             . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.pow_name.value = '';\n";
      print "document.dialog.pow_description.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
