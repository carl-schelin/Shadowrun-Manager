<?php
# Script: bioware.fill.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: Fill in the forms for editing

  header('Content-Type: text/javascript');

  include('settings.php');
  $called = 'yes';
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

  if (isset($_SESSION['username'])) {
    $package = "bioware.fill.php";
    $formVars['id'] = 0;
    if (isset($_GET['id'])) {
      $formVars['id'] = clean($_GET['id'], 10);
    }

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Requesting record " . $formVars['id'] . " from r_bioware");

      $q_string  = "select r_bio_specialize,r_bio_number,r_bio_grade,bio_name,bio_rating,bio_essence ";
      $q_string .= "from r_bioware ";
      $q_string .= "left join bioware on bioware.bio_id = r_bioware.r_bio_number ";
      $q_string .= "where r_bio_id = " . $formVars['id'];
      $q_r_bioware = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_r_bioware = mysqli_fetch_array($q_r_bioware);
      mysql_free_result($q_r_bioware);

      $rating = return_Rating($a_r_bioware['bio_rating']);

      $essence = return_Essence($a_r_bioware['bio_essence']);

      $bio_name = $a_r_bioware['bio_name'] . " [Rating: " . $rating . ", Essence: " . $essence . "]";

      print "document.getElementById('r_bio_item').innerHTML = '" . mysql_real_escape_string($bio_name) . "';\n\n";
      print "document.edit.r_bio_number.value = '"     . mysql_real_escape_string($a_r_bioware['r_bio_number'])     . "';\n\n";
      print "document.edit.r_bio_grade.value = '"      . mysql_real_escape_string($a_r_bioware['r_bio_grade'])      . "';\n\n";
      print "document.edit.r_bio_specialize.value = '" . mysql_real_escape_string($a_r_bioware['r_bio_specialize']) . "';\n\n";

      print "document.edit.r_bio_id.value = " . $formVars['id'] . ";\n";
      print "document.edit.r_bio_update.disabled = false;\n\n";

    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
