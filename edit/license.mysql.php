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

    if (check_userlevel($db, $AL_Shadowrunner)) {
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
          logaccess($db, $_SESSION['username'], $package, "Building the query.");

          $q_string =
            "lic_character  =   " . $formVars['lic_character']   . "," .
            "lic_type       = \"" . $formVars['lic_type']        . "\"," .
            "lic_rating     = \"" . $formVars['lic_rating']      . "\"," .
            "lic_identity   =   " . $formVars['lic_identity'];

          if ($formVars['update'] == 0) {
            $query = "insert into r_license set lic_id = NULL," . $q_string;
          }
          if ($formVars['update'] == 1) {
            $query = "update r_license set " . $q_string . " where lic_id = " . $formVars['lic_id'];
          }

          logaccess($db, $_SESSION['username'], $package, "Saving Changes to: " . $formVars['lic_type']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }
    } else {
      logaccess($db, $_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
