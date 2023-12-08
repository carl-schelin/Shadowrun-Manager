<?php
# Script: error.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  include($Sitepath . "/guest.php");

  $package = "error.php";

  logaccess($formVars['uid'], $package, "Accessing the script.");

  $formVars['script'] = clean($_GET['script'], 60);
  $formVars['error']  = mysql_real_escape_string(clean($_GET['error'], 1024));
  $formVars['mysql']  = mysql_real_escape_string(clean($_GET['mysql'], 1024));

# add a bug report to the bug tracking database.
# need to create an initial one,
# then add the errors to the tracker
# error string to be used for the die() function
# die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $query . "&mysql=" . mysql_error()));

  $q_string  =
    "bug_module     =   " . "7"                                   . "," . 
    "bug_severity   =   " . "0"                                   . "," . 
    "bug_priority   =   " . "0"                                   . "," . 
    "bug_discovered = \"" . date('Y-m-d')                         . "\"," . 
    "bug_subject    = \"" . "Bug found in " . $formVars['script'] . "\"," . 
    "bug_openby     =   " . $_SESSION['uid'];

  $query = "insert into bugs set bug_id = null," . $q_string;
  $insert = mysqli_query($db, $query) or die($query . ": " . mysql_error());

  $bug_id = last_insert_id();

  $q_string = 
    "bug_bug_id =   " . $bug_id                               . "," . 
    "bug_text   = \"" . "Query String: " . $formVars['error'] . "\"," . 
    "bug_user   =   " . $_SESSION['uid'];

  $query = "insert into bugs_detail set bug_id = null," . $q_string;
  $insert = mysqli_query($db, $query) or die($query . ": " . mysql_error());

  $q_string = 
    "bug_bug_id =   " . $bug_id                               . "," . 
    "bug_text   = \"" . "MySQL Error: " . $formVars['mysql'] . "\"," . 
    "bug_user   =   " . $_SESSION['uid'];

  $query = "insert into bugs_detail set bug_id = null," . $q_string;
  $insert = mysqli_query($db, $query) or die($query . ": " . mysql_error());

  if ($called == 'no') {
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Error!</title>

<?php include($Sitepath . "/head.php"); ?>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<h1>Error</h1>

<div class="main ui-widget-content">

<p>An error was just generated.</p>

<p>The error has been logged into the Bug Tracker and will be investiaged.</p>

<p>If you want to add details to the report, go into the Bug Tracker and add a comment.</p>

<p>Thank you, Carl</p>

</div>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
<?php
} else {
  print "alert(\"Error: An error was just generated.\\n\\nThe error has been logged into the Bug Tracker and will be investigated.\\n\\nIf you want to provide more information, go to the Bug Tracker and add a comment.\\n\\nThank you, Carl.\\n\");\n";
}
?>
