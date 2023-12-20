<?php
# Script: foci.mysql.php
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
    $package = "foci.mysql.php";
    $formVars['update']              = clean($_GET['update'],           10);
    $formVars['r_foci_character']    = clean($_GET['r_foci_character'], 10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['r_foci_id']          = clean($_GET['r_foci_id'],           10);
        $formVars['r_foci_number']      = clean($_GET['r_foci_number'],       10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if ($formVars['r_foci_number'] > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_foci_character   =   " . $formVars['r_foci_character']   . "," .
            "r_foci_number      =   " . $formVars['r_foci_number'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_foci set r_foci_id = NULL," . $q_string;
            $message = "Foci added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update r_foci set " . $q_string . " where r_foci_id = " . $formVars['r_foci_id'];
            $message = "Foci updated.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_foci_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -3) {

        logaccess($db, $_SESSION['username'], $package, "Creating the form for viewing.");

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Foci Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('foci-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"foci-listing-help\" style=\"display: none\">\n";

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

# we need to find out how much magic the character has
# then we need to total up all the foci the character already has and only display what they can 
        $runner_magic = 0;
        $q_string  = "select runr_magic ";
        $q_string .= "from runners ";
        $q_string .= "where runr_id = " . $formVars['r_foci_character'] . " ";
        $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        if (mysqli_num_rows($q_runners) > 0) {
          $a_runners = mysqli_fetch_array($q_runners);
          $runner_magic = $a_runners['runr_magic'];
        }
        
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Karma</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        if ($runner_magic > 0) {
          $q_string  = "select foci_id,foci_name,foci_karma,foci_avail,foci_perm,foci_cost,ver_book,foci_page ";
          $q_string .= "from foci ";
          $q_string .= "left join versions on versions.ver_id = foci.foci_book ";
          $q_string .= "where ver_active = 1 ";
          $q_string .= "order by foci_name,foci_karma ";
          $q_foci = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_foci) > 0) {
            while ($a_foci = mysqli_fetch_array($q_foci)) {

              $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('foci.mysql.php?update=0&r_foci_character=" . $formVars['r_foci_character'] . "&r_foci_number=" . $a_foci['foci_id'] . "');\">";
              $linkend   = "</a>";

              $foci_avail = return_Avail($a_foci['foci_avail'], $a_foci['foci_perm']);

              $foci_cost = return_Cost($a_foci['foci_cost']);

              $foci_book = return_Book($a_foci['ver_book'], $a_foci['foci_page']);

              $class = return_Class($a_foci['foci_perm']);

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_foci['foci_name']  . $linkend . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $a_foci['foci_karma'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $foci_avail           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $foci_cost            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $foci_book            . "</td>\n";
              $output .= "</tr>\n";

            }
          } else {
            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">No available Foci.</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"5\">No available Foci. (" . $runner_magic . ")</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "</table>\n";

        mysqli_free_result($q_foci);

        print "document.getElementById('focis_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      }

      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">My Foci</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('foci-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"foci-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Karma</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $costtotal = 0;
      $q_string  = "select r_foci_id,foci_id,foci_name,foci_karma,foci_avail,foci_perm,foci_cost,ver_book,foci_page ";
      $q_string .= "from r_foci ";
      $q_string .= "left join foci on foci.foci_id = r_foci.r_foci_number ";
      $q_string .= "left join versions on versions.ver_id = foci.foci_book ";
      $q_string .= "where r_foci_character = " . $formVars['r_foci_character'] . " ";
      $q_string .= "order by foci_name,foci_karma ";
      $q_r_foci = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_foci) > 0) {
        while ($a_r_foci = mysqli_fetch_array($q_r_foci)) {

          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_foci('foci.del.php?id="  . $a_r_foci['r_foci_id'] . "');\">";

          $foci_avail = return_Avail($a_r_foci['foci_avail'], $a_r_foci['foci_perm']);

          $foci_cost = return_Cost($a_r_foci['foci_cost']);
          $costtotal += $a_r_foci['foci_cost'];

          $foci_book = return_Book($a_r_foci['ver_book'], $a_r_foci['foci_page']);

          $class = return_Class($a_r_foci['foci_perm']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_r_foci['foci_name']  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_r_foci['foci_karma'] . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $foci_avail             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $foci_cost              . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $foci_book              . "</td>\n";
          $output .= "</tr>\n";

        }
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";
      } else {
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">No Foci selected.</td>\n";
      }
      $output .= "</table>\n";

      mysqli_free_result($q_r_foci);

      print "document.getElementById('my_focis_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
