<?php
# Script: bugs.mysql.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Fixer);

  $package = "bugs.mysql.php";

  $formVars['bug_module']        = clean($_POST['bug_module'],       10);
  $formVars['bug_severity']      = clean($_POST['bug_severity'],     10);
  $formVars['bug_priority']      = clean($_POST['bug_priority'],     10);
  $formVars['bug_discovered']    = clean($_POST['bug_discovered'],   15);
  $formVars['bug_openby']        = clean($_POST['bug_openby'],       10);
  $formVars['bug_subject']       = clean($_POST['bug_subject'],     255);

  logaccess($db, $_SESSION['username'], $package, "Creating a new record.");

  if (strlen($formVars['bug_subject']) > 0) {

    $query = "insert into bugs set bug_id = NULL, " . 
      "bug_module     =   " . $formVars['bug_module']     . "," . 
      "bug_severity   =   " . $formVars['bug_severity']   . "," . 
      "bug_priority   =   " . $formVars['bug_priority']   . "," . 
      "bug_discovered = \"" . $formVars['bug_discovered'] . "\"," . 
      "bug_openby     =   " . $formVars['bug_openby']     . "," . 
      "bug_timestamp  = \"" . date("Y-m-d H:i:s")         . "\"," . 
      "bug_subject    = \"" . $formVars['bug_subject']    . "\"";

    logaccess($db, $_SESSION['username'], $package, "Adding detail: " . $formVars['bug_module']);

    $result = mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

    $query = "select last_insert_id($db)";
    $q_result = mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));
    $a_result = mysqli_fetch_array($q_result);

    $bug = $a_result['last_insert_id($db)'];

    $q_string = 
      "bug_bug_id    =   " . $bug                     . "," . 
      "bug_text      = \"" . $formVars['bug_subject'] . "\"," . 
      "bug_user      =   " . $formVars['bug_openby'];

    $query = "insert into bugs_detail set bug_id = NULL," . $q_string;

    $result = mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysqli_error($db)));

    $url = $Bugroot . "/ticket.php?id=" . $bug . "#problem";

  } else {
    $url = $Bugroot . "/bugs.php";
  }

  header('Location: ' . $url);

?>
