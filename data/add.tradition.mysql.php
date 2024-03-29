<?php
# Script: add.tradition.mysql.php
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
    $package = "add.tradition.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Johnson)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']                 = clean($_GET['id'],                  10);
        $formVars['trad_name']          = clean($_GET['trad_name'],           60);
        $formVars['trad_description']   = clean($_GET['trad_description'],   255);
        $formVars['trad_combat']        = clean($_GET['trad_combat'],         10);
        $formVars['trad_detection']     = clean($_GET['trad_detection'],      10);
        $formVars['trad_health']        = clean($_GET['trad_health'],         10);
        $formVars['trad_illusion']      = clean($_GET['trad_illusion'],       10);
        $formVars['trad_manipulation']  = clean($_GET['trad_manipulation'],   10);
        $formVars['trad_drainleft']     = clean($_GET['trad_drainleft'],      10);
        $formVars['trad_drainright']    = clean($_GET['trad_drainright'],     10);
        $formVars['trad_book']          = clean($_GET['trad_book'],           10);
        $formVars['trad_page']          = clean($_GET['trad_page'],           10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if (strlen($formVars['trad_name']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "trad_name          = \"" . $formVars['trad_name']           . "\"," .
            "trad_description   = \"" . $formVars['trad_description']    . "\"," .
            "trad_combat        =   " . $formVars['trad_combat']         . "," .
            "trad_detection     =   " . $formVars['trad_detection']      . "," .
            "trad_health        =   " . $formVars['trad_health']         . "," .
            "trad_illusion      =   " . $formVars['trad_illusion']       . "," .
            "trad_manipulation  =   " . $formVars['trad_manipulation']   . "," .
            "trad_drainleft     =   " . $formVars['trad_drainleft']      . "," .
            "trad_drainright    =   " . $formVars['trad_drainright']     . "," .
            "trad_book          = \"" . $formVars['trad_book']           . "\"," .
            "trad_page          =   " . $formVars['trad_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into tradition set trad_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update tradition set " . $q_string . " where trad_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['trad_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $tradition_name[0] = "Unassigned";
      $q_string  = "select s_trad_id,s_trad_name ";
      $q_string .= "from s_tradition ";
      $q_s_tradition = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ($a_s_tradition = mysqli_fetch_array($q_s_tradition)) {
        $tradition_name[$a_s_tradition['s_trad_id']] = $a_s_tradition['s_trad_name'];
      }

      $attribute_name[0] = "Unassigned";
      $q_string  = "select att_id,att_name ";
      $q_string .= "from attributes ";
      $q_attributes = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ($a_attributes = mysqli_fetch_array($q_attributes)) {
        $attribute_name[$a_attributes['att_id']] = $a_attributes['att_name'];
      }

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
      $output .=   "<th class=\"ui-state-default\">Tradition</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Combat</th>\n";
      $output .=   "<th class=\"ui-state-default\">Detection</th>\n";
      $output .=   "<th class=\"ui-state-default\">Health</th>\n";
      $output .=   "<th class=\"ui-state-default\">Illusion</th>\n";
      $output .=   "<th class=\"ui-state-default\">Manipulation</th>\n";
      $output .=   "<th class=\"ui-state-default\">Drain</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select trad_id,trad_name,trad_description,trad_combat,trad_detection,trad_health,";
      $q_string .= "trad_illusion,trad_manipulation,trad_drainleft,trad_drainright,ver_book,trad_page ";
      $q_string .= "from tradition ";
      $q_string .= "left join versions on versions.ver_id = tradition.trad_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by trad_name,ver_version ";
      $q_tradition = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_tradition) > 0) {
        while ($a_tradition = mysqli_fetch_array($q_tradition)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.tradition.fill.php?id="  . $a_tradition['trad_id'] . "');jQuery('#dialogTradition').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_tradition('add.tradition.del.php?id=" . $a_tradition['trad_id'] . "');\">";
          $linkend = "</a>";

          $trad_book = return_Book($a_tradition['ver_book'], $a_tradition['trad_page']);

          $class = "ui-widget-content";

          $total = 0;
          $q_string  = "select r_trad_id ";
          $q_string .= "from r_tradition ";
          $q_string .= "where r_trad_number = " . $a_tradition['trad_id'] . " ";
          $q_r_tradition = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_tradition) > 0) {
            while ($a_r_tradition = mysqli_fetch_array($q_r_tradition)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">"              . $a_tradition['trad_id']                                                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">"              . $total                                                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $linkstart . $a_tradition['trad_name']                                   . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                                  . $a_tradition['trad_description']                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                                  . $tradition_name[$a_tradition['trad_combat']]                           . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                                  . $tradition_name[$a_tradition['trad_detection']]                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                                  . $tradition_name[$a_tradition['trad_health']]                           . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                                  . $tradition_name[$a_tradition['trad_illusion']]                         . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                                  . $tradition_name[$a_tradition['trad_manipulation']]                     . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                                  . $attribute_name[$a_tradition['trad_drainleft']] . " + " . $attribute_name[$a_tradition['trad_drainright']] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"                           . $trad_book                                                             . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"12\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.dialog.trad_name.value = '';\n";
      print "document.dialog.trad_description.value = '';\n";
      print "document.dialog.trad_combat.value = '';\n";
      print "document.dialog.trad_detection.value = '';\n";
      print "document.dialog.trad_health.value = '';\n";
      print "document.dialog.trad_illusion.value = '';\n";
      print "document.dialog.trad_manipulation.value = '';\n";
      print "document.dialog.trad_drainleft.value = '';\n";
      print "document.dialog.trad_drainright.value = '';\n";
      print "document.dialog.trad_book.value = '';\n";
      print "document.dialog.trad_page.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
