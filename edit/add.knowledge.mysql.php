<?php
# Script: add.knowledge.mysql.php
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
    $package = "add.knowledge.mysql.php";
    $formVars['update']        = clean($_GET['update'],       10);
    $formVars['know_name']     = clean($_GET['know_name'],    50);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0) {
        $formVars['id']               = clean($_GET['id'],             10);
        $formVars['know_attribute']   = clean($_GET['know_attribute'], 10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if (strlen($formVars['know_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "know_name        = \"" . $formVars['know_name']      . "\"," .
            "know_attribute   =   " . $formVars['know_attribute'];

          if ($formVars['update'] == 0) {
            $query = "insert into knowledge set know_id = NULL, " . $q_string;
            $message = "Knowledge Skill added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['know_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }

      print "document.edit.know_name.value = '';\n";
      print "document.edit.know_attribute[0].selected = true;\n";

# rebuild the knowledge skill drop down in case of changes in the listing
      print "var selbox = document.edit.r_know_number;\n\n";
      print "selbox.options.length = 0;\n";
      print "selbox.options[selbox.options.length] = new Option(\"None\",0);\n";

      $q_string  = "select know_id,know_name ";
      $q_string .= "from knowledge ";
      $q_string .= "order by know_name ";
      $q_knowledge = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      while ($a_knowledge = mysqli_fetch_array($q_knowledge)) {
        print "selbox.options[selbox.options.length] = new Option(\"" . htmlspecialchars($a_knowledge['know_name']) . "\"," . $a_knowledge['know_id'] . ");\n";
      }

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
