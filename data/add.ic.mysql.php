<?php
# Script: add.ic.mysql.php
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
    $package = "add.ic.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Johnson)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],             10);
        $formVars['ic_name']        = clean($_GET['ic_name'],        30);
        $formVars['ic_defense']     = clean($_GET['ic_defense'],     30);
        $formVars['ic_description'] = clean($_GET['ic_description'], 60);
        $formVars['ic_book']        = clean($_GET['ic_book'],        10);
        $formVars['ic_page']        = clean($_GET['ic_page'],        10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['ic_page'] == '') {
          $formVars['ic_page'] = 0;
        }

        if (strlen($formVars['ic_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "ic_name          = \"" . $formVars['ic_name']        . "\"," .
            "ic_defense       = \"" . $formVars['ic_defense']     . "\"," .
            "ic_description   = \"" . $formVars['ic_description'] . "\"," .
            "ic_book          =   " . $formVars['ic_book']        . "," .
            "ic_page          =   " . $formVars['ic_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into ic set ic_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update ic set " . $q_string . " where ic_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['ic_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Sprite Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('countermeasure-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"countermeasure-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Defense</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select ic_id,ic_name,ic_defense,ic_description,ver_book,ic_page ";
      $q_string .= "from ic ";
      $q_string .= "left join versions on versions.ver_id = ic.ic_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by ic_name,ver_version ";
      $q_ic = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_ic) > 0) {
        while ($a_ic = mysqli_fetch_array($q_ic)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.ic.fill.php?id="  . $a_ic['ic_id'] . "');jQuery('#dialogIC').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_ic('add.ic.del.php?id=" . $a_ic['ic_id'] . "');\">";
          $linkend = "</a>";

          $ic_book = return_Book($a_ic['ver_book'], $a_ic['ic_page']);

          $class = "ui-widget-content";

          $output .= "<tr>\n";
          $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_ic['ic_id']                      . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_ic['ic_name']         . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_ic['ic_defense']                 . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_ic['ic_description']             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $ic_book                            . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.dialog.ic_name.value = '';\n";
      print "document.dialog.ic_defense.value = '';\n";
      print "document.dialog.ic_description.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
