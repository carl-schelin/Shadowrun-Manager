<?php
# Script: tags.mysql.php
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
    $package = "tags.mysql.php";
    $formVars['update']            = clean($_GET['update'],           10);
    $formVars['tag_character']     = clean($_GET['tag_character'],    10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['tag_character'] == '') {
      $formVars['tag_character'] = 0;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],            10);
        $formVars['tag_name']       = str_replace(' ', '_', clean($_GET['tag_name'], 20));
        $formVars['tag_view']       = clean($_GET['tag_view'],      10);
        $formVars['tag_owner']      = clean($_SESSION['uid'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if (strlen($formVars['tag_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "tag_character =   " . $formVars['tag_character'] . "," .
            "tag_name      = \"" . $formVars['tag_name']      . "\"," .
            "tag_view      =   " . $formVars['tag_view']      . "," .
            "tag_owner     =   " . $formVars['tag_owner'];

          if ($formVars['update'] == 0) {
            $query = "insert into tags set tag_id = NULL," . $q_string;
            $message = "Tag added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update tags set " . $q_string . " where tag_id = " . $formVars['id'];
            $message = "Tag updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['tag_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -3) {
        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"tag_refresh\" value=\"Refresh Tags Listing\" onClick=\"javascript:attach_tags('tags.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"tag_update\"  value=\"Update Tag\"           onClick=\"javascript:attach_tags('tags.mysql.php', 1);hideDiv('tags-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"tag_id\"      value=\"0\">\n";
        $output .= "<input type=\"button\" name=\"tag_addbtn\"  value=\"Add New Tag\"          onClick=\"javascript:attach_tags('tags.mysql.php', 0);\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"2\">Tag Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Tag Name <input type=\"text\" name=\"tag_name\" size=\"20\"> <input type=\"hidden\" name=\"tag_character\" value=\"" . $formVars['tag_character'] . "\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Visibility: <label><input type=\"radio\" checked=\"true\" name=\"tag_view\" value=\"0\"> Personal</label> <label><input type=\"radio\" name=\"tag_view\" value=\"1\"> Public</label></td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<t4>Personal Tag Cloud</t4>\n";

        $output .= "<ul id=\"cloud\">\n";

        $q_string  = "select tag_id,tag_name ";
        $q_string .= "from tags ";
        $q_string .= "where tag_view = 0 and tag_owner = " . $_SESSION['uid'] . " ";
        $q_string .= "group by tag_name ";
        $q_tags = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_tags = mysqli_fetch_array($q_tags)) {
          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('tags.fill.php?id="  . $a_tags['tag_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "  <li>" . $linkstart . $a_tags['tag_name'] . $linkend . "</li>\n";
        }

        $output .= "</ul>\n";


        $output .= "<t4>Public Tag Cloud</t4>\n";

        $output .= "<ul id=\"cloud\">\n";

        $q_string  = "select tag_id,tag_name ";
        $q_string .= "from tags ";
        $q_string .= "where tag_view = 1 ";
        $q_string .= "group by tag_name ";
        $q_tags = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        while ($a_tags = mysqli_fetch_array($q_tags)) {
          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('tags.fill.php?id="  . $a_tags['tag_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "  <li>" . $linkstart . $a_tags['tag_name'] . $linkend . "</li>\n";
        }

        $output .= "</ul>\n";

        print "document.getElementById('tags_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" colspan=\"5\">Tag Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('tags-listing-help');\">Help</a></th>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"tags-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Tag Listing</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li><strong>Delete (x)</strong> - Clicking the <strong>x</strong> will delete this tag from this server.</li>\n";
      $output .= "    <li><strong>Editing</strong> - Click on a tag to toggle the form for editing.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Notes</strong>\n";
      $output .= "  <ul>\n";
      $output .= "    <li>Click the <strong>Tag Management</strong> title bar to toggle the <strong>Tag Form</strong>.</li>\n";
      $output .= "  </ul></li>\n";
      $output .= "</ul>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">Tag</th>\n";
      $output .=   "<th class=\"ui-state-default\">Visibility</th>\n";
      $output .=   "<th class=\"ui-state-default\">Owner</th>\n";
      $output .=   "<th class=\"ui-state-default\">Runner</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select tag_id,tag_name,tag_view,usr_first,usr_last,grp_name,runr_name ";
      $q_string .= "from tags ";
      $q_string .= "left join runners on runners.runr_id = tags.tag_character ";
      $q_string .= "left join groups on groups.grp_id = tags.tag_group ";
      $q_string .= "left join users on users.usr_id = tags.tag_owner ";
      $q_string .= "where tag_character = " . $formVars['tag_character'] . " ";
      $q_string .= "order by tag_view,tag_name";
      $q_tags = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_tags) > 0) {
        while ($a_tags = mysqli_fetch_array($q_tags)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('tags.fill.php?id="  . $a_tags['tag_id'] . "');showDiv('tags-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_tags('tags.del.php?id=" . $a_tags['tag_id'] . "');\">";
          $linkend   = "</a>";

          $tagview = "Personal";
          if ($a_tags['tag_view'] == 1) {
            $tagview = "Public";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content delete\" width=\"60\">" . $linkdel                                                                 . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_tags['tag_name']                              . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $tagview                                         . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_tags['usr_first'] . " " . $a_tags['usr_last'] . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_tags['runr_name']                             . $linkend . "</td>\n";
          $output .= "</tr>\n";

        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No Tags defined.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysql_free_result($q_tags);

      print "document.getElementById('tags_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.edit.tag_update.disabled = true;\n";
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
