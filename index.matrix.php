<?php
  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Shadowrunner);

  logaccess($db, $_SESSION['username'], "index.php", "Checking out the index.");

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
  $( "#matrixtabs"  ).tabs( ).addClass( "tab-shadow" );
  $( "#legaltabs"   ).tabs( ).addClass( "tab-shadow" );
  $( "#hackingtabs" ).tabs( ).addClass( "tab-shadow" );
  $( "#cybertabs"   ).tabs( ).addClass( "tab-shadow" );
});

</script>

</head>
<body class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div id="main"> 

<div id="tabs">

<ul>
  <li><a href="#matrix">Matrix Work</a></li>
  <li><a href="#legal">Legal Access</a></li>
  <li><a href="#hacking">Hacking</a></li>
  <li><a href="#cyber">Cybercombat</a></li>
</ul>


<div id="matrix">

<div id="matrixtabs">

<ul>
  <li><a href="#mat1st">1st Edition</a></li>
  <li><a href="#mat2nd">2nd Edition</a></li>
  <li><a href="#mat3rd">3rd Edition</a></li>
  <li><a href="#mat4th">4th Edition</a></li>
  <li><a href="#mat5th">5th Edition</a></li>
  <li><a href="#mat6th">6th Edition</a></li>
</ul>


<div id="mat1st">

<p>First</p>

</div>


<div id="mat2nd">

<p>Second</p>

</div>


<div id="mat3rd">

<p>Third</p>

</div>


<div id="mat4th">

<p>Fourth</p>

</div>


<div id="mat5th">

<p>Fifth</p>

</div>


<div id="mat6th">


<p></p>

<p>This section provides a list of matrix tasks and how to accomplish them.</p>

<p><a href="https://www.shadowrunsixthworld.com/shadowrun-sixth-world-faq/#matrix-device">Matrix FAQ</a> - If you need to find some clarity.</p>

<p><strong>Preparation</strong></p>

<p>You can rotate your non-zero stats (pg 174) between the ASDF to find the best combo, even between devices. However when starting a hack, your AS cannot be changed. Pg 178</p>

<p>If you use an Action or Program that is linked to Sleaze or Attack and the attribute is lower than the other, the difference is a negative to the dice pool.</p>

<p><strong>Illegal Actions</strong></p>

<ul>
  <li>Backdoor Entry - Linked to Sleaze</li>
  <li>Brute Force - Linked to Attack</li>
  <li>Data Spike - Linked to Attack</li>
  <li>Probe - Linked to Sleaze</li>
  <li>Tarpit - Linked to Attack</li>
</ul>

<p><strong>Illegal Programs</strong></p>

<ul>
  <li>Biofeedback: Causes Stun if cold-sim or Physical if hot-sim. - Linked to Attack</li>
  <li>Blackout: Causes Stun damage only.</li - Linked to Attack>
  <li>Stealth: When Hide action, gain 1 Edge. - Linked to Sleaze</li>
  <li>Trace: When Trace Icon action, add 1 Edge. - Linked to Sleaze</li>
</ul>

<p><strong>Matrix Access</strong></p>

<p>This is the process for accessing the following Matrix Devices</p>

<p>Both Brute Force and Probe are Illegal actions.

<p>As a reminder, the discussion is about default values of the following devices. Since you can shift values around, functions follow the values.</p>


<p><strong>Access a Commlink</strong></p>

<p>Commlinks have a Data Processing between 1 and 3 and the Firewall stat is either 0 or 1.</p>

<p>with such low values, either method will work however Probe is best as the owner isn't alerted by the attempt.</p>

<p>If a Brute Force is attempted and the owner is a hacker, they might reboot to clear the attack. Otherwise the owner will futz with it for a bit, maybe ignore it, maybe take it to a Stuffshack or repair location to investigate.</p>


<p><strong>Access a Cyberdeck</strong></p>

<p>A Cyberdeck treats D/F as 0/0 (pg 174). Hence attacking a Cyberdeck is easy, however with a D of 0, it can't run programs.</p>


<p><strong>Access a Cyberjack</strong></p>

<p>This device does have a D/F so you'll use normal actions as described below.</p>


<p><strong>Access a Personal Area Network (PAN)</strong></p>

<p>A PAN is a collection of Matrix gear. It can also incorporate the PANs of the team, as long as they're withing 100 meters. The Persona of the Network Controller becomes the collected PAN defense.</p>

<p>Controlling a PAN depends on the devices that incorporate it. For a single, non Runner, it might simply be the Commlink. For a decker, likely it would be the Cyberdeck+CyberJack combo or Cyberdeck+Commlink combo.</p>



<p><strong>Access a Host</strong></p>

<p>A host has a rating of 1-12 and the ASDF are rating, +1, +2, and +3 in any order (and can be shifted before an attack).</p>

<p>The Patrol IC monitors the network, going from host to host. It does a Matrix Perception Action against Icons/Personas on a host.</p>

<p>If detected, IC up to the host rating can be started (remember Patrol is IC). The security staff are also notified.</p>




<p><strong>Access a Drone</strong></p>





</div>


</div>

</div>


<div id="legal">

<div id="legaltabs">

<ul>
  <li><a href="#leg1st">1st Edition</a></li>
  <li><a href="#leg2nd">2nd Edition</a></li>
  <li><a href="#leg3rd">3rd Edition</a></li>
  <li><a href="#leg4th">4th Edition</a></li>
  <li><a href="#leg5th">5th Edition</a></li>
  <li><a href="#leg6th">6th Edition</a></li>
</ul>


<div id="leg1st">

<p>First</p>

</div>


<div id="leg2nd">

<p>Second</p>

</div>


<div id="leg3rd">

<p>Third</p>

</div>


<div id="leg4th">

<p>Fourth</p>

</div>


<div id="leg5th">

<p>Fifth</p>

</div>


<div id="leg6th">

<p>This section lists the legal things you can do in the Matrix in the 6th World</p>

<p>The following list are the actions you can perform legally, without involving GOD.</p>

<p>Outsider Actions</p>

<ul>
  <li>Enter/Exit Host with Outsider access level</li>
  <li>Full Matrix Defense</li>
  <li>Jack Out</li>
  <li>Matrix Perception</li>
  <li>Matrix Search</li>
  <li>Send Message</li>
</ul>

<p>User Actions: You must have used the Brute Force action</p>

<ul>
  <li>Change Icon</li>
  <li>Control Device</li>
  <li>Disarm Data Bomb</li>
  <li>Edit File</li>
  <li>Encrypt File</li>
  <li>Enter/Exit Host with User access level</li>
  <li>Full Matrix Defense</li>
  <li>Jack Out</li>
  <li>Jump Into Rigged Device</li>
  <li>Matrix Perception</li>
  <li>Matrix Search</li>
  <li>Send Message</li>
</ul>

<p>Admin Actions: You can use Brute Force or Backdoor Entry actions. Admin only are bolded.</p>

<ul>
  <li>Change Icon</li>
  <li>Control Device</li>
  <li>Disarm Data Bomb</li>
  <li>Edit File</li>
  <li>Encrypt File</li>
  <li>Enter/Exit Host with admin access level</li>
  <li><strong>Format Device</strong></li>
  <li>Full Matrix Defense</li>
  <li>Jack Out</li>
  <li>Jump Into Rigged Device</li>
  <li>Matrix Perception</li>
  <li>Matrix Search</li>
  <li><strong>Reboot Device</strong></li>
  <li><strong>Reconfigure Matrix Attribute</strong></li>
  <li>Send Message</li>
  <li><strong>Switch Interface Mode</strong></li>
</ul>

<p>The following programs can be legally installed and run.</li>

<ul>
  <li>Baby Monitor: Tells Overwatch Score with using a Matrix action.</li>
  <li>Browse: Gain 1 Edge when doing a Matrix Search action.</li>
  <li>Configurator: Store alternate deck configurations. Swap to it instead of changing two attributes.</li>
  <li>Edit: Gain 1 Edge when doing Edit File action.</li>
  <li>Encryption: +2 dice when doing Encrypt File action.</li>
  <li>Signal Scrubber: Reduce noise level by 2.</li>
  <li>Toolbox: +1 to Data Processing.</li>
  <li>Virtual Machine: 2 extra program slots but take 1 extra box of Matrix Damage when attacked.</li>
</ul>

</div>


</div>

</div>


<div id="hacking">

<div id="hackingtabs">

<ul>
  <li><a href="#hck1st">1st Edition</a></li>
  <li><a href="#hck2nd">2nd Edition</a></li>
  <li><a href="#hck3rd">3rd Edition</a></li>
  <li><a href="#hck4th">4th Edition</a></li>
  <li><a href="#hck5th">5th Edition</a></li>
  <li><a href="#hck6th">6th Edition</a></li>
</ul>


<div id="hck1st">

<p>First</p>

</div>


<div id="hck2nd">

<p>Second</p>

</div>


<div id="hck3rd">

<p>Third</p>

</div>


<div id="hck4th">

<p>Hacking SR4 236</p>
<ul>
  <li>Exploit (pg: )</li>
  <li>Stealth (pg: )</li>
  <li>Hacking (pg: )</li>
  <li>Extended TEst (pg: )</li>
  <li>Personal, Security, Admin (pg: )</li>
</ul>

<p>Node Info</p>
<ul>
  <li>Analyze (pg: )</li>
  <li>System (pg: )</li>
  <li>Firewall? (pg: )</li>
</ul>

</div>


<div id="hck5th">

<p>Fifth</p>

</div>


<div id="hck6th">

<p>This section lists the illegal things you can do in the Matrix in the 6th World</p>

<p>The following list are the actions you can perform illegally.</p>

<p>Plus 1 to your Overwatch Score per every opposing hit. Reaching 40 will invoke Convergence and involves GOD filling the Condition Monitor and reporting your physical location to the authorities.</p>

<p>Illegal Outsider Actions: Outsider only actions are bolded.</p>

<ul>
  <li><strong>Backdoor Entry</strong> - Linked to Sleaze</li>
  <li>Brute Force - Linked to Attack</li>
  <li>Data Spike - Linked to Attack</li>
  <li>Hide</li>
  <li>Probe - Linked to Sleaze</li>
  <li>Spoof Command</li>
  <li>Tarpit - Linked to Attack</li>
</ul>

<p>Illegal User Actions: You must have used the Brute Force action for this level. Plus 1 per round to your Overwatch Score for each host.</p>

<ul>
  <li>Brute Force - Linked to Attack</li>
  <li>Crack File</li>
  <li>Data Spike - Linked to Attack</li>
  <li>Erase Matrix Signature</li>
  <li>Hash Check</li>
  <li>Hide</li>
  <li>Probe - Linked to Sleaze</li>
  <li>Spoof Command</li>
  <li>Tarpit - Linked to Attack</li>
</ul>

<p>Illegal Admin Actions: You must have used Brute Force or Backdoor Entry for this leve. Admin only actions are bolded. Plus 3 per round to your Overwatch Score for each host.</p>

<ul>
  <li>Brute Force - Linked to Attack</li>
  <li><strong>Check OS (Overwatch Score)</strong></li>
  <li>Crack File</li>
  <li><strong>Crash Program</strong></li>
  <li>Data Spike - Linked to Attack</li>
  <li>Erase Matrix Signature</li>
  <li>Hash Check</li>
  <li>Hide</li>
  <li><strong>Jam Signals</strong></li>
  <li>Probe - Linked to Sleaze</li>
  <li><strong>Set Data Bomb</strong></li>
  <li><strong>Snoop</strong></li>
  <li>Spoof Command</li>
  <li>Tarpit - Linked to Attack</li>
  <li><strong>Trace Icon</strong></li>
</ul>

<p>The following programs can be illegally installed and run.</p>

<p>Note: Having these running increases your Overwatch Score by 1 for each Action performed.</p>

<ul>
  <li>Armor: +2 to Defense Rating</li>
  <li>Biofeedback: Causes Stun if cold-sim or Physical if hot-sim. - Linked to Attack</li>
  <li>Biofeedback Filter: Allow Device Rating or Body roll to soak Matrix Damage.</li>
  <li>Blackout: Causes Stun damage only.</li - Linked to Attack>
  <li>Decription: +2 on Crack File action.</li>
  <li>Defuse: Allow Device Rating or Body roll to soak Data Bomb Damage.</li>
  <li>Exploit: Reduce target Defense Rating by 2.</li>
  <li>Fork: Hit two targets with a single Matrix action.</li>
  <li>Lockdown: Cause link-lock when doing Matrix Damage.</li>
  <li>Overclock: Add 2 to any Matrix action. One is a Wild Die</li>
  <li>Stealth: When Hide action, gain 1 Edge. - Linked to Sleaze</li>
  <li>Trace: When Trace Icon action, add 1 Edge. - Linked to Sleaze</li>
</ul>

</div>


</div>

</div>


<div id="cyber">

<div id="cybertabs">

<ul>
  <li><a href="#cbr1st">1st Edition</a></li>
  <li><a href="#cbr2nd">2nd Edition</a></li>
  <li><a href="#cbr3rd">3rd Edition</a></li>
  <li><a href="#cbr4th">4th Edition</a></li>
  <li><a href="#cbr5th">5th Edition</a></li>
  <li><a href="#cbr6th">6th Edition</a></li>
</ul>


<div id="cbr1st">

<p>First</p>

</div>


<div id="cbr2nd">

<p>Second</p>

</div>


<div id="cbr3rd">

<p>Third</p>

</div>


<div id="cbr4th">

<p>Fourth</p>

</div>


<div id="cbr5th">

<p>Starts SR5 - Page </p>
<p></p>
<ol>
  <li></li>
</ol>


<p>Matrix Combat</p>
<ul>
  <li>Attacker: Icon or Digital (pg: )</li>
  <li>Attack Program Rating (pg: )</li>
  <li>Black IC? (pg: )</li>
  <li>Cybercombat (pg: )</li>
  <li>matrix Wound Modifier (pg: )</li>
  <li>Simsense Link, Cold/Hot (pg: )</li>
</ul>

<p>Defender</p>
<ul>
  <li>Response (pg: )</li>
  <li>Firewall (pg: )</li>
  <li>Full Defense (pg: )</li>
  <li>Hacking (pg: )</li>
  <li>Simsinse Link hot/cold (pg: )</li>
  <li>Matrix Wound Modifier (pg: )</li>
  <li>Soak System (pg: )</li>
  <li>Soak Armor (pg: )</li>
</ul>

</div>


<div id="cbr6th">

<p>Page 179</p>

<p>For CyberCombat, you need to determine your initiative, damage, and how to attack a Persona or IC.</p>

<p><strong>Matrix Initiative</strong></p>

<ul>
  <li>AR Initiative: Reaction + Intuition</li>
  <li>VR Cold Sim: Intuition + Data Processing + 1d6</li>
  <li>VR Hot Sim: Intuition + Data Processing + 2d6</li>
</ul>

<p><strong>Damage</strong></p>

<p>Matrix Condition Monitor: ceil(Device Rating / 2) + 8. Filling the MCM bricks the device.</p>

<p>Note from Page 176: "Cyberjacks are a fairly invasive piece of gear, implanted deep into the brain"</p>

<p>Biofeedback Resistance: Willpower</p>

<p>Matrix Damage: Firewall</p>

<p><strong>Attacking a Persona (IC is also a Persona)</strong></p>

<p>Matrix stats for a Decker based Persona. ASDF of the device replaces physical stats. Mental stats remain.</p>

<p>For non Decker Personas such as IC, Agents, and Programs, they use the Host Attack Rating (A+S) and MCM is Data Processing x 2 + 3d6. An agent does have CILW equal to rating.</p>

<p>Hosts ratings range from 1-12. ASDF is Rating plus 1, plus 2, plus 3, plus 4 in any order. (pg 185)</p>



<p>Attack Rating: Attack + Sleaze</p>

<p>Defense Rating: Data Processing + Firewall</p>

<ul>
  <li>1 Edge - Emergency Boost. Temporarily increase Matrix attribute by 1 for one test.</li>
  <li>2 Edge - Hog. </li>
  <li>2 Edge - Signal Scream. Ignore noise penalty for next action.</li>
  <li>2 Edge - Technobabble. Use Charisma instead of Logic. Technomancer only.</li>
  <li>3 Edge - Under the Radar. OS does not increase for the next illegal action.</li>
</ul>

<ul>
  <li>Grab Dice. Legal is Electronics+Logic. Illegal is Cracking+Logic.</li>
  <li>Distribute Edge. See above.</li>
  <li>Roll Dice.</li>
  <li>Determine Effect.</li>
</ul>







</div>


</div>

</div>


</div>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
