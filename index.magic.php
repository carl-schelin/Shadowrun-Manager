<?php
  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login(3);

  logaccess($_SESSION['username'], "index.php", "Checking out the index.");

  $formVars['start'] = clean($_GET['start'], 10);
  if ($formVars['start'] == '') {
    $formVars['start'] = 'inventory';
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Mooks Manager</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">

$(document).ready( function() {
  $( "#tabs"            ).tabs( ).addClass( "tab-shadow" );
  $( "#generaltabs"     ).tabs( ).addClass( "tab-shadow" );
  $( "#astraltabs"      ).tabs( ).addClass( "tab-shadow" );
  $( "#sorcerytabs"     ).tabs( ).addClass( "tab-shadow" );
  $( "#conjuringtabs"   ).tabs( ).addClass( "tab-shadow" );
  $( "#enchantingtabs"  ).tabs( ).addClass( "tab-shadow" );
});

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main"> 

<div id="tabs">

<ul>
  <li><a href="#general">General Spellcasting</a></li>
  <li><a href="#astral">Astral Combat</a></li>
  <li><a href="#sorcery">Sorcery</a></li>
  <li><a href="#conjuring">Conjuring</a></li>
  <li><a href="#enchanting">Enchanting</a></li>
</ul>


<div id="general">

<div id="generaltabs">

<ul>
  <li><a href="#gen1st">1st Edition</a></li>
  <li><a href="#gen2nd">2nd Edition</a></li>
  <li><a href="#gen3rd">3rd Edition</a></li>
  <li><a href="#gen4th">4th Edition</a></li>
  <li><a href="#gen5th">5th Edition</a></li>
  <li><a href="#gen6th">6th Edition</a></li>
</ul>

<div id="gen1st">

<p>First</p>

</div>

<div id="gen2nd">

<p>Second</p>

</div>

<div id="gen3rd">

<p>Third</p>

</div>

<div id="gen4th">

<p>Fourth</p>

</div>

<div id="gen5th">

<p>Fifth</p>

</div>

<div id="gen6th">

<p>Noticing Magic requires a Perception + Intuition (Casters Magic Rating) (pg 129)</p>

<p>Object Resistance (pg 129)</p>

<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Natural objects</td>
  <td class="ui-widget-content">3</td>
</tr>
<tr>
  <td class="ui-widget-content">Manufactured low-tech objects and materials</td>
  <td class="ui-widget-content">6</td>
</tr>
<tr>
  <td class="ui-widget-content">Manufactured high-tech objects and materials</td>
  <td class="ui-widget-content">9</td>
</tr>
<tr>
  <td class="ui-widget-content">Highly processes objects</td>
  <td class="ui-widget-content">15+</td>
</tr>
</table>

<p>Counterspelling (pg 143)</p>

<p>Boosted Defense</p>

<ol>
  <li>Counterspell Major Action</li>
  <li>Sorcer + Magic</li>
  <li>LOS, 2 meter radius, +1m per dram of reagents)</li>
  <li>Net hits added to radius for Magic combat rounds</li>
</ol>

<p>Dispelling</p>

<ol>
  <li>Counterspell Action</li>
  <li>Sorcery + Magic vs Drain Value x 2</li>
  <li>Net hits cancels spell net hits</li>
</ol>

<p>Ritual Spellcasting (pg 144)</p>

<ol>
  <li>Choose a Ritual Leader (-2 for any with a different tradition)</li>
  <li>Choose the Ritual (see list)</li>
  <li>Set up the Foundation (aka the magical lodge)</li>
  <li>Spend Reagents. If spending more than required, reduce drain by 1 to a minimum of 2</li>
  <li>Perform the Ritual</li>
  <li>Seal the Ritual. Sorcery + Magic vs Ritual Threshold</li>
  <li>Participant drain = Threshold x 2, minimum 2. If Leader Sealing hits is greater than participant Magic, drain is physical otherwise stun</li>
</ol>

<p>Failure</p>

<p>Drain = roll Threshold x 2 in Stun</p>

</div>


</div>

</div>


<div id="astral">

<div id="astraltabs">

<ul>
  <li><a href="#ast1st">1st Edition</a></li>
  <li><a href="#ast2nd">2nd Edition</a></li>
  <li><a href="#ast3rd">3rd Edition</a></li>
  <li><a href="#ast4th">4th Edition</a></li>
  <li><a href="#ast5th">5th Edition</a></li>
  <li><a href="#ast6th">6th Edition</a></li>
</ul>


<div id="ast1st">

<p>First</p>

</div>


<div id="ast2nd">

<p>Second</p>

</div>


<div id="ast3rd">

<p>Third</p>

</div>


<div id="ast4th">

<p>Page 184</p>
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


<div id="ast5th">

<p>Page 315</p>

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

</div>


<div id="ast6th">

<p>Page 160</p>

<p>Similar to regular combat</p>

<ul>
  <li>Attack Rating: Magic + Tradition attribute (Hermetic: Logic, Shaman: Charisma)</li>
  <li>Defence Rating: Intuition + Innate Armor + Armor Spell</li>
</ul>

<p>Combat</p>

<ul>
  <li>Spellcasting: Sorcery + Magic vs Intuition + Logic</li>
  <li>Unarmed combat: Astral + Willpower vs Intuition + Logic</li>
  <li>Damage: ceil(Tradition Attribute / 2)</li>
  <li>Weapon focus: Close Combat + Willpower vs Intuition + Logic</li>
  <li>Damage: Weapon damage.</li>
</li>

<p>Damage resistance: Willpower</p>

</div>


</div>

</div>


<div id="sorcery">

<div id="sorcerytabs">

<ul>
  <li><a href="#sor1st">1st Edition</a></li>
  <li><a href="#sor2nd">2nd Edition</a></li>
  <li><a href="#sor3rd">3rd Edition</a></li>
  <li><a href="#sor4th">4th Edition</a></li>
  <li><a href="#sor5th">5th Edition</a></li>
  <li><a href="#sor6th">6th Edition</a></li>
</ul>


<div id="sor1st">

<p>First</p>

</div>


<div id="sor2nd">

<p>Second</p>

</div>


<div id="sor3rd">

<p>Third</p>

</div>


<div id="sor4th">

<p>Page 182</p>

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


<div id="sor5th">

<p>Page 281</p>
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

</div>


<div id="sor6th">

<p>Spellcasting. Page 131</p>

<ul>
  <li>Adjust the spell</li>
  <li>Spellcasting Test: Sorcery + Magic</li>
  <li>Deal with drain: Willpower + Tradition (Charisma or Logic). If equal or greater, no effect. If hits is less than Magic, Stun. Otherwise Physical.</li>
</ul>

<p>Adjust the spell: Max adjustments == Magic or Sorcery, whichever is higher</p>

<ul>
  <li>Amp Up: Combat Spells only. For each point of damage, +2 points of drain</li>
  <li>Increase Area: Increase area effect 2 meters, increase drain by 1</li>
  <li>Shift Area: Shift certain area-effect spells; see spell. Minor Action. No additional drain</li>
</ul>

</div>


</div>

</div>


<div id="conjuring">

<div id="conjuringtabs">

<ul>
  <li><a href="#con1st">1st Edition</a></li>
  <li><a href="#con2nd">2nd Edition</a></li>
  <li><a href="#con3rd">3rd Edition</a></li>
  <li><a href="#con4th">4th Edition</a></li>
  <li><a href="#con5th">5th Edition</a></li>
  <li><a href="#con6th">6th Edition</a></li>
</ul>


<div id="con1st">

<p>First</p>

</div>


<div id="con2nd">

<p>Second</p>

</div>


<div id="con3rd">

<p>Third</p>

</div>


<div id="con4th">

<p>Summoning: page 188</p>
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


<p>Binding: page 188</p>


<p>Banishing: page 188</p>
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


<div id="con5th">

<p>Page 300</p>

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

</div>


<div id="con6th">

<p>Reminder that Spirits are self-aware entities unlike Watcher spirits. If an unpleasant or painful service, reduce Spirit Reputation by 1.</p>


<p>Summoning: Page 146</p>

<ul>
  <li>Decide on the Force of the Spirit you're summoning</li>
  <li>Roll Conjuring + Magic vs Force x 2.</li>
  <li>services are equal to Net Hits</li>
  <li>Resist drain == Number of total Hits.</li>
  <li>stun unless greater than Magic rank then Physical</li>
  <li>Spirit is released when services are completed, or after the second sunrise or sunset, or Willpower based condition is filled.</li>
  <li>Spend reagents equal to the spirit Force to gain a point of Edge.</li>
  <li>No more than Magic x 3 in total Spirit Force is allowed.</li>
</ul>

<p>Banishing: Page 147</p>

<ul>
  <li>Major Action</li>
  <li>Conjuring + Magic vs Force x 2.</li>
  <li>Net hits reduce Spirit Services.</li>
  <li>Drain is twice the number of total Hits</li>
  <li>stun unless greater than Magic rank then Physical</li>
</ul>

</div>


</div>

</div>


<div id="enchanting">

<div id="enchantingtabs">

<ul>
  <li><a href="#enc1st">1st Edition</a></li>
  <li><a href="#enc2nd">2nd Edition</a></li>
  <li><a href="#enc3rd">3rd Edition</a></li>
  <li><a href="#enc4th">4th Edition</a></li>
  <li><a href="#enc5th">5th Edition</a></li>
  <li><a href="#enc6th">6th Edition</a></li>
</ul>


<div id="enc1st">

<p>First</p>

</div>


<div id="enc2nd">

<p>Second</p>

</div>


<div id="enc3rd">

<p>Third</p>

</div>


<div id="enc4th">

<p>Fourth</p>

</div>


<div id="enc5th">

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


<div id="enc6th">

<p>Sixth</p>

</div>


</div>

</div>


</div>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
