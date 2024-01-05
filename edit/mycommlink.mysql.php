<?php
# Script: mycommlink.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  if (isset($_SESSION['username'])) {
    $package = "mycommlink.mysql.php";
    $formVars['update']              = clean($_GET['update'],              10);
    $formVars['r_link_character']    = clean($_GET['r_link_character'],    10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      if ($formVars['update'] == 1) {
        $formVars['link_id'] = clean($_GET['link_id'], 10);

        if ($formVars['link_id'] == '') {
          $formVars['link_id'] = 0;
        }

        if ($formVars['link_id'] > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string  = "select link_data,link_firewall,link_access ";
          $q_string .= "from commlink ";
          $q_string .= "where link_id = " . $formVars['link_id'] . " ";
          $q_commlink = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          $a_commlink = mysqli_fetch_array($q_commlink);

          $link_access =
            $a_commlink['link_access'] . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767)) . ":" .
            dechex(rand(0,32767));

          $q_string =
            "r_link_character     =   " . $formVars['r_link_character'] . "," .
            "r_link_number        =   " . $formVars['link_id']          . "," .
            "r_link_data          =   " . $a_commlink['link_data']     . "," .
            "r_link_firewall      =   " . $a_commlink['link_firewall'] . "," .
            "r_link_access        = \"" . $link_access                  . "\"";

          if ($formVars['update'] == 1) {
            $query = "insert into r_commlink set r_link_id = NULL," . $q_string;
            $message = "Commlink added.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_link_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');\n";
        }
      }

# adding a program to an existing commlink
      if ($formVars['update'] == 3) {
        $formVars['pgm_id']    = clean($_GET['pgm_id'],    10);
        $formVars['r_link_id'] = clean($_GET['r_link_id'], 10);

        if ($formVars['pgm_id'] == '') {
          $formVars['pgm_id'] = 0;
        }

        if ($formVars['r_link_id'] == '') {
          $formVars['r_link_id'] = 0;
        }

        if ($formVars['r_link_id'] > 0 && $formVars['pgm_id'] > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_pgm_character   =   " . $formVars['r_link_character']   . "," .
            "r_pgm_commlink    =   " . $formVars['r_link_id']          . "," .
            "r_pgm_number      =   " . $formVars['pgm_id'];

          if ($formVars['update'] == 3) {
            $query = "insert into r_program set r_pgm_id = NULL," . $q_string;
            $message = "Program added.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['pgm_id']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must select a commlink before purchasing a program.\\n\\nClick on one of your commlinks to activate it.');\n";
        }
      }


# adding an agent to an existing commlink
      if ($formVars['update'] == 4) {
        $formVars['agt_id']    = clean($_GET['agt_id'],    10);
        $formVars['r_link_id'] = clean($_GET['r_link_id'], 10);

        if ($formVars['agt_id'] == '') {
          $formVars['agt_id'] = 0;
        }

        if ($formVars['r_link_id'] == '') {
          $formVars['r_link_id'] = 0;
        }

        if ($formVars['r_link_id'] > 0 && $formVars['agt_id'] > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_agt_character   =   " . $formVars['r_link_character']   . "," .
            "r_agt_commlink    =   " . $formVars['r_link_id']          . "," .
            "r_agt_number      =   " . $formVars['agt_id'];

          if ($formVars['update'] == 4) {
            $query = "insert into r_agents set r_agt_id = NULL," . $q_string;
            $message = "Agent added.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['agt_id']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must select a commlink before purchasing a program.\\n\\nClick on one of your commlinks to activate it.');\n";
        }
      }


      if ($formVars['update'] == -3) {

# print forms for each of the commlink bits.
# first; the main one which sets the commlink being edited. You can't buy anything else until you get a commlink

        logaccess($db, $_SESSION['username'], $package, "Creating the form for viewing.");

        $shiftleft  = "<input type=\"button\" value=\"&lt;\" onClick=\"javascript:commlink_left('commlink.option.php'";
        $shiftright = "<input type=\"button\" value=\"&gt;\" onClick=\"javascript:commlink_right('commlink.option.php'";

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Active Commlink Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" width=\"20%\">Active Commlink: <span id=\"r_link_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\" width=\"20%\">" . $shiftleft . ", 'firewall');\">Data Processing: <span id=\"link_data_val\">0</span>" . $shiftright . ", 'firewall');\">\n";
        $output .= "  <td class=\"ui-widget-content\" width=\"20%\">" . $shiftleft . ", 'data');\">Firewall: <span id=\"link_firewall_val\">0</span>"        . $shiftright . ", 'data');\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<input type=\"hidden\" name=\"r_link_id\"      value=\"0\">\n";

        print "document.getElementById('commlink_form').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

# show your commlink and all associated programs.
      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Commlink Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('commlink-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"commlink-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<p><strong>How It Works</strong></p>\n\n";

      $output .= "<p>If you want to add a Commlink, select one from the <strong>Commlinks</strong> tab.</p>\n\n";

      $output .= "<p>In order to add programs, agents, and accessories to your Commlink, you'll need to select the Commlink from ";
      $output .= "the list you've purchased. With it selected, you'll be able to buy what you need to accessorize your Commlink. </p>\n";

      $output .= "<p>For <strong>Common Programs</strong>, click the tab and click to buy one. Same for <strong>Hacking Programs</strong>. ";
      $output .= "While you can buy more than one, you can only use one at a time so it's a waste of funds to buy more than one of each. </p>\n";

      $output .= "<p>For <strong>Agents</strong>, you can only have one per Commlink. Select one and it'll be associated with your ";
      $output .= "Commlink. Finally, <strong>Accessories</strong> for you Commlink are listed. Click on the accessory however many ";
      $output .= "times you want to purchase that item.</p>\n";

      $output .= "<p>In the <strong>My Commlinks</strong> tab, you should see the configuration. As a reminder, you can switch the values ";
      $output .= "between the four link values. You are not able to switch a positive value with a setting that is zero. ";
      $output .= "This can be done by clicking on your Commlink. This brings up a display with the two values and left and right buttons. Click the buttons ";
      $output .= "to switch the values. The Commlink is automatically updated when you do this and the change is set until you decide to change it again.</p>\n";

      $output .= "</div>\n";

      $output .= "</div>\n";

      $totalcost = 0;
      $q_string  = "select r_link_id,link_brand,link_model,r_link_number,r_link_data,r_link_firewall,";
      $q_string .= "link_rating,link_avail,link_perm,link_program,r_link_access,link_cost,ver_book,link_page ";
      $q_string .= "from r_commlink ";
      $q_string .= "left join commlink on commlink.link_id = r_commlink.r_link_number ";
      $q_string .= "left join versions on versions.ver_id = commlink.link_book ";
      $q_string .= "where r_link_character = " . $formVars['r_link_character'] . " ";
      $q_string .= "order by link_brand,link_model,ver_version ";
      $q_r_commlink = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_commlink) > 0) {
        while ($a_r_commlink = mysqli_fetch_array($q_r_commlink)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:attach_linkacc(" . $a_r_commlink['r_link_id'] . ");showDiv('commlink-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_commlink('commlink.del.php?id="  . $a_r_commlink['r_link_id'] . "');\">";
          $linkend   = "</a>";

          $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
          $output .= "<tr>\n";
          $output .=   "<th class=\"ui-state-default\" colspan=\"12\">Commlink ID: " . $a_r_commlink['r_link_access'] . "</th>\n";
          $output .= "</tr>\n";
          $output .= "<tr>\n";
          $output .=   "<th class=\"ui-state-default\">Del</th>\n";
          $output .=   "<th class=\"ui-state-default\">Commlink</th>\n";
          $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
          $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
          $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
          $output .=   "<th class=\"ui-state-default\">Programs</th>\n";
          $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
          $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
          $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
          $output .= "</tr>\n";

          $link_rating = return_Rating($a_r_commlink['link_rating']);

          $totalcost += $a_r_commlink['link_cost'];

          $link_avail = return_Avail($a_r_commlink['link_avail'], $a_r_commlink['link_perm']);

          $link_cost = return_Cost($a_r_commlink['link_cost']);

          $link_book = return_Book($a_r_commlink['ver_book'], $a_r_commlink['link_page']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $linkdel                                                                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $linkstart . $a_r_commlink['link_brand'] . " " . $a_r_commlink['link_model'] . $linkend . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $link_rating                                                     . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_commlink['r_link_data']                                    . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_commlink['r_link_firewall']                                . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $a_r_commlink['link_program']                                  . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $link_avail                                                      . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $link_cost                                                       . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">"              . $link_book                                                       . "</td>\n";
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
          $q_string .= "where r_acc_character = " . $formVars['r_link_character'] . " and r_acc_parentid = " . $a_r_commlink['r_link_id'] . " ";
          $q_string .= "order by acc_name,acc_rating,ver_version ";
          $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_accessory) > 0) {

            $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" colspan=\"12\">Commlink Accessories</th>\n";
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

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_linkacc('linkacc.del.php?id="  . $a_r_accessory['r_acc_id'] . "');\">";
              $linkend   = "</a>";

              $acc_rating = return_Rating($a_r_accessory['acc_rating']);

              $totalcost += $a_r_accessory['acc_cost'];

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
          $q_string .= "where r_pgm_character = " . $formVars['r_link_character'] . " and r_pgm_commlink = " . $a_r_commlink['r_link_id'] . " and pgm_type = 0 ";
          $q_string .= "order by pgm_name ";
          $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_program) > 0) {
            $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" colspan=\"7\">Commlink Common Programs</th>\n";
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

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_linkpgm('program.del.php?id="  . $a_r_program['r_pgm_id'] . "');\">";
              $linkend   = "</a>";

              $totalcost += $a_r_program['pgm_cost'];

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
          $q_string .= "where r_pgm_character = " . $formVars['r_link_character'] . " and r_pgm_commlink = " . $a_r_commlink['r_link_id'] . " and pgm_type = 1 ";
          $q_string .= "order by pgm_name ";
          $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_program) > 0) {
            $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" colspan=\"7\">Commlink Hacking Programs</th>\n";
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

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_linkpgm('program.del.php?id="  . $a_r_program['r_pgm_id'] . "');\">";
              $linkend   = "</a>";

              $totalcost += $a_r_program['pgm_cost'];

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
          $q_string .= "where r_agt_character = " . $formVars['r_link_character'] . " and r_agt_commlink = " . $a_r_commlink['r_link_id'] . " ";
          $q_string .= "order by agt_name,agt_rating ";
          $q_r_agents = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_agents) > 0) {
            $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" colspan=\"7\">Commlink Agents</th>\n";
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

              $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_linkagt('agent.del.php?id="  . $a_r_agents['r_agt_id'] . "');\">";
              $linkend   = "</a>";

              $totalcost += $a_r_agents['agt_cost'];

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
        $output .= "  <td class=\"ui-widget-content\">Total Cost: " . return_Cost($totalcost) . "</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        }
      } else {
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" colspan=\"10\">Commlink ID:</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\">Del</th>\n";
        $output .=   "<th class=\"ui-state-default\">Brand</th>\n";
        $output .=   "<th class=\"ui-state-default\">Model</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Data Processing</th>\n";
        $output .=   "<th class=\"ui-state-default\">Firewall</th>\n";
        $output .=   "<th class=\"ui-state-default\">Programs</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">No Commlinks added.</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";
      }

      mysqli_free_result($q_r_commlink);

      print "document.getElementById('my_commlink_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
