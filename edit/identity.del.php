<?php
# Script: identity.del.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Delete association entries

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "identity.del.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Deleting " . $formVars['id'] . " from r_identity");

      $q_string  = "delete ";
      $q_string .= "from r_identity ";
      $q_string .= "where id_id= " . $formVars['id'] . " ";
      $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

      print "alert('Identity deleted.');\n";

      $q_string  = "select lic_id ";
      $q_string .= "from r_license ";
      $q_string .= "where lic_identity = " . $formVars['id'] . " ";
      $q_r_license = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      if (mysql_num_rows($q_r_license) > 0) {

        $q_string  = "delete ";
        $q_string .= "from r_license ";
        $q_string .= "where lic_identity = " . $formVars['id'] . " ";

        $insert = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));

        print "alert('" . mysql_num_rows($q_r_license) . " license(s) deleted.');\n";
      }
    } else {
      logaccess($_SESSION['username'], $package, "Access denied");
    }
  }
?>
