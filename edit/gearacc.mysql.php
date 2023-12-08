<?php
# Script: gearacc.mysql.php
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
    $package = "gearacc.mysql.php";
    $formVars['update']          = clean($_GET['update'],       10);
    $formVars['r_gear_id']       = clean($_GET['r_gear_id'],    10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }
    if ($formVars['r_gear_id'] == '') {
      $formVars['r_gear_id'] = 0;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0) {
        $formVars['r_acc_character']    = clean($_GET['r_acc_character'],    10);
        $formVars['r_acc_number']       = clean($_GET['r_acc_number'],       10);

        if ($formVars['r_acc_character'] == '') {
          $formVars['r_acc_character'] = 0;
        }
        if ($formVars['r_acc_number'] == '') {
          $formVars['r_acc_number'] = 1;
        }

        if ($formVars['r_acc_number'] > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_acc_character   =   " . $formVars['r_acc_character']   . "," .
            "r_acc_number      =   " . $formVars['r_acc_number']      . "," . 
            "r_acc_parentid    =   " . $formVars['r_gear_id'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_accessory set r_acc_id = NULL," . $q_string;
            $message = "Gear Accessory added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_acc_number']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";

        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">Accessories (" . $formVars['r_gear_id'] . ")</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('gear-accessories-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"gear-accessories-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Capacity</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

# on new load of data only
      if ($formVars['r_gear_id'] == 0) {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"6\">Select one item of Gear in order to purchase accessories.</td>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('gearacc_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      } else {

# r_gear_id == the id of the gear owned/selected. If zero, then no gear has been selected and no accessories presented.
# then r_gear_number == the id of actual gear that was selected which gives me the class id.
# show the accessories associated with 'Gear' (get from accessory table (acc_type) linked to subjects)
# also where the class == the accessory class or zero for all classes
# also where the item itself is called out in the accessory table; acc_accessory.
# basically building the where clause.

# so Gear by default
        $where = "where sub_name = \"Gear\" ";

# get the purchased gear id
# got the number, now get the gear_class and gear_name from the gear table
# now get the class name from the class table
        $q_string  = "select gear_id,gear_class,gear_name,gear_capacity,r_gear_character,r_gear_number ";
        $q_string .= "from r_gear ";
        $q_string .= "left join gear on gear.gear_id = r_gear.r_gear_number ";
        $q_string .= "where r_gear_id = " . $formVars['r_gear_id'] . " ";
        $q_r_gear = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        $a_r_gear = mysqli_fetch_array($q_r_gear);

# for that class or something that works for all; numbers because both acc_class and gear_class are numeric. no need to convert to text
        $where .= "and (acc_class = " . $a_r_gear['gear_class'] . " or acc_class = 0) ";

# for that item or something that works for all
        $where .= "and (acc_accessory = \"" . $a_r_gear['gear_name'] . "\" or acc_accessory = \"\") ";


# this one has capacity. we have the r_gear_number so we know which accessories have been purchased.
# get a total of all acc_capacity and subtract it from gear_capacity. if the total is less than 0, don't display it.

        $totalcapacity = 0;
        $q_string  = "select acc_capacity ";
        $q_string .= "from r_accessory ";
        $q_string .= "left join accessory on accessory.acc_id = r_accessory.r_acc_number ";
        $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
        $q_string .= "where sub_name = \"Gear\" and r_acc_character = " . $a_r_gear['r_gear_character'] . " and r_acc_parentid = " . $formVars['r_gear_id'] . " ";
        $q_string .= "order by acc_name,acc_rating ";
        $q_r_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_r_accessory) > 0) {
          while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {
            $totalcapacity += $a_r_accessory['acc_capacity'];
          }
        }

# this should give me the total used capacity of all accessories for this piece of armor
        $availablecapacity = -1 * abs($a_r_gear['gear_capacity'] + $totalcapacity);
# now I have a negative available capacity. just return any accessories that have an equal or greater value.
        $where .= "and acc_capacity >= " . $availablecapacity . " ";


# now display the available accessories
        $q_string  = "select acc_id,acc_name,acc_rating,acc_capacity,acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
        $q_string .= "from accessory ";
        $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
        $q_string .= "left join class on class.class_id = accessory.acc_class ";
        $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
        $q_string .= $where . " and ver_active = 1 ";
        $q_string .= "order by acc_name,acc_rating,ver_version ";
        $q_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_accessory) > 0) {
          while ($a_accessory = mysqli_fetch_array($q_accessory)) {

            $linkstart  = "<a href=\"#\" onclick=\"javascript:show_file('gearacc.mysql.php";
            $linkstart .= "?update=0";
            $linkstart .= "&r_gear_id="          . $formVars['r_gear_id'];
            $linkstart .= "&r_acc_character="    . $a_r_gear['r_gear_character'];
            $linkstart .= "&r_acc_number="       . $a_accessory['acc_id'];
            $linkstart .= "');";
            $linkstart .= "show_file('gear.mysql.php";
            $linkstart .= "?update=-1";
            $linkstart .= "&r_gear_character=" . $a_r_gear['r_gear_character'];
            $linkstart .= "');\">";

            $linkend   = "</a>";

            $acc_rating = return_Rating($a_accessory['acc_rating']);

            $acc_capacity = return_Capacity($a_accessory['acc_capacity']);

            $acc_avail = return_Avail($a_accessory['acc_avail'], $a_accessory['acc_perm']);

            $acc_cost = return_Cost($a_accessory['acc_cost']);

            $acc_book = return_Book($a_accessory['ver_book'], $a_accessory['acc_page']);

            $class = return_Class($a_accessory['acc_perm']);

            $output .= "<tr>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_accessory['acc_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_rating                                      . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_capacity                                    . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_avail                                       . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_cost                                        . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">" . $acc_book                                        . "</td>\n";
            $output .= "</tr>\n";

          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">Either you've reached gear capacity or there are no appropriate Accessories for this item.</td>\n";
          $output .= "</tr>\n";
        }
        $output .= "</table>\n";

        mysql_free_result($q_accessory);

        print "document.getElementById('gearacc_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      }
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
