<?php
# Script: members.mysql.php
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
    $package = "members.mysql.php";
    $formVars['mem_id']    = clean($_GET['mem_id'], 10);
    $formVars['status']    = clean($_GET['status'], 10);

    if (check_userlevel(3)) {
      logaccess($_SESSION['username'], $package, "Building the query.");

      $headers  = "From: Mooks Manager <jackpoint@schelin.org>\r\n";

# get user's email for the CC
      $q_string  = "select usr_first,usr_email ";
      $q_string .= "from members ";
      $q_string .= "left join runners on runners.runr_id = members.mem_runner ";
      $q_string .= "left join users on users.usr_id = runners.runr_owner ";
      $q_string .= "where mem_id = " . $formVars['mem_id'] . " ";
      $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_users = mysqli_fetch_array($q_users);

      $headers .= "CC: " . $a_users['usr_email'] . "\r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

      $q_string  = "select grp_name,usr_email ";
      $q_string .= "from members ";
      $q_string .= "left join groups on groups.grp_id = members.mem_group ";
      $q_string .= "left join users on users.usr_id = groups.grp_owner ";
      $q_string .= "where mem_id = " . $formVars['mem_id'] . " ";
      $q_members = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
      $a_members = mysqli_fetch_array($q_members);

      $headers .= "Reply-To: " . $a_members['usr_email'] . "\r\n";
#      $headers .= "Disposition-Notification-To: $dm_gsxr@yahoo.com\r\n";

      $q_string = "mem_invite = 2";
      $message = "Membership pending.";
      if ($formVars['status'] == 1) {
        $q_string = "mem_invite = 1";
        $message = "Membership accepted.";

        $subject = "Game invitation accepted.";

        $body  = "<p>" . $a_users['usr_first'] . " has accepted your invitation and is now a member of " . $a_members['grp_name'] . "</p>\n";
        $body .= "<br>This mail box is not monitored, replies will be ignored.</p>\n\n";

      }
      if ($formVars['status'] == 0) {
        $q_string = "mem_invite = 0";
        $message = "Membership declined.";

        $subject = "Game invitation declined.";

        $body  = "<p>" . $a_users['usr_first'] . " has declined your invitation to join the " . $a_members['grp_name'] . " group.</p>\n";
        $body .= "<br>This mail box is not monitored, replies will be ignored.</p>\n\n";

      }

      $query = "update members set " . $q_string . " where mem_id = " . $formVars['mem_id'];

      logaccess($_SESSION['username'], $package, "Saving Changes to: " . $formVars['mem_group']);

      mysql_query($query) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

      print "alert('" . $message . "');\n";

      if ($formVars['status'] == 0) {
        print "document.user.btndecline.disabled = true;\n";
        print "document.user.btnaccept.disabled = false;\n";

        if ($a_members['usr_email'] == '') {
          print "alert('No e-mail address can be located for the group owner so no email will be sent.')";
        } else {
          mail($a_members['usr_email'], $subject, $body, $headers);
        }

      }
      if ($formVars['status'] == 1) {
        print "document.user.btnaccept.disabled = true;\n";
        print "document.user.btndecline.disabled = false;\n";

        if ($a_members['usr_email'] == '') {
          print "alert('No e-mail address can be located for the group owner so no email will be sent.')";
        } else {
          mail($a_members['usr_email'], $subject, $body, $headers);
        }

      }
    } else {
      logaccess($_SESSION['username'], $package, "Unauthorized access.");
    }
  }
?>
