<br>

<?php
  if (isset($_SESSION['username'])) {
    $q_string  = "select grp_name ";
    $q_string .= "from members ";
    $q_string .= "left join groups on groups.grp_id = members.mem_group ";
    $q_string .= "left join runners on runners.runr_id = members.mem_runner ";
    $q_string .= "where mem_invite = 1 and runr_owner = " . $_SESSION['uid'] . " ";
    $q_string .= "group by grp_name ";
    $q_members = mysqli_query($db, $q_string) or die($q_string . ": " . mysqli_error($db));
    if (mysqli_num_rows($q_members) > 0) {
      print "<p style=\"text-align: center;\">Your characters are members of the following groups: ";
      while ($a_groups = mysqli_fetch_array($q_members)) {
        print "<u>" . $a_groups['grp_name'] . "</u>, ";
      }
      print "</p>\n";
    } else {
      print "<p style=\"text-align: center;\">None of your characters belong to a group.</p>\n";
    }
  } else {
    print "<p style=\"text-align: center;\">You are not currently logged in.</p>\n";
  }
?>
