<?php
# Script: add.language.mysql.php
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
    $package = "add.language.mysql.php";
    $formVars['update']        = clean($_GET['update'],       10);
    $formVars['lang_name']     = clean($_GET['lang_name'],    50);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0) {
        $formVars['id']               = clean($_GET['id'],             10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }

        if (strlen($formVars['lang_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "lang_name        = \"" . $formVars['lang_name']      . "\"," .
            "lang_attribute   =   " . "1";

          if ($formVars['update'] == 0) {
            $query = "insert into language set lang_id = NULL, " . $q_string;
            $message = "Language added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['lang_name']);

          mysqli_query($db, $query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }

      print "document.edit.lang_name.value = '';\n";

# rebuild the language drop down in case of changes in the listing
      print "var selbox = document.edit.r_lang_number;\n\n";
      print "selbox.options.length = 0;\n";
      print "selbox.options[selbox.options.length] = new Option(\"None\",0);\n";

      $q_string  = "select lang_id,lang_name ";
      $q_string .= "from language ";
      $q_string .= "order by lang_name ";
      $q_language = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      while ($a_language = mysqli_fetch_array($q_language)) {
        print "selbox.options[selbox.options.length] = new Option(\"" . htmlspecialchars($a_language['lang_name']) . "\"," . $a_language['lang_id'] . ");\n";
      }

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
