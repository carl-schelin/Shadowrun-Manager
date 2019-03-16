<?php
# Script: add.accessory.mysql.php
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
    $package = "add.accessory.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']             = clean($_GET['id'],            10);
        $formVars['acc_type']       = clean($_GET['acc_type'],      10);
        $formVars['acc_class']      = clean($_GET['acc_class'],     10);
        $formVars['acc_accessory']  = clean($_GET['acc_accessory'], 60);
        $formVars['acc_name']       = clean($_GET['acc_name'],      60);
        $formVars['acc_mount']      = clean($_GET['acc_mount'],     15);
        $formVars['acc_essence']    = clean($_GET['acc_essence'],   10);
        $formVars['acc_rating']     = clean($_GET['acc_rating'],    10);
        $formVars['acc_capacity']   = clean($_GET['acc_capacity'],  10);
        $formVars['acc_avail']      = clean($_GET['acc_avail'],     10);
        $formVars['acc_perm']       = clean($_GET['acc_perm'],      10);
        $formVars['acc_cost']       = clean($_GET['acc_cost'],      10);
        $formVars['acc_book']       = clean($_GET['acc_book'],      10);
        $formVars['acc_page']       = clean($_GET['acc_page'],      10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['acc_essence'] == '') {
          $formVars['acc_essence'] = 0;
        }
        if ($formVars['acc_rating'] == '') {
          $formVars['acc_rating'] = 0;
        }
        if ($formVars['acc_capacity'] == '') {
          $formVars['acc_capacity'] = 0;
        }
        if ($formVars['acc_avail'] == '') {
          $formVars['acc_avail'] = 0;
        }
        if ($formVars['acc_cost'] == '') {
          $formVars['acc_cost'] = 0;
        }
        if ($formVars['acc_page'] == '') {
          $formVars['acc_page'] = 0;
        }

        if (strlen($formVars['acc_type']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "acc_type        =   " . $formVars['acc_type']      . "," .
            "acc_class       =   " . $formVars['acc_class']     . "," .
            "acc_accessory   = \"" . $formVars['acc_accessory'] . "\"," .
            "acc_name        = \"" . $formVars['acc_name']      . "\"," .
            "acc_mount       = \"" . $formVars['acc_mount']     . "\"," .
            "acc_essence     =   " . $formVars['acc_essence']   . "," .
            "acc_rating      =   " . $formVars['acc_rating']    . "," .
            "acc_capacity    =   " . $formVars['acc_capacity']  . "," .
            "acc_avail       =   " . $formVars['acc_avail']     . "," .
            "acc_perm        = \"" . $formVars['acc_perm']      . "\"," .
            "acc_cost        =   " . $formVars['acc_cost']      . "," .
            "acc_book        =   " . $formVars['acc_book']      . "," .
            "acc_page        =   " . $formVars['acc_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into accessory set acc_id = NULL, " . $q_string;
            $message = "Accessory added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update accessory set " . $q_string . " where acc_id = " . $formVars['id'];
            $message = "Accessory updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['acc_name']);

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
      $output .= "  <th class=\"ui-state-default\">Accessory Listing</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('accessory-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"accessory-listing-help\" style=\"display: none\">\n";

      $output .= "<div class=\"main-help ui-widget-content\">\n";

      $output .= "<ul>\n";
      $output .= "  <li><strong>Ammunition Listing</strong>\n";
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
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Type</th>\n";
      $output .=   "<th class=\"ui-state-default\">Class</th>\n";
      $output .=   "<th class=\"ui-state-default\">Accessory To</th>\n";
      $output .=   "<th class=\"ui-state-default\">Accessory Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Mount</th>\n";
      $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Capacity</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $nuyen = '&yen;';
      $q_string  = "select acc_id,sub_name,acc_class,class_name,acc_accessory,acc_name,acc_mount,acc_rating,acc_essence,";
      $q_string .= "acc_capacity,acc_avail,acc_perm,acc_cost,ver_book,acc_page ";
      $q_string .= "from accessory ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "left join class on class.class_id = accessory.acc_class ";
      $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by sub_name,acc_type,acc_class,acc_name,acc_accessory,acc_rating,ver_version ";
      $q_accessory = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_accessory) > 0) {
        while ($a_accessory = mysql_fetch_array($q_accessory)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.accessory.fill.php?id="  . $a_accessory['acc_id'] . "');showDiv('accessory-hide');\">";
          $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_accessory('add.accessory.del.php?id=" . $a_accessory['acc_id'] . "');\">";
          $linkend = "</a>";

          $itemclass = $a_accessory['class_name'];
          if ($a_accessory['acc_class'] == 0) {
            $itemclass = "Any Subheading";
          }

          $accessory = $a_accessory['acc_accessory'];
          if ($a_accessory['acc_accessory'] == '') {
            $accessory = "Any Item";
          }

          $class = return_Class($a_accessory['acc_perm']);

          $output .= "<tr>\n";
          $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_accessory['acc_id']                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_accessory['sub_name']                               . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $itemclass                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $accessory                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_accessory['acc_name']                                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . return_Mount($a_accessory['acc_mount'])                           . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . return_Essence($a_accessory['acc_essence'])                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . return_Rating($a_accessory['acc_rating'])                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . return_Capacity($a_accessory['acc_capacity'])                     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . return_Avail($a_accessory['acc_avail'], $a_accessory['acc_perm']) . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . return_Cost($a_accessory['acc_cost'])                             . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . return_Book($a_accessory['ver_book'], $a_accessory['acc_page'])   . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"13\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";

      print "document.accessory.acc_accessory.value = '';\n";
      print "document.accessory.acc_name.value = '';\n";
      print "document.accessory.acc_mount.value = '';\n";
      print "document.accessory.acc_essence.value = '';\n";
      print "document.accessory.acc_rating.value = '';\n";
      print "document.accessory.acc_capacity.value = '';\n";
      print "document.accessory.acc_avail.value = '';\n";
      print "document.accessory.acc_perm.value = '';\n";
      print "document.accessory.acc_cost.value = '';\n";

      print "document.accessory.update.disabled = true;\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
