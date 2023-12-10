<?php
# Script: bioware.mysql.php
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
    $package = "bioware.mysql.php";
    if (isset($_GET['update'])) {
      $formVars['update'] = clean($_GET['update'], 10);
    } else {
      $formVars['update'] = -1;
    }
    if (isset($_GET['r_bio_id'])) {
      $formVars['r_bio_id'] = clean($_GET['r_bio_id'], 10);
    } else {
      $formVars['r_bio_id'] = 0;
    }
    if (isset($_GET['r_bio_character'])) {
      $formVars['r_bio_character'] = clean($_GET['r_bio_character'], 10);
    } else {
      $formVars['r_bio_character'] = 0;
    }

    if (check_userlevel($db, $AL_Shadowrunner)) {
      if ($formVars['update'] == 0 | $formVars['update'] == 1) {
        $formVars['r_bio_number']      = clean($_GET['r_bio_number'],       10);
        $formVars['r_bio_specialize']  = clean($_GET['r_bio_specialize'],   60);
        $formVars['r_bio_grade']       = clean($_GET['r_bio_grade'],        10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['r_bio_number'] == '') {
          $formVars['r_bio_number'] = 1;
        }

# default is Standard; id == 1
        if ($formVars['r_bio_grade'] == '') {
          $formVars['r_bio_grade'] = 1;
        }

        if ($formVars['r_bio_number'] > 0) {
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string =
            "r_bio_character   =   " . $formVars['r_bio_character']   . "," .
            "r_bio_number      =   " . $formVars['r_bio_number']      . "," .
            "r_bio_grade       =   " . $formVars['r_bio_grade']       . "," .
            "r_bio_specialize  = \"" . $formVars['r_bio_specialize']  . "\"";

          if ($formVars['update'] == 0) {
            $query = "insert into r_bioware set r_bio_id = NULL," . $q_string;
            $message = "Bioware added.";
          }

          if ($formVars['update'] == 1) {
            $query = "update r_bioware set " . $q_string . " where r_bio_id = " . $formVars['r_bio_id'];
            $message = "Bioware updated.";
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['r_bio_number']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      if ($formVars['update'] == -3) {

        $output  = "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"button ui-widget-content\">\n";
        $output .= "<input type=\"button\" name=\"r_bio_refresh\" value=\"Refresh My Bioware Listing\" onClick=\"javascript:attach_bioware('bioware.mysql.php', -1);\">\n";
        $output .= "<input type=\"button\" name=\"r_bio_update\"  value=\"Update Bioware\"          onClick=\"javascript:attach_bioware('bioware.mysql.php', 1);hideDiv('bioware-hide');\">\n";
        $output .= "<input type=\"hidden\" name=\"r_bio_id\"      value=\"0\">\n";
        $output .= "<input type=\"hidden\" name=\"r_bio_number\"  value=\"0\">\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\" colspan=\"3\">Active Bioware Form</th>\n";
        $output .= "</tr>\n";
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\">Active Bioware: <span id=\"r_bio_item\">None Selected</span></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Description: <input type=\"text\" name=\"r_bio_specialize\" size=\"30\"></td>\n";
        $output .= "  <td class=\"ui-widget-content\">Grade: <select name=\"r_bio_grade\">\n";

        $q_string  = "select grade_id,grade_name ";
        $q_string .= "from grades ";
        $q_string .= "order by grade_essence desc ";
        $q_grades = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
        while ($a_grades = mysqli_fetch_array($q_grades)) {
          $output .= "<option value=\"" . $a_grades['grade_id'] . "\">" . $a_grades['grade_name'] . "</option>\n";
        }
        $output .= "</select></td>\n";

        $output .= "</tr>\n";
        $output .= "</table>\n";

        print "document.getElementById('bioware_form').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";


        $bioware_list = array("basic", "biosculpting", "cosmetic", "cultured", "endosymbiont", "leech");

        foreach ($bioware_list as &$bioware) {

          $output  = "<p></p>\n";
          $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
          $output .= "<tr>\n";
          $output .=   "<th class=\"ui-state-default\">Bioware</th>\n";
          $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $bioware . "-listing-help');\">Help</a></th>\n";
          $output .= "</tr>\n";
          $output .= "</table>\n";

          $output .= "<div id=\"" . $bioware . "-listing-help\" style=\"display: none\">\n";

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
          $output .=   "<th class=\"ui-state-default\">Class</th>\n";
          $output .=   "<th class=\"ui-state-default\">Name</th>\n";
          $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
          $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
          $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
          $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
          $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
          $output .= "</tr>\n";

          $q_string  = "select bio_id,class_name,bio_name,bio_rating,bio_essence,";
          $q_string .= "bio_avail,bio_perm,bio_cost,ver_book,bio_page ";
          $q_string .= "from bioware ";
          $q_string .= "left join class on class.class_id = bioware.bio_class ";
          $q_string .= "left join versions on versions.ver_id = bioware.bio_book ";
          $q_string .= "where class_name like \"" . $bioware . "%\" and ver_active = 1 ";
          $q_string .= "order by bio_name,bio_rating,class_name,ver_version ";
          $q_bioware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
          if (mysqli_num_rows($q_bioware) > 0) {
            while ($a_bioware = mysqli_fetch_array($q_bioware)) {

# this adds the bio_id to the r_bio_character
              $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('bioware.mysql.php?update=0&r_bio_character=" . $formVars['r_bio_character'] . "&r_bio_number=" . $a_bioware['bio_id'] . "');\">";
              $linkend = "</a>";

              $bio_rating = return_Rating($a_bioware['bio_rating']);

              $bio_essence = return_Essence($a_bioware['bio_essence']);

              $bio_avail = return_Avail($a_bioware['bio_avail'], $a_bioware['bio_perm']);

              $bio_cost = return_Cost($a_bioware['bio_cost']);

              $bio_book = return_Book($a_bioware['ver_book'], $a_bioware['bio_page']);

              $class = return_Class($a_bioware['bio_perm']);

              $output .= "<tr>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_bioware['class_name'] . $linkend . "</td>\n";
              $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_bioware['bio_name']   . $linkend . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $bio_rating                         . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $bio_essence                        . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $bio_avail                          . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $bio_cost                           . "</td>\n";
              $output .= "  <td class=\"" . $class . " delete\">"              . $bio_book                           . "</td>\n";
              $output .= "</tr>\n";
            }
          } else {
            $output .= "<tr>\n";
            $output .= "  <td class=\"ui-widget-content\" colspan=\"7\">No records found.</td>\n";
            $output .= "</tr>\n";
          }

          $output .= "</table>\n";

          print "document.getElementById('" . $bioware . "_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";
        }
      }


      logaccess($db, $_SESSION['username'], $package, "Creating the table for viewing.");

      $output  = "<p></p>\n";
      $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
      $output .= "<tr>\n";
      $output .= "  <th class=\"ui-state-default\">My Bioware</th>\n";
      $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('my-bioware-listing-help');\">Help</a></th>\n";
      $output .= "</tr>\n";
      $output .= "</table>\n";

      $output .= "<div id=\"my-bioware-listing-help\" style=\"display: none\">\n";

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
      $output .=   "<th class=\"ui-state-default\">Del</th>\n";
      $output .=   "<th class=\"ui-state-default\">Class</th>\n";
      $output .=   "<th class=\"ui-state-default\">Name</th>\n";
      $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
      $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
      $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
      $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
      $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
      $output .= "</tr>\n";

      $costgrade = 0;
      $costtotal = 0;
      $essencegrade = 0;
      $essencetotal = 0;
      $q_string  = "select r_bio_id,r_bio_specialize,bio_id,bio_class,class_name,bio_name,bio_rating,bio_essence,";
      $q_string .= "bio_avail,bio_perm,bio_cost,ver_book,bio_page,grade_name,grade_essence,grade_avail,grade_cost ";
      $q_string .= "from r_bioware ";
      $q_string .= "left join bioware on bioware.bio_id = r_bioware.r_bio_number ";
      $q_string .= "left join class on class.class_id = bioware.bio_class ";
      $q_string .= "left join grades on grades.grade_id = r_bioware.r_bio_grade ";
      $q_string .= "left join versions on versions.ver_id = bioware.bio_book ";
      $q_string .= "where r_bio_character = " . $formVars['r_bio_character'] . " ";
      $q_string .= "order by bio_name,bio_rating,bio_class,ver_version ";
      $q_r_bioware = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      if (mysqli_num_rows($q_r_bioware) > 0) {
        while ($a_r_bioware = mysqli_fetch_array($q_r_bioware)) {

          $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('bioware.fill.php?id=" . $a_r_bioware['r_bio_id'] . "');showDiv('bioware-hide');\">";
          $linkdel = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_bioware('bioware.del.php?id="  . $a_r_bioware['r_bio_id'] . "');\">";
          $linkend = "</a>";

          $bio_name = $a_r_bioware['bio_name'];
          if ($a_r_bioware['r_bio_specialize'] != '') {
            $bio_name = $a_r_bioware['bio_name'] . " (" . $a_r_bioware['r_bio_specialize'] . ")";
          }

          $grade = '';
          if ($a_r_bioware['grade_essence'] != 1.00) {
            $grade = " (" . $a_r_bioware['grade_name'] . ")";
          }

          $bio_rating = return_Rating($a_r_bioware['bio_rating']);

          $essencegrade = ($a_r_bioware['bio_essence'] * $a_r_bioware['grade_essence']);
          $bio_essence = return_Essence($essencegrade);
          $essencetotal += $essencegrade;

          $bio_avail = return_Avail($a_r_bioware['bio_avail'], $a_r_bioware['bio_perm']);

          $costgrade = ($a_r_bioware['bio_cost'] * $a_r_bioware['grade_cost']);
          $costtotal += $costgrade;

          $bio_cost = return_Cost($costgrade);

          $bio_book = return_Book($a_r_bioware['ver_book'], $a_r_bioware['bio_page']);

          $class = "ui-widget-content";
          if (isset($formVars['r_bio_number']) && $formVars['r_bio_number'] == $a_r_bioware['bio_id']) {
            $class = "ui-state-error";
          }

          $output .= "<tr>\n";
          $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $linkdel                      . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $a_r_bioware['class_name']                 . "</td>\n";
          $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $bio_name . $grade . $linkend . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $bio_rating                                . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $bio_essence                               . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $bio_avail                                 . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $bio_cost                                  . "</td>\n";
          $output .= "  <td class=\"" . $class . " delete\">" . $bio_book                                  . "</td>\n";
          $output .= "</tr>\n";

        }

        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">Total Essence: " . return_Essence($essencetotal) . ", Total Cost: " . return_Cost($costtotal) . "</td>\n";
        $output .= "</tr>\n";

      } else {
        $output .= "<tr>\n";
        $output .= "  <td class=\"ui-widget-content\" colspan=\"9\">No Bioware added.</td>\n";
        $output .= "</tr>\n";
      }
      $output .= "</table>\n";

      mysqli_free_result($q_r_bioware);

      print "document.getElementById('my_bioware_mysql').innerHTML = '" . mysqli_real_escape_string($db, $output) . "';\n\n";

    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
