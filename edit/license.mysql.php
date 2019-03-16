<?php
# Script: license.mysql.php
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
    $package = "license.mysql.php";
    $formVars['update']              = clean($_GET['update'],             10);
    $formVars['lic_character']       = clean($_GET['lic_character'],      10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0 || $formVars['update'] == 1) {
        $formVars['lic_id']         = clean($_GET['id'],               10);
        $formVars['lic_type']       = clean($_GET['lic_type'],         80);
        $formVars['lic_rating']     = clean($_GET['lic_rating'],       10);
        $formVars['lic_identity']   = clean($_GET['lic_identity'],     10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['lic_rating'] == '') {
          $formVars['lic_rating'] = 0;
        }

        if (strlen($formVars['lic_type']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "lic_character  =   " . $formVars['lic_character']   . "," .
            "lic_type       = \"" . $formVars['lic_type']        . "\"," .
            "lic_rating     = \"" . $formVars['lic_rating']      . "\"," .
            "lic_identity   =   " . $formVars['lic_identity'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_license set lic_id = NULL," . $q_string;
            $message = "License added.";
          }
          if ($formVars['update'] == 1) {
            $query = "update r_license set " . $q_string . " where lic_id = " . $formVars['lic_id'];
            $message = "License updated.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['lic_type']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }


      if ($formVars['update'] == -2) {
        $formVars['copyfrom'] = clean($_GET['lic_copyfrom'], 10);

        if ($formVars['copyfrom'] > 0) {
          $q_string  = "select lic_type ";
          $q_string .= "from r_license ";
          $q_string .= "where r_lic_character = " . $formVars['copyfrom'];
          $q_r_license = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
          while ($a_r_license = mysql_fetch_array($q_r_license)) {

            $q_string =
              "lic_character     =   " . $formVars['lic_character'] . "," .
              "lic_type          = \"" . $a_r_license['lic_type']   . "\"";
  
            $query = "insert into r_license set r_lic_id = NULL, " . $q_string;
            mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));
          }
        }
      }

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
