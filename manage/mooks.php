<?php
# Script: mooks.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Sitepath . '/guest.php');

  $package = "mooks.php";

  logaccess($formVars['username'], $package, "Accessing the script.");

  if (isset($_GET['id'])) {
    $formVars['id'] = clean($_GET['id'], 10);
  }

  if (!mooks_Available($formVars['id'])) {
    include($Loginpath . '/user_level.php');
    exit;
  }

  $q_string  = "select runr_name ";
  $q_string .= "from runners ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_runners = mysqli_fetch_array($q_runners);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Managing <?php print $a_runners['runr_name']; ?></title>

<style type='text/css' title='currentStyle' media='screen'>
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">

function clear_fields() {
  show_file('detail.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('history.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('karma.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('nuyen.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('street.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('notoriety.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('awareness.mysql.php?id=<?php print $formVars['id']; ?>');

  show_file('tags.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('active.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('knowledge.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('language.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('lifestyle.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('qualities.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('contact.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('identity.mysql.php?id=<?php print $formVars['id']; ?>');

  show_file('spells.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('traditions.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('mentor.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('spirits.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('alchemy.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('adept.mysql.php?id=<?php print $formVars['id']; ?>');

  show_file('commlink.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('command.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('cyberdeck.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('cyberjack.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('sprites.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('complex.mysql.php?id=<?php print $formVars['id']; ?>');

  show_file('gear.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('armor.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('bioware.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('cyberware.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('melee.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('ammunition.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('projectile.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('firearms.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('vehicles.mysql.php?id=<?php print $formVars['id']; ?>');
}

$(document).ready( function() {
  $( "#history"   ).tabs( ).addClass( "tab-shadow" );
  $( "#tabs"      ).tabs( ).addClass( "tab-shadow" );
  $( "#magic"     ).tabs( ).addClass( "tab-shadow" );
  $( "#matrix"    ).tabs( ).addClass( "tab-shadow" );
  $( "#decktabs"  ).tabs( ).addClass( "tab-shadow" );
  $( "#meatspace" ).tabs( ).addClass( "tab-shadow" );
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main">

<div id="tabs">

<ul>
  <li><a href="#detail">Detail</a></li>
  <li><a href="#history">History</a></li>
  <li><a href="#tags">Tags</a></li>
  <li><a href="#active">Active Skills</a></li>
  <li><a href="#knowledge">Knowledge Skills</a></li>
  <li><a href="#language">Language Skills</a></li>
  <li><a href="#lifestyle">Lifestyles</a></li>
  <li><a href="#qualities">Qualities</a></li>
  <li><a href="#contacts">Contacts</a></li>
  <li><a href="#identity">Identity</a></li>
  <li><a href="#magic">Magic</a></li>
  <li><a href="#matrix">Matrix</a></li>
  <li><a href="#meatspace">Meat Space</a></li>
</ul>


<div id="detail">

<span id="detail_mysql"><?php print wait_Process('Shadowrunner Loading...')?></span>

</div>


<div id="history">

<ul>
  <li><a href="#character">Character History</a></li>
  <li><a href="#karma">Karma</a></li>
  <li><a href="#nuyen">Nuyen</a></li>
  <li><a href="#street">Street Cred</a></li>
  <li><a href="#notoriety">Notoriety</a></li>
  <li><a href="#awareness">Public Awareness</a></li>
</ul>


<div id="character">

<span id="character_mysql"><?php print wait_Process('Character History Loading...')?></span>

</div>


<div id="karma">

<span id="karma_mysql"><?php print wait_Process('Karma Loading...')?></span>

</div>


<div id="nuyen">

<span id="nuyen_mysql"><?php print wait_Process('Nuyen Loading...')?></span>

</div>


<div id="street">

<span id="street_mysql"><?php print wait_Process('Street Cred Loading...')?></span>

</div>


<div id="notoriety">

<span id="notoriety_mysql"><?php print wait_Process('Notoriety Loading...')?></span>

</div>


<div id="awareness">

<span id="awareness_mysql"><?php print wait_Process('Public Awareness Loading...')?></span>

</div>


</div>


<div id="tags">

<span id="tags_mysql"><?php print wait_Process('Tags Loading...')?></span>

</div>


<div id="active">

<span id="active_mysql"><?php print wait_Process('Active Skills Loading...')?></span>

</div>


<div id="knowledge">

<span id="knowledge_mysql"><?php print wait_Process('Knowledge Skills Loading...')?></span>

</div>


<div id="language">

<span id="language_mysql"><?php print wait_Process('Language Skills Loading...')?></span>

</div>


<div id="lifestyle">

<span id="lifestyle_mysql"><?php print wait_Process('Lifestyles Loading...')?></span>

</div>


<div id="qualities">

<span id="qualities_mysql"><?php print wait_Process('Qualities Skills Loading...')?></span>

</div>


<div id="contacts">

<span id="contact_mysql"><?php print wait_Process('Contacts Loading...')?></span>

</div>


<div id="identity">

<span id="identity_mysql"><?php print wait_Process('Identities Loading...')?></span>

</div>



<div id="magic">

<ul>
  <li><a href="#spells">Spells</a></li>
  <li><a href="#traditions">Tradition</a></li>
  <li><a href="#mentor">Mentor Spirits</a></li>
  <li><a href="#spirits">Spirits</a></li>
  <li><a href="#alchemy">Alchemy</a></li>
  <li><a href="#adept">Adept</a></li>
</ul>


<div id="spells">

<span id="spells_mysql"><?php print wait_Process('Spells Loading...')?></span>

</div>


<div id="traditions">

<span id="traditions_mysql"><?php print wait_Process('Traditions Loading...')?></span>

</div>


<div id="spirits">

<span id="spirits_mysql"><?php print wait_Process('Spirits Loading...')?></span>

</div>


<div id="mentor">

<span id="mentor_mysql"><?php print wait_Process('Mentor Spirits Loading...')?></span>

</div>


<div id="alchemy">

<span id="alchemy_mysql"><?php print wait_Process('Alchemy Loading...')?></span>

</div>


<div id="adept">

<span id="adept_mysql"><?php print wait_Process('Adept Skills Loading...')?></span>

</div>


</div>


<div id="matrix">

<ul>
  <li><a href="#commlink">Commlinks</a></li>
  <li><a href="#command">Command Consoles</a></li>
  <li><a href="#cyberdeck">Cyberdecks</a></li>
  <li><a href="#cyberjack">Cyberjacks</a></li>
  <li><a href="#sprites">Sprites</a></li>
  <li><a href="#complex">Complex Forms</a></li>
</ul>


<div id="commlink">

<span id="commlink_mysql"><?php print wait_Process('Commlinks Loading...')?></span>

</div>


<div id="command">

<span id="command_mysql"><?php print wait_Process('Rigger Command Consoles Loading...')?></span>

</div>


<div id="cyberdeck">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel('1') || check_owner($formVars['id'])) {
?>
<a href="<?php print $Editroot; ?>/mooks.php?id=<?php print $formVars['id']; ?>#cyberdeck" target="_blank"><img src="<?php print $Siteroot; ?>/imgs/pencil.gif">
<?php
  }
?>
Cyberdeck Information
<?php
  if (check_userlevel('1') || check_owner($formVars['id'])) {
?>
</a>
<?php
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('cyberdeck-help');">Help</a></th>
</tr>
</table>

<div id="cyberdeck-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Cyberdeck</strong> - </li>
</ul>

</div>

</div>

<div id="decktabs">

<?php

  $tablist = '';
  $decklist = '';

  $q_string  = "select r_deck_id,deck_brand ";
  $q_string .= "from r_cyberdeck ";
  $q_string .= "left join cyberdeck on cyberdeck.deck_id = r_cyberdeck.r_deck_number ";
  $q_string .= "where r_deck_character = " . $formVars['id'] . " ";
  $q_r_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_cyberdeck) > 0) {
    while ($a_r_cyberdeck = mysqli_fetch_array($q_r_cyberdeck)) {

      $tablist .= "  <li><a href=\"#" . $a_r_cyberdeck['deck_brand'] . $a_r_cyberdeck['r_deck_id'] . "\">" . $a_r_cyberdeck['deck_brand'] . "</a></li>\n";

      $decklist .= "<div id=\"" . $a_r_cyberdeck['deck_brand'] . $a_r_cyberdeck['r_deck_id'] . "\">\n\n";
      $decklist .= "<span id=\"" . $a_r_cyberdeck['deck_brand'] . $a_r_cyberdeck['r_deck_id'] . "_mysql\">" . wait_Process('Cyberdecks Loading...') . "</span>\n\n";
      $decklist .= "</div>\n\n\n";

    }
    print "<ul>\n";
    print $tablist;
    print "</ul>\n\n";

    print $decklist;
  } else {
    print "<span id=\"nodeck_mysql\">" . wait_Process('Loading...'). "</span>\n";
  }

?>

</div>

</div>


<div id="cyberjack">

<span id="cyberjacks_mysql"><?php print wait_Process('Cyberjacks Loading...')?></span>

</div>


<div id="sprites">

<span id="sprites_mysql"><?php print wait_Process('Sprites Loading...')?></span>

</div>


<div id="complex">

<span id="complex_mysql"><?php print wait_Process('Complex Forms Loading...')?></span>

</div>


</div>


<div id="meatspace">

<ul>
  <li><a href="#gear">Gear</a></li>
  <li><a href="#armor">Armor</a></li>
  <li><a href="#bioware">Bioware</a></li>
  <li><a href="#cyberware">Cyberware</a></li>
  <li><a href="#melee">Melee</a></li>
  <li><a href="#ammunition">Ammunition</a></li>
  <li><a href="#projectile">Projectile</a></li>
  <li><a href="#firearms">Firearms</a></li>
  <li><a href="#vehicles">Vehicles</a></li>
</ul>


<div id="gear">

<span id="gear_mysql"><?php print wait_Process('Gear Loading...')?></span>

</div>


<div id="armor">

<span id="armor_mysql"><?php print wait_Process('Armor Loading...')?></span>

</div>


<div id="bioware">

<span id="bioware_mysql"><?php print wait_Process('Bioware Loading...')?></span>

</div>


<div id="cyberware">

<span id="cyberware_mysql"><?php print wait_Process('Cyberware Loading...')?></span>

</div>


<div id="melee">

<span id="melee_mysql"><?php print wait_Process('Melee Weapons Loading...')?></span>

</div>


<div id="ammunition">

<span id="ammunition_mysql"><?php print wait_Process('Ammunition Loading...')?></span>

</div>


<div id="projectile">

<span id="projectile_mysql"><?php print wait_Process('Projectile Weapons Loading...')?></span>

</div>


<div id="firearms">

<span id="firearms_mysql"><?php print wait_Process('Firearms Loading...')?></span>

</div>


<div id="vehicles">

<span id="vehicles_mysql"><?php print wait_Process('Vehicles Loading...')?></span>

</div>

</div>


</div>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
