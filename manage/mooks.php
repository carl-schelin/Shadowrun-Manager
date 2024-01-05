<?php
# Script: mooks.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Sitepath . '/guest.php');

  $package = "mooks.php";

  logaccess($db, $formVars['username'], $package, "Accessing the script.");

# if help has not been seen yet,
  if (show_Help($db, $Dataroot . "/" . $package)) {
    $display = "display: block";
  } else {
    $display = "display: none";
  }

  if (isset($_GET['id'])) {
    $formVars['id'] = clean($_GET['id'], 10);
  }

  if (!mooks_Available($db, $formVars['id'])) {
    include($Loginpath . '/user_level.php');
    exit;
  }

  $q_string  = "select runr_name ";
  $q_string .= "from runners ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
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
  $( "#linktabs"  ).tabs( ).addClass( "tab-shadow" );
  $( "#cmdtabs"   ).tabs( ).addClass( "tab-shadow" );
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

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#character\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Character Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('character-help');">Help</a></th>
</tr>
</table>

<div id="character-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="character_mysql"><?php print wait_Process('Character History Loading...')?></span>

</div>


<div id="karma">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#karma\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Karma Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('karma-help');">Help</a></th>
</tr>
</table>

<div id="karma-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="karma_mysql"><?php print wait_Process('Karma Loading...')?></span>

</div>


<div id="nuyen">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#nuyen\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Nuyen Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('nuyen-help');">Help</a></th>
</tr>
</table>

<div id="nuyen-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="nuyen_mysql"><?php print wait_Process('Nuyen Loading...')?></span>

</div>


<div id="street">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#street\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Street Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('street-help');">Help</a></th>
</tr>
</table>

<div id="street-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="street_mysql"><?php print wait_Process('Street Cred Loading...')?></span>

</div>


<div id="notoriety">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#notoriety\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Notoriety Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('notoriety-help');">Help</a></th>
</tr>
</table>

<div id="notoriety-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="notoriety_mysql"><?php print wait_Process('Notoriety Loading...')?></span>

</div>


<div id="awareness">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#awareness\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Awareness Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('awareness-help');">Help</a></th>
</tr>
</table>

<div id="awareness-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="awareness_mysql"><?php print wait_Process('Public Awareness Loading...')?></span>

</div>


</div>


<div id="tags">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#tags\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Tag Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('tags-help');">Help</a></th>
</tr>
</table>

<div id="tags-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="tags_mysql"><?php print wait_Process('Tags Loading...')?></span>

</div>


<div id="active">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#active\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Active Skill Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('active-help');">Help</a></th>
</tr>
</table>

<div id="active-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="active_mysql"><?php print wait_Process('Active Skills Loading...')?></span>

</div>


<div id="knowledge">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#knowledge\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Knowledge Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('knowledge-help');">Help</a></th>
</tr>
</table>

<div id="knowledge-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="knowledge_mysql"><?php print wait_Process('Knowledge Skills Loading...')?></span>

</div>


<div id="language">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#language\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Language Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('language-help');">Help</a></th>
</tr>
</table>

<div id="language-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="language_mysql"><?php print wait_Process('Language Skills Loading...')?></span>

</div>


<div id="lifestyle">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#lifestyle\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Lifestyle Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('lifestyle-help');">Help</a></th>
</tr>
</table>

<div id="lifestyle-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="lifestyle_mysql"><?php print wait_Process('Lifestyles Loading...')?></span>

</div>


<div id="qualities">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#qualities\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Qualities Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('qualities-help');">Help</a></th>
</tr>
</table>

<div id="qualities-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="qualities_mysql"><?php print wait_Process('Qualities Skills Loading...')?></span>

</div>


<div id="contacts">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#contacts\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Contacts Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('contacts-help');">Help</a></th>
</tr>
</table>

<div id="contacts-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="contact_mysql"><?php print wait_Process('Contacts Loading...')?></span>

</div>


<div id="identity">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#identity\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Identities and License Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('identity-help');">Help</a></th>
</tr>
</table>

<div id="identity-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

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

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#spells\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Spell Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('spells-help');">Help</a></th>
</tr>
</table>

<div id="spells-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Drain</strong> - Roll Tradition Drain - if Net Hits > Magic, damage is physical, otherwise stun.</li>
  <li><strong>Combat Spells</strong> sr5/283
    <ul>
      <li><strong>Direct</strong> - Opposed: Spellcasting + Magic (up to 2 x Magic) [Force] vs Body (Physical) or Willpower (Mana) + Counterspelling. Armor ineffective.</li>
      <li><strong>Indirect</strong> - Spellcasting + Magic vs Reaction + Intuition + Counterspelling. Damage is Force + Net Hits, if opposed reduces to zero, no damage. Opposed soak is Body + Armor + AP (-Force).</li>
      <li><strong>Elemental</strong> - Additional effects based on type.</li>
    </li></ul>
  <li><strong>Detection Spells</strong> Range = Touch. sr5/285
    <ul>
      <li><strong>Active</strong> - Opposed: Spellcasting + Magic [Force] vs Willpower + Logic + Counterspelling [Mental] (Physical: Living w/Aura), Force x 2 (Magical Objects), or Object Resistance (sr5/295).</li>
      <li><strong>Area</strong> - Like hearing. Force x Magic x 10 in meters</li>
      <li><strong>Directional</strong> - Like normal sight.</li>
      <li><strong>Extended Area</strong> - Like hearing. Force x Magic x 10 in meters.</li>
      <li><strong>Passive</strong> - Spellcasting + Magic [Force]. Net Hits replaces Mental as the detection [Limit].</li>
      <li><strong>Psychic</strong> - Adds an extra sense like Telepathy.</li>
    </li></ul>
  <li><strong>Health Spells</strong> Healing is -points of lost essence rounded down to dice pool. sr5/287
    <ul>
      <li><strong>Negative</strong> - Opposed: Spellcasting + Magic vs Attribute + Counterspelling.</li>
    </li></ul>
  <li><strong>Illusion Spells</strong> sr5/289
    <ul>
      <li><strong>Area</strong> - .</li>
      <li><strong>Multi-Sense</strong> - .</li>
      <li><strong>Obvious</strong> - .</li>
      <li><strong>Realistic</strong> - .</li>
      <li><strong>Single-Sense</strong> - .</li>
    </li></ul>
  <li><strong>Manipulation Spells</strong> sr5/292
    <ul>
      <li><strong>Area</strong> - .</li>
      <li><strong>Environmental</strong> - .</li>
      <li><strong>Mental</strong> - .</li>
      <li><strong>Physical</strong> - .</li>
    </li></ul>
</ul>

</div>

</div>

<span id="spells_mysql"><?php print wait_Process('Spells Loading...')?></span>

</div>


<div id="traditions">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#tradition\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Tradition Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('tradition-help');">Help</a></th>
</tr>
</table>

<div id="tradition-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="traditions_mysql"><?php print wait_Process('Traditions Loading...')?></span>

</div>


<div id="spirits">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#spirits\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Spirit Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('spirits-help');">Help</a></th>
</tr>
</table>

<div id="spirits-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="spirits_mysql"><?php print wait_Process('Spirits Loading...')?></span>

</div>


<div id="mentor">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#mentor\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Mentor Spirit Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('mentor-help');">Help</a></th>
</tr>
</table>

<div id="mentor-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="mentor_mysql"><?php print wait_Process('Mentor Spirits Loading...')?></span>

</div>


<div id="alchemy">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#alchemy\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Alchemy Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('alchemy-help');">Help</a></th>
</tr>
</table>

<div id="alchemy-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="alchemy_mysql"><?php print wait_Process('Alchemy Loading...')?></span>

</div>


<div id="adept">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#magic\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Adept Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('adept-help');">Help</a></th>
</tr>
</table>

<div id="adept-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

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

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#commlink\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Commlink Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('commlink-help');">Help</a></th>
</tr>
</table>

<div id="commlink-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<div id="linktabs">

<?php

  $tablist = '';
  $linklist = '';

  $q_string  = "select r_link_id,link_brand ";
  $q_string .= "from r_commlink ";
  $q_string .= "left join commlink on commlink.link_id = r_commlink.r_link_number ";
  $q_string .= "where r_link_character = " . $formVars['id'] . " ";
  $q_r_commlink = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_commlink) > 0) {
    while ($a_r_commlink = mysqli_fetch_array($q_r_commlink)) {

      $tablist .= "  <li><a href=\"#" . $a_r_commlink['link_brand'] . $a_r_commlink['r_link_id'] . "\">" . $a_r_commlink['link_brand'] . "</a></li>\n";

      $linklist .= "<div id=\"" . $a_r_commlink['link_brand'] . $a_r_commlink['r_link_id'] . "\">\n\n";
      $linklist .= "<span id=\"" . $a_r_commlink['link_brand'] . $a_r_commlink['r_link_id'] . "_mysql\">" . wait_Process('Commlinks Loading...') . "</span>\n\n";
      $linklist .= "</div>\n\n\n";

    }
    print "<ul>\n";
    print $tablist;
    print "</ul>\n\n";

    print $linklist;
  } else {
    print "<span id=\"nolink_mysql\">" . wait_Process('Loading...'). "</span>\n";
  }

?>

</div>

</div>


<div id="command">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#command\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Command Console Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('command-help');">Help</a></th>
</tr>
</table>

<div id="command-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<div id="cmdtabs">

<?php

  $tablist = '';
  $cmdlist = '';

  $q_string  = "select r_cmd_id,cmd_brand,cmd_model ";
  $q_string .= "from r_command ";
  $q_string .= "left join command on command.cmd_id = r_command.r_cmd_number ";
  $q_string .= "where r_cmd_character = " . $formVars['id'] . " ";
  $q_r_command = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_command) > 0) {
    while ($a_r_command = mysqli_fetch_array($q_r_command)) {

      $tablist .= "  <li><a href=\"#" . $a_r_command['cmd_brand'] . $a_r_command['cmd_model'] . $a_r_command['r_cmd_id'] . "\">" . $a_r_command['cmd_brand'] . " " . $a_r_command['cmd_model'] . "</a></li>\n";

      $cmdlist .= "<div id=\"" . $a_r_command['cmd_brand'] . $a_r_command['cmd_model'] . $a_r_command['r_cmd_id'] . "\">\n\n";
      $cmdlist .= "<span id=\"" . $a_r_command['cmd_brand'] . $a_r_command['cmd_model'] .  $a_r_command['r_cmd_id'] . "_mysql\">" . wait_Process('Command Consoles Loading...') . "</span>\n\n";
      $cmdlist .= "</div>\n\n\n";

    }
    print "<ul>\n";
    print $tablist;
    print "</ul>\n\n";

    print $cmdlist;
  } else {
    print "<span id=\"nocmd_mysql\">" . wait_Process('Loading...'). "</span>\n";
  }

?>

</div>

</div>


<div id="cyberdeck">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#cyberdeck\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Cyberdeck Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('cyberdeck-help');">Help</a></th>
</tr>
</table>

<div id="cyberdeck-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<div id="decktabs">

<?php

  $tablist = '';
  $decklist = '';

  $q_string  = "select r_deck_id,deck_brand,deck_model ";
  $q_string .= "from r_cyberdeck ";
  $q_string .= "left join cyberdeck on cyberdeck.deck_id = r_cyberdeck.r_deck_number ";
  $q_string .= "where r_deck_character = " . $formVars['id'] . " ";
  $q_r_cyberdeck = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  if (mysqli_num_rows($q_r_cyberdeck) > 0) {
    while ($a_r_cyberdeck = mysqli_fetch_array($q_r_cyberdeck)) {

      $tablist .= "  <li><a href=\"#" . $a_r_cyberdeck['deck_brand'] . $a_r_cyberdeck['deck_model'] . $a_r_cyberdeck['r_deck_id'] . "\">" . $a_r_cyberdeck['deck_brand'] . " " . $a_r_cyberdeck['deck_model'] . "</a></li>\n";

      $decklist .= "<div id=\"" . $a_r_cyberdeck['deck_brand'] . $a_r_cyberdeck['deck_model'] . $a_r_cyberdeck['r_deck_id'] . "\">\n\n";
      $decklist .= "<span id=\"" . $a_r_cyberdeck['deck_brand'] . $a_r_cyberdeck['deck_model'] . $a_r_cyberdeck['r_deck_id'] . "_mysql\">" . wait_Process('Cyberdecks Loading...') . "</span>\n\n";
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

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#cyberjack\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Cyberjack Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('cyberjack-help');">Help</a></th>
</tr>
</table>

<div id="cyberjack-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="cyberjacks_mysql"><?php print wait_Process('Cyberjacks Loading...')?></span>

</div>


<div id="sprites">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#sprites\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Sprite Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('sprites-help');">Help</a></th>
</tr>
</table>

<div id="sprites-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="sprites_mysql"><?php print wait_Process('Sprites Loading...')?></span>

</div>


<div id="complex">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#complex\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Complex Form Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('complex-help');">Help</a></th>
</tr>
</table>

<div id="complex-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

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

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#gear\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Gear Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('gear-help');">Help</a></th>
</tr>
</table>

<div id="gear-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="gear_mysql"><?php print wait_Process('Gear Loading...')?></span>

</div>


<div id="armor">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#armor\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Armor Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('armor-help');">Help</a></th>
</tr>
</table>

<div id="armor-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="armor_mysql"><?php print wait_Process('Armor Loading...')?></span>

</div>


<div id="bioware">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#bioware\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Bioware Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('bioware-help');">Help</a></th>
</tr>
</table>

<div id="bioware-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="bioware_mysql"><?php print wait_Process('Bioware Loading...')?></span>

</div>


<div id="cyberware">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#cyberware\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Cyberware Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('cyberware-help');">Help</a></th>
</tr>
</table>

<div id="cyberware-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="cyberware_mysql"><?php print wait_Process('Cyberware Loading...')?></span>

</div>


<div id="melee">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#melee\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Melee Weapon Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('melee-help');">Help</a></th>
</tr>
</table>

<div id="melee-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="melee_mysql"><?php print wait_Process('Melee Weapons Loading...')?></span>

</div>


<div id="ammunition">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#ammunition\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Ammunition Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('ammunition-help');">Help</a></th>
</tr>
</table>

<div id="ammunition-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="ammunition_mysql"><?php print wait_Process('Ammunition Loading...')?></span>

</div>


<div id="projectile">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#projectile\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Projectile Weapon Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('projectile-help');">Help</a></th>
</tr>
</table>

<div id="projectile-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="projectile_mysql"><?php print wait_Process('Projectile Weapons Loading...')?></span>

</div>


<div id="firearms">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#firearms\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Firearm Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('firearms-help');">Help</a></th>
</tr>
</table>

<div id="firearms-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="firearms_mysql"><?php print wait_Process('Firearms Loading...')?></span>

</div>


<div id="vehicles">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "<a href=\"" . $Editroot . "/mooks.php?id=" . $formVars['id'] . "#vehicles\" target=\"_blank\"><img src=\"" . $Siteroot . "/imgs/pencil.gif\">";
  }
?>
Vehicle Information
<?php
  if (check_userlevel($db, $AL_Johnson) || check_owner($db, $formVars['id'])) {
    print "</a>";
  }
?>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('vehicles-listing-help');">Help</a></th>
</tr>
</table>

<div id="vehicles-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p>Help</p>

</div>

</div>

<span id="vehicles_mysql"><?php print wait_Process('Vehicles Loading...')?></span>

</div>

</div>


</div>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
