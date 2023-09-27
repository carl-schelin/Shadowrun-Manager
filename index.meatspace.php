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
  $( "#tabs"        ).tabs( ).addClass( "tab-shadow" );
  $( "#combattabs"  ).tabs( ).addClass( "tab-shadow" );
  $( "#rangedtabs"  ).tabs( ).addClass( "tab-shadow" );
  $( "#meleetabs"   ).tabs( ).addClass( "tab-shadow" );
  $( "#vehicletabs" ).tabs( ).addClass( "tab-shadow" );
});

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main"> 

<div id="tabs">

<ul>
  <li><a href="#combat">Combat Sequence</a></li>
  <li><a href="#ranged">Ranged Combat</a></li>
  <li><a href="#melee">Melee Combat</a></li>
  <li><a href="#vehicle">Vehicle Combat</a></li>
</ul>

<div id="combat">

<div id="combattabs">

<ul>
  <li><a href="#com1st">1st Edition</a></li>
  <li><a href="#com2nd">2nd Edition</a></li>
  <li><a href="#com3rd">3rd Edition</a></li>
  <li><a href="#com4th">4th Edition</a></li>
  <li><a href="#com5th">5th Edition</a></li>
  <li><a href="#com6th">6th Edition</a></li>
</ul>


<div id="com1st">

<p>First</p>

</div>


<div id="com2nd">

<p>Second</p>

</div>


<div id="com3rd">

<p>Third</p>

</div>


<div id="com4th">

<p>Fourth</p>

</div>


<div id="com5th">

<p>Starts SR5 - Page 158</p>
<ol>
  <li>Roll Initiative - sr5-159</li>
  <li>Begin Initiative Pass. Highest to lowest.</li>
  <li>Declare Actions - 1 free and 2 Simple or 1 complex.
    <ol type=\"a\"><li>Free: Call a Shot - Pg 163 ref: Pg 178</li>
                   <li>Free: Changed Linked Device Mode - pg 163</li>
                   <li>Free: Drop Object - pg 163</li>
                   <li>Free: Drop Prone - pg 164 ref: pg 192</li>
                   <li>Free: Eject Smartgun Clip - pg 164 ref: pg 433</li>
                   <li>Free: Gesture - pg 164</li>
                   <li>Free: Multiple Attacks - pg 164</li>
                   <li>Free: Run - pg 164</li>
                   <li>Free: Speak/Text/Transmit Phrase - pg 164</li>
                   <li>Simple: Active Focus</li>
                   <li>Simple: Call Spirit</li>
                   <li>Simple: Change Device Mode</li>
                   <li>Simple: Command Spirit</li>
                   <li>Simple: Dismiss Spirit</li>
                   <li>Simple: Fire Bow</li>
                   <li>Simple: Fire Weapon (SA, SS, BF, FA)</li>
                   <li>Simple: Insert Clip</li>
                   <li>Simple: Observe in Detail</li>
                   <li>Simple: Pick Up/Put Down Object</li>
                   <li>Simple: Quick Draw</li>
                   <li>Simple: Ready/Draw Weapon</li>
                   <li>Simple: Reckless Spellcasting</li>
                   <li>Simple: Reload Weapon (see Table)</li>
                   <li>Simple: Remove Clip</li>
                   <li>Simple: Shift Perception</li>
                   <li>Simple: Take Aim</li>
                   <li>Simple: Take Cover</li>
                   <li>Simple: Throw Weapon</li>
                   <li>Simple: Use Simple Device</li>
                   <li>Complex: Astral Projection</li>
                   <li>Complex: Banish Spirit</li>
                   <li>Complex: Cast Spell</li>
                   <li>Complex: Fire Weapon (FA)</li>
                   <li>Complex: Fire Long or Semi-Auto Burst</li>
                   <li>Complex: Fire Mounted or Vehicle Weapon</li>
                   <li>Complex: Melee Attack</li>
                   <li>Complex: Reload Weapon (see Table)</li>
                   <li>Complex: Rigger Jump In</li>
                   <li>Complex: Sprint</li>
                   <li>Complex: Summoning</li>
                   <li>Complex: Use Skill</li>
                   <li>Interrupt: Block</li>
                   <li>Interrupt: Dodge</li>
                   <li>Interrupt: Full Defense</li>
                   <li>Interrupt: Hit the Dirt</li>
                   <li>Interrupt: Intercept</li>
                   <li>Interrupt: Parry</li>
  </ol></li>
  <li>Resolve Actions</li>
  <li>Subtract 10 from the Initiative, who is ever above 10, goes again until all are at or below zero.</li>
  <li>Start over</li>
</ol>

</div>


<div id="com6th">

<p>Sixth</p>

</div>


</div>

</div>


<div id="ranged">

<div id="rangedtabs">

<ul>
  <li><a href="#ran1st">1st Edition</a></li>
  <li><a href="#ran2nd">2nd Edition</a></li>
  <li><a href="#ran3rd">3rd Edition</a></li>
  <li><a href="#ran4th">4th Edition</a></li>
  <li><a href="#ran5th">5th Edition</a></li>
  <li><a href="#ran6th">6th Edition</a></li>
</ul>


<div id="ran1st">

<p>First</p>

</div>


<div id="ran2nd">

<p>Second</p>

</div>


<div id="ran3rd">

<p>Third</p>

</div>


<div id="ran4th">

<p>Fourth</p>

</div>


<div id="ran5th">

<p>Starts SR5 - Page 172</p>
<ol>
  <li>Declare</li>
  <li>Attack</li>
  <li>Defend</li>
  <li>Apply Effect</li>
</ol>
<p>Opposed Test: Weapon Skill + Agility [Weapon Accuracy] vs Reaction + Intuition (+ Willpower and -10 to Initiative
 if Full Defense)</p>
<ul>
  <li>Environmental</li>
  <li>Recoil</li>
  <li>Situational</li>
  <li>Wound</li>
</ul>

<p>Modifiers from SR4: Pg 150</p>
<ul>
  <li>Fire Mode (pg: )</li>
  <li>Agility (pg: )</li>
  <li>Edge (pg: )</li>
  <li>Weapon Skill (pg: )</li>
  <li>Burst mode or Shot (pg: )</li>
  <li>In Melee (enemy < 2m) (pg: )</li>
  <li>Running (pg: )</li>
  <li>Firing from Cover? (pg: )</li>
  <li>In a moving vehicle (pg: )</li>
  <li>Laser or smartlink Active (pg: )</li>
  <li>Long Shot (use 1 edge) (pg: )</li>
  <li>Off Hand (pg: )</li>
  <li>Area Effect (grenade, missile...) (pg: )</li>
  <li>Heavy Weapon? (pg: )</li>
  <li>Dumpshocked? (pg: )</li>
  <li>Gyro-Stabilized? (pg: )</li>
  <li>Ambidextrous? (pg: )</li>
  <li>Home Ground? (pg: )</li>
  <li>Wound Modifier (pg: )</li>
  <li>Range Modifier (0 to -3) (pg: )</li>
  <li>Vision Modifier (pg: )</li>
  <li>Called Shot (DP/DV) (pg: )</li>
  <li>Recoil Compensation (Full Comp) (pg: )</li>
  <li>Aimed Shot? (+1DP per delay) (pg: )</li>
  <li>Additional Targets this phase (pg: )</li>
  <li>Shotgun? Spread Narrow, Medium, Wide (pg: )</li>
</ul>

<p>Defender</p>
<ul>
  <li>Reaction: (pg: )</li>
  <li>Full Defense (pg: )</li>
  <li>Dodge (pg: )</li>
  <li>Gymnastics (pg: )</li>
  <li>Cover (%) 0, 25, 50, 75, 100 (pg: )</li>
  <li>Wound Modifier (pg: )</li>
  <li>Number of Previous Attacks (pg: )</li>
  <li>Dumpshocked (pg: )</li>
  <li>Prone (pg: )</li>
  <li>Home Ground (pg: )</li>
  <li>In a moving vehicle (pg: )</li>
  <li>In a melee combat (pg: )</li>
</ul>

<p>Soak</p>
<ul>
  <li>Body: (pg: )</li>
  <li>Ballistic: (pg: )</li>
  <li>Modified: (pg: )</li>
  <li>Impact: (pg: )</li>
  <li>Modified: (pg: )</li>
</ul>

</div>


<div id="ran6th">

<p>Sixth</p>

</div>


</div>

</div>


<div id="melee">

<div id="meleetabs">

<ul>
  <li><a href="#mel1st">1st Edition</a></li>
  <li><a href="#mel2nd">2nd Edition</a></li>
  <li><a href="#mel3rd">3rd Edition</a></li>
  <li><a href="#mel4th">4th Edition</a></li>
  <li><a href="#mel5th">5th Edition</a></li>
  <li><a href="#mel6th">6th Edition</a></li>
</ul>


<div id="mel1st">

<p>First</p>

</div>


<div id="mel2nd">

<p>Second</p>

</div>


<div id="mel3rd">

<p>Third</p>

</div>


<div id="mel4th">

<p>Fourth</p>

</div>


<div id="mel5th">

<p>Starts SR5 - Page 184</p>
<ol>
  <li>Declare</li>
  <li>Attack</li>
  <li>Defend</li>
  <li>Apply Effect</li>
</ol>
<p>Opposed Test: Combat Skill + Agility [Weapon Accuracy] vs Options:</br>";
1. Free Action: Reaction + Intuition</br>";
2. Interrupt (-5 init): Parry (has weapon): Reaction + Intuition + Weapon Skill [Physical]</br>";
3. Interrupt (-5 init): Block (empty handed): Reaction + Intuition + Unarmed Combat Skill [Physical]</br>";
4. Interrupt (-5 init): Dodge: Reaction + Intuition + Gymnastics [Physical]</br>";
5. Interrupt (-10 init): Full Defense: Reaction + Intuition + Willpower [Physical]</p>";

</div>


<div id="mel6th">

<p>Sixth</p>

</div>


</div>

</div>


<div id="vehicle">

<div id="vehicletabs">

<ul>
  <li><a href="#veh1st">1st Edition</a></li>
  <li><a href="#veh2nd">2nd Edition</a></li>
  <li><a href="#veh3rd">3rd Edition</a></li>
  <li><a href="#veh4th">4th Edition</a></li>
  <li><a href="#veh5th">5th Edition</a></li>
  <li><a href="#veh6th">6th Edition</a></li>
</ul>


<div id="veh1st">

<p>First</p>

</div>


<div id="veh2nd">

<p>Second</p>

</div>


<div id="veh3rd">

<p>Third</p>

</div>


<div id="veh4th">

<p>Fourth</p>

</div>


<div id="veh5th">

<p>Fifth</p>

</div>


<div id="veh6th">

<p>Sixth</p>

</div>


</div>

</div>


</div>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
