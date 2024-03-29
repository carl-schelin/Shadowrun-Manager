<?php
# Script: comments.mysql.php
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
    $package = "comments.mysql.php";
    $formVars['update']          = clean($_GET['update'], 10);
    $formVars['id']              = clean($_GET['id'],     10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['id'] == '') {
      $formVars['id'] = 0;
    }

    if (check_userlevel($db, $AL_Fixer)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars["bug_id"]          = clean($_GET["bug_id"],          10);
        $formVars["bug_text"]        = clean($_GET["bug_text"],      2000);
        $formVars["bug_timestamp"]   = clean($_GET["bug_timestamp"],   60);
        $formVars["bug_user"]        = clean($_GET["bug_user"],        10);

        if ($formVars['bug_timestamp'] == "YYYY-MM-DD HH:MM:SS" || $formVars['bug_timestamp'] == '' || $formVars['bug_timestamp'] == 'Current Time') {
          $formVars['bug_timestamp'] = date("Y-m-d H:i:s");
        }

        if (strlen($formVars['bug_text']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string =
            "bug_bug_id    =   " . $formVars['id']            . "," . 
            "bug_text      = \"" . $formVars['bug_text']      . "\"," . 
            "bug_timestamp = \"" . $formVars['bug_timestamp'] . "\"," . 
            "bug_user      =   " . $formVars['bug_user'];

          if ($formVars['update'] == 0) {
            $query = "insert into bugs_detail set bug_id = NULL, " . $q_string;
            $message = "Comment added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update bugs_detail set " . $q_string . " where bug_id = " . $formVars['bug_id'];
            $message = "Comment updated.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['bug_id']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $q_string  = "select bug_closed ";
      $q_string .= "from bugs ";
      $q_string .= "where bug_id = " . $formVars['id'];
      $q_bugs = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_bugs = mysqli_fetch_array($q_bugs);


      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\">";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\">Problem Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('problem-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"problem-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Problem Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Delete (x)</strong> - Click here to Delete this detail record.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a detail record to load the data which lets you make changes.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Problem Management</strong> title bar to toggle the <strong>Problem Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";


      $output .= "<table class=\"ui-styled-table\">";
      $output .= "<tr>";
      $output .= "  <th class=\"ui-state-default\">Del</th>";
      $output .= "  <th class=\"ui-state-default\">Date/Time</th>";
      $output .= "  <th class=\"ui-state-default\">User</th>";
      $output .= "  <th class=\"ui-state-default\">Detail</th>";
      $output .= "</tr>";

      $q_string  = "select bug_id,bug_text,bug_timestamp,usr_first,usr_last ";
      $q_string .= "from bugs_detail ";
      $q_string .= "left join users on users.usr_id = bugs_detail.bug_user ";
      $q_string .= "where bug_bug_id = " . $formVars['id'] . " ";
      $q_string .= "order by bug_timestamp desc ";
      $q_bugs_detail = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ($a_bugs_detail = mysqli_fetch_array($q_bugs_detail)) {

        if ($a_bugs['bug_closed'] == '0000-00-00') {
          $linkstart = "<a href=\"#details\" onclick=\"show_file('"     . $Bugroot . "/comments.fill.php?id=" . $a_bugs_detail['bug_id'] . "');showDiv('problem-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_detail('comments.del.php?id="  . $a_bugs_detail['bug_id'] . "');\">";
          $linkend   = "</a>";
        } else {
          $linkstart = '';
          $linkend = '';
          $linkdel = "--";
        }

        $output .= "<tr>";
        $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                                                                               . "</td>";
        $output .= "  <td class=\"ui-widget-content delete\">"        . $linkstart . $a_bugs_detail['bug_timestamp']                                . $linkend . "</td>";
        $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_bugs_detail['usr_first'] . " " . $a_bugs_detail['usr_last'] . $linkend . "</td>";
        $output .= "  <td class=\"ui-widget-content\">"                     . $a_bugs_detail['bug_text']                                                . "</td>";
        $output .= "</tr>";
      }

      mysqli_free_result($q_bugs_detail);

      $output .= "</table>";

      print "document.getElementById('detail_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";

      if ($a_bugs['bug_closed'] == '0000-00-00') {
        print "document.start.bug_text.value = '';\n";
        print "document.start.bug_timestamp.value = 'Current Time';\n";
        print "document.start.bugupdate.disabled = true;\n";
      }

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
