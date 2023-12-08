<?php
# Script: cyberjack.mysql.php
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
    $package = "cyberjack.mysql.php";
    $formVars['update']               = clean($_GET['update'],                10);
    $formVars['r_jack_character']     = clean($_GET['r_jack_character'],      10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_jack_character'] == '') {
      $formVars['r_jack_character'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0) {
        $formVars['r_jack_number']  = clean($_GET['r_jack_number'],  10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_jack_number'] == '') {
          $formVars['r_jack_number'] = 1;
        }

        if ($formVars['r_jack_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

# get the company id
          $q_string  = "select jack_access ";
          $q_string .= "from cyberjack ";
          $q_string .= "where jack_id = " . $formVars['r_jack_number'] . " ";
          $q_cyberjack = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $a_cyberjack = mysqli_fetch_array($q_cyberjack);

          $jack_access =
            $a_cyberjack['jack_access'] . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767));

          $q_string =
            "r_jack_character =   " . $formVars['r_jack_character'] . "," .
            "r_jack_number    =   " . $formVars['r_jack_number']    . "," . 
            "r_jack_access    = \"" . $formVars['r_jack_access']    . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_cyberjack set r_jack_id = NULL," . $q_string;
            $message = "Cyberjack added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_jack_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == 1) {
        $formVars['r_jack_number']  = clean($_GET['r_jack_number'],  10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_jack_number'] == '') {
          $formVars['r_jack_number'] = 1;
        }

        if ($formVars['r_jack_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

# need to make sure the user doesn't fudge the numbers, adding more of one and less of another.
# make sure 

# get the company id
          $q_string  = "select jack_access ";
          $q_string .= "from cyberjack ";
          $q_string .= "where jack_id = " . $formVars['r_jack_number'] . " ";
          $q_cyberjack = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $a_cyberjack = mysqli_fetch_array($q_cyberjack);

          $jack_access =
            $a_cyberjack['jack_access'] . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767));

          $q_string =
            "r_jack_character =   " . $formVars['r_jack_character'] . "," .
            "r_jack_number    =   " . $formVars['r_jack_number']    . "," . 
            "r_jack_access    = \"" . $formVars['r_jack_access']    . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_cyberjack set r_jack_id = NULL," . $q_string;
            $message = "Cyberjack added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_jack_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

# list all the available cyberjacks
      if ($formVars['update'] == -3) {
        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Cyberjack</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('cyberjack-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"cyberjack-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Weapon Listing</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li><strong>Remove</strong> - Click here to delete this Weapon from the Mooks Database.</li>\n";
        $output .= "    <li><strong>Editing</strong> - Click on a Weapon to toggle the form and edit the Weapon.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Notes</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li>Click the <strong>Firearm Management</strong> title bar to toggle the <strong>Firearm Form</strong>.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
        $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
        $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
        $output .=   "<th class=\"ui-state-default\">Company ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

# if the person has a 'jack', do not display the table.
        $q_string  = "select r_jack_id ";
        $q_string .= "from r_cyberjack ";
        $q_string .= "where r_jack_character = " . $formVars['r_jack_character'] . " ";
        $q_r_cyberjack = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_r_cyberjack) > 0) {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">Cyberjack already assigned.</td>\n";
          $output .= "</tr>\n";
        } else {

          $q_string  = "select jack_id,jack_name,jack_rating,";
          $q_string .= "jack_data,jack_firewall,jack_essence,jack_access,jack_avail,jack_perm,jack_cost,ver_book,jack_page ";
          $q_string .= "from cyberjack ";
          $q_string .= "left join versions on versions.ver_id = cyberjack.jack_book ";
          $q_string .= "where ver_active = 1 ";
          $q_string .= "order by jack_rating,jack_cost,ver_version ";
          $q_cyberjack = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_cyberjack) > 0) {
            while ($a_cyberjack = mysqli_fetch_array($q_cyberjack)) {

# this adds the jack_id to the r_jack_character and refreshes the cyberjack table
              $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('mycyberjack.mysql.php?update=1&r_jack_character=" . $formVars['r_jack_character'] . "&jack_id=" . $a_cyberjack['jack_id'] . "');javascript:show_file('cyberjack.mysql.php?update=-3&r_jack_character=" . $formVars['r_jack_character'] . "');\">";
              $linkend = "</a>";

              $jack_rating = return_Rating($a_cyberjack['jack_rating']);

              $jack_avail = return_Avail($a_cyberjack['jack_avail'], $a_cyberjack['jack_perm']);

              $jack_cost = return_Cost($a_cyberjack['jack_cost']);

              $jack_book = return_Book($a_cyberjack['ver_book'], $a_cyberjack['jack_page']);

              $class = return_Class($a_cyberjack['jack_perm']);

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_cyberjack['jack_name'] . $linkend . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $jack_rating                         . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberjack['jack_data']            . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberjack['jack_firewall']        . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberjack['jack_essence']         . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberjack['jack_access']          . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $jack_avail                          . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $jack_cost                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $jack_book                           . "</td>\n";
              $output .= "</tr>\n";
            }
          } else {
            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No records found.</td>\n";
            $output .= "</tr>\n";
          }
        }

        $output .= "</table>\n";

        print "document.getElementById('cyberjack_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
