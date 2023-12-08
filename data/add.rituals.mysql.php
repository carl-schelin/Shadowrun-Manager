<?php
# Script: add.rituals.mysql.php
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
    $package = "add.rituals.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],             10);
        $formVars['rit_name']       = clean($_GET['rit_name'],       60);
        $formVars['rit_anchor']     = clean($_GET['rit_anchor'],     10);
        $formVars['rit_link']       = clean($_GET['rit_link'],       10);
        $formVars['rit_minion']     = clean($_GET['rit_minion'],     10);
        $formVars['rit_spell']      = clean($_GET['rit_spell'],      10);
        $formVars['rit_spotter']    = clean($_GET['rit_spotter'],    10);
        $formVars['rit_threshold']  = clean($_GET['rit_threshold'],  10);
        $formVars['rit_length']     = clean($_GET['rit_length'],     30);
        $formVars['rit_duration']   = clean($_GET['rit_duration'],   30);
        $formVars['rit_book']       = clean($_GET['rit_book'],       10);
        $formVars['rit_page']       = clean($_GET['rit_page'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['rit_anchor'] == 'true') {
          $formVars['rit_anchor'] = 1;
        } else {
          $formVars['rit_anchor'] = 0;
        }
        if ($formVars['rit_link'] == 'true') {
          $formVars['rit_link'] = 1;
        } else {
          $formVars['rit_link'] = 0;
        }
        if ($formVars['rit_minion'] == 'true') {
          $formVars['rit_minion'] = 1;
        } else {
          $formVars['rit_minion'] = 0;
        }
        if ($formVars['rit_spell'] == 'true') {
          $formVars['rit_spell'] = 1;
        } else {
          $formVars['rit_spell'] = 0;
        }
        if ($formVars['rit_spotter'] == 'true') {
          $formVars['rit_spotter'] = 1;
        } else {
          $formVars['rit_spotter'] = 0;
        }
        if ($formVars['rit_threshold'] == '') {
          $formVars['rit_threshold'] = 0;
        }
        if ($formVars['rit_page'] == '') {
          $formVars['rit_page'] = 0;
        }

        if (strlen($formVars['rit_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "rit_name =       \"" . $formVars['rit_name']      . "\"," .
            "rit_anchor =       " . $formVars['rit_anchor']    . "," .
            "rit_link =         " . $formVars['rit_link']      . "," .
            "rit_minion =       " . $formVars['rit_minion']    . "," .
            "rit_spell =        " . $formVars['rit_spell']     . "," .
            "rit_spotter =      " . $formVars['rit_spotter']   . "      ," .
            "rit_threshold =    " . $formVars['rit_threshold'] . "," .
            "rit_length =     \"" . $formVars['rit_length']    . "\"," .
            "rit_duration =   \"" . $formVars['rit_duration']  . "\"," .
            "rit_book =         " . $formVars['rit_book']      . "," .
            "rit_page =         " . $formVars['rit_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into rituals set rit_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update rituals set " . $q_string . " where rit_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['rit_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Ritual Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('ritual-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"ritual-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Ritual Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Remove</strong> - Click here to delete this Language from the Mooks Database.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a Language to toggle the form and edit the Language.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Language Management</strong> title bar to toggle the <strong>Language Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Threshold</th>\n";
      $output .=   "<th class=\"ui-state-default\">Length</th>\n";
      $output .=   "<th class=\"ui-state-default\">Duration</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select rit_id,rit_name,rit_anchor,rit_link,rit_minion,rit_spell,rit_spotter,rit_threshold,rit_length,rit_duration,ver_book,rit_page ";
      $q_string .= "from rituals ";
      $q_string .= "left join versions on versions.ver_id = rituals.rit_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by rit_name ";
      $q_rituals = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_rituals) > 0) {
        while ($a_rituals = mysqli_fetch_array($q_rituals)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.rituals.fill.php?id="  . $a_rituals['rit_id'] . "');jQuery('#dialogRitual').dialog('open');return false;\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_ritual('add.rituals.del.php?id=" . $a_rituals['rit_id'] . "');\">";
          $linkend = "</a>";

          $keywords = " (";
          $comma = '';
          if ($a_rituals['rit_anchor']) { 
            $keywords .= "Anchored";
            $comma = ', ';
          }
          if ($a_rituals['rit_link']) { 
            $keywords .= $comma . "Material Link";
            $comma = ', ';
          }
          if ($a_rituals['rit_minion']) { 
            $keywords .= $comma . "Minion";
            $comma = ', ';
          }
          if ($a_rituals['rit_spell']) { 
            $keywords .= $comma . "Spell";
            $comma = ', ';
          }
          if ($a_rituals['rit_spotter']) { 
            $keywords .= $comma . "Spotter";
          }
          $keywords .= ")";

          $book = return_Book($a_rituals['ver_book'], $a_rituals['rit_page']);

          $class = "ui-widget-content";

          $total = 0;
          $q_string  = "select r_rit_id ";
          $q_string .= "from r_rituals ";
          $q_string .= "where r_rit_number = " . $a_rituals['rit_id'] . " ";
          $q_r_rituals = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_rituals) > 0) {
            while ($a_r_rituals = mysqli_fetch_array($q_r_rituals)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">"              . $a_rituals['rit_id']              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">"              . $total                            . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $linkstart . $a_rituals['rit_name'] . $keywords . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"                           . $a_rituals['rit_threshold']       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"                           . $a_rituals['rit_length']          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"                           . $a_rituals['rit_duration']        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"                           . $book                             . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.dialog.rit_name.value = '';\n";
      print "document.dialog.rit_anchor.checked = false;\n";
      print "document.dialog.rit_link.checked = false;\n";
      print "document.dialog.rit_minion.checked = false;\n";
      print "document.dialog.rit_spell.checked = false;\n";
      print "document.dialog.rit_spotter.checked = false;\n";
      print "document.dialog.rit_threshold.value = '';\n";
      print "document.dialog.rit_length.value = '';\n";
      print "document.dialog.rit_duration.value = '';\n";
      print "document.dialog.rit_page.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
