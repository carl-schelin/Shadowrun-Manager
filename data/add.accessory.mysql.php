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

    if (check_userlevel($db, $AL_Johnson)) {
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
        $formVars['acc_basetime']   = clean($_GET['acc_basetime'],  10);
        $formVars['acc_duration']   = clean($_GET['acc_duration'],  10);
        $formVars['acc_index']      = clean($_GET['acc_index'],     10);
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
        if ($formVars['acc_basetime'] == '') {
          $formVars['acc_basetime'] = 0;
        }
        if ($formVars['acc_index'] == '') {
          $formVars['acc_index'] = 0.0;
        }
        if ($formVars['acc_cost'] == '') {
          $formVars['acc_cost'] = 0;
        }
        if ($formVars['acc_page'] == '') {
          $formVars['acc_page'] = 0;
        }

        if (strlen($formVars['acc_type']) > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

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
            "acc_basetime    =   " . $formVars['acc_basetime']  . "," .
            "acc_duration    =   " . $formVars['acc_duration']  . "," .
            "acc_index       =   " . $formVars['acc_index']     . "," .
            "acc_cost        =   " . $formVars['acc_cost']      . "," .
            "acc_book        =   " . $formVars['acc_book']      . "," .
            "acc_page        =   " . $formVars['acc_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into accessory set acc_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update accessory set " . $q_string . " where acc_id = " . $formVars['id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['acc_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .=   "<th class=\"ui-state-default\" width=\"60\">Delete</th>\n";
      $output .=   "<th class=\"ui-state-default\">ID</th>\n";
      $output .=   "<th class=\"ui-state-default\">Total</th>\n";
      $output .=   "<th class=\"ui-state-default\">Type</th>\n";
      $output .=   "<th class=\"ui-state-default\">Class</th>\n";
      $output .=   "<th class=\"ui-state-default\">Accessory To</th>\n";
      $output .=   "<th class=\"ui-state-default\">Accessory Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Mount</th>\n";
      $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Capacity</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Street Index</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $q_string  = "select acc_id,sub_name,acc_class,class_name,acc_accessory,acc_name,acc_mount,acc_rating,acc_essence,";
      $q_string .= "acc_capacity,acc_avail,acc_perm,acc_basetime,acc_duration,acc_index,acc_cost,ver_book,acc_page ";
      $q_string .= "from accessory ";
      $q_string .= "left join subjects on subjects.sub_id = accessory.acc_type ";
      $q_string .= "left join class on class.class_id = accessory.acc_class ";
      $q_string .= "left join versions on versions.ver_id = accessory.acc_book ";
      $q_string .= "where ver_admin = 1 ";
      $q_string .= "order by sub_name,acc_type,acc_class,acc_name,acc_accessory,acc_rating,ver_version ";
      $q_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_accessory) > 0) {
        while ($a_accessory = mysqli_fetch_array($q_accessory)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.accessory.fill.php?id="  . $a_accessory['acc_id'] . "');jQuery('#dialogAccessory').dialog('open');return false;\">";
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

          $acc_mount = return_Mount($a_accessory['acc_mount']);

          $acc_essence = return_Essence($a_accessory['acc_essence']);

          $acc_rating = return_Rating($a_accessory['acc_rating']);

          $acc_capacity = return_Capacity($a_accessory['acc_capacity']);

          $acc_avail = return_Avail($a_accessory['acc_avail'], $a_accessory['acc_perm'], $a_accessory['acc_basetime'], $a_accessory['acc_duration']);

          $acc_index = return_StreetIndex($a_accessory['acc_index']);

          $acc_cost = return_Cost($a_accessory['acc_cost']);

          $acc_book = return_Book($a_accessory['ver_book'], $a_accessory['acc_page']);

          $class = return_Class($a_accessory['acc_perm']);

          $total = 0;
          $q_string  = "select r_acc_id ";
          $q_string .= "from r_accessory ";
          $q_string .= "where r_acc_number = " . $a_accessory['acc_id'] . " ";
          $q_r_accessory = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_r_accessory) > 0) {
            while ($a_r_accessory = mysqli_fetch_array($q_r_accessory)) {
              $total++;
            }
          }

          $output .= "<tr>\n";
          if ($total > 0) {
            $output .=   "<td class=\"" . $class . " delete\">In use</td>\n";
          } else {
            $output .=   "<td class=\"" . $class . " delete\">" . $linkdel                                                  . "</td>\n";
          }
          $output .= "  <td class=\"" . $class . " delete\">"              . $a_accessory['acc_id']                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $total                                                            . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_accessory['sub_name']                               . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $itemclass                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $accessory                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"                     . $a_accessory['acc_name']                                          . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $acc_mount                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $acc_essence                                                      . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $acc_rating                                                       . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $acc_capacity                                                     . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $acc_avail                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $acc_index                                                        . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $acc_cost                                                         . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">"              . $acc_book                                                         . "</td>\n";
          $output .= "</tr>\n";
        }
      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"15\">No records found.</td>\n";
        $output .= "</tr>\n";
      }

      $output .= "</table>\n";

      print "document.getElementById('mysql_table').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

      print "document.dialog.acc_accessory.value = '';\n";
      print "document.dialog.acc_name.value = '';\n";
      print "document.dialog.acc_mount.value = '';\n";
      print "document.dialog.acc_essence.value = '';\n";
      print "document.dialog.acc_rating.value = '';\n";
      print "document.dialog.acc_capacity.value = '';\n";
      print "document.dialog.acc_avail.value = '';\n";
      print "document.dialog.acc_perm.value = '';\n";
      print "document.dialog.acc_basetime.value = '';\n";
      print "document.dialog.acc_duration.value = 0;\n";
      print "document.dialog.acc_index.value = '';\n";
      print "document.dialog.acc_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
