<?php
# Script: add.lifestyle.mysql.php
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
    $package = "add.lifestyle.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                   = clean($_GET['id'],                  10);
        $formVars['life_style']           = clean($_GET['life_style'],          60);
        $formVars['life_comforts']        = clean($_GET['life_comforts'],       10);
        $formVars['life_security']        = clean($_GET['life_security'],       10);
        $formVars['life_neighborhood']    = clean($_GET['life_neighborhood'],   10);
        $formVars['life_entertainment']   = clean($_GET['life_entertainment'],  10);
        $formVars['life_cost']            = clean($_GET['life_cost'],           10);
        $formVars['life_book']            = clean($_GET['life_book'],           10);
        $formVars['life_page']            = clean($_GET['life_page'],           10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['life_comforts'] == '') {
          $formVars['life_comforts'] = 0;
        }
        if ($formVars['life_security'] == '') {
          $formVars['life_security'] = 0;
        }
        if ($formVars['life_neighborhood'] == '') {
          $formVars['life_neighborhood'] = 0;
        }
        if ($formVars['life_entertainment'] == '') {
          $formVars['life_entertainment'] = 0;
        }
        if ($formVars['life_cost'] == '') {
          $formVars['life_cost'] = 0;
        }
        if ($formVars['life_page'] == '') {
          $formVars['life_page'] = 0;
        }

        if (strlen($formVars['life_style']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "life_style    = \"" . $formVars['life_style']                    . "\"," .
            "life_comforts     =   " . $formVars['life_comforts']             . "," .
            "life_security     =   " . $formVars['life_security']             . "," .
            "life_neighborhood     =   " . $formVars['life_neighborhood']     . "," .
            "life_entertainment     =   " . $formVars['life_entertainment']   . "," .
            "life_cost     =   " . $formVars['life_cost']                     . "," .
            "life_book     = \"" . $formVars['life_book']                     . "\"," .
            "life_page     =   " . $formVars['life_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into lifestyle set life_id = NULL, " . $q_string;
            $message = "Lifestyle added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update lifestyle set " . $q_string . " where life_id = " . $formVars['id'];
            $message = "Lifestyle updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['life_style']);

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
      $output .= "  <th class=\"ui-state-default\">Lifestyle Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('lifestyle-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"lifestyle-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Lifestyle Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Lifestyle from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Lifestyle to toggle the form and edit the Lifestyle.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Lifestyle Management</strong> title bar to toggle the <strong>Lifestyle Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Lifestyle</th>\n";
      $output .=   "<th class=\"ui-state-default\">Comforts & Necessities</th>\n";
      $output .=   "<th class=\"ui-state-default\">Security</th>\n";
      $output .=   "<th class=\"ui-state-default\">Neighborhood</th>\n";
      $output .=   "<th class=\"ui-state-default\">Entertainment</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $nuyen = '&yen;';
      $q_string  = "select life_id,life_style,life_comforts,life_security,life_neighborhood,life_entertainment,life_cost,ver_book,life_page ";
      $q_string .= "from lifestyle ";
      $q_string .= "left join versions on versions.ver_id = lifestyle.life_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by life_cost,ver_version ";
      $q_lifestyle = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_lifestyle) > 0) {
        while ($a_lifestyle = mysql_fetch_array($q_lifestyle)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.lifestyle.fill.php?id="  . $a_lifestyle['life_id'] . "');jQuery('#dialogLifestyle').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_lifestyle('add.lifestyle.del.php?id=" . $a_lifestyle['life_id'] . "');\">";
          $linkend = "</a>";

          $output .= "<tr>\n";
          $output .=   "<td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                                                                  . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $a_lifestyle['life_id']                                                   . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_lifestyle['life_style']                                     . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_lifestyle['life_comforts']                                             . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_lifestyle['life_security']                                             . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_lifestyle['life_neighborhood']                                         . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_lifestyle['life_entertainment']                                        . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . number_format($a_lifestyle['life_cost'], 0, '.', ',') . $nuyen            . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_lifestyle['ver_book'] . ": " . $a_lifestyle['life_page']               . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.life_style.value = '';\n";
      print "document.dialog.life_comforts.value = '';\n";
      print "document.dialog.life_security.value = '';\n";
      print "document.dialog.life_neighborhood.value = '';\n";
      print "document.dialog.life_entertainment.value = '';\n";
      print "document.dialog.life_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
