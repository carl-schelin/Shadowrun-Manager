<?php
# Script: lifestyle.mysql.php
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
    $package = "lifestyle.mysql.php";
    $formVars['update']              = clean($_GET['update'],               10);
    $formVars['r_life_character']    = clean($_GET['r_life_character'],     10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_life_id']            = clean($_GET['id'],                    10);
        $formVars['r_life_number']        = clean($_GET['r_life_number'],         10);
        $formVars['r_life_comforts']      = clean($_GET['r_life_comforts'],       10);
        $formVars['r_life_necessities']   = clean($_GET['r_life_necessities'],    10);
        $formVars['r_life_security']      = clean($_GET['r_life_security'],       10);
        $formVars['r_life_neighborhood']  = clean($_GET['r_life_neighborhood'],   10);
        $formVars['r_life_entertainment'] = clean($_GET['r_life_entertainment'],  10);
        $formVars['r_life_space']         = clean($_GET['r_life_space'],          10);
        $formVars['r_life_desc']          = clean($_GET['r_life_desc'],          100);
        $formVars['r_life_months']        = clean($_GET['r_life_months'],         10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_life_comforts'] == '') {
          $formVars['r_life_comforts'] = 0;
        }
        if ($formVars['r_life_necessities'] == '') {
          $formVars['r_life_necessities'] = 0;
        }
        if ($formVars['r_life_security'] == '') {
          $formVars['r_life_security'] = 0;
        }
        if ($formVars['r_life_neighborhood'] == '') {
          $formVars['r_life_neighborhood'] = 0;
        }
        if ($formVars['r_life_entertainment'] == '') {
          $formVars['r_life_entertainment'] = 0;
        }
        if ($formVars['r_life_space'] == '') {
          $formVars['r_life_space'] = 0;
        }

        if ($formVars['r_life_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_life_character     =   " . $formVars['r_life_character']     . "," .
            "r_life_number        =   " . $formVars['r_life_number']        . "," .
            "r_life_comforts      =   " . $formVars['r_life_comforts']      . "," .
            "r_life_necessities   =   " . $formVars['r_life_necessities']   . "," .
            "r_life_security      =   " . $formVars['r_life_security']      . "," .
            "r_life_neighborhood  =   " . $formVars['r_life_neighborhood']  . "," .
            "r_life_entertainment =   " . $formVars['r_life_entertainment'] . "," .
            "r_life_space         =   " . $formVars['r_life_space']         . "," .
            "r_life_desc          = \"" . $formVars['r_life_desc']          . "\"," .
            "r_life_months        =   " . $formVars['r_life_months'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_lifestyle set r_life_id = NULL," . $q_string;
            $message = "Lifestyle added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update r_lifestyle set " . $q_string . " where r_life_id = " . $formVars['r_life_id'];
            $message = "Lifestyle updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_life_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -2) {
        $formVars['copyfrom'] = clean($_GET['r_life_copyfrom'], 10);

        if ($formVars['copyfrom'] > 0) {
          $q_string  = "select r_life_number,r_life_desc,r_life_months ";
          $q_string .= "from r_lifestyle ";
          $q_string .= "where r_life_character = " . $formVars['copyfrom'];
          $q_r_lifestyle = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          while ($a_r_lifestyle = mysql_fetch_array($q_r_lifestyle)) {

            $q_string =
              "r_life_character     =   " . $formVars['r_life_character']      . "," .
              "r_life_number        =   " . $a_r_lifestyle['r_life_number']    . "," .
              "r_life_desc          = \"" . $a_r_lifestyle['r_life_desc']      . "\"";
              "r_life_months        =   " . $a_r_lifestyle['r_life_months'];
  
            $query = "insert into r_lifestyle set r_life_id = NULL, " . $q_string;
            mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
          }
        }
      }

      $q_string  = "select meta_name ";
      $q_string .= "from metatypes ";
      $q_string .= "left join runners on runners.runr_metatype = metatypes.meta_id ";
      $q_string .= "where runr_id = " . $formVars['r_life_character'] . " ";
      $q_metatypes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_metatypes = mysql_fetch_array($q_metatypes);
# if troll, * 2;
# if dwarf, * 1.2;
      $multiplier = 1;
      if ($a_metatypes['meta_name'] == 'Dwarf') {
        $multiplier = 1.2;
      }
      if ($a_metatypes['meta_name'] == 'Troll') {
        $multiplier = 2;
      }

      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_life_refresh\" value=\"Refresh Lifestyle Listing\" onClick=\"javascript:attach_lifestyle('lifestyle.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_life_update\"  value=\"Update Lifestyle\"          onClick=\"javascript:attach_lifestyle('lifestyle.mysql.php', 1);hideDiv('lifestyle-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_life_id\"      value=\"0\">\n";
        $output .= "<input type=\"button\" name=\"r_life_addbtn\"  value=\"Add Lifestyle\"             onClick=\"javascript:attach_lifestyle('lifestyle.mysql.php', 0);\">\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"copyitem\"  value=\"Copy Lifestyle Table From:\" onClick=\"javascript:attach_lifestyle('lifestyle.mysql.php', -2);\">\n";
        $output .= "<select name=\"r_life_copyfrom\">\n";
        $output .= "<option value=\"0\">None</option>\n";

        $q_string  = "select runr_id,runr_aliases ";
        $q_string .= "from runners ";
        $q_string .= "order by runr_aliases ";
        $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_runners = mysql_fetch_array($q_runners)) {
          $q_string  = "select r_life_id ";
          $q_string .= "from r_lifestyle ";
          $q_string .= "where r_life_character = " . $a_runners['runr_id'] . " ";
          $q_r_lifestyle = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $r_life_total = mysql_num_rows($q_r_lifestyle);

          if ($r_life_total > 0) {
            $output .= "<option value=\"" . $a_runners['runr_id'] . "\">" . $a_runners['runr_aliases'] . " (" . $r_life_total . ")</option>\n";
          }
        }

        $output .= "</select></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Lifestyle Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Lifestyle: <select name=\"r_life_number\">\n";

        $q_string  = "select life_id,life_style ";
        $q_string .= "from lifestyle ";
        $q_string .= "order by life_style ";
        $q_lifestyle = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_lifestyle = mysql_fetch_array($q_lifestyle)) {
          $output .= "<option value=\"" . $a_lifestyle['life_id'] . "\">" . $a_lifestyle['life_style'] . "</option>\n";
        }

        $output .= "</select></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Description: <input type=\"text\" name=\"r_life_desc\" size=\"40\">\n";
        $output .= "  <td class=\"ui-widget-content\">Months Paid Up: <input type=\"text\" name=\"r_life_months\" size=\"10\">\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Comforts <input type=\"text\" name=\"r_life_comforts\" size=\"3\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Necessities <input type=\"text\" name=\"r_life_necessities\" size=\"3\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Security <input type=\"text\" name=\"r_life_security\" size=\"3\"></td>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Neighborhood <input type=\"text\" name=\"r_life_neighborhood\" size=\"3\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Entertainment <input type=\"text\" name=\"r_life_entertainment\" size=\"3\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Space <input type=\"text\" name=\"r_life_space\" size=\"3\"></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('lifestyle_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Language Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('lifestyle-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"lifestyle-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Lifestyle</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Months Paid</th>\n";
      $output .=   "<th class=\"ui-state-default\">Comforts</th>\n";
      $output .=   "<th class=\"ui-state-default\">Necessities</th>\n";
      $output .=   "<th class=\"ui-state-default\">Security</th>\n";
      $output .=   "<th class=\"ui-state-default\">Neighborhood</th>\n";
      $output .=   "<th class=\"ui-state-default\">Entertainment</th>\n";
      $output .=   "<th class=\"ui-state-default\">Space</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost per Month</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $costtotal = 0;
      $q_string  = "select r_life_id,r_life_desc,r_life_months,life_id,life_style,r_life_comforts,r_life_necessities,r_life_security,r_life_neighborhood,r_life_entertainment,r_life_space,ver_book,life_page ";
      $q_string .= "from r_lifestyle ";
      $q_string .= "left join lifestyle on lifestyle.life_id = r_lifestyle.r_life_number ";
      $q_string .= "left join versions on versions.ver_id = lifestyle.life_book ";
      $q_string .= "where r_life_character = " . $formVars['r_life_character'] . " ";
      $q_string .= "order by life_style ";
      $q_r_lifestyle = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_lifestyle) > 0) {
        while ($a_r_lifestyle = mysql_fetch_array($q_r_lifestyle)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('lifestyle.fill.php?id=" . $a_r_lifestyle['r_life_id'] . "');showDiv('lifestyle-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_lifestyle('lifestyle.del.php?id="  . $a_r_lifestyle['r_life_id'] . "');\">";
          $linkend   = "</a>";

          $costtotal += ($a_r_lifestyle['r_life_months'] * $a_r_lifestyle['life_cost'] * $multiplier);

          $life_cost = return_Cost(($a_r_lifestyle['life_cost'] * $multiplier));

          $life_book = return_Book($a_r_lifestyle['ver_book'], $a_r_lifestyle['life_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_life_number']) && $formVars['r_life_number'] == $a_r_lifestyle['life_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_lifestyle['life_style']         . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_lifestyle['r_life_desc']                   . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_lifestyle['r_life_months']                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_lifestyle['r_life_comforts']               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_lifestyle['r_life_necessities']            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_lifestyle['r_life_security']               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_lifestyle['r_life_neighborhood']           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_lifestyle['r_life_entertainment']          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_lifestyle['r_life_space']                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $life_cost                                      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $life_book                                      . "</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";

      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"12\">No Lifestyle selected.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_lifestyle);

      print "document.getElementById('lifestyle_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.r_life_update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
