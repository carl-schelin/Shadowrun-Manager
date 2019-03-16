<?php
if (isset($_SESSION['username'])) {
  print "<li id=\"tm_account\"><a href=\"" . $Siteroot . "/index.account.php\">Account (" . $_SESSION['username'] . ")</a>\n";
} else {
  print "<li id=\"tm_account\"><a href=\"" . $Siteroot . "/index.account.php\">Account</a>\n";
}
?>
    <ul>
<?php
  if (isset($_SESSION['username'])) {
?>
      <li><a href="<?php print $Usersroot;  ?>/users.php">Account Profile</a></li>
      <li><a href="<?php print $Bugroot; ?>/bugs.php">Report a Bug</a></li>
      <li><a href="<?php print $Featureroot; ?>/features.php">Request Enhancement</a></li>
      <li><a href="<?php print $FAQroot; ?>/answers.php">Ask a Question (FAQ)</a></li>
      <li><a href="<?php print $FAQroot; ?>/whatsnew.php">What's New?</a></li>
      <li><a href="<?php print $Knowroot; ?>/didyouknow.php">Did You Know?</a></li>
      <li><a href="<?php print $Loginroot; ?>/logout.php">Logout (<?php print $_SESSION['username']; ?>)</a></li>
<?php
    if (check_userlevel(2)) {
?>
      <li><a href="">-------------------------</a></li>
      <li><a href="<?php print $Usersroot; ?>/add.groups.php">Group Management</a></li>
<?php
    }
?>
<?php
    if (check_userlevel(1)) {
?>
      <li><a href="">-------------------------</a></li>
      <li><a href="<?php print $Usersroot; ?>/add.users.php">User Management</a></li>
      <li><a href="<?php print $Loginroot; ?>/assume.php">Change Credentials</a></li>
      <li><a href="<?php print $Usersroot; ?>/add.levels.php">Access Level Management</a></li>
      <li><a href="<?php print $Reportroot;  ?>/logs.php">View Month's Logs</a></li>
<?php
    }
  } else {
?>
      <li><a href="<?php print $Loginroot; ?>/login.php">Login</a></li>
<?php
  }
?>
    </ul>
  </li>
</ul>

</div>

</div>

<p></p>

