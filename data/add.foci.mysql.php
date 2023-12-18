<?php
# Script: add.foci.mysql.php
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
    $package = "add.foci.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Johnson)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                = clean($_GET['id'],               10);
        $formVars['foci_name']         = clean($_GET['foci_name'],        30);
        $formVars['foci_karma']        = clean($_GET['foci_karma'],       10);
        $formVars['foci_avail']        = clean($_GET['foci_avail'],       10);
        $formVars['foci_perm']         = clean($_GET['foci_perm'],         5);
        $formVars['foci_cost']         = clean($_GET['foci_cost'],        10);
        $formVars['foci_book']         = clean($_GET['foci_book'],        10);
        $formVars['foci_page']         = clean($_GET['foci_page'],        10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['foci_karma'] == '') {
          $formVars['foci_karma'] = 0;
        }
        if ($formVars['foci_cost'] == '') {
          $formVars['foci_cost'] = 0;
        }
        if ($formVars['foci_avail'] == '') {
          $formVars['foci_avail'] = 0;
        }
        if ($formVars['foci_page'] == '') {
          $formVars['foci_page'] = 0;
        }

        if (strlen($formVars['foci_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "foci_name           = \"" . $formVars['foci_name']        . "\"," .
            "foci_karma          =   " . $formVars['foci_karma']       . "," .
            "foci_avail          =   " . $formVars['foci_avail']       . "," .
            "foci_perm           = \"" . $formVars['foci_perm']        . "\"," .
            "foci_cost           =   " . $formVars['foci_cost']        . "," .
            "foci_book           =   " . $formVars['foci_book']        . "," .
            "foci_page           =   " . $formVars['foci_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into foci set foci_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update foci set " . $q_string . " where foci_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['foci_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

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
      $output .=   "<th class=\"ui-state-default\" width=\"160\">Delete Foci</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Karma</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select foci_id,foci_name,foci_karma,foci_avail,foci_perm,foci_cost,ver_book,foci_page ";
      $q_string .= "from foci ";
      $q_string .= "left join versions on versions.ver_id = foci.foci_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by foci_name,foci_karma,ver_version ";
      $q_foci = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_foci) > 0) {
        while ($a_foci = mysqli_fetch_array($q_foci)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.foci.fill.php?id="  . $a_foci['foci_id'] . "');jQuery('#dialogFoci').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_foci('add.foci.del.php?id=" . $a_foci['foci_id'] . "');\">";
          $linkend = "</a>";

          $foci_avail = return_Avail($a_foci['foci_avail'], $a_foci['foci_perm']);

          $foci_cost = return_Cost($a_foci['foci_cost']);

          $foci_book = return_Book($a_foci['ver_book'], $a_foci['foci_page']);

          $class = return_Class($a_foci['foci_perm']);

          $output .= "<tr>\n";
          $output .=   "<td class=\"" . $class . " delete\">" . $linkdel . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_foci['foci_id']               . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_foci['foci_name']  . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_foci['foci_karma']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $foci_avail                      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $foci_cost                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $foci_book                       . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.dialog.foci_name.value = '';\n";
      print "document.dialog.foci_karma.value = '';\n";
      print "document.dialog.foci_avail.value = '';\n";
      print "document.dialog.foci_cost.value = '';\n";
      print "document.dialog.foci_page.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
