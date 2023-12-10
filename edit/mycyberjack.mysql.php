<?php
# Script: mycyberjack.mysql.php
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
    $package = "mycyberjack.mysql.php";
    $formVars['update']              = clean($_GET['update'],              10);
    $formVars['r_jack_character']    = clean($_GET['r_jack_character'],    10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      if ($formVars['update'] == 1) {
        $formVars['jack_id'] = clean($_GET['jack_id'], 10);

        if ($formVars['jack_id'] == '') {
          $formVars['jack_id'] = 0;
        }

        if ($formVars['jack_id'] > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string  = "select jack_data,jack_firewall,jack_access ";
          $q_string .= "from cyberjack ";
          $q_string .= "where jack_id = " . $formVars['jack_id'] . " ";
          $q_cyberjack = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          $a_cyberjack = mysqli_fetch_array($q_cyberjack);

          $jack_access =
            $a_cyberjack['jack_access'] . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767));

          $q_string =
            "r_jack_character     =   " . $formVars['r_jack_character'] . "," .
            "r_jack_number        =   " . $formVars['jack_id']          . "," .
            "r_jack_data          =   " . $a_cyberjack['jack_data']     . "," .
            "r_jack_firewall      =   " . $a_cyberjack['jack_firewall'] . "," .
            "r_jack_access        = \"" . $jack_access                  . "\"";

          if ($formVars['update'] == 1) {
            $query = "insert into r_cyberjack set r_jack_id = NULL," . $q_string;
            $message = "Cyberjack added.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_jack_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');\n";
        }
      }

      if ($formVars['update'] == -3) {

# print forms for each of the cyberjack bits.
# first; the main one which sets the cyberjack being edited. You can't buy anything else until you get a cyberjack

        logaccess($db, $_SESSION['username'], $package, "Creating the form for viewing.");

        $shiftleft  = "<input type=\"button\" value=\"&lt;\" onClick=\"javascript:cyberjack_left('cyberjack.option.php'";
        $shiftright = "<input type=\"button\" value=\"&gt;\" onClick=\"javascript:cyberjack_right('cyberjack.option.php'";

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Active Cyberjack Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" width=\"33%\">Active Cyberjack: <span id=\"r_jack_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\" width=\"33%\">" . $shiftleft . ", 'firewall');\">Data Processing: <span id=\"r_jack_data\">0</span>" . $shiftright . ", 'firewall');\">\n";
        $output .= "  <td class=\"ui-widget-content\" width=\"33%\">" . $shiftleft . ", 'data');\">Firewall: <span id=\"r_jack_firewall\">0</span>"      . $shiftright . ", 'data');\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";
        $output .= "<input type=\"hidden\" name=\"r_jack_id\"      value=\"0\">\n";

        print "document.getElementById('cyberjack_form').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

# show your cyberjack and all associated programs.
      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Cyberjack Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('cyberjack-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"cyberjack-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<p><strong>How It Works</strong></p>\n\n";

      $output .= "<p>If you don't already have a Cyberjack, in the <strong>Cyberjacks</strong> tab, select one by clicking on it.</p>\n\n";

      $output .= "<p>In the <strong>My Cyberjacks</strong> tab, you should see the configuration. As a reminder, you can switch the values between Data Processing and Firewall. ";
      $output .= "This can be done by clicking on your Cyberjack. This brings up a display with the two values and left and right buttons. Click the buttons ";
      $output .= "to switch the values. The Cyberjack is automatically updated when you do this and the change is set until you decide to change it again.</p>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $essencetotal = 0;
      $costtotal = 0;
      $q_string  = "select r_jack_id,jack_name,r_jack_number,r_jack_data,r_jack_firewall,";
      $q_string .= "jack_rating,jack_essence,jack_avail,jack_perm,r_jack_access,jack_cost,ver_book,jack_page ";
      $q_string .= "from r_cyberjack ";
      $q_string .= "left join cyberjack on cyberjack.jack_id = r_cyberjack.r_jack_number ";
      $q_string .= "left join versions on versions.ver_id = cyberjack.jack_book ";
      $q_string .= "where r_jack_character = " . $formVars['r_jack_character'] . " ";
      $q_string .= "order by jack_name,ver_version ";
      $q_r_cyberjack = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_cyberjack) > 0) {
        while ($a_r_cyberjack = mysqli_fetch_array($q_r_cyberjack)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:attach_jackacc(" . $a_r_cyberjack['r_jack_id'] . ");showDiv('cyberjack-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_cyberjack('cyberjack.del.php?id="  . $a_r_cyberjack['r_jack_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
          $output .= "<tr>\n";
          $output .=   "<th class=\"ui-state-default\" colspan=\"12\">Cyberjack ID: " . $a_r_cyberjack['r_jack_access'] . "</th>\n";
          $output .= "</tr>\n";
          $output .= "<tr>\n";
          $output .=   "<th class=\"ui-state-default\">Del</th>\n";
          $output .=   "<th class=\"ui-state-default\">cyberjack</th>\n";
          $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
          $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
          $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
          $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
          $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
          $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
          $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
          $output .= "</tr>\n";

          $jack_rating = return_Rating($a_r_cyberjack['jack_rating']);

          $jack_essence = return_Essence($a_r_cyberjack['jack_essence']);
          $essencetotal += $jack_essence;

          $costtotal += $a_r_cyberjack['jack_cost'];

          $jack_avail = return_Avail($a_r_cyberjack['jack_avail'], $a_r_cyberjack['jack_perm']);

          $jack_cost = return_Cost($a_r_cyberjack['jack_cost']);

          $jack_book = return_Book($a_r_cyberjack['ver_book'], $a_r_cyberjack['jack_page']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel                                                                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_r_cyberjack['jack_name'] . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $jack_rating                                                     . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberjack['r_jack_data']                                    . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberjack['r_jack_firewall']                                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $jack_essence                                                    . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $jack_avail                                                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $jack_cost                                                       . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $jack_book                                                       . "</td>\n";
          $output .= "</tr>\n";

          $output .= "</table>\n";

        }

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Total Essence: " . return_Essence($essencetotal) . ", Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

      } else {
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" colspan=\"12\">Cyberjack ID:</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Del</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cyberjack</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
        $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
        $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"12\">No Cyberjacks added.</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";
      }

      mysqli_free_result($q_r_cyberjack);

      print "document.getElementById('my_cyberjack_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
