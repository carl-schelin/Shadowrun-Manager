<?php
# Script: commlink.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'yes';
  include($Sitepath . '/guest.php');

  header('Content-Type: text/javascript');

  if (isset($_SESSION['username'])) {
    $package = "commlink.mysql.php";

    $formVars['id'] = clean($_GET['id'], 10);

    if (check_userlevel($db, $AL_Shadowrunner)) {

      $q_string  = "select ver_version ";
      $q_string .= "from versions ";
      $q_string .= "left join runners on runners.runr_version = versions.ver_id ";
      $q_string .= "where runr_id = " . $formVars['id'] . " ";
      $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      $a_versions = mysqli_fetch_array($q_versions);

      logaccess($db, $formVars['username'], $package, "Accessing the script.");

# people can have more than one commlink so get the commlinks and then check for system info and finally build the program listing and stats
# a basic commlink might have just the normal info. A decker will have programs and possibly black programs (separate listing)

      $q_string  = "select r_link_id,link_brand,link_model,link_rating,link_program,link_program,";
      $q_string .= "link_avail,link_perm,ver_book,link_page,link_cost,r_link_access ";
      $q_string .= "from r_commlink ";
      $q_string .= "left join commlink on commlink.link_id = r_commlink.r_link_number ";
      $q_string .= "left join versions on versions.ver_id = commlink.link_book ";
      $q_string .= "where r_link_character = " . $formVars['id'] . " ";
      $q_string .= "order by link_rating,ver_version ";
      $q_r_commlink = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_commlink) > 0) {
        while ($a_r_commlink = mysqli_fetch_array($q_r_commlink)) {

          $output  = "<table class=\"ui-styled-table\" width=\"100%\">";
          $output .= "<tr>\n";
          $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Commlink ID: " . $a_r_commlink['r_link_access'] . "</th>\n";
          $output .= "</tr>\n";
          $output .= "<tr>";
          $output .= "  <th class=\"ui-state-default\">Commlink</th>";
          $output .= "  <th class=\"ui-state-default\">Rating</th>";
          $output .= "  <th class=\"ui-state-default\">Active Slots</th>";
          $output .= "  <th class=\"ui-state-default\">Availability</th>";
          $output .= "  <th class=\"ui-state-default\">Cost</th>";
          $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
          $output .= "</tr>";

          $commlink_rating = return_Rating($a_r_commlink['link_rating']);

          $commlink_avail = return_Avail($a_r_commlink['link_avail'], $a_r_commlink['link_perm']);

          $commlink_cost = return_Cost($a_r_commlink['link_cost']);
          $totalcost = $a_r_commlink['link_cost'];

          $commlink_book = return_Book($a_r_commlink['ver_book'], $a_r_commlink['link_page']);

          $output .= "<tr>";
          $output .= "<td class=\"ui-widget-content\">"        . $a_r_commlink['link_brand'] . " " . $a_r_commlink['link_model'] . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $commlink_rating                                                . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $a_r_commlink['link_program']                                   . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $commlink_avail                                                 . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $commlink_cost                                                  . "</td>";
          $output .= "<td class=\"ui-widget-content delete\">" . $commlink_book                                                  . "</td>";
          $output .= "</tr>";

# now get any accessories. Simple enough; check for r_accessory for character and the id of the parent, then get the info
# need to get the r_acc_number based on the character and parent id (r_ware_id) of the item
# ultimately to get, from the accessories table, the items that are associated with the parent id
          $q_string  = "select r_acc_id,acc_id,acc_class,class_name,acc_name,acc_rating,acc_essence,acc_capacity,";
          $q_string .= "acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
          $q_string .= "from r_accessory ";
          $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
          $q_string .= "left join class on class.class_id = accessory.acc_class ";
          $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
          $q_string .= "where r_acc_character = " . $formVars['id'] . " and r_acc_parentid = " . $a_r_commlink['r_link_id'] . " ";
          $q_string .= "order by acc_name,acc_rating,ver_version ";
          $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_accessory) > 0) {

            $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\" colspan=\"5\">Commlink Accessories</th>\n";
            $output .= "</tr>\n";
            $output .= "<tr>\n";
            $output .=   "<th class=\"ui-state-default\">Name</th>\n";
            $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
            $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
            $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
            $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
            $output .= "</tr>\n";

            while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {

              $acc_rating = return_Rating($a_r_accessory['acc_rating']);

              $acc_avail = return_Avail($a_r_accessory['acc_avail'], $a_r_accessory['acc_perm']);

              $acc_cost = return_Cost($a_r_accessory['acc_cost']);
              $totalcost += $a_r_accessory['acc_cost'];

              $acc_book = return_Book($a_r_accessory['ver_book'], $a_r_accessory['acc_page']);

              $class = "ui-widget-content";
              if (isset($formVars['r_acc_number']) && $formVars['r_acc_number'] == $a_r_accessory['acc_id']) {
                $class = "ui-state-error";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $a_r_accessory['acc_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_rating                . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail                 . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost                  . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $acc_book                  . "</td>\n";
              $output .= "</tr>\n";
            }

            $output .= "</table>\n";
          }


# legal programs first
          $q_string  = "select pgm_name,pgm_desc,r_pgm_active,pgm_cost,pgm_avail,pgm_perm,ver_book,pgm_page ";
          $q_string .= "from r_program ";
          $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
          $q_string .= "left join versions on versions.ver_id = program.pgm_book ";
          $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_commlink = " . $a_r_commlink['r_link_id'] . " and pgm_type = 0 ";
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
              $totalcost += $a_r_program['pgm_cost'];

              $book = return_Book($a_r_program['ver_book'], $a_r_program['pgm_page']);

              $class = "ui-widget-content";
              if ($a_r_program['r_pgm_active']) {
                $class = "ui-state-highlight";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $a_r_program['pgm_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $a_r_program['pgm_desc'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $avail                   . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $cost                    . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $book                    . "</td>\n";
              $output .= "</tr>\n";
            }
            $output .= "</table>\n";
          }

# now Hacking programs
          $q_string  = "select pgm_name,pgm_desc,r_pgm_active,pgm_cost,pgm_avail,pgm_perm,ver_book,pgm_page ";
          $q_string .= "from r_program ";
          $q_string .= "left join program on program.pgm_id = r_program.r_pgm_number ";
          $q_string .= "left join versions on versions.ver_id = program.pgm_book ";
          $q_string .= "where r_pgm_character = " . $formVars['id'] . " and r_pgm_commlink = " . $a_r_commlink['r_link_id'] . " and pgm_type = 1 ";
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
              $totalcost += $a_r_program['pgm_cost'];

              $book = return_Book($a_r_program['ver_book'], $a_r_program['pgm_page']);

              $class = "ui-widget-content";
              if ($a_r_program['r_pgm_active']) {
                $class = "ui-state-highlight";
              }

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $a_r_program['pgm_name'] . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $a_r_program['pgm_desc'] . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $avail                   . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $cost                    . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">" . $book                    . "</td>\n";
              $output .= "</tr>\n";

            }
            $output .= "</table>\n";
          }
          $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\">Total cost: " . return_Cost($totalcost) . "</th>\n";
          $output .= "</tr>\n";
          $output .= "</table>\n";

          print "document.getElementById('" . $a_r_commlink['link_brand'] . $a_r_commlink['r_link_id'] . "_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";
        }
      } else {
        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"6\">Commlink ID:</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">Commlink</th>";
        $output .= "  <th class=\"ui-state-default\">Rating</th>";
        $output .= "  <th class=\"ui-state-default\">Active Slots</th>";
        $output .= "  <th class=\"ui-state-default\">Availability</th>";
        $output .= "  <th class=\"ui-state-default\">Cost</th>";
        $output .= "  <th class=\"ui-state-default\">Book/Page</th>";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">No Commlinks added.</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('nolink_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n";
      }
    }     
  }
?>
