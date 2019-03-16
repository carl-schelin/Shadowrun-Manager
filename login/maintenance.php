<?php
session_start(); 
include('settings.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Mooks Management</title>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<META NAME="robots" content="index,follow">

<link rel="stylesheet" href="<?php print $Loginroot; ?>/stylesheet.css" />

</head>
<body>

<div id="header">
    
<div id="title">

<h1>Mooks Login</h1>

</div>

</div>

<div id="main">

<h1 style='margin: 0; padding: 0; font-size: 20px;'>Maintenance Mode</h1>

<p>Sorry, currently maintenance is being done on the Mooks system.</p>

<p>Please contact the system administrator for further information.</p>

<h2>What to do now?</h2>

<p>Return to <a href='/'>the main page</a></p>

</div>

<div id="footer"><a href="<?php print $Siteroot; ?>">Mooks Management</a></div>

</body>
</html>
