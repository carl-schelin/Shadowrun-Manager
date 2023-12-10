<?php
# Script: cyberdeck.mysql.php
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
    $package = "cyberdeck.mysql.php";
    $formVars['update']               = clean($_GET['update'],                10);
    $formVars['r_deck_character']     = clean($_GET['r_deck_character'],      10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_deck_character'] == '') {
      $formVars['r_deck_character'] = -1;
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      if ($formVars['update'] == 0) {
        $formVars['r_deck_number']  = clean($_GET['r_deck_number'],  10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_deck_number'] == '') {
          $formVars['r_deck_number'] = 1;
        }

        if ($formVars['r_deck_number'] > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

# get the company id
          $q_string  = "select deck_access ";
          $q_string .= "from cyberdeck ";
          $q_string .= "where deck_id = " . $formVars['r_deck_number'] . " ";
          $q_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          $a_cyberdeck = mysqli_fetch_array($q_cyberdeck);

          $deck_access =
            $a_cyberdeck['deck_access'] . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767));

          $q_string =
            "r_deck_character =   " . $formVars['r_deck_character'] . "," .
            "r_deck_number    =   " . $formVars['r_deck_number']    . "," . 
            "r_deck_access    = \"" . $formVars['r_deck_access']    . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_cyberdeck set r_deck_id = NULL," . $q_string;
            $message = "Cyberdeck added.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_deck_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == 1) {
        $formVars['r_deck_number']  = clean($_GET['r_deck_number'],  10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_deck_number'] == '') {
          $formVars['r_deck_number'] = 1;
        }

        if ($formVars['r_deck_number'] > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

# need to make sure the user doesn't fudge the numbers, adding more of one and less of another.
# make sure 

# get the company id
          $q_string  = "select deck_access ";
          $q_string .= "from cyberdeck ";
          $q_string .= "where deck_id = " . $formVars['r_deck_number'] . " ";
          $q_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          $a_cyberdeck = mysqli_fetch_array($q_cyberdeck);

          $deck_access =
            $a_cyberdeck['deck_access'] . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767));

          $q_string =
            "r_deck_character =   " . $formVars['r_deck_character'] . "," .
            "r_deck_number    =   " . $formVars['r_deck_number']    . "," . 
            "r_deck_access    = \"" . $formVars['r_deck_access']    . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_cyberdeck set r_deck_id = NULL," . $q_string;
            $message = "Cyberdeck added.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_deck_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

# list all the available cyberdecks
      if ($formVars['update'] == -3) {
        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Cyberdeck</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('cyberdeck-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"cyberdeck-listing-help\" style=\"display: none\">\n";

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
        $output .=   "<th class=\"ui-state-default\">Brand</th>\n";
        $output .=   "<th class=\"ui-state-default\">Model</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
        $output .=   "<th class=\"ui-state-default\">Sleaze</th>\n";
        $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
        $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
        $output .=   "<th class=\"ui-state-default\">Programs</th>\n";
        $output .=   "<th class=\"ui-state-default\">Company ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        $q_string  = "select deck_id,deck_brand,deck_model,deck_rating,deck_attack,deck_sleaze,";
        $q_string .= "deck_data,deck_firewall,deck_programs,deck_access,deck_avail,deck_perm,deck_cost,ver_book,deck_page ";
        $q_string .= "from cyberdeck ";
        $q_string .= "left join versions on versions.ver_id = cyberdeck.deck_book ";
        $q_string .= "where ver_active = 1 ";
        $q_string .= "order by deck_rating,deck_cost,ver_version ";
        $q_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        if (mysqli_num_rows($q_cyberdeck) > 0) {
          while ($a_cyberdeck = mysqli_fetch_array($q_cyberdeck)) {

# this adds the deck_id to the r_deck_character
            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('mycyberdeck.mysql.php?update=1&r_deck_character=" . $formVars['r_deck_character'] . "&deck_id=" . $a_cyberdeck['deck_id'] . "');\">";
            $linkend = "</a>";

            $deck_rating = return_Rating($a_cyberdeck['deck_rating']);

            $deck_avail = return_Avail($a_cyberdeck['deck_avail'], $a_cyberdeck['deck_perm']);

            $deck_cost = return_Cost($a_cyberdeck['deck_cost']);

            $deck_book = return_Book($a_cyberdeck['ver_book'], $a_cyberdeck['deck_page']);

            $class = return_Class($a_cyberdeck['deck_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_cyberdeck['deck_brand'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_cyberdeck['deck_model'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $deck_rating                          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberdeck['deck_attack']           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberdeck['deck_sleaze']           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberdeck['deck_data']             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberdeck['deck_firewall']         . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberdeck['deck_programs']         . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $a_cyberdeck['deck_access']           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $deck_avail                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $deck_cost                            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $deck_book                            . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('cyberdeck_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      }
    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
