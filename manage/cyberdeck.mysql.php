<?php
# Script: cyberdeck.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  $package = "cyberdeck.mysql.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

  header('Content-Type: text/javascript');

  $formVars['id'] = clean($_GET['id'], 10);
  $output = '';

# people can have more than one cyberdeck so get the cyberdecks and then check for system info and finally build the program listing and stats
# a basic cyberdeck might have just the normal info. A decker will have common programs and possibly hacking programs (separate listing)

  $q_string  = "select r_deck_id,deck_brand,deck_model,deck_rating,deck_programs,r_deck_attack,r_deck_sleaze,";
  $q_string .= "r_deck_data,r_deck_firewall,r_deck_access,deck_avail,deck_perm,deck_cost,ver_book,deck_page ";
  $q_string .= "from r_cyberdeck ";
  $q_string .= "left join cyberdeck on cyberdeck.deck_id = r_cyberdeck.r_deck_number ";
  $q_string .= "left join versions on versions.ver_id = cyberdeck.deck_book ";
  $q_string .= "where r_deck_character = " . $formVars['id'] . " ";
  $q_string .= "order by deck_brand,deck_model,deck_rating,ver_version ";
  $q_r_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_cyberdeck) > 0) {

    while ($a_r_cyberdeck = mysqli_fetch_array($q_r_cyberdeck)) {

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\" colspan=\"10\">Cyberdeck ID: " . $a_r_cyberdeck['r_deck_access'] . "</th>\n";
      $output .= "</tr>\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Cyberdeck</th>\n";
      $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
      $output .= "  <th class=\"ui-state-default\">Attack</th>\n";
      $output .= "  <th class=\"ui-state-default\">Sleaze</th>\n";
      $output .= "  <th class=\"ui-state-default\">Data Processing</th>\n";
      $output .= "  <th class=\"ui-state-default\">Firewall</th>\n";
      $output .= "  <th class=\"ui-state-default\">Programs</th>\n";
      $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
      $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
      $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $rating = return_Rating($a_r_cyberdeck['deck_rating']);

      $avail = return_Avail($a_r_cyberdeck['deck_avail'], $a_r_cyberdeck['deck_perm']);

      $cost = return_Cost($a_r_cyberdeck['deck_cost']);

      $book = return_Book($a_r_cyberdeck['ver_book'], $a_r_cyberdeck['deck_page']);

      $output .= "<tr>\n";
      $output .= "<td class=\"ui-widget-content\">"        . $a_r_cyberdeck['deck_brand'] . " " . $a_r_cyberdeck['deck_model'] . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $rating                                                           . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberdeck['r_deck_attack']                                   . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberdeck['r_deck_sleaze']                                   . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberdeck['r_deck_data']                                     . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberdeck['r_deck_firewall']                                 . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $a_r_cyberdeck['deck_programs']                                   . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $avail                                                            . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $cost                                                             . "</td>\n";
      $output .= "<td class=\"ui-widget-content delete\">" . $book                                                             . "</td>\n";
      $output .= "</tr>\n";

      $output .= "</table>\n";
      
# legal programs first
      $q_string  = "select pgm_name,pgm_desc,r_pgm_active,pgm_cost,pgm_avail,pgm_perm,ver_book,pgm_page ";
      $q_string .= "from r_program ";
      $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
      $q_string .= "left join versions on versions.ver_id = program.pgm_book ";
      $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " and pgm_type = 0 ";
      $q_string .= "order by pgm_name,ver_version ";
      $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_program) > 0) {

        $output .= "<table class=\"ui-styled-table\" width=\"100%\"\n>";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Programs</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Program</th>\n";
        $output .= "  <th class=\"ui-state-default\">Description</th>\n";
        $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
        $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
        $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        while ($a_r_program = mysqli_fetch_array($q_r_program)) {

          $avail = return_Avail($a_r_program['pgm_avail'], $a_r_program['pgm_perm']);

          $cost = return_Cost($a_r_program['pgm_cost']);

          $book = return_Book($a_r_program['ver_book'], $a_r_program['pgm_page']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_program['pgm_name'] . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_program['pgm_desc'] . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $avail                   . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $cost                    . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $book                    . "</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "</table>\n";
      }

# now Hacking programs
      $q_string  = "select pgm_name,pgm_desc,r_pgm_active,pgm_cost,pgm_avail,pgm_perm,ver_book,pgm_page ";
      $q_string .= "from r_program ";
      $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
      $q_string .= "left join versions on versions.ver_id = program.pgm_book ";
      $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " and pgm_type = 1 ";
      $q_string .= "order by pgm_name,ver_version ";
      $q_r_program = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_program) > 0) {

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Hacking Programs</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Program</th>\n";
        $output .= "  <th class=\"ui-state-default\">Description</th>\n";
        $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
        $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
        $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>";

        while ($a_r_program = mysqli_fetch_array($q_r_program)) {

          $avail = return_Avail($a_r_program['pgm_avail'], $a_r_program['pgm_perm']);

          $cost = return_Cost($a_r_program['pgm_cost']);

          $book = return_Book($a_r_program['ver_book'], $a_r_program['pgm_page']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_program['pgm_name'] . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_program['pgm_desc'] . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $avail                   . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $cost                    . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $book                    . "</td>\n";
          $output .= "</tr>\n";

        }
        $output .= "</table>\n";

      }

# now Agents
      $q_string  = "select agt_name,agt_rating,r_agt_active,agt_cost,agt_avail,agt_perm,ver_book,agt_page ";
      $q_string .= "from r_agents ";
      $q_string .= "left join agents on agents.agt_id = r_agents.r_agt_number ";
      $q_string .= "left join versions on versions.ver_id = agents.agt_book ";
      $q_string .= "where r_agt_character = " . $formVars['id'] . " and r_agt_cyberdeck = " . $a_r_cyberdeck['r_deck_id'] . " ";
      $q_string .= "order by agt_name,ver_version ";
      $q_r_agents = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_agents) > 0) {

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"5\">Agents</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Agent</th>\n";
        $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
        $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
        $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
        $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        while ($a_r_agents = mysqli_fetch_array($q_r_agents)) {

          $rating = return_Rating($a_r_agents['agt_rating']);

          $avail = return_Avail($a_r_agents['agt_avail'], $a_r_agents['agt_perm']);

          $cost = return_Cost($a_r_agents['agt_cost']);

          $book = return_Book($a_r_agents['ver_book'], $a_r_agents['agt_page']);

          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $a_r_agents['agt_name'] . "</td>\n";
          $output .= "  <td class=\"ui-widget-content\">"        . $rating                 . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $avail                  . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $cost                   . "</td>\n";
          $output .= "  <td class=\"ui-widget-content delete\">" . $book                   . "</td>\n";
          $output .= "</tr>\n";

        }
        $output .= "</table>\n";

      }

      print "document.getElementById('" . $a_r_cyberdeck['deck_brand'] . $a_r_cyberdeck['r_deck_id'] . "_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";
    }
  } else {
    $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\" colspan=\"10\">Cyberdeck ID:</th>\n";
    $output .= "</tr>\n";
    $output .= "<tr>\n";
    $output .= "  <th class=\"ui-state-default\">Cyberdeck</th>\n";
    $output .= "  <th class=\"ui-state-default\">Rating</th>\n";
    $output .= "  <th class=\"ui-state-default\">Attack</th>\n";
    $output .= "  <th class=\"ui-state-default\">Sleaze</th>\n";
    $output .= "  <th class=\"ui-state-default\">Data Processing</th>\n";
    $output .= "  <th class=\"ui-state-default\">Firewall</th>\n";
    $output .= "  <th class=\"ui-state-default\">Programs</th>\n";
    $output .= "  <th class=\"ui-state-default\">Availability</th>\n";
    $output .= "  <th class=\"ui-state-default\">Cost</th>\n";
    $output .= "  <th class=\"ui-state-default\">Book/Page</th>\n";
    $output .= "</tr>\n";
    $output .= "<tr>\n";
    $output .= "  <td class=\"ui-widget-content\" colspan=\"10\">No Cyberdecks added.</td>\n";
    $output .= "</tr>\n";
    $output .= "</table>\n";

    print "document.getElementById('nodeck_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";
  }

?>
