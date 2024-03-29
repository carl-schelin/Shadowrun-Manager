<?php
# Script: add.contact.mysql.php
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
    $package = "add.contact.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Johnson)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],            10);
        $formVars['con_name']       = clean($_GET['con_name'],      60);
        $formVars['con_archetype']  = clean($_GET['con_archetype'], 60);
        $formVars['con_character']  = clean($_GET['con_character'], 10);
        $formVars['con_book']       = clean($_GET['con_book'],      10);
        $formVars['con_page']       = clean($_GET['con_page'],      10);
        $formVars['con_owner']      = clean($_GET['con_owner'],     10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['con_page'] == '') {
          $formVars['con_page'] = 0;
        }

        if (strlen($formVars['con_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "con_name      = \"" . $formVars['con_name']      . "\"," .
            "con_archetype = \"" . $formVars['con_archetype'] . "\"," .
            "con_character =   " . $formVars['con_character'] . "," .
            "con_book      = \"" . $formVars['con_book']      . "\"," .
            "con_page      =   " . $formVars['con_page']      . "," .
            "con_owner     =   " . $formVars['con_owner'];

          if ($formVars['update'] == 0) {
            $query = "insert into contact set con_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update contact set " . $q_string . " where con_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['con_archetype']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Contact Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('contact-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"contact-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete Contact</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Archetype</th>\n";
      $output .=   "<th class=\"ui-state-default\">Character</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .=   "<th class=\"ui-state-default\">Owner</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select con_id,con_name,con_archetype,runr_name,runr_archetype,ver_book,con_page,usr_last,usr_first ";
      $q_string .= "from contact ";
      $q_string .= "left join runners on runners.runr_id = contact.con_character ";
      $q_string .= "left join versions on versions.ver_id = contact.con_book ";
      $q_string .= "left join users on users.usr_id = contact.con_owner ";
      $q_string .= "order by con_archetype,con_name,ver_version ";
      $q_contact = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_contact) > 0) {
        while ($a_contact = mysqli_fetch_array($q_contact)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.contact.fill.php?id="  . $a_contact['con_id'] . "');jQuery('#dialogContact').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_contact('add.contact.del.php?id=" . $a_contact['con_id'] . "');\">";
          $linkend = "</a>";

          $character = $a_contact['runr_archetype'] . " (" . $a_contact['runr_name'] . ")";
          if ($a_contact['runr_archetype'] == '') {
            $character = "";
          }

          $con_book = return_Book($a_contact['ver_book'], $a_contact['con_page']);

          $class = "ui-widget-content";

          $total = 0;
          $q_string  = "select r_con_id ";
          $q_string .= "from r_contact ";
          $q_string .= "where r_con_number = " . $a_contact['con_id'] . " ";
          $q_r_contact = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_contact) > 0) {
            while ($a_r_contact = mysqli_fetch_array($q_r_contact)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                           . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_contact['con_id']                                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                                                             . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_contact['con_name']                                  . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_contact['con_archetype']                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $character                                                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $con_book                                                          . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_contact['usr_last'] . ", " . $a_contact['usr_first']            . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.dialog.con_name.value = '';\n";
      print "document.dialog.con_archetype.value = '';\n";
      print "document.dialog.con_character[0].selected = true;\n";
      print "document.dialog.con_owner[0].selected = true\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
