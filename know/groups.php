<?php
# Script: groups.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = 'no';
  include($Sitepath . '/guest.php');

  $package = "groups.php";

  logaccess($formVars['uid'], $package, "How do Groups work?");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Groups, how does it work?</title>

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

<div class="main">

<div class="main-help ui-widget-content">

<p><strong><u>Groups</u></strong></p>

<p>Groups are how you gather together teams to be managed. You may have dozens or more of characters but only five will be in a group. This assumes there are characters already in the system.</p>

<p>Initially, you'll need to create the group. Under the orange Account menu, select Group Management. This will list all the groups that you manage.</p>

<p>Click on the "Group Management" header. This opens the "Group Form". Create a unique Group Name. Add an email which lets you be notified. Select the group Owner (typically you but you may be creating the group for someone else. Initially the group is Enabled but you can disable a group here as well. Disabled groups don't show up in any group lists other than this one.</p>

<p>To add users, click the "Manage" link. Clicking on the group name will let you edit the group information including disabling the group.</p>

<p>Clicking on the "Group Member Management" header will open the "Group Member Form". From you you can select any available runners. Note that a runner will need to check the "Character available for runs" checkbox in the character edit page.</p>

<p>Once identified, if you add the member to the group, the character owner will receive an email (if added) requesting approval. In the mean time, the Status stays at "Pending".</p>

<p>At this point, you're done. You remove members whenever you like by clicking Delete.</p>

<p>The users will need to approve or decline the request.</p>

<p>The user will go into their profile. At the bottom is an "Invitation Management" form. It shows all the groups your characters are members of. You can delete the member from the group by clicking the "Delete" button. You can Accept membership in the group by clicking Accept or decline by clicking "Decline". In both cases, the GM will receive an email with the results. Once accepted or declined, the appropriate button is disabled.</p>

<p>Note the "Visibility On" checkbox. The intention is to let you restrict what others can see of your character. If you check this, only active physical items will be visible. Clothes, guns, decks, etc. Unchecking this basically lets you see all the equipment the user has. You've shown off your deck to your partners, etc. Others will not be able to see your mental information. They can't see Skills or Qualities for instance so they won't know if you're an Elf Poser unless you tell them.</p>

<p><strong><u>Finally</u></strong></p>

<p>This page was created to try and provide an understanding of how to manage groups in the system. Let me know if you have questions or need clarification.</p>


</div>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
