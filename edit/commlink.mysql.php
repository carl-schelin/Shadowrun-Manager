<?php
# Script: commlink.mysql.php
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
    $package = "commlink.mysql.php";
    if (isset($_GET['update'])) {
      $formVars['update'] = clean($_GET['update'], 10);
    } else {
      $formVars['update'] = -1;
    }
    if (isset($_GET['r_link_character'])) {
      $formVars['r_link_character'] = clean($_GET['r_link_character'], 10);
    } else {
      $formVars['r_link_character'] = 0;
    }
    if (isset($_GET['r_link_id'])) {
      $formVars['r_link_id'] = clean($_GET['r_link_id'], 10);
    } else {
      $formVars['r_link_id'] = 0;
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      if ($formVars['update'] == 1) {
        $formVars['r_link_number'] = clean($_GET['r_link_number'], 10);

        if ($formVars['r_link_number'] == '') {
          $formVars['r_link_number'] = 0;
        }

        if ($formVars['r_link_number'] > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

# get the company id
          $q_string  = "select link_data,link_firewall,link_access ";
          $q_string .= "from commlink ";
          $q_string .= "where link_id = " . $formVars['r_link_number'] . " ";
          $q_commlink = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          $a_commlink = mysqli_fetch_array($q_commlink);

          $link_access =
            $a_commlink['link_access'] . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767));

          $q_string =
            "r_link_character   =   " . $formVars['r_link_character']   . "," .
            "r_link_number      =   " . $formVars['r_link_number']      . "," .
            "r_link_data        =   " . $a_commlink['link_data']        . "," .
            "r_link_firewall    =   " . $a_commlink['link_firewall']    . "," .
            "r_link_access      = \"" . $link_access                    . "\"";

          if ($formVars['update'] == 1) {
            $query = "insert into r_commlink set r_link_id = NULL," . $q_string;
            $message = "Commlink added.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_link_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');\n";
        }
      }

      if ($formVars['update'] == -3) {

        logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Commlinks</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('commlinks-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"commlinks-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<p><strong>Notes</strong></p>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Commlink</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
        $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
        $output .=   "<th class=\"ui-state-default\">Programs</th>\n";
        $output .=   "<th class=\"ui-state-default\">Company ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

# by rating because they're all commlinks and that orders by the power.
        $q_string  = "select link_id,link_brand,link_model,link_rating,link_data,link_firewall,";
        $q_string .= "link_program,link_access,link_avail,link_perm,link_cost,ver_book,link_page ";
        $q_string .= "from commlink ";
        $q_string .= "left join versions on versions.ver_id = commlink.link_book ";
        $q_string .= "where ver_active = 1 ";
        $q_string .= "order by link_rating,link_cost,ver_version ";
        $q_commlink = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        if (mysqli_num_rows($q_commlink) > 0) {
          while ($a_commlink = mysqli_fetch_array($q_commlink)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:select_commlink('commlink.mysql.php?update=1&r_link_character=" . $formVars['r_link_character'] . "&r_link_number=" . $a_commlink['link_id'] . "');\">";
            $linkend = "</a>";

            $link_rating = return_Rating($a_commlink['link_rating']);

            $link_avail = return_Avail($a_commlink['link_avail'], $a_commlink['link_perm']);

            $link_cost = return_Cost($a_commlink['link_cost']);

            $link_book = return_Book($a_commlink['ver_book'], $a_commlink['link_page']);

            $class = return_Class($a_commlink['link_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_commlink['link_brand'] . " " . $a_commlink['link_model']  . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $link_rating                          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_commlink['link_data']              . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_commlink['link_firewall']          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_commlink['link_program']           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_commlink['link_access']            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $link_avail                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $link_cost                            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $link_book                            . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('commlink_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      }

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
