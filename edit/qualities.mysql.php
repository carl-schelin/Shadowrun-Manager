<?php
# Script: qualities.mysql.php
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
    $package = "qualities.mysql.php";
    $formVars['update']              = clean($_GET['update'],               10);
    $formVars['r_qual_character']    = clean($_GET['r_qual_character'],    10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_qual_id']          = clean($_GET['id'],                  10);
        $formVars['r_qual_number']      = clean($_GET['r_qual_number'],       10);
        $formVars['r_qual_details']     = clean($_GET['r_qual_details'],     255);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if ($formVars['r_qual_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_qual_character   =   " . $formVars['r_qual_character']   . "," .
            "r_qual_number      =   " . $formVars['r_qual_number']      . "," .
            "r_qual_details     = \"" . $formVars['r_qual_details']     . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_qualities set r_qual_id = NULL," . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update r_qualities set " . $q_string . " where r_qual_id = " . $formVars['r_qual_id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_qual_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -2) {
        $formVars['copyfrom'] = clean($_GET['r_qual_copyfrom'], 10);

        if ($formVars['copyfrom'] > 0) {
          $q_string  = "select r_qual_number,r_qual_details ";
          $q_string .= "from r_qualities ";
          $q_string .= "where r_qual_character = " . $formVars['copyfrom'];
          $q_r_qualities = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          while ($a_r_qualities = mysql_fetch_array($q_r_qualities)) {

            $q_string =
              "r_qual_character     =   " . $formVars['r_qual_character']      . "," .
              "r_qual_number        =   " . $a_r_qualities['r_qual_number']    . "," .
              "r_qual_details       =   " . $a_r_qualities['r_qual_details']   . "\"";
  
            $query = "insert into r_qualities set r_qual_id = NULL, " . $q_string;
            mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
          }
        }
      }


      if ($formVars['update'] == -3) {

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_qual_refresh\" value=\"Refresh Quality Listing\" onClick=\"javascript:attach_qualities('qualities.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_qual_update\"  value=\"Update Quality\"          onClick=\"javascript:attach_qualities('qualities.mysql.php', 1);hideDiv('qualities-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_qual_id\"      value=\"0\">\n";
        $output .= "<input type=\"button\" name=\"r_qual_addbtn\"  value=\"Add Quality\"             onClick=\"javascript:attach_qualities('qualities.mysql.php', 0);\">\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"copyitem\"  value=\"Copy Quality Table From:\" onClick=\"javascript:attach_qualities('qualities.mysql.php', -2);\">\n";
        $output .= "<select name=\"r_qual_copyfrom\">\n";
        $output .= "<option value=\"0\">None</option>\n";

        $q_string  = "select runr_id,runr_aliases ";
        $q_string .= "from runners ";
        $q_string .= "order by runr_aliases ";
        $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_runners = mysql_fetch_array($q_runners)) {
          $q_string  = "select r_qual_id ";
          $q_string .= "from r_qualities ";
          $q_string .= "where r_qual_character = " . $a_runners['runr_id'] . " ";
          $q_r_qualities = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $r_qual_total = mysql_num_rows($q_r_qualities);

          if ($r_qual_total > 0) {
            $output .= "<option value=\"" . $a_runners['runr_id'] . "\">" . $a_runners['runr_aliases'] . " (" . $r_qual_total . ")</option>\n";
          }
        }

        $output .= "</select></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Quality Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Quality: <select name=\"r_qual_number\">\n";
        $output .= "<option value=\"0\">None</option>\n";

        $q_string  = "select qual_id,qual_name ";
        $q_string .= "from qualities ";
        $q_string .= "left join versions on versions.ver_id = qualities.qual_book ";
        $q_string .= "where ver_active = 1 ";
        $q_string .= "order by qual_name ";
        $q_qualities = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_qualities = mysql_fetch_array($q_qualities)) {
          $output .= "<option value=\"" . $a_qualities['qual_id'] . "\">" . $a_qualities['qual_name'] . "</option>\n";
        }

        $output .= "</select></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Details: <input type=\"text\" name=\"r_qual_details\" size=\"30\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('qualities_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Quality Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('qualities-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"qualities-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Spell Listing</strong>\n";
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
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Value</th>\n";
      $output .=   "<th class=\"ui-state-default\">Description</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select r_qual_id,qual_id,qual_name,qual_value,qual_desc,ver_book,qual_page,r_qual_details ";
      $q_string .= "from r_qualities ";
      $q_string .= "left join qualities on qualities.qual_id = r_qualities.r_qual_number ";
      $q_string .= "left join versions on versions.ver_id = qualities.qual_book ";
      $q_string .= "where r_qual_character = " . $formVars['r_qual_character'] . " ";
      $q_string .= "order by qual_value desc,qual_name ";
      $q_r_qualities = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_qualities) > 0) {
        while ($a_r_qualities = mysql_fetch_array($q_r_qualities)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('qualities.fill.php?id=" . $a_r_qualities['r_qual_id'] . "');showDiv('qualities-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_qualities('qualities.del.php?id="  . $a_r_qualities['r_qual_id'] . "');\">";
          $linkend   = "</a>";

          $details = '';
          if (strlen($a_r_qualities['r_qual_details']) > 0) {
            $details = " (" . $a_r_qualities['r_qual_details'] . ")";
          }

          $qual_book = return_Book($a_r_qualities['ver_book'], $a_r_qualities['qual_page']);

          if ($a_r_qualities['qual_value'] < 0) {
            $quality = "Negative";
          } else {
            $quality = "Positive";
          }

          $class = "ui-widget-content";
          if (isset($formVars['r_qual_number']) && $formVars['r_qual_number'] == $a_r_qualities['qual_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                                          . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_r_qualities['qual_name'] . $details . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_qualities['qual_value']                      . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_qualities['qual_desc']                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $qual_book                                        . "</td>\n";
          $output .= "</tr>\n";

        }
      } else {
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">No Qualities added.</td>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_r_qualities);

      print "document.getElementById('qualities_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.r_qual_details.value = '';\n";

      print "document.edit.r_qual_update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
