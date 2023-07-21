<?php
# Script: identity.mysql.php
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
    $package = "identity.mysql.php";
    $formVars['update']             = clean($_GET['update'],            10);
    $formVars['id_character']       = clean($_GET['id_character'],      10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id_id']         = clean($_GET['id'],              10);
        $formVars['id_name']       = clean($_GET['id_name'],         80);
        $formVars['id_rating']     = clean($_GET['id_rating'],       10);
        $formVars['id_type']       = clean($_GET['id_type'],         10);
        $formVars['id_background'] = clean($_GET['id_background'], 1800);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['id_rating'] == '') {
          $formVars['id_rating'] = 0;
        }

        if (strlen($formVars['id_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "id_character  =   " . $formVars['id_character']   . "," .
            "id_name       = \"" . $formVars['id_name']        . "\"," .
            "id_rating     =   " . $formVars['id_rating']      . "," . 
            "id_type       =   " . $formVars['id_type']        . "," . 
            "id_background = \"" . $formVars['id_background']  . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_identity set id_id = NULL," . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update r_identity set " . $q_string . " where id_id = " . $formVars['id_id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['id_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

# create identity form
        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"id_refresh\" value=\"Refresh Identity Listing\" onClick=\"javascript:attach_identity('identity.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"id_update\"  value=\"Update Identity\"          onClick=\"javascript:attach_identity('identity.mysql.php', 1);hideDiv('identity-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"id_id\"      value=\"0\">\n";
        $output .= "<input type=\"button\" name=\"id_addbtn\"  value=\"Add Identity\"             onClick=\"javascript:attach_identity('identity.mysql.php', 0);\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Identity Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Identity Name (SIN): <input type=\"text\" name=\"id_name\" size=\"40\">\n";
        $output .= "  <td class=\"ui-widget-content\">Rating: <input type=\"text\" name=\"id_rating\" size=\"10\">\n";
        $output .= "  <td class=\"ui-widget-content\">Type: SIN <input type=\"radio\" value=\"0\" checked name=\"id_type\"> Fake SIN <input type=\"radio\" value=\"1\" checked name=\"id_type\"> Criminal SIN <input type=\"radio\" value=\"2\" name=\"id_type\">\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"3\">\n";
        $output .= "<textarea id=\"id_background\" name=\"id_background\" cols=\"200\" rows=\"5\"\n";
        $output .= "  onKeyDown=\"textCounter(document.edit.id_background, document.edit.idbLen, 1800);\"\n";
        $output .= "  onKeyUp  =\"textCounter(document.edit.id_background, document.edit.idbLen, 1800);\">\n";
        $output .= "</textarea>\n\n";
        $output .= "<br><input readonly type=\"text\" name=\"idbLen\" size=\"5\" value=\"1800\"> characters left\n";
        $output .= "</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('identity_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";


# create license form
        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"lic_refresh\" value=\"Refresh License Listing\" onClick=\"javascript:attach_license('license.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"lic_update\"  value=\"Update License\"          onClick=\"javascript:attach_license('license.mysql.php', 1);hideDiv('license-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"lic_id\"      value=\"0\">\n";
        $output .= "<input type=\"button\" name=\"lic_addbtn\"  value=\"Add License\"             onClick=\"javascript:attach_license('license.mysql.php', 0);\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"4\">License Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">License Type: <input type=\"text\" name=\"lic_type\" size=\"40\">\n";
        $output .= "  <td class=\"ui-widget-content\">Rating: <input type=\"text\" name=\"lic_rating\" size=\"10\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('license_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Identity and License Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('identity-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"identity-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Knowledge Skill Listing</strong>\n";
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
      $output .=   "<th class=\"ui-state-default\">Type</th>\n";
      $output .=   "<th class=\"ui-state-default\">Identity</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select id_id,id_name,id_type,id_rating,id_background ";
      $q_string .= "from r_identity ";
      $q_string .= "where id_character = " . $formVars['id_character'] . " ";
      $q_string .= "order by id_name ";
      $q_r_identity = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_identity) > 0) {
        while ($a_r_identity = mysql_fetch_array($q_r_identity)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('identity.fill.php?id=" . $a_r_identity['id_id'] . "');showDiv('identity-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_identity('identity.del.php?id="  . $a_r_identity['id_id'] . "');\">";
          $linkend   = "</a>";

          if ($a_r_identity['id_type'] == 2) {
            $type = "Criminal SIN: ";
          } else {
            if ($a_r_identity['id_type'] == 1) {
              $type = "Fake SIN: ";
            } else {
              $type = "SIN: ";
            }
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"         . $linkdel                                            . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"                . $linkstart . "Identity"                  . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $type . $linkstart . $a_r_identity['id_name']    . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"                      . $a_r_identity['id_rating']             . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"                      . "Shadowrun 5th Core: 24"               . "</td>\n";
          $output .= "</tr>\n";
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">" . "Background: " . $linkstart . $a_r_identity['id_background'] . $linkend . "</td>\n";
          $output .= "</tr>\n";

          $q_string  = "select lic_id,lic_type,lic_rating ";
          $q_string .= "from r_license ";
          $q_string .= "where lic_identity = " . $a_r_identity['id_id'] . " ";
          $q_string .= "order by lic_type ";
          $q_r_license = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_license) > 0) {
            while ($a_r_license = mysql_fetch_array($q_r_license)) {

              $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('license.fill.php?id=" . $a_r_license['lic_id'] . "');showDiv('license-hide');\">";
              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_license('license.del.php?id="  . $a_r_license['lic_id'] . "');\">";
              $linkend   = "</a>";

              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel                                            . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . "License"                   . $linkend . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\">&gt "    . $linkstart . $a_r_license['lic_type']    . $linkend . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_license['lic_rating']             . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"              . "Shadowrun 5th Core: 419"              . "</td>\n";
              $output .= "</tr>\n";

            }
          } else {
            $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">No Licenses added.</td>\n";
          }
          mysql_free_result($q_r_license);

        }
      } else {
        $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">No Identities added.</td>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_identity);

      print "document.getElementById('identity_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.id_update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
