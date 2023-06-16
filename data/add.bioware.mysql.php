<?php
# Script: add.bioware.mysql.php
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
    $package = "add.bioware.mysql.php";
    $formVars['update']         = clean($_GET['update'],         10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(1)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['id']           = clean($_GET['id'],           10);
        $formVars['bio_class']    = clean($_GET['bio_class'],    30);
        $formVars['bio_name']     = clean($_GET['bio_name'],     50);
        $formVars['bio_rating']   = clean($_GET['bio_rating'],   10);
        $formVars['bio_essence']  = clean($_GET['bio_essence'],  10);
        $formVars['bio_avail']    = clean($_GET['bio_avail'],    10);
        $formVars['bio_perm']     = clean($_GET['bio_perm'],     10);
        $formVars['bio_cost']     = clean($_GET['bio_cost'],     10);
        $formVars['bio_book']     = clean($_GET['bio_book'],     10);
        $formVars['bio_page']     = clean($_GET['bio_page'],     10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['bio_rating'] == '') {
          $formVars['bio_rating'] = 0;
        }
        if ($formVars['bio_essence'] == '') {
          $formVars['bio_essence'] = 0.0;
        }
        if ($formVars['bio_avail'] == '') {
          $formVars['bio_avail'] = 0;
        }
        if ($formVars['bio_cost'] == '') {
          $formVars['bio_cost'] = 0;
        }
        if ($formVars['bio_page'] == '') {
          $formVars['bio_page'] = 0;
        }

        if (strlen($formVars['bio_class']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string = 
            "bio_class   = \"" . $formVars['bio_class']   . "\"," .
            "bio_name    = \"" . $formVars['bio_name']    . "\"," .
            "bio_rating  =   " . $formVars['bio_rating']  . "," .
            "bio_essence =   " . $formVars['bio_essence'] . "," .
            "bio_avail   =   " . $formVars['bio_avail']   . "," .
            "bio_perm    = \"" . $formVars['bio_perm']    . "\"," .
            "bio_cost    =   " . $formVars['bio_cost']    . "," .
            "bio_book    = \"" . $formVars['bio_book']    . "\"," .
            "bio_page    =   " . $formVars['bio_page'];

          if ($formVars['update'] == 0) {
            $query = "insert into bioware set bio_id = NULL, " . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update bioware set " . $q_string . " where bio_id = " . $formVars['id'];
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['bio_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      logaccess($_SESSION['username'], $package, "Creating the table for viewing.");

      $bioware_list = array("basic", "biosculpting", "cosmetic", "cultured", "endosymbiont", "leech");

      foreach ($bioware_list as &$bioware) {

        $output  = "<p></p>\n";
        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .= "  <th class=\"ui-state-default\">" . ucfirst($bioware) . " Bioware Listing</th>\n";
        $output .= "  <th class=\"ui-state-default\" width=\"20\"><a href=\"javascript:;\" onmousedown=\"toggleDiv('" . $bioware . "-listing-help');\">Help</a></th>\n";
        $output .= "</tr>\n";
        $output .= "</table>\n";

        $output .= "<div id=\"" . $bioware . "-listing-help\" style=\"display: none\">\n";

        $output .= "<div class=\"main-help ui-widget-content\">\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Bioware Listing</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li><strong>Remove</strong> - Click here to delete this Bioware from the Mooks Database.</li>\n";
        $output .= "    <li><strong>Editing</strong> - Click on a Bioware to toggle the form and edit the Bioware.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "<ul>\n";
        $output .= "  <li><strong>Notes</strong>\n";
        $output .= "  <ul>\n";
        $output .= "    <li>Click the <strong>Bioware Management</strong> title bar to toggle the <strong>Bioware Form</strong>.</li>\n";
        $output .= "  </ul></li>\n";
        $output .= "</ul>\n";

        $output .= "</div>\n";

        $output .= "</div>\n";

        $output .= "<table class=\"ui-styled-table\" width=\"100%\">\n";
        $output .= "<tr>\n";
        $output .=   "<th class=\"ui-state-default\" width=\"160\">Delete</th>\n";
        $output .=   "<th class=\"ui-state-default\">ID</th>\n";
        $output .=   "<th class=\"ui-state-default\">Total</th>\n";
        $output .=   "<th class=\"ui-state-default\">Name</th>\n";
        $output .=   "<th class=\"ui-state-default\">Rating</th>\n";
        $output .=   "<th class=\"ui-state-default\">Essence</th>\n";
        $output .=   "<th class=\"ui-state-default\">Availability</th>\n";
        $output .=   "<th class=\"ui-state-default\">Cost</th>\n";
        $output .=   "<th class=\"ui-state-default\">Book/Page</th>\n";
        $output .= "</tr>\n";

        if ($bioware == "basic") {
          $bioware_group = "Basic Bioware";
        }
        if ($bioware == "biosculpting") {
          $bioware_group = "Biosculpting";
        }
        if ($bioware == "cosmetic") {
          $bioware_group = "Cosmetic";
        }
        if ($bioware == "cultured") {
          $bioware_group = "Cultured Bioware";
        }
        if ($bioware == "endosymbiont") {
          $bioware_group = "Endosymbiont";
        }
        if ($bioware == "leech") {
          $bioware_group = "Leech Symbiont";
        }

        $nuyen = '&yen;';
        $q_string  = "select bio_id,bio_name,bio_rating,bio_essence,bio_avail,bio_perm,bio_cost,ver_book,bio_page ";
        $q_string .= "from bioware ";
        $q_string .= "left join class on class.class_id = bioware.bio_class ";
        $q_string .= "left join versions on versions.ver_id = bioware.bio_book ";
        $q_string .= "where class_name = \"" . $bioware_group . "\" and ver_admin = 1 "; 
        $q_string .= "order by class_name,bio_name,bio_rating,ver_version ";
        $q_bioware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
        if (mysql_num_rows($q_bioware) > 0) {
          while ($a_bioware = mysql_fetch_array($q_bioware)) {

            $linkstart = "<a href=\"#\" onclick=\"javascript:show_file('add.bioware.fill.php?id="  . $a_bioware['bio_id'] . "');jQuery('#dialogBioWare').dialog('open');return false;\">";
            $linkdel   = "<input type=\"button\" value=\"Remove\" onClick=\"javascript:delete_bioware('add.bioware.del.php?id=" . $a_bioware['bio_id'] . "');\">";
            $linkend = "</a>";

            $rating = return_Rating($a_bioware['bio_rating']);

            $essence = return_Essence($a_bioware['bio_essence']);

            $avail = return_Avail($a_bioware['bio_avail'], $a_bioware['bio_perm']);

            $cost = return_Essence($a_bioware['bio_cost']);

            $book = return_Avail($a_bioware['ver_book'], $a_bioware['bio_page']);

            $class = return_Class($a_bioware['bio_perm']);

            $total = 0;
            $q_string  = "select r_bio_id ";
            $q_string .= "from r_bioware ";
            $q_string .= "where r_bio_number = " . $a_bioware['bio_id'] . " ";
            $q_r_bioware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
            if (mysql_num_rows($q_r_bioware) > 0) {
              while ($a_r_bioware = mysql_fetch_array($q_r_bioware)) {
                $total++;
              }
            }

            $output .= "<tr>\n";
            if ($total > 0) {
              $output .=   "<td class=\"ui-widget-content delete\">In use</td>\n";
            } else {
              $output .=   "<td class=\"ui-widget-content delete\">" . $linkdel                                                  . "</td>\n";
            }
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $a_bioware['bio_id']              . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\" width=\"60\">" . $total                            . "</td>\n";
            $output .= "  <td class=\"" . $class . "\">"        . $linkstart . $a_bioware['bio_name'] . $linkend . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $rating                           . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $essence                          . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $avail                            . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $cost                             . "</td>\n";
            $output .= "  <td class=\"" . $class . " delete\">"              . $book                             . "</td>\n";
            $output .= "</tr>\n";
          }
        } else {
          $output .= "<tr>\n";
          $output .= "  <td class=\"ui-widget-content\" colspan=\"8\">No records found.</td>\n";
          $output .= "</tr>\n";
        }

        $output .= "</table>\n";

        print "document.getElementById('" . $bioware . "_table').innerHTML = '" . mysql_real_escape_string($output) . "';\n\n";
      }

      print "document.dialog.bio_name.value = '';\n";
      print "document.dialog.bio_rating.value = '';\n";
      print "document.dialog.bio_essence.value = '';\n";
      print "document.dialog.bio_avail.value = '';\n";
      print "document.dialog.bio_perm.value = '';\n";
      print "document.dialog.bio_cost.value = '';\n";

      print "$(\"#button-update\").button(\"disable\");\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }

?>
