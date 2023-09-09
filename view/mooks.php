<?php
# Script: mooks.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description:

  include('settings.php');
  $called = "No";
  include($Sitepath . '/guest.php');

  $package = "mooks.php";

  logaccess($formVars['username'], $package, "Accessing the script");

  $formVars['id'] = 1;
  if (isset($_GET['id'])) {
    $formVars['id'] = clean($_GET['id'], 10);
  }

  if (!mooks_Available($formVars['id'])) {
    include($Loginpath . '/user_level.php');
    exit;
  }

# basically looking for the user name and magic stat for the display
  $q_string  = "select runr_name,runr_magic ";
  $q_string .= "from runners ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_runners = mysql_fetch_array($q_runners);

# now seeing if the runner has any vehicles again for display
  $vehicles = 'no';
  $q_string  = "select r_veh_id ";
  $q_string .= "from r_vehicles ";
  $q_string .= "where r_veh_character = " . $formVars['id'] . " ";
  $q_r_vehicles = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  if (mysql_num_rows($q_r_vehicles) > 0) {
    $vehicles = 'yes';
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Viewing <?php print $a_runners['runr_name']; ?></title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">

function edit_RunnerCondition( p_id, p_function) {
  var er_url = 'condition.checked.mysql.php';

  er_url += "?id="            + <?php print $formVars['id']; ?>;
  er_url += "&cond_id="       + p_id;
  er_url += "&cond_function=" + p_function;

  show_file(er_url);
}

function edit_CommlinkCondition( p_id, p_commlink, p_function) {
  var er_url = 'condition.checked.mysql.php';

  er_url += "?id="            + p_commlink;
  er_url += "&cond_id="       + p_id;
  er_url += "&cond_function=" + p_function;

  show_file(er_url);
}

function edit_CyberdeckCondition( p_id, p_cyberdeck, p_function) {
  var er_url = 'condition.checked.mysql.php';

  er_url += "?id="            + p_cyberdeck;
  er_url += "&cond_id="       + p_id;
  er_url += "&cond_function=" + p_function;

  show_file(er_url);
}

function edit_CommandCondition( p_id, p_command, p_function) {
  var er_url = 'condition.checked.mysql.php';

  er_url += "?id="            + p_command;
  er_url += "&cond_id="       + p_id;
  er_url += "&cond_function=" + p_function;

  show_file(er_url);
}

function edit_SpriteCondition( p_id, p_sprite, p_function) {
  var er_url = 'condition.checked.mysql.php';

  er_url += "?id="            + p_sprite;
  er_url += "&cond_id="       + p_id;
  er_url += "&cond_function=" + p_function;

  show_file(er_url);
}

function edit_VehicleCondition( p_id, p_vehicle, p_function) {
  var er_url = 'condition.checked.mysql.php';

  er_url += "?id="            + p_vehicle;
  er_url += "&cond_id="       + p_id;
  er_url += "&cond_function=" + p_function;

  show_file(er_url);
}

function clear_fields() {
  show_file('active.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('adept.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('alchemy.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('armor.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('ammo.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('bioware.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('command.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('commlink.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('complexform.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('condition.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('contacts.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('costs.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('cyberdeck.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('cyberware.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('firearms.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('gear.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('identity.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('knowledge.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('language.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('lifestyle.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('melee.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('mentors.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('mooks.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('persona.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('pregen.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('projectile.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('qualities.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('spells.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('spirits.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('sprites.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('traditions.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('vehicles.mysql.php?id=<?php print $formVars['id']; ?>');
  show_file('visible.mysql.php?id=<?php print $formVars['id']; ?>');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<div class="main">

<div id="tabs">

<ul>
  <li><a href="#character">Character</a></li>
  <li><a href="#active">Skills</a></li>
  <li><a href="#visible">Visible</a></li>
  <li><a href="#pregens">Pregen View</a></li>
</ul>


<div id="character">

<span id="character_mysql"><?php print wait_Process('Character Loading...')?></span>

<span id="costs_mysql"><?php print wait_Process('Costs Loading...')?></span>

<span id="lifestyle_mysql"><?php wait_Process('Lifestyle Loading...'); ?></span>

<span id="identity_mysql"><?php wait_Process('Identity Loading...'); ?></span>

<span id="condition_mysql"><?php wait_Process('Condition Monitors Loading...'); ?></span>

<span id="qualities_mysql"><?php wait_Process('Qualities Loading...'); ?></span>

<span id="contacts_mysql"><?php wait_Process('Contacts Loading...'); ?></span>

<span id="spells_mysql"><?php wait_Process('Spells Loading...'); ?></span>

<span id="alchemy_mysql"><?php wait_Process('Alchemical Preparations Loading...'); ?></span>

<span id="traditions_mysql"><?php wait_Process('Traditions Loading...'); ?></span>

<span id="mentors_mysql"><?php wait_Process('Mentor Spirits Loading...'); ?></span>

<span id="spirits_mysql"><?php wait_Process('Spirits Loading...'); ?></span>

<span id="adept_mysql"><?php wait_Process('Adapt Powers Loading...'); ?></span>

<span id="commlink_mysql"><?php wait_Process('Commlink Loading...'); ?></span>

<span id="command_mysql"><?php wait_Process('Command Console Loading...'); ?></span>

<span id="cyberdeck_mysql"><?php wait_Process('Cyberdeck Loading...'); ?></span>

<span id="persona_mysql"><?php wait_Process('Persona Loading...'); ?></span>

<span id="sprites_mysql"><?php wait_Process('Sprites Loading...'); ?></span>

<span id="complexform_mysql"><?php wait_Process('Complex Forms Loading...'); ?></span>

<span id="armor_mysql"><?php wait_Process('Armor Loading...'); ?></span>

<span id="cyberware_mysql"><?php wait_Process('Cyberware Loading...'); ?></span>

<span id="bioware_mysql"><?php wait_Process('Bioware Loading...'); ?></span>

<span id="gear_mysql"><?php wait_Process('Gear Loading...'); ?></span>

<span id="melee_mysql"><?php wait_Process('Melee Weapons Loading...'); ?></span>

<span id="projectile_mysql"><?php wait_Process('Projectile Weapons Loading...'); ?></span>

<span id="firearms_mysql"><?php wait_Process('Firearms Loading...'); ?></span>

<span id="ammo_mysql"><?php wait_Process('Ammunition Loading...'); ?></span>

<span id="vehicles_mysql"><?php wait_Process('Vehicles Loading...'); ?></span>

</div>


<div id="active">

<span id="active_mysql"><?php wait_Process('Active Skills Loading...'); ?></span>

<span id="knowledge_mysql"><?php wait_Process('Knowledge Skills Loading...'); ?></span>

<span id="language_mysql"><?php wait_Process('Language Skills Loading...'); ?></span>

</div>


<div id="visible">

<span id="visible_mysql"><?php print wait_Process('Visible Character Loading...')?></span>

</div>


<div id="pregens">

<span id="pregen_mysql"><?php print wait_Process('Pregen Sheet Loading...')?></span>

</div>


</div>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
