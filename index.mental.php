<?php

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  logaccess($_SESSION['username'], "index.php", "Checking out the index.");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Miscellaneous Information</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">


<p>Contacts</p>

<p>1st Edition Page 32 and 162</p>

<p>A paragraph description and a list of contacts with stats.</p>


<p>2nd Edition Page 200</p>

<p>Roll on Street or Corporate Etiquette, A target of 4. Net successes provide additional information.</p>


<p>3rd Edition Page 45 and 253</p>

<p>Level 1: Opposed Intelligence test, target 6, to determine if contact has the requested info.</p>

<p>Level 2: +1 die for Etiquette tests to acquire info from the contact. Opposed Willpower target 5 if the contact might refuse to answer.</p>

<p>Level 3: +2 dice for Etiquette tests to acquire info from the contact. Opposed Willpower target 6 if the contact might refuse to answer.</p>



<p>4th Edition Page 70-71 and 285 </p>

<p>Connection (1-6): how important the contact is. The higher the number, the larger their network.</p>

<p>Loyalty (1-6): how reliable the contact is. From "Just Biz" to "Friend for Life".</p>

<p>Availability: Generally per the game although you can roll 1d6 and if it meets or exceeds the connection rating, they're available (they're busy people, if they're a well connected person, they may not be easy to get in touch with)</p>

<p>Legwork: To see if the connection knows anything, appropriate knowledge skill + attribute. Obviously if outside the contact's area of expertise, add modifiers. Opposed Negotiation test if the info is important. Can do Charisma/Knowledge + Connection extended test, 1 hour to ask around for the info.</p> 



<p>5th Edition Page 98 and 386</p>

<p>Connection (1-12): How important or influencial the contact is. Higher has a larger network of contacts so might have the info you're looking for.</p>

<p>Loyalty (1-6): How much of a friend the contact is. Lower is business only, mercenary. Highest is BFF and will have your back.</p>

<p>Availability: Basically if a GM wants to get info to the team, the contact is either available or will even contact them if they don't think to call. If you want to do a random, roll 2D6 and if it meets or exceeds the connection rating, they're available.</p>

<p>Legwork: If an appropriate contact for the search, Knowledge Skill + Attribute. Apply social limit to the test. Then it depends on the info. Minor info probably no issue. If the info can harm the contact, it may require some palm greasing (aka a Negotiation test; add Loyalty to your roll). Stret Cred can affect the info. If payment is required, 100 X (7 - loyalty) in nuyen.</p>



<p>6th Edition Page 50-51 and 237</p>

<p>Connection (1-12): How important the contact is. The higher the number, the larger their network. Roll Connection + Connection to determine how much they know. See legwork table to see what they know.</p>

<p>loyalty (1-12): Determines how much they are willing to share for free. Roll Influence + Charisma + Loyalty. Above that to the Connection result will cost.</p>

<p>Example. If Con+Con is 10 and Influence + Cha + Loy is 6, then from 0 to 6 is free but from 7 to 10 will cost.</p>


</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
