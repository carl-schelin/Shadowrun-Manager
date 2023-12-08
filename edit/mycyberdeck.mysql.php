<?php
# Script: mycyberdeck.mysql.php
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
    $package = "mycyberdeck.mysql.php";
    $formVars['update']              = clean($_GET['update'],              10);
    $formVars['r_deck_character']    = clean($_GET['r_deck_character'],    10);
    $formVars['r_deck_id']           = clean($_GET['r_deck_id'],           10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 1) {
        $formVars['deck_id'] = clean($_GET['deck_id'], 10);

        if ($formVars['deck_id'] == '') {
          $formVars['deck_id'] = 0;
        }

        if ($formVars['deck_id'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string  = "select deck_attack,deck_sleaze,deck_data,deck_firewall,deck_access ";
          $q_string .= "from cyberdeck ";
          $q_string .= "where deck_id = " . $formVars['deck_id'] . " ";
          $q_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          $a_cyberdeck = mysqli_fetch_array($q_cyberdeck);

          $deck_access =
            $a_cyberdeck['deck_access'] . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767));

          $q_string =
            "r_deck_character     =   " . $formVars['r_deck_character'] . "," .
            "r_deck_number        =   " . $formVars['deck_id']          . "," .
            "r_deck_attack        =   " . $a_cyberdeck['deck_attack']   . "," .
            "r_deck_sleaze        =   " . $a_cyberdeck['deck_sleaze']   . "," .
            "r_deck_data          =   " . $a_cyberdeck['deck_data']     . "," .
            "r_deck_firewall      =   " . $a_cyberdeck['deck_firewall'] . "," .
            "r_deck_access        = \"" . $deck_access                  . "\"";

          if ($formVars['update'] == 1) {
            $query = "insert into r_cyberdeck set r_deck_id = NULL," . $q_string;
            $message = "Cyberdeck added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_deck_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        }
      }

# adding a program to an existing cyberdeck
      if ($formVars['update'] == 3) {
        $formVars['pgm_id']            = clean($_GET['pgm_id'],           10);

        if ($formVars['pgm_id'] == '') {
          $formVars['pgm_id'] = 0;
        }

        if ($formVars['r_deck_id'] > 0 && $formVars['pgm_id'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_pgm_character   =   " . $formVars['r_deck_character']   . "," .
            "r_pgm_cyberdeck   =   " . $formVars['r_deck_id']          . "," .
            "r_pgm_number      =   " . $formVars['pgm_id'];

          if ($formVars['update'] == 3) {
            $query = "insert into r_program set r_pgm_id = NULL," . $q_string;
            $message = "Program added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['pgm_id']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must select a cyberdeck before purchasing a program.\\n\\nClick on one of your cyberdecks to activate it.');\n";
        }
      }


# adding an agent to an existing cyberdeck
      if ($formVars['update'] == 4) {
        $formVars['agt_id']            = clean($_GET['agt_id'],           10);

        if ($formVars['agt_id'] == '') {
          $formVars['agt_id'] = 0;
        }

        if ($formVars['r_deck_id'] > 0 && $formVars['agt_id'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_agt_character   =   " . $formVars['r_deck_character']   . "," .
            "r_agt_cyberdeck   =   " . $formVars['r_deck_id']          . "," .
            "r_agt_number      =   " . $formVars['agt_id'];

          if ($formVars['update'] == 4) {
            $query = "insert into r_agents set r_agt_id = NULL," . $q_string;
            $message = "Agent added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['agt_id']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must select a cyberdeck before purchasing a program.\\n\\nClick on one of your cyberdecks to activate it.');\n";
        }
      }


      if ($formVars['update'] == -3) {

# print forms for each of the cyberdeck bits.
# first; the main one which sets the cyberdeck being edited. You can't buy anything else until you get a cyberdeck

        logaccess($_SESSION['username'], $package, "Creating the form for viewing.");

        $shiftleft  = "<input type=\"button\" value=\"&lt;\" onClick=\"javascript:cyberdeck_left('cyberdeck.option.php'";
        $shiftright = "<input type=\"button\" value=\"&gt;\" onClick=\"javascript:cyberdeck_right('cyberdeck.option.php'";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Active Cyberdeck Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" width=\"20%\">Active Cyberdeck: <span id=\"r_deck_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\" width=\"20%\">" . $shiftleft . ", 'firewall');\">Attack: <span id=\"attack_val\">0</span>"      . $shiftright . ", 'sleaze');\">\n";
        $output .= "  <td class=\"ui-widget-content\" width=\"20%\">" . $shiftleft . ", 'attack');\">Sleaze: <span id=\"sleaze_val\">0</span>"        . $shiftright . ", 'data');\">\n";
        $output .= "  <td class=\"ui-widget-content\" width=\"20%\">" . $shiftleft . ", 'sleaze');\">Data Processing: <span id=\"data_val\">0</span>" . $shiftright . ", 'firewall');\">\n";
        $output .= "  <td class=\"ui-widget-content\" width=\"20%\">" . $shiftleft . ", 'data');\">Firewall: <span id=\"firewall_val\">0</span>"      . $shiftright . ", 'attack');\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<input type=\"hidden\" name=\"r_deck_id\"      value=\"0\">\n";

        print "document.getElementById('cyberdeck_form').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

# show your cyberdeck and all associated programs.
      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Cyberdeck Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('cyberdeck-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"cyberdeck-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<p><strong>How It Works</strong></p>\n\n";

      $output .= "<p>If you want to add a Cyberdeck, select one from the <strong>Cyberdecks</strong> tab.</p>\n\n";

      $output .= "<p>In order to add programs, agents, and accessories to your Cyberdeck, you'll need to select the Cyberdeck from ";
      $output .= "the list you've purchased. With it selected, you'll be able to buy what you need to accessorize your Cyberdeck. </p>\n";

      $output .= "<p>For <strong>Common Programs</strong>, click the tab and click to buy one. Same for <strong>Hacking Programs</strong>. ";
      $output .= "While you can buy more than one, you can only use one at a time so it's a waste of funds to buy more than one of each. </p>\n";

      $output .= "<p>For <strong>Agents</strong>, you can only have one per Cyberdeck. Select one and it'll be associated with your ";
      $output .= "Cyberdeck. Finally, <strong>Accessories</strong> for you Cyberdeck are listed. Click on the accessory however many ";
      $output .= "times you want to purchase that item.</p>\n";

      $output .= "<p>In the <strong>My Cyberdecks</strong> tab, you should see the configuration. As a reminder, you can switch the values ";
      $output .= "between the four deck values. You are not able to switch a positive value with a setting that is zero. ";
      $output .= "This can be done by clicking on your Cyberdeck. This brings up a display with the two values and left and right buttons. Click the buttons ";
      $output .= "to switch the values. The Cyberdeck is automatically updated when you do this and the change is set until you decide to change it again.</p>\n";


      $output .= "<p>Comparison table for programs between 4th, 5th, and 6th. Shows the list for each and what might be an appropriate transition.</p>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">4th Edition</td>\n";
      $output .= "  <th class=\"ui-state-default\">5th Edition</td>\n";
      $output .= "  <th class=\"ui-state-default\">6th Edition</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Analyze</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Browse</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Baby Monitor</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Browse</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Configurator</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Browse</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Command</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Edit</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Configurator</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Edit</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Encryption</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Edit</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Encrypt</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Signal Scrub</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Encryption</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Reality Filter</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Toolbox</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Signal Scrubber</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Scan</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Virtual Machine</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Toolbox</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\"></td>\n";
      $output .= "  <td class=\"ui-widget-content\"></td>\n";
      $output .= "  <td class=\"ui-widget-content\">Virtual Machine</td>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">4th Edition</td>\n";
      $output .= "  <th class=\"ui-state-default\">5th Edition</td>\n";
      $output .= "  <th class=\"ui-state-default\">6th Edition</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Armor</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Armor</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Armor</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Attack</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Biofeedback</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Biofeedback</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Biofeedback Filter</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Biofeedback Filter</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Biofeedback Filter</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Black Hammer</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Blackout</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Blackout</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Blackout</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Decryption</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Decryption</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Data Bomb</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Defuse</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Defuse</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Decrypt</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Demolition</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Exploit</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Defuse</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Exploit</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Fork</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">ECCM</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Fork</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Lockdown</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Exploit</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Guard</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Overclock</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Medic</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Hammer</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Stealth</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Sniffer</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Lockdown</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Track</td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Spoof</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Mugger</td>\n";
      $output .= "  <td class=\"ui-widget-content\"></td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Stealth</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Shell</td>\n";
      $output .= "  <td class=\"ui-widget-content\"></td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\">Track</td>\n";
      $output .= "  <td class=\"ui-widget-content\">Sneak</td>\n";
      $output .= "  <td class=\"ui-widget-content\"></td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\"></td>\n";
      $output .= "  <td class=\"ui-widget-content\">Stealth</td>\n";
      $output .= "  <td class=\"ui-widget-content\"></td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\"></td>\n";
      $output .= "  <td class=\"ui-widget-content\">Track</td>\n";
      $output .= "  <td class=\"ui-widget-content\"></td>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <td class=\"ui-widget-content\"></td>\n";
      $output .= "  <td class=\"ui-widget-content\">Wrapper</td>\n";
      $output .= "  <td class=\"ui-widget-content\"></td>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $costtotal = 0;
      $q_string  = "select r_deck_id,deck_brand,deck_model,r_deck_number,r_deck_attack,r_deck_sleaze,r_deck_data,r_deck_firewall,";
      $q_string .= "deck_rating,deck_avail,deck_perm,deck_programs,r_deck_access,deck_cost,ver_book,deck_page ";
      $q_string .= "from r_cyberdeck ";
      $q_string .= "left join cyberdeck on cyberdeck.deck_id = r_cyberdeck.r_deck_number ";
      $q_string .= "left join versions on versions.ver_id = cyberdeck.deck_book ";
      $q_string .= "where r_deck_character = " . $formVars['r_deck_character'] . " ";
      $q_string .= "order by deck_brand,deck_model,ver_version ";
      $q_r_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_cyberdeck) > 0) {
        while ($a_r_cyberdeck = mysqli_fetch_array($q_r_cyberdeck)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:attach_deckacc(" . $a_r_cyberdeck['r_deck_id'] . ");showDiv('cyberdeck-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_cyberdeck('cyberdeck.del.php?id="  . $a_r_cyberdeck['r_deck_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
          $output .= "<tr>\n";
          $output .=   "<th class=\"ui-state-default\" colspan=\"12\">Cyberdeck ID: " . $a_r_cyberdeck['r_deck_access'] . "</th>\n";
          $output .= "</tr>\n";
          $output .= "<tr>\n";
          $output .=   "<th class=\"ui-state-default\">Del</th>\n";
          $output .=   "<th class=\"ui-state-default\">cyberdeck</th>\n";
          $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
          $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
          $output .=   "<th class=\"ui-state-default\">Sleaze</th>\n";
          $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
          $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
          $output .=   "<th class=\"ui-state-default\">Programs</th>\n";
          $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
          $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
          $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
          $output .= "</tr>\n";

          $deck_rating = return_Rating($a_r_cyberdeck['deck_rating']);

          $costtotal += $a_r_cyberdeck['deck_cost'];

          $deck_avail = return_Avail($a_r_cyberdeck['deck_avail'], $a_r_cyberdeck['deck_perm']);

          $deck_cost = return_Cost($a_r_cyberdeck['deck_cost']);

          $deck_book = return_Book($a_r_cyberdeck['ver_book'], $a_r_cyberdeck['deck_page']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel                                                                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_r_cyberdeck['deck_brand'] . " " . $a_r_cyberdeck['deck_model'] . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $deck_rating                                                     . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberdeck['r_deck_attack']                                  . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberdeck['r_deck_sleaze']                                  . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberdeck['r_deck_data']                                    . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberdeck['r_deck_firewall']                                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_cyberdeck['deck_programs']                                  . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $deck_avail                                                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $deck_cost                                                       . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $deck_book                                                       . "</td>\n";
          $output .= "</tr>\n";

          $output .= "</table>\n";

# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
          $q_string  = "select r_acc_id,acc_id,acc_class,class_name,acc_name,acc_rating,acc_essence,acc_capacity,";
          $q_string .= "acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
          $q_string .= "from r_accessory ";
          $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
          $q_string .= "left join class on class.class_id = accessory.acc_class ";
          $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
          $q_string .= "where r_acc_character = " . $formVars['r_deck_character'] . " and r_acc_parentid = " . $a_r_cyberdeck['r_deck_id'] . " ";
          $q_string .= "order by acc_name,acc_rating,ver_version ";
          $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_accessory) > 0) {

            $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" colspan=\"12\">Cyberdeck Accessories</th>\n";
            $output .= "</tr>\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\">Del</th>\n";
            $output .=   "<th class=\"ui-state-default\">Name</th>\n";
            $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
            $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
            $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
            $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
            $output .= "</tr>\n";

            while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_deckacc('deckacc.del.php?id="  . $a_r_accessory['r_acc_id'] . "');\">";
              $linkend   = "</a>";

              $acc_rating = return_Rating($a_r_accessory['acc_rating']);

              $costtotal += $a_r_accessory['acc_cost'];

              $acc_avail = return_Avail($a_r_accessory['acc_avail'], $a_r_accessory['acc_perm']);

              $acc_cost = return_Cost($a_r_accessory['acc_cost']);

              $acc_book = return_Book($a_r_accessory['ver_book'], $a_r_accessory['acc_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_acc_number']) && $formVars['r_acc_number'] == $a_r_accessory['acc_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel      . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $a_r_accessory['acc_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_rating                . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail                 . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost                  . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_book                  . "</td>\n";
              $output .= "</tr>\n";
            }

            $output .= "</table>\n";
          }

# show all common programs
          $q_string  = "select r_pgm_id,pgm_name,pgm_desc,pgm_cost,pgm_avail,pgm_perm,ver_book,pgm_page ";
          $q_string .= "from r_program ";
          $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
          $q_string .= "left join versions on versions.ver_id = program.pgm_book ";
          $q_string .= "where r_pgm_character = " . $formVars['r_deck_character'] . " and r_pgm_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " and pgm_type = 0 ";
          $q_string .= "order by pgm_name ";
          $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_program) > 0) {
            $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" colspan=\"7\">Cyberdeck Common Programs</th>\n";
            $output .= "</tr>\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" width=\"60\">Del</th>\n";
            $output .=   "<th class=\"ui-state-default\">Program</th>\n";
            $output .=   "<th class=\"ui-state-default\">Description</th>\n";
            $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
            $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
            $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
            $output .= "</tr>\n";

            while ($a_r_program = mysqli_fetch_array($q_r_program)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_program('program.del.php?id="  . $a_r_program['r_pgm_id'] . "');\">";
              $linkend   = "</a>";

              $costtotal += $a_r_program['pgm_cost'];

              $pgm_avail = return_Avail($a_r_program['pgm_avail'], $a_r_program['pgm_perm']);

              $pgm_cost = return_Cost($a_r_program['pgm_cost']);

              $pgm_book = return_Book($a_r_program['ver_book'], $a_r_program['pgm_page']);

              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel                            . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\">"                   . $a_r_program['pgm_name'] . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\">"                   . $a_r_program['pgm_desc'] . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $pgm_avail               . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $pgm_cost                . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $pgm_book                . "</td>\n";
              $output .= "</tr>\n";
            }

            $output .= "</table>\n";
          }

# show all hacking programs
          $q_string  = "select r_pgm_id,pgm_name,pgm_desc,pgm_avail,pgm_perm,pgm_cost,ver_book,pgm_page ";
          $q_string .= "from r_program ";
          $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
          $q_string .= "left join versions on versions.ver_id = program.pgm_book ";
          $q_string .= "where r_pgm_character = " . $formVars['r_deck_character'] . " and r_pgm_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " and pgm_type = 1 ";
          $q_string .= "order by pgm_name ";
          $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_program) > 0) {
            $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" colspan=\"7\">Cyberdeck Hacking Programs</th>\n";
            $output .= "</tr>\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" width=\"60\">Del</th>\n";
            $output .=   "<th class=\"ui-state-default\">Program</th>\n";
            $output .=   "<th class=\"ui-state-default\">Description</th>\n";
            $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
            $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
            $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
            $output .= "</tr>\n";

            while ($a_r_program = mysqli_fetch_array($q_r_program)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_program('program.del.php?id="  . $a_r_program['r_pgm_id'] . "');\">";
              $linkend   = "</a>";

              $costtotal += $a_r_program['pgm_cost'];

              $pgm_avail = return_Avail($a_r_program['pgm_avail'], $a_r_program['pgm_perm']);

              $pgm_cost = return_Cost($a_r_program['pgm_cost']);

              $pgm_book = return_Book($a_r_program['ver_book'], $a_r_program['pgm_page']);

              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel                            . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\">"                   . $a_r_program['pgm_name'] . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\">"                   . $a_r_program['pgm_desc'] . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $pgm_avail               . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $pgm_cost                . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $pgm_book                . "</td>\n";
              $output .= "</tr>\n";
            }

            $output .= "</table>\n";
          }

# show all agents
          $q_string  = "select r_agt_id,agt_name,agt_rating,agt_avail,agt_perm,agt_cost,ver_book,agt_page ";
          $q_string .= "from r_agents ";
          $q_string .= "left join agents on agents.agt_id = r_agents.r_agt_number ";
          $q_string .= "left join versions on versions.ver_id = agents.agt_book ";
          $q_string .= "where r_agt_character = " . $formVars['r_deck_character'] . " and r_agt_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " ";
          $q_string .= "order by agt_name,agt_rating ";
          $q_r_agents = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          if (mysql_num_rows($q_r_agents) > 0) {
            $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" colspan=\"7\">Cyberdeck Agents</th>\n";
            $output .= "</tr>\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" width=\"60\">Del</th>\n";
            $output .=   "<th class=\"ui-state-default\">Agent</th>\n";
            $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
            $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
            $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
            $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
            $output .= "</tr>\n";

            while ($a_r_agents = mysqli_fetch_array($q_r_agents)) {

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_agent('agent.del.php?id="  . $a_r_agents['r_agt_id'] . "');\">";
              $linkend   = "</a>";

              $costtotal += $a_r_agents['agt_cost'];

              $agt_rating = return_Rating($a_r_agents['agt_rating']);

              $agt_avail = return_Avail($a_r_agents['agt_avail'], $a_r_agents['agt_perm']);

              $agt_cost = return_Cost($a_r_agents['agt_cost']);

              $agt_book = return_Book($a_r_agents['ver_book'], $a_r_agents['agt_page']);

              $output .= "<tr>\n";
              $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel                           . "</td>\n";
              $output .= "  <td class=\"ui-widget-content\">"                   . $a_r_agents['agt_name'] . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $agt_rating             . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $agt_avail              . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $agt_cost               . "</td>\n";
              $output .= "  <td class=\"ui-widget-content delete\">"            . $agt_book               . "</td>\n";
              $output .= "</tr>\n";
            }

            $output .= "</table>\n";
          }
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        }
      } else {
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" colspan=\"12\">Cyberdeck ID: " . $a_r_cyberdeck['r_deck_access'] . "</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Del</th>\n";
        $output .=   "<th class=\"ui-state-default\">Brand</th>\n";
        $output .=   "<th class=\"ui-state-default\">Model</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Attack</th>\n";
        $output .=   "<th class=\"ui-state-default\">Sleaze</th>\n";
        $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
        $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
        $output .=   "<th class=\"ui-state-default\">Programs</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"12\">No Cyberdecks added.</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";
      }

      mysql_free_result($q_r_cyberdeck);

      print "document.getElementById('my_cyberdeck_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
