<?php
# Script: active.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: This is used with Spirits to add a description to the powers

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "active.mysql.php";
    $formVars['update']         = clean($_GET['update'],    10);

    $formVars['sp_act_creature'] = '';
    if (isset($_GET['r_spirit_id'])) {
      $formVars['sp_act_creature']    = clean($_GET['r_spirit_id'],   10);
    }

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['sp_act_creature'] == '') {
      $formVars['sp_act_creature'] = 0;
    }

    if (check_userlevel($db, $AL_Johnson)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                 = clean($_GET['id'],                 10);
        $formVars['sp_act_number']      = clean($_GET['act_id'],             10);
        $formVars['sp_act_specialize']  = clean($_GET['sp_act_specialize'],  60);

        if ($formVars['sp_act_number'] == '') {
          $formVars['sp_act_number'] = 0;
        }

        if ($formVars['sp_act_number'] > 0 || $formVars['id'] > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "sp_act_creature    =   " . $formVars['sp_act_creature']  . "," .
            "sp_act_number      =   " . $formVars['sp_act_number'];

          if ($formVars['update'] == 0) {
            $query = "insert into sp_active set sp_act_id = NULL, " . $q_string;
          }

          if ($formVars['update'] == 1) {

            $q_string = 
              "sp_act_specialize = \"" . $formVars['sp_act_specialize']  . "\"";

            $query = "update sp_active set " . $q_string . " where sp_act_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['sp_act_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Active Skill Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('active-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"active-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Attribute</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";


      if ($formVars['sp_act_creature'] == 0) {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">Select one of your Spirits in order to select an Active Skill.</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";
      } else {
        $q_string  = "select act_id,act_name,att_name,ver_book,act_page ";
        $q_string .= "from active ";
        $q_string .= "left join attributes on attributes.att_id = active.act_attribute ";
        $q_string .= "left join versions on versions.ver_id = active.act_book ";
        $q_string .= "where ver_admin = 1 ";
        $q_string .= "order by act_name,ver_version ";
        $q_active = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        if (mysqli_num_rows($q_active) > 0) {
          while ($a_active = mysqli_fetch_array($q_active)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:attach_active('active.mysql.php?act_id=" . $a_active['act_id'] . "', 0);\">";
            $linkend = "</a>";

            $active_book = return_Book($a_active['ver_book'], $a_active['act_page']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $a_active['act_id']              . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_active['act_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_active['att_name']            . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $active_book                     . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";
      }

      print "document.getElementById('active_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
