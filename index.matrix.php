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



<p>This section provides a list of matrix tasks and how to accomplish them.</p>



<p><strong>Access a Commlink</strong></p>



<p><strong>Attack a Commlink</strong></p>



<p><strong>Access a Cyberdeck</strong></p>


<p><strong>Attack a Cyberdeck</strong></p>



<p><strong>Access a Personal Area Network (PAN)</strong></p>


<p><strong>Attack a Personal Area Network (PAN)</strong></p>



<p><strong>Access a Host</strong></p>


<p><strong>Attack a Host</strong></p>



<p><strong>Access a Drone</strong></p>


<p><strong>Attack a Drone</strong></p>



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
  <li><strong>Backdoor Entry</strong></li>
  <li>Brute Force</li>
  <li>Data Spike</li>
  <li>Hide</li>
  <li>Probe</li>
  <li>Spoof Command</li>
  <li>Tarpit</li>
</ul>

<p>Illegal User Actions: You must have used the Brute Force action for this level. Plus 1 per round to your Overwatch Score for each host.</p>

<ul>
  <li>Brute Force</li>
  <li>Crack File</li>
  <li>Data Spike</li>
  <li>Erase Matrix Signature</li>
  <li>Hash Check</li>
  <li>Hide</li>
  <li>Probe</li>
  <li>Spoof Command</li>
  <li>Tarpit</li>
</ul>

<p>Illegal Admin Actions: You must have used Brute Force or Backdoor Entry for this leve. Admin only actions are bolded. Plus 3 per round to your Overwatch Score for each host.</p>

<ul>
  <li>Brute Force</li>
  <li><strong>Check OS (Overwatch Score)</strong></li>
  <li>Crack File</li>
  <li><strong>Crash Program</strong></li>
  <li>Data Spike</li>
  <li>Erase Matrix Signature</li>
  <li>Hash Check</li>
  <li>Hide</li>
  <li><strong>Jam Signals</strong></li>
  <li>Probe</li>
  <li><strong>Set Data Bomb</strong></li>
  <li><strong>Snoop</strong></li>
  <li>Spoof Command</li>
  <li>Tarpit</li>
  <li><strong>Trace Icon</strong></li>
</ul>

<p>The following programs can be illegally installed and run.</p>

<p>Note: Having these running increases your Overwatch Score by 1 for each Action performed.</p>

<ul>
  <li>Armor: +2 to Defense Rating</li>
  <li>Biofeedback: Causes Stun if cold-sim or Physical if hot-sim.</li>
  <li>Biofeedback Filter: Allow Device Rating or Body roll to soak Matrix Damage.</li>
  <li>Blackout: Causes Stun damage only.</li>
  <li>Decription: +2 on Crack File action.</li>
  <li>Defuse: Allow Device Rating or Body roll to soak Data Bomb Damage.</li>
  <li>Exploit: Reduce target Defense Rating by 2.</li>
  <li>Fork: Hit two targets with a single Matrix action.</li>
  <li>Lockdown: Cause link-lock when doing Matrix Damage.</li>
  <li>Overclock: Add 2 to any Matrix action. One is a Wild Die</li>
  <li>Stealth: When Hide action, gain 1 Edge.</li>
  <li>Trace: When Trace Icon action, add 1 Edge.</li>
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

<p>Matrix Condition Monitor: ceil(Device Rating / 2) + 8</p>

<p>Biofeedback Resistance: Willpower</p>

<p>Matrix Damage: Firewall</p>


<p><strong>Attacking a Persona or IC</strong></p>

<p>Attack Rating: Attack + Sleaze</p>

<p>Defense Rating: Data Processing + Firewall</p>





</div>


</div>

</div>


</div>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
