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
<title>Magic Details</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );
});

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main">

<div id="tabs">

<ul>
  <li><a href="#astral">Astral Combat</a></li>
  <li><a href="#sorcery">Sorcery</a></li>
  <li><a href="#conjuring">Conjuring</a></li>
  <li><a href="#enchanting">Enchanting</a></li>
</ul>

<div id="astral">

<p>Starts SR5 - Page 315</p>
<p>Astral Combat - Same as Physical combat except no ranged combat (yes spells).</p>
<ol>
  <li>Agility is replaced by Logic</li>
  <li>Body is replaced by Willpower</li>
  <li>Reaction is replaced by Intuition</li>
  <li>Strength is replaced by Charisma</li>
  <li>Normal combat for physical or dual natured</li>
  <li>Astral Combat + willpower for astral entities</li>
  <li>Unarmed: Astral Combat + Willpower [Astral] vs Intuition + Logic, DV is Charisma</li>
  <li>Weapon Foci: Astral Combat + Willpower [Accuracy] vs Intuition + Logic, DV is by weapon; CHA vs STR</li>
  <li>Spirit DV is Force</li>
  <li>Watcher DV is 1</li>
</ol>

<p>From sR4: 184</p>
<ul>
  <li>Attacker is a Mage, Spirit, or Watcher Spirit (pg: )</li>
  <li>Willpower (pg: )</li>
  <li>Logic (pg: )</li>
  <li>Astral Combat (pg: )</li>
  <li>Wound Modifier (pg: )</li>
  <li>Reach: apply to DP or Opponant (pg: )</li>
  <li>Weapon Focus Rating (pg: )</li>
  <li>Damage Value (pg: )</li>
  <li>Physical or Stun (pg: )</li>
  <li>Armor Penetration (pg: )</li>
  <li>Reach (pg: )</li>
</ul>

<p>Defender</p>
<ul>
  <li>Reaction: (pg: )</li>
  <li>Defender will Parry, Block or Dodge: (pg: )</li>
  <li>Weapon Skill: (pg: )</li>
  <li>Full Defense: (pg: )</li>
  <li>Dodge: (pg: )</li>
  <li>Wound Modifier: (pg: )</li>
  <li>Number of Previous Attacks: (pg: )</li>
  <li>Dumpshocked: (pg: )</li>
  <li>Receiving Charge: (pg: )</li>
  <li>Prone: (pg: )</li>
  <li>Running: (pg: )</li>
  <li>Body: (pg: )</li>
  <li>Impact: (pg: )</li>
  <li>Modifier: (pg: )</li>
</ul>

</div>


<div id="sorcery">

<p>Starts SR5 - Page 281</p>
<p>Spellcasting</p>
<ol>
  <li>Choose Spell</li>
  <li>Choose the Target</li>
  <li>Choose Spell Force - Up to twice; if hits [limit] exceed Magic rating, then Physical damage, otherwise Stun</li>
  <li>Cast Spell - Spellcasting + Magic [Force].</li>
  <li>Determine Effect</li>
  <li>Resist Drain - Tradition drain pool. Cannot be lower than 2. </li>
  <li>Determine Ongoing Effects</li>
</ol>

<p>Counterspelling</p>
<p>Used to aid others by adding to their or your pool. Or Dispelling to counter a sustained or quickened spell</p>

<p>Ritual Spellcasting</p>
<ol>
  <li>Choose Ritual Leader</li>
  <li>Choose Ritual</li>
  <li>Choose the Force of the Ritual Spell</li>
  <li>Set Up The Foundatin</li>
  <li>Give The Offering</li>
  <li>Perform The Ritual</li>
  <li>Seal The Ritual</li>
</ol>


<p>Spellcasting from SR4: 182</p>
<ul>
  <li>Spell Force (pg: )</li>
  <li>Magic (pg: )</li>
  <li>Spellcasting (pg: )</li>
  <li>Additional Sustained Spells (pg: )</li>
  <li>Visibility Modifier (pg: )</li>
  <li>Mentor Spirit MOdifier (pg: )</li>
  <li>Spellcasting focus (pg: )</li>
  <li>Wound Modifier (pg: )</li>
</ul>

<p>Target</p>
<ul>
  <li>Spell: Physical or Mana (pg: )</li>
  <li>Body (pg: )</li>
  <li>Willpower (pg: )</li>
  <li>Counterspelling Dice (pg: )</li>
  <li>Astral Barrier Force (pg: )</li>
</ul>

<p>Spellcaster drain</p>
<ul>
  <li>Force (pg: )</li>
  <li>Modifier (pg: )</li>
  <li>Willpower (pg: )</li>
  <li>Drain Attribute based on Tradition (pg: )</li>
  <li>Spellcasting Focus (pg: )</li>
</ul>

</div>


<div id="conjuring">

<p>Starts SR5 - Page 300</p>
<ol>
  <li>Choose Spirit Type and Force - Based on your Tradition, and up to Twice Magic</li>
  <li>Attempt Summoning - Opposed test: Summoning + Magic [Force] vs Spirit's Force. Spend reagents to change the [limit]</li>
  <li>Resist Drain - DV = Spirit Hits x 2. If the Spirits force is greater than your magic, then Physical otherwise Stun.</li>
</ol>

<ol>
  <li>Attempt Binding - Opposed test: Summoning + Magic [Force] vs Spirit's Force. Spend reagents to change the [limit]</li>
  <li>Resist Drain - DV = Spirit Hits x 2, minimum of 2. If the Spirits force is greater than your magic, then Physical otherwise Stun.</li>
</ol>

<ol>
  <li>Attempt Banishing - Opposed test: Banishing + Magic [Astral] vs Spirit's Force. Spend reagents to change the [limit]</li>
  <li>Resist Drain - DV = Spirit Hits x 2, minimum of 2. If the Spirits force is greater than your magic, then Physical otherwise Stun.</li>
</ol>



<p>Summoning: SR4 page 188</p>
<ul>
  <li>Magic (pg: )</li>
  <li>Summoning Skill (pg: )</li>
  <li>Mentor Spirit Modifier (pg: )</li>
  <li>Summoning Focus (pg: )</li>
  <li>Wound Modifier (pg: )</li>
</ul>

<p>Spirit</p>
<ul>
  <li>Spirit Force (pg: )</li>
  <li>GM Rolls Spirit Force (pg: )</li>
  <li>Number of Services (pg: )</li>
</ul>

<p>Spellcaster Drain</p>
<ul>
  <li>Willpower (pg: )</li>
  <li>Drain Attribute based on Tradition (pg: )</li>
  <li>Summoning focus (pg: )</li>
  <li>Focused Concentration No, Lvl1, Lvl 2 (pg: )</li>
</ul>


<p>Binding: SR4 page 188</p>


<p>Banishing: SR4 page 188</p>
<ul>
  <li>Magic (pg: )</li>
  <li>Banishing Skill (pg: )</li>
  <li>Mentor Spirit Modifier (pg: )</li>
  <li>Banishing Focus (pg: )</li>
  <li>Wound Modifier (pg: )</li>
</ul>

<p>Spirit</p>
<ul>
  <li>Spirit Force (Bound?) (pg: )</li>
  <li>Summoners Magic (pg: )</li>
  <li>GM Rolls Spirit Force (pg: )</li>
  <li>Number of Services Reduced (pg: )</li>
</ul>

<p>Spellcaster Drain</p>
<ul>
  <li>Willpower (pg: )</li>
  <li>Drain Attribute based on Tradition (pg: )</li>
  <li>Banishing focus (pg: )</li>
  <li>Focused Concentration No, Lvl1, Lvl 2 (pg: )</li>
</ul>

</div>


<div id="enchanting">

<p>Starts SR5 - Page 304</p>
<p>Alchemy - Creating a Preparation</p>
<ol>
  <li>Choose A Spell</li>
  <li>Choose Spell Force - Force up to twice Magic</li>
  <li>Choose The Lynchpin For The Preparation - An object you can handle; small enough to lift.</li>
  <li>Choose Preparation Trigger - Command, Contact, or Timed</li>
  <li>Create The Preparation - Alchemy + Magic [Force]. You can use reagents to increase [limit]</li>
  <li>Resist Drain - If number of hits exceeds Magic rating, it's Physical Damage, otherwise Stun.</li>
</ol>

<p>Artificing - Creating a Focus</p>
<ol>
  <li>Choose Focus Formula</li>
  <li>Obtain The Telesma</li>
  <li>Prepare The Magical Lodge</li>
  <li>Spend Reagents</li>
  <li>Craft The Focus</li>
  <li>Resist Drain</li>
</ol>

</div>

</div>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
