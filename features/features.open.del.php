<?php
# Script: features.open.del.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "features.open.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel($db, $AL_Guest)) {
      $q_string  = "select feat_id ";
      $q_string .= "from features_detail ";
      $q_string .= "where feat_feat_id = " . $formVars['id'];
      $q_features_detail = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      while ($a_features_detail = mysqli_fetch_array($q_features_detail)) {

        logaccess($db, $_SESSION['username'], $package, "Deleting " . $a_features_detail['feat_id'] . " from features_detail");

        $q_string  = "delete ";
        $q_string .= "from features_detail ";
        $q_string .= "where feat_id = " . $a_features_detail['feat_id'];
        $result = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
      }

      logaccess($db, $_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from features");

      $q_string  = "delete ";
      $q_string .= "from features ";
      $q_string .= "where feat_id = " . $formVars['id'];
      $insert = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));

      print "alert('Feature Request deleted.');\n";

      print "clear_fields();\n";
    } else {
      logaccess($db, $_SESSION['username'], $package, "Access denied");
    }
  }
?>
