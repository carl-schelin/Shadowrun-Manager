<?php
# Script: add.spells.mysql.php
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
    $package = "add.spells.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']              = clean($_GET['id'],              10);
        $formVars['spell_group']     = clean($_GET['spell_group'],     30);
        $formVars['spell_name']      = clean($_GET['spell_name'],      30);
        $formVars['spell_class']     = clean($_GET['spell_class'],     60);
        $formVars['spell_type']      = clean($_GET['spell_type'],      10);
        $formVars['spell_test']      = clean($_GET['spell_test'],      20);
        $formVars['spell_range']     = clean($_GET['spell_range'],     10);
        $formVars['spell_damage']    = clean($_GET['spell_damage'],    10);
        $formVars['spell_duration']  = clean($_GET['spell_duration'],  20);
        $formVars['spell_force']     = clean($_GET['spell_force'],     10);
        $formVars['spell_half']      = clean($_GET['spell_half'],      10);
        $formVars['spell_drain']     = clean($_GET['spell_drain'],     10);
        $formVars['spell_book']      = clean($_GET['spell_book'],      10);
        $formVars['spell_page']      = clean($_GET['spell_page'],      10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['spell_force'] == 'true') {
          $formVars['spell_force'] = 1;
        } else {
          $formVars['spell_force'] = 0;
        }
        if ($formVars['spell_half'] == 'true') {
          $formVars['spell_force'] = 2;
        }
        if ($formVars['spell_drain'] == '') {
          $formVars['spell_drain'] = 0;
        }

        if (strlen($formVars['spell_group']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "spell_group      = \"" . $formVars['spell_group']     . "\"," .
            "spell_name       = \"" . $formVars['spell_name']      . "\"," .
            "spell_class      = \"" . $formVars['spell_class']     . "\"," .
            "spell_type       = \"" . $formVars['spell_type']      . "\"," .
            "spell_test       = \"" . $formVars['spell_test']      . "\"," .
            "spell_range      = \"" . $formVars['spell_range']     . "\"," .
            "spell_damage     = \"" . $formVars['spell_damage']    . "\"," .
            "spell_duration   = \"" . $formVars['spell_duration']  . "\"," .
            "spell_force      =   " . $formVars['spell_force']     . "," .
            "spell_drain      =   " . $formVars['spell_drain']     . "," .
            "spell_book       = \"" . $formVars['spell_book']      . "\"," .
            "spell_page       =   " . $formVars['spell_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into spells set spell_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update spells set " . $q_string . " where spell_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['spell_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $spell_list = array("combat", "detection", "health", "illusion", "manipulation");

      foreach ($spell_list as &$spell) {

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">" . ucfirst($spell) . " Spell Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $spell . "-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"" . $spell . "-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<p><strong>Spell Listing</p>\n";

        $output .= "<p>The main thing you'll want to know is WIL+REA is an Indirect Spell and INT+WIL is a Direct Spell.</p>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
        $output .=   "<th class=\"ui-state-default\">ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Total</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Class</th>\n";
        $output .=   "<th class=\"ui-state-default\">Type</th>\n";
        $output .=   "<th class=\"ui-state-default\">Test</th>\n";
        $output .=   "<th class=\"ui-state-default\">Range</th>\n";
        $output .=   "<th class=\"ui-state-default\">Damage</th>\n";
        $output .=   "<th class=\"ui-state-default\">Duration</th>\n";
        $output .=   "<th class=\"ui-state-default\">Drain</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select spell_id,spell_name,class_name,spell_class,spell_type,spell_test,spell_range,";
        $q_string .= "spell_damage,spell_duration,spell_force,spell_drain,ver_book,spell_page ";
        $q_string .= "from spells ";
        $q_string .= "left join class on class.class_id = spells.spell_group ";
        $q_string .= "left join versions on versions.ver_id = spells.spell_book ";
        $q_string .= "where class_name = \"" . $spell . "\" and ver_admin = 1 ";
        $q_string .= "order by spell_group,spell_name,ver_version ";
        $q_spells = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_spells) > 0) {
          while ($a_spells = mysql_fetch_array($q_spells)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.spells.fill.php?id="  . $a_spells['spell_id'] . "');jQuery('#dialogSpells').dialog('open');return false;\">";
            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_spells('add.spells.del.php?id=" . $a_spells['spell_id'] . "');\">";
            $linkend = "</a>";

            $spell_drain = return_Drain($a_spells['spell_drain'], $a_spells['spell_force']);

            $total = 0;
            $q_string  = "select r_spell_id ";
            $q_string .= "from r_spells ";
            $q_string .= "where r_spell_number = " . $a_spells['spell_id'] . " ";
            $q_r_spells = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_r_spells) > 0) {
              while ($a_r_spells = mysql_fetch_array($q_r_spells)) {
                $total++;
              }
            }

            $output .= "<tr>\n";
            if ($total > 0) {
              $output .=   "<td class=\"ui-widget-content delete\">In use</td>\n";
            } else {
              $output .=   "<td class=\"ui-widget-content delete\">" . $linkdel                                                  . "</td>\n";
            }
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $a_spells['spell_id']                                  . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $total                                                 . "</td>\n";
            $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_spells['spell_name']                     . $linkend . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_spells['class_name']                                . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_spells['spell_type']                                . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_spells['spell_test']                                . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_spells['spell_range']                               . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_spells['spell_damage']                              . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_spells['spell_duration']                            . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $spell_drain                                           . "</td>\n";
            $output .= "  <td class=\"ui-widget-content delete\">"              . $a_spells['ver_book'] . ": " . $a_spells['spell_page'] . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"12\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('" . $spell . "_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }

      print "document.dialog.spell_name.value = '';\n";
      print "document.dialog.spell_group.value = '';\n";
      print "document.dialog.spell_class.value = '';\n";
      print "document.dialog.spell_type.value = '';\n";
      print "document.dialog.spell_test.value = '';\n";
      print "document.dialog.spell_range.value = '';\n";
      print "document.dialog.spell_damage.value = '';\n";
      print "document.dialog.spell_duration.value = '';\n";
      print "document.dialog.spell_force.value = false;\n";
      print "document.dialog.spell_half.value = false;\n";
      print "document.dialog.spell_drain.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
