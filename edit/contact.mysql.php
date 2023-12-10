<?php
# Script: contact.mysql.php
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
    $package = "contact.mysql.php";
    $formVars['update']              = clean($_GET['update'],               10);
    $formVars['r_con_character']     = clean($_GET['r_con_character'],      10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_con_id']          = clean($_GET['id'],                  10);
        $formVars['r_con_number']      = clean($_GET['r_con_number'],        10);
        $formVars['r_con_loyalty']     = clean($_GET['r_con_loyalty'],       10);
        $formVars['r_con_connection']  = clean($_GET['r_con_connection'],    10);
        $formVars['r_con_faction']     = clean($_GET['r_con_faction'],       10);
        $formVars['r_con_notes']       = clean($_GET['r_con_notes'],       2000);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_con_loyalty'] == '') {
          $formVars['r_con_loyalty'] = 0;
        }
        if ($formVars['r_con_connection'] == '') {
          $formVars['r_con_connection'] = 0;
        }
        if ($formVars['r_con_faction'] == '') {
          $formVars['r_con_faction'] = 0;
        }

        if ($formVars['r_con_number'] > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_con_character   =   " . $formVars['r_con_character']   . "," .
            "r_con_number      =   " . $formVars['r_con_number']      . "," .
            "r_con_loyalty     =   " . $formVars['r_con_loyalty']     . "," .
            "r_con_connection  =   " . $formVars['r_con_connection']  . "," . 
            "r_con_faction     =   " . $formVars['r_con_faction']     . "," . 
            "r_con_notes       = \"" . $formVars['r_con_notes']       . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_contact set r_con_id = NULL," . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update r_contact set " . $q_string . " where r_con_id = " . $formVars['r_con_id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_con_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -3) {

        logaccess($db, $_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_con_refresh\" value=\"Refresh Contact Listing\" onClick=\"javascript:attach_contact('contact.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_con_update\"  value=\"Update Contact\"          onClick=\"javascript:attach_contact('contact.mysql.php', 1);hideDiv('contact-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_con_id\"      value=\"0\">\n";
        $output .= "<input type=\"button\" name=\"r_con_addbtn\"  value=\"Add Contact\"             onClick=\"javascript:attach_contact('contact.mysql.php', 0);\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"4\">Contact Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Contact: <select name=\"r_con_number\">\n";
        $output .= "<option value=\"0\">None</option>\n";

        $q_string  = "select con_id,con_name,con_archetype ";
        $q_string .= "from contact ";
        $q_string .= "order by con_archetype,con_name ";
        $q_contact = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        while ($a_contact = mysqli_fetch_array($q_contact)) {
          $output .= "<option value=\"" . $a_contact['con_id'] . "\">" . $a_contact['con_archetype'] . " (" . $a_contact['con_name'] . ")</option>\n";
        }

        $output .= "</select></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Loyalty: <input type=\"text\" name=\"r_con_loyalty\" size=\"10\">\n";
        $output .= "  <td class=\"ui-widget-content\">Connection: <input type=\"text\" name=\"r_con_connection\" size=\"10\">\n";
        $output .= "  <td class=\"ui-widget-content\">Faction: <input type=\"text\" name=\"r_con_faction\" size=\"10\">\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"4\">\n";
        $output .= "<textarea id=\"r_con_notes\" name=\"r_con_notes\" cols=\"200\" rows=\"5\"\n";
        $output .= "  onKeyDown=\"textCounter(document.start.r_con_notes, document.edit.remLen, 1800);\"\n";
        $output .= "  onKeyUp  =\"textCounter(document.start.r_con_notes, document.edit.remLen, 1800);\">\n";
        $output .= "</textarea>\n\n";
        $output .= "<br><input readonly type=\"text\" name=\"remLen\" size=\"5\" value=\"1800\"> characters left\n";
        $output .= "</td>\n";
        $output .= "</tr>\n";

        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Contact Add Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Archetype: <input type=\"text\" name=\"con_archetype\" size=\"40\">\n";
        $output .= "  <td class=\"ui-widget-content\">Contact Name: <input type=\"text\" name=\"con_name\" size=\"40\">\n";
        $output .= "  <td class=\"ui-widget-content\">Book <select name=\"con_book\">\n";

        $q_string  = "select ver_id,ver_short ";
        $q_string .= "from versions ";
        $q_string .= "where ver_admin = 1 ";
        $q_string .= "order by ver_short ";
        $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        while ($a_versions = mysqli_fetch_array($q_versions)) {
          $output .= "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
        }

        $output .= "</select></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Page: <input type=\"text\" name=\"con_page\" size=\"10\">\n";
        $output .= "  <td class=\"button ui-widget-content\"><input type=\"button\" name=\"con_skill\" value=\"Add New Contact\" onClick=\"javascript:attach_contact_dialog('add.contact.mysql.php', 0);\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('contact_form').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Contact Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('knowledge-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"knowledge-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Contact Listing</strong>\n";
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
      $output .=   "<th class=\"ui-state-default\">Contact Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Archetype</th>\n";
      $output .=   "<th class=\"ui-state-default\">Loyalty</th>\n";
      $output .=   "<th class=\"ui-state-default\">Connection</th>\n";
      $output .=   "<th class=\"ui-state-default\">Faction</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_con_id,r_con_number,r_con_connection,r_con_loyalty,r_con_faction,r_con_notes,con_id,con_name,con_archetype,ver_book,con_page ";
      $q_string .= "from r_contact ";
      $q_string .= "left join contact on contact.con_id = r_contact.r_con_number ";
      $q_string .= "left join versions on versions.ver_id = contact.con_book ";
      $q_string .= "where r_con_character = " . $formVars['r_con_character'] . " ";
      $q_string .= "order by con_archetype,con_name ";
      $q_r_contact = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_contact) > 0) {
        while ($a_r_contact = mysqli_fetch_array($q_r_contact)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('contact.fill.php?id=" . $a_r_contact['r_con_id'] . "');showDiv('contact-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_contact('contact.del.php?id="  . $a_r_contact['r_con_id'] . "');\">";
          $linkend   = "</a>";

          $con_book = return_Book($a_r_contact['ver_book'], $a_r_contact['con_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_con_number']) && $formVars['r_con_number'] == $r_con_contact['con_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $linkdel                                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_contact['con_name']         . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_contact['con_archetype']    . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_contact['r_con_loyalty']               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_contact['r_con_connection']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_contact['r_con_faction']               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $con_book                                   . "</td>\n";
          $output .= "</tr>\n";

        }
      } else {
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No Contacts added.</td>\n";
      }
      $output .= "</table>\n";

      mysqli_free_result($q_r_contact);

      print "document.getElementById('contact_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.edit.r_con_update.disabled = true;\n";
    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
