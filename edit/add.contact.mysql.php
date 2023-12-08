<?php
# Script: add.contact.mysql.php
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
    $package = "add.contact.mysql.php";
    $formVars['update']        = clean($_GET['update'],       10);

    if ($formVars['update'] == '') {
      $formVars['update'] = -1;
    }

    if (check_userlevel(3)) {
      if ($formVars['update'] == 0) {
        $formVars['id']               = clean($_GET['id'],             10);
        $formVars['con_name']         = clean($_GET['con_name'],       60);
        $formVars['con_archetype']    = clean($_GET['con_archetype'],  60);
        $formVars['con_book']         = clean($_GET['con_book'],       10);
        $formVars['con_page']         = clean($_GET['con_page'],       10);
        $formVars['con_owner']        = clean($_SESSION['uid'],        10);

        if ($formVars['id'] == '') {
          $formVars['id'] = 0;
        }
        if ($formVars['con_page'] == '') {
          $formVars['con_page'] = 0;
        }

        if (strlen($formVars['con_name']) > 0) {
          logaccess($_SESSION['username'], $package, "Building the query.");

          $q_string =
            "con_name        = \"" . $formVars['con_name']      . "\"," .
            "con_archetype   = \"" . $formVars['con_archetype'] . "\"," . 
            "con_book        = \"" . $formVars['con_book']      . "\"," . 
            "con_page        =   " . $formVars['con_page']      . "," . 
            "con_owner       =   " . $formVars['con_owner'];

          if ($formVars['update'] == 0) {
            $query = "insert into contact set con_id = NULL, " . $q_string;
            $message = "Contact added.";
          }

          logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['con_name']);

          mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

          print "alert('" . $message . "');\n";
        } else {
          print "alert('You must input data before saving changes.');\n";
        }
      }

      print "document.edit.con_name.value = '';\n";
      print "document.edit.con_archetype.value = '';\n";
      print "document.edit.con_book.value = '';\n";
      print "document.edit.con_page.value = '';\n";

# rebuild the knowledge skill drop down in case of changes in the listing
      print "var selbox = document.edit.r_con_number;\n\n";
      print "selbox.options.length = 0;\n";
      print "selbox.options[selbox.options.length] = new Option(\"None\",0);\n";

      $q_string  = "select con_id,con_name,con_archetype ";
      $q_string .= "from contact ";
      $q_string .= "order by con_archetype ";
      $q_contact = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      while ($a_contact = mysqli_fetch_array($q_contact)) {
        print "selbox.options[selbox.options.length] = new Option(\"" . htmlspecialchars($a_contact['con_archetype'] . " (" . $a_contact['con_name'] . ")") . "\"," . $a_contact['con_id'] . ");\n";
      }

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
