<?php
# Script: mooks.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');

# connect to the database
  $db = db_connect($DBserver, $DBname, $DBuser, $DBpassword);

  check_login($db, $AL_Shadowrunner);

  $package = "mooks.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

# if help has not been seen yet,
  if (show_Help($db, $Dataroot . "/" . $package)) {
    $display = "display: block";
  } else {
    $display = "display: none";
  }

  if (isset($_GET['id'])) {
    $formVars['id'] = clean($_GET['id'], 10);
  }

  if (!isset($formVars['id']) || $formVars['id'] == '') {
    $formVars['id'] = 0;
  }

  $q_string  = "select runr_owner,runr_aliases,runr_name,runr_archetype,runr_agility,runr_body,runr_reaction,runr_strength,";
  $q_string .= "runr_charisma,runr_intuition,runr_logic,runr_willpower,runr_metatype,runr_essence,runr_totaledge,";
  $q_string .= "runr_currentedge,runr_magic,runr_initiate,runr_resonance,runr_age,runr_sex,runr_height,";
  $q_string .= "runr_physicalcon,runr_stuncon,runr_desc,runr_sop,runr_available,runr_version ";
  $q_string .= "from runners ";
  $q_string .= "where runr_id = " . $formVars['id'] . " ";
  $q_runners = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  $a_runners = mysqli_fetch_array($q_runners);

  if ($a_runners['runr_available']) {
    $available = " checked";
  } else {
    $available = "";
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Editing <?php print $a_runners['runr_name'];?></title>

<style type='text/css' title='currentStyle' media='screen'>
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">

<?php
  include('mooks.detail.php');
  include('mooks.matrix.php');
  include('mooks.magic.php');
  include('mooks.meatspace.php');
?>

function clear_fields() {

  show_file('mooks.fill.php'        + '?update=-3&id=<?php print $formVars['id']; ?>');
<?php
# load all the forms
  if ($formVars['id'] > 0) {
?>
  show_file('mycommlink.mysql.php'      + '?update=-3&r_link_character=<?php print $formVars['id']; ?>');
  show_file('commlink.mysql.php'        + '?update=-3&r_link_character=<?php print $formVars['id']; ?>');
  show_file('program.mysql.php'         + '?id=0');
  show_file('program.mysql.php'         + '?id=1');
  show_file('agent.mysql.php'           + '?id=0');
  show_file('linkacc.mysql.php'         + '?update=-1&r_link_id=0');

  show_file('mycommand.mysql.php'       + '?update=-3&r_cmd_character=<?php print $formVars['id']; ?>');
  show_file('command.mysql.php'         + '?update=-3&r_cmd_character=<?php print $formVars['id']; ?>');
  show_file('cmdacc.mysql.php'          + '?update=-1&r_cmd_id=0');
  show_file('program.mysql.php'         + '?id=2');
  show_file('program.mysql.php'         + '?id=3');

  show_file('mycyberdeck.mysql.php'     + '?update=-3&r_deck_character=<?php print $formVars['id']; ?>');
  show_file('cyberdeck.mysql.php'       + '?update=-3&r_deck_character=<?php print $formVars['id']; ?>');
  show_file('program.mysql.php'         + '?id=4');
  show_file('program.mysql.php'         + '?id=5');
  show_file('agent.mysql.php'           + '?id=2');
  show_file('deckacc.mysql.php'         + '?update=-1&r_deck_id=0');

  show_file('mycyberjack.mysql.php'     + '?update=-3&r_jack_character=<?php print $formVars['id']; ?>');
  show_file('cyberjack.mysql.php'       + '?update=-3&r_jack_character=<?php print $formVars['id']; ?>');


  show_file('active.mysql.php'          + '?update=-3&r_act_character=<?php print $formVars['id']; ?>');
  show_file('adept.mysql.php'           + '?update=-3&r_adp_character=<?php print $formVars['id']; ?>');
  show_file('alchemy.mysql.php'         + '?update=-3&r_alc_character=<?php print $formVars['id']; ?>');
  show_file('ammo.mysql.php'            + '?update=-3&r_ammo_character=<?php print $formVars['id']; ?>');
  show_file('armor.mysql.php'           + '?update=-3&r_arm_character=<?php print $formVars['id']; ?>');
  show_file('armoracc.mysql.php'        + '?update=-1&r_arm_id=0');
  show_file('bioware.mysql.php'         + '?update=-3&r_bio_character=<?php print $formVars['id']; ?>');
  show_file('contact.mysql.php'         + '?update=-3&r_con_character=<?php print $formVars['id']; ?>');
  show_file('complexform.mysql.php'     + '?update=-3&r_form_character=<?php print $formVars['id']; ?>');
  show_file('cyberware.mysql.php'       + '?update=-3&r_ware_character=<?php print $formVars['id']; ?>');
  show_file('cyberacc.mysql.php'        + '?update=-1&r_ware_id=0');
  show_file('finance.mysql.php'         + '?update=-3&fin_character=<?php print $formVars['id']; ?>');
  show_file('firearms.mysql.php'        + '?update=-3&r_fa_character=<?php print $formVars['id']; ?>');
  show_file('fireacc.mysql.php'         + '?update=-3&r_fa_id=0');
  show_file('foci.mysql.php'            + '?update=-3&r_foci_character=<?php print $formVars['id']; ?>');
  show_file('gear.mysql.php'            + '?update=-3&r_gear_character=<?php print $formVars['id']; ?>');
  show_file('gearacc.mysql.php'         + '?update=-3&r_gear_id=0');
  show_file('history.mysql.php'         + '?update=-3&his_character=<?php print $formVars['id']; ?>');
  show_file('identity.mysql.php'        + '?update=-3&id_character=<?php print $formVars['id']; ?>');
  show_file('karma.mysql.php'           + '?update=-3&kar_character=<?php print $formVars['id']; ?>');
  show_file('knowledge.mysql.php'       + '?update=-3&r_know_character=<?php print $formVars['id']; ?>');
  show_file('language.mysql.php'        + '?update=-3&r_lang_character=<?php print $formVars['id']; ?>');
  show_file('lifestyle.mysql.php'       + '?update=-3&r_life_character=<?php print $formVars['id']; ?>');
  show_file('melee.mysql.php'           + '?update=-3&r_melee_character=<?php print $formVars['id']; ?>');
  show_file('melacc.mysql.php'          + '?update=-3&r_melee_id=0');
  show_file('mentor.mysql.php'          + '?update=-3&r_mentor_character=<?php print $formVars['id']; ?>');
  show_file('metamagics.mysql.php'      + '?update=-3&r_meta_character=<?php print $formVars['id']; ?>');
  show_file('notoriety.mysql.php'       + '?update=-3&not_character=<?php print $formVars['id']; ?>');
  show_file('projectile.mysql.php'      + '?update=-3&r_proj_character=<?php print $formVars['id']; ?>');
  show_file('projacc.mysql.php'         + '?update=-3&r_proj_id=0');
  show_file('publicity.mysql.php'       + '?update=-3&pub_character=<?php print $formVars['id']; ?>');
  show_file('qualities.mysql.php'       + '?update=-3&r_qual_character=<?php print $formVars['id']; ?>');
  show_file('spells.mysql.php'          + '?update=-3&r_spell_character=<?php print $formVars['id']; ?>');
  show_file('spirit.mysql.php'          + '?update=-3&r_spirit_character=<?php print $formVars['id']; ?>');
  show_file('sprite.mysql.php'          + '?update=-3&r_sprite_character=<?php print $formVars['id']; ?>');
  show_file('street.mysql.php'          + '?update=-3&st_character=<?php print $formVars['id']; ?>');
  show_file('tags.mysql.php'            + '?update=-3&tag_character=<?php print $formVars['id']; ?>');
  show_file('tradition.mysql.php'       + '?update=-3&r_trad_character=<?php print $formVars['id']; ?>');
  show_file('vehicles.mysql.php'        + '?update=-3&r_veh_character=<?php print $formVars['id']; ?>');
  show_file('vehacc.mysql.php'          + '?update=-3&r_veh_id=0');
<?php
  }
?>
}


$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );
<?php
  if ($formVars['id'] > 0) {
?>
  $( "#alchemytabs"      ).tabs( ).addClass( "tab-shadow" );
  $( "#armortabs"        ).tabs( ).addClass( "tab-shadow" );
  $( "#biowaretabs"      ).tabs( ).addClass( "tab-shadow" );
  $( "#commandtabs"      ).tabs( ).addClass( "tab-shadow" );
  $( "#commlinktabs"     ).tabs( ).addClass( "tab-shadow" );
  $( "#complexformtabs"  ).tabs( ).addClass( "tab-shadow" );
  $( "#cyberdecktabs"    ).tabs( ).addClass( "tab-shadow" );
  $( "#cyberjacktabs"    ).tabs( ).addClass( "tab-shadow" );
  $( "#cyberwaretabs"    ).tabs( ).addClass( "tab-shadow" );
  $( "#focitabs"         ).tabs( ).addClass( "tab-shadow" );
  $( "#geartabs"         ).tabs( ).addClass( "tab-shadow" );
  $( "#historytabs"      ).tabs( ).addClass( "tab-shadow" );
  $( "#magic"            ).tabs( ).addClass( "tab-shadow" );
  $( "#spelltabs"        ).tabs( ).addClass( "tab-shadow" );
  $( "#spirittabs"       ).tabs( ).addClass( "tab-shadow" );
  $( "#spritetabs"       ).tabs( ).addClass( "tab-shadow" );
  $( "#traditiontabs"    ).tabs( ).addClass( "tab-shadow" );
  $( "#mentortabs"       ).tabs( ).addClass( "tab-shadow" );
  $( "#metamagicstabs"   ).tabs( ).addClass( "tab-shadow" );
  $( "#adepttabs"        ).tabs( ).addClass( "tab-shadow" );
  $( "#matrix"           ).tabs( ).addClass( "tab-shadow" );
  $( "#meatspace"        ).tabs( ).addClass( "tab-shadow" );
  $( "#meleetabs"        ).tabs( ).addClass( "tab-shadow" );
  $( "#projectiletabs"   ).tabs( ).addClass( "tab-shadow" );
  $( "#firearmtabs"      ).tabs( ).addClass( "tab-shadow" );
  $( "#ammotabs"         ).tabs( ).addClass( "tab-shadow" );
  $( "#vehicletabs"      ).tabs( ).addClass( "tab-shadow" );
<?php
  }
?>
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . '/topmenu.start.php'); ?>
<?php include($Sitepath . '/topmenu.end.php'); ?>

<form name="edit">

<input type="hidden" name="id" value="<?php print $formVars['id']; ?>">

<div class="main">

<div id="tabs">

<ul>
  <li><a href="#detail"><?php print $a_runners['runr_name']; ?> Detail</a></li>
<?php
  if ($formVars['id'] > 0) {
?>
  <li><a href="#history">History</a></li>
  <li><a href="#tags">Tags</a></li>
  <li><a href="#active">Active Skills</a></li>
  <li><a href="#knowledge">Knowledge Skills</a></li>
  <li><a href="#language">Language Skills</a></li>
  <li><a href="#qualities">Qualities</a></li>
  <li><a href="#contacts">Contacts</a></li>
  <li><a href="#lifestyle">Lifestyles</a></li>
  <li><a href="#identity">Identity</a></li>
  <li><a href="#magic">Magic</a></li>
  <li><a href="#matrix">Matrix</a></li>
  <li><a href="#meatspace">Meat Space</a></li>
<?php
  }
?>
</ul>

<div id="detail">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Detail Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('detail-help');">Help</a></th>
</tr>
</table>

<div id="detail-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Buttons</strong>
  <ul>
    <li><strong>Update Shaodwrunner</strong> - Save any changes made to this form.</li>
    <li><strong>Add New Shadowrunner</strong> - If you need to add a new Shadowrunner based on an existing runner, change the name and select this button if enabled.</li>
  </ul></li>
</ul>

<ul>
  <li><strong>Personal Form</strong>
  <ul>
    <li><strong>Aliases</strong> - </li>
    <li><strong>Archtype</strong> - </li>
    <li><strong>Metatype</strong> - </li>
    <li><strong>Name</strong> - </li>
    <li><strong>Total Karma</strong> - </li>
    <li><strong>Current Karma</strong> - </li>
    <li><strong>Street Cred</strong> - </li>
    <li><strong>Notoriety</strong> - </li>
    <li><strong>Public Awareness</strong> - </li>
    <li><strong>Current Edge</strong> - </li>
  </ul></li>
  <li><strong>Attribute Form</strong>
  <ul>
    <li><strong>Agility</strong> - </li>
    <li><strong>Body</strong> - </li>
    <li><strong>Reaction</strong> - </li>
    <li><strong>Strength</strong> - </li>
    <li><strong>Charisma</strong> - </li>
    <li><strong>Intuition</strong> - </li>
    <li><strong>Logic</strong> - </li>
    <li><strong>Willpower</strong> - </li>
    <li><strong>Edge</strong> - </li>
    <li><strong>Essence</strong> - </li>
    <li><strong>Magic</strong> - </li>
    <li><strong>Initiate</strong> - </li>
    <li><strong>Resonence</strong> - </li>
  </ul></li>
  <li><strong>Statistics Form</strong>
  <ul>
    <li><strong>Weight</strong> - </li>
    <li><strong>Height</strong> - </li>
    <li><strong>Sex</strong> - </li>
    <li><strong>Age</strong> - </li>
    <li><strong>Lifestyle</strong> - </li>
  </ul></li>
</ul>

</div>

</div>

<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content">
<input type="button" disabled="true" name="update" value="Update Shadowrunner"  onClick="javascript:attach_detail('mooks.mysql.php', 1);">
<input type="button"                 name="addnew" value="Add New Shadowrunner" onClick="javascript:attach_detail('mooks.mysql.php', 0);">
  </td>
</tr>
</table>

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="4">Personal Form</th>
</tr>
<tr>
  <td class="ui-widget-content" id="edit_runner">Owner <select name="runr_owner">
<?php
  $q_string  = "select usr_id,usr_first,usr_last ";
  $q_string .= "from users ";
  $q_string .= "order by usr_last ";
  $q_users = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_users = mysqli_fetch_array($q_users)) {
    if ($a_users['usr_id'] == $a_runners['runr_owner']) {
      print "<option selected value=\"" . $a_users['usr_id'] . "\">" . $a_users['usr_last'] . ", " . $a_users['usr_first'] . "</option>\n";
    } else {
      print "<option value=\"" . $a_users['usr_id'] . "\">" . $a_users['usr_last'] . ", " . $a_users['usr_first'] . "</option>\n";
    }
  }
?>
</select></td>
  <td class="ui-widget-content" id="edit_runner">Character Name <input type="text" name="runr_name" size="20" onkeyup="check_runner();"><span id="runner_name"></span></td>
  <td class="ui-widget-content">Aliases <input type="text" name="runr_aliases" size="50"></td>
</tr>
<tr>
  <td class="ui-widget-content">Metatype <select name="runr_metatype">
<option value="0">None</option>
<?php
  $q_string  = "select meta_id,meta_name ";
  $q_string .= "from metatypes ";
  $q_string .= "left join versions on versions.ver_id = metatypes.meta_book ";
  $q_string .= "where ver_active = 1 ";
  $q_string .= "order by meta_name ";
  $q_metatypes = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_metatypes = mysqli_fetch_array($q_metatypes)) {
    print "<option value=\"" . $a_metatypes['meta_id'] . "\">" . $a_metatypes['meta_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Archetype <input type="text" name="runr_archetype" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Total Edge <input type="text" name="runr_totaledge" size="20"></td>
  <td class="ui-widget-content">Current Edge<input type="text" name="runr_currentedge" size="20"></td>
</tr>
</table>

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="12">Attribute Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Body <input type="text" name="runr_body" size="3"></td>
  <td class="ui-widget-content">Agility <input type="text" name="runr_agility" size="3"></td>
  <td class="ui-widget-content">Reaction <input type="text" name="runr_reaction" size="3"></td>
  <td class="ui-widget-content">Strength <input type="text" name="runr_strength" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Willpower <input type="text" name="runr_willpower" size="3"></td>
  <td class="ui-widget-content">Logic <input type="text" name="runr_logic" size="3"></td>
  <td class="ui-widget-content">Intuition <input type="text" name="runr_intuition" size="3"></td>
  <td class="ui-widget-content">Charisma <input type="text" name="runr_charisma" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Essence <input type="text" name="runr_essence" size="5"></td>
  <td class="ui-widget-content">Magic <input type="text" name="runr_magic" size="3"></td>
  <td class="ui-widget-content">Initiate <input type="text" name="runr_initiate" size="3"></td>
  <td class="ui-widget-content">Resonance <input type="text" name="runr_resonance" size="3"></td>
</tr>
</table>

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="4">Statistics Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Weight <input type="text" name="runr_weight" size="20"></td>
  <td class="ui-widget-content">Height <input type="text" name="runr_height" size="20"></td>
  <td class="ui-widget-content">Sex <select name="runr_sex">
<option value="0">Female</option>
<option value="1">Male</option>
</select></td>
  <td class="ui-widget-content">Age <input type="text" name="runr_age" size="20"></td>
</tr>
</table>


<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="2">Character Notes</th>
</tr>
<tr>
  <th class="ui-state-default" colspan="2">Availability</th>
</tr>
<tr>
  <td class="ui-widget-content">Character available for runs? <input type="checkbox" <?php print $available; ?> name="runr_available"></td>
  <td class="ui-widget-content">What Shadowrun Release? <select name="runr_version" onChange="check_runner();>
<option value="0">Any Version</option>
<?php
  $q_string  = "select ver_id,ver_book ";
  $q_string .= "from versions ";
  $q_string .= "where ver_core = 1 and ver_active = 1 ";
  $q_string .= "order by ver_book ";
  $q_versions = mysqli_query($db, $q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysqli_error($db)));
  while ($a_versions = mysqli_fetch_array($q_versions)) {
    if ($a_runners['runr_version'] == $a_versions['ver_id']) {
      print "<option selected=\"true\" value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_book'] . "</option>\n";
    } else {
      print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_book'] . "</option>\n";
   }
  }
?></select></td>
</tr>
<tr>
  <th class="ui-state-default" colspan="2">Character Description</th>
</tr>
<tr>
  <td class="ui-widget-content" colspan="2">
<textarea id="runr_desc" name="runr_desc" cols="200" rows="5"
  onKeyDown="textCounter(document.edit.runr_desc, document.edit.descLen, 2000);"
  onKeyUp  ="textCounter(document.edit.runr_desc, document.edit.descLen, 2000);">
<?php print $a_runners['runr_desc']; ?>
</textarea>
<br><input readonly type="text" name="descLen" size="5" value="<?php print (2000 - strlen($a_runners['runr_desc'])); ?>"> characters left
</td>
</tr>
<tr>
  <th class="ui-state-default" colspan="2">Character Standard Operating Procedure</th>
</tr>
<tr>
  <td class="ui-widget-content" colspan="2">
<textarea id="runr_sop" name="runr_sop" cols="200" rows="5"
  onKeyDown="textCounter(document.edit.runr_sop, document.edit.sopLen, 2000);"
  onKeyUp  ="textCounter(document.edit.runr_sop, document.edit.sopLen, 2000);">
<?php print $a_runners['runr_sop']; ?>
</textarea>
<br><input readonly type="text" name="sopLen" size="5" value="<?php print (2000 - strlen($a_runners['runr_sop'])); ?>"> characters left
</td>
</tr>
</table>

</div>

<?php
# the start of character details when managing an existing character
  if ($formVars['id'] > 0) {
?>


<div id="history">

<div id="historytabs">

<ul>
  <li><a href="#my_history">Character History</a></li>
  <li><a href="#my_karma">Karma</a></li>
  <li><a href="#my_nuyen">Nuyen</a></li>
  <li><a href="#my_street">Street Cred</a></li>
  <li><a href="#my_notoriety">Notoriety</a></li>
  <li><a href="#my_publicity">Public Awareness</a></li>
</ul>


<div id="my_history">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('history-hide');">Character History Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('history-listing-help');">Help</a></th>
</tr>
</table>

<div id="history-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="history-hide" style="display: none">

<span id="history_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="history_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="my_karma">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('karma-hide');">Character Karma Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('karma-listing-help');">Help</a></th>
</tr>
</table>

<div id="karma-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="karma-hide" style="display: none">

<span id="karma_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="karma_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="my_nuyen">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('finance-hide');">Character Nuyen Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('finance-listing-help');">Help</a></th>
</tr>
</table>

<div id="finance-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="finance-hide" style="display: none">

<span id="finance_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="finance_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="my_street">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('street-hide');">Character Street Cred Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('street-listing-help');">Help</a></th>
</tr>
</table>

<div id="street-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="street-hide" style="display: none">

<span id="street_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="street_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="my_notoriety">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('notoriety-hide');">Character Notoriety Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('notoriety-listing-help');">Help</a></th>
</tr>
</table>

<div id="notoriety-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="notoriety-hide" style="display: none">

<span id="notoriety_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="notoriety_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="my_publicity">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('publicity-hide');">Character Public Awareness Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('publicity-listing-help');">Help</a></th>
</tr>
</table>

<div id="publicity-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="publicity-hide" style="display: none">

<span id="publicity_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="publicity_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="tags">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('tags-hide');">Tag Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('tags-help');">Help</a></th>
</tr>
</table>

<div id="tags-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Buttons</strong>
  <ul>
    <li><strong>Refresh Tag Listing</strong> - Reloads the Tag Listing table. At times, especially when removing several items, the table fails to refresh.</li>
    <li><strong>Update Tag</strong> - After selecting a tag to edit, click here to save changes.</li>
    <li><strong>Add New Tag</strong> - Add a new tag. You can also select an existing piece, make changes if needed, and click this button to add a second item.</li>
    <li><strong>Copy Tags From:</strong> - Select a server from the listing to copy the tag list from.</li>
  </ul></li>
</ul>

<ul>
  <li><strong>Tag Form</strong>
  <ul>
    <li><strong>Tag Name</strong> - Enter a tag name.</li>
    <li><strong>Visibility</strong> - Select a cloud to save the tag in.
    <ul>
      <li><strong>Private</strong> - This is your private tag cloud. No one else can see these tags so you can feel free to be expressive.</li>
      <li><strong>Group</strong> - This tag is visible to all members of your team. Other team members can also edit or delete this tag.</li>
      <li><strong>Public</strong> - This tag is visible to every user of the Inventory program.</li>
    </ul></li>
  </ul></li>
</ul>

</div>

</div>

<div id="tags-hide" style="display: none">

<span id="tags_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="tags_table"><?php print wait_Process("Please Wait"); ?></span>

</div>



<div id="active">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('active-hide');">Active Skills Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('active-help');">Help</a></th>
</tr>
</table>

<div id="active-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="active-hide" style="display: none">

<span id="active_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="active_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="knowledge">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('knowledge-hide');">Knowledge Skills Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('knowledge-help');">Help</a></th>
</tr>
</table>

<div id="knowledge-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="knowledge-hide" style="display: none">

<span id="knowledge_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="knowledge_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="language">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('language-hide');">Language Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('language-help');">Help</a></th>
</tr>
</table>

<div id="language-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="language-hide" style="display: none">

<span id="language_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="language_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="qualities">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('qualities-hide');">Qualities Selection</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('qualities-help');">Help</a></th>
</tr>
</table>

<div id="qualities-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="qualities-hide" style="display: none">

<span id="qualities_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="qualities_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="contacts">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('contact-hide');">Contact Selection</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('contact-help');">Help</a></th>
</tr>
</table>

<div id="contact-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="contact-hide" style="display: none">

<span id="contact_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="contact_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="lifestyle">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('lifestyle-hide');">Lifestyle Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('lifestyle-help');">Help</a></th>
</tr>
</table>

<div id="lifestyle-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="lifestyle-hide" style="display: none">

<span id="lifestyle_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="lifestyle_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="identity">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('identity-hide');">Identity Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('identity-help');">Help</a></th>
</tr>
</table>

<div id="identity-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="identity-hide" style="display: none">

<span id="identity_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('license-hide');">License Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('license-help');">Help</a></th>
</tr>
</table>

<div id="license-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="license-hide" style="display: none">

<span id="license_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<span id="identity_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="magic">

<ul>
  <li><a href="#spells">Spells</a></li>
  <li><a href="#tradition">Traditions</a></li>
  <li><a href="#mentor">Mentor Spirits</a></li>
  <li><a href="#spirit">Spirits</a></li>
  <li><a href="#foci">Power Foci</a></li>
  <li><a href="#alchemy">Alchemy</a></li>
  <li><a href="#metamagics">Metamagics</a></li>
  <li><a href="#adept">Adept</a></li>
</ul>


<div id="spells">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('spells-hide');">Spell Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('spells-help');">Help</a></th>
</tr>
</table>

<div id="spells-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="spells-hide" style="display: none">

<span id="spells_form"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="spelltabs">

<ul>
  <li><a href="#my_spells">My Spells</a></li>
  <li><a href="#spell">Spells</a></li>
</ul>

<div id="my_spells">

<span id="my_spells_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="spell">

<span id="spell_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="tradition">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Tradition</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('tradition-help');">Help</a></th>
</tr>
</table>

<div id="tradition-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>


<div id="traditiontabs">

<ul>
  <li><a href="#my_traditions">My Traditions</a></li>
  <li><a href="#traditions">Traditions</a></li>
</ul>

<div id="my_traditions">

<span id="my_traditions_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="traditions">

<span id="traditions_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>



<div id="mentor">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Mentor Spirits</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('mentor-help');">Help</a></th>
</tr>
</table>

<div id="mentor-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>


<div id="mentortabs">

<ul>
  <li><a href="#my_mentorspirits">My Mentor Spirit</a></li>
  <li><a href="#mentorspirits">Mentor Spirits</a></li>
</ul>

<div id="my_mentorspirits">

<span id="my_mentorspirits_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="mentorspirits">

<span id="mentorspirits_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>





<div id="spirit">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('spirit-hide');">Spirits</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('spirit-help');">Help</a></th>
</tr>
</table>

<div id="spirit-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="spirit-hide" style="display: none">

<span id="spirits_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="spirittabs">

<ul>
  <li><a href="#my_spirits">My Spirits</a></li>
  <li><a href="#spirits">Spirits</a></li>
</ul>

<div id="my_spirits">

<span id="my_spirits_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="spirits">

<span id="spirits_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>





<div id="foci">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('foci-hide');">Power Focuses</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('foci-help');">Help</a></th>
</tr>
</table>

<div id="foci-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="foci-hide" style="display: none">

<span id="foci_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="focitabs">

<ul>
  <li><a href="#my_foci">My Foci</a></li>
  <li><a href="#focis">Foci</a></li>
</ul>

<div id="my_foci">

<span id="my_focis_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="focis">

<span id="focis_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>







<div id="alchemy">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('alchemy-hide');">Alchemical Preparation Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('alchemy-help');">Help</a></th>
</tr>
</table>

<div id="alchemy-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="alchemy-hide" style="display: none">

<span id="alchemy_form"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="alchemytabs">

<ul>
  <li><a href="#my_preps">My Preparations</a></li>
  <li><a href="#preps">Preparations</a></li>
</ul>

<div id="my_preps">

<span id="my_preps_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="preps">

<span id="preps_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>



<div id="metamagics">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('metamagics-hide');">Metamagics Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('metamagics-help');">Help</a></th>
</tr>
</table>

<div id="metamagics-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="metamagics-hide" style="display: none">

<span id="metamagics_form"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="metamagicstabs">

<ul>
  <li><a href="#my_magics">My Metamagics</a></li>
  <li><a href="#magics">Metamagics</a></li>
</ul>

<div id="my_magics">

<span id="my_magics_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="magics">

<span id="magics_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>



<div id="adept">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('adept-hide');">Adept Powers</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('adept-help');">Help</a></th>
</tr>
</table>

<div id="adept-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="adept-hide" style="display: none">

<span id="adept_form"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="adepttabs">

<ul>
  <li><a href="#my_powers">My Adept Powers</a></li>
  <li><a href="#powers">Adept Powers</a></li>
</ul>

<div id="my_powers">

<span id="my_powers_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="powers">

<span id="powers_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


</div>


<div id="matrix">

<ul>
  <li><a href="#commlink">Commlinks</a></li>
  <li><a href="#command">Command Consoles</a></li>
  <li><a href="#cyberdeck">Cyberdeck</a></li>
  <li><a href="#cyberjack">Cyberjack</a></li>
  <li><a href="#sprite">Sprites</a></li>
  <li><a href="#complexform">Complex Forms</a></li>
</ul>


<div id="commlink">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('commlink-hide');">Commlink Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('commlink-help');">Help</a></th>
</tr>
</table>

<div id="commlink-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="commlink-hide" style="display: none">

<span id="commlink_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="commlinktabs">

<ul>
  <li><a href="#my_commlinks">My Commlinks</a></li>
  <li><a href="#commlinks">Commlinks</a></li>
  <li><a href="#linkpgms">Common Programs</a></li>
  <li><a href="#linkhack">Hacking Programs</a></li>
  <li><a href="#linkagt">Agents</a></li>
  <li><a href="#linkacc">Accessories</a></li>
</ul>

<div id="my_commlinks">

<span id="my_commlink_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="commlinks">

<span id="commlink_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="linkpgms">

<span id="linkpgms_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="linkhack">

<span id="linkhack_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="linkagt">

<span id="linkagt_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="linkacc">

<span id="linkacc_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="command">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('command-hide');">Rigger Command Console Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('command-help');">Help</a></th>
</tr>
</table>

<div id="command-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="command-hide" style="display: none">

<span id="command_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="commandtabs">

<ul>
  <li><a href="#my_command_consoles">My Command Consoles</a></li>
  <li><a href="#command_consoles">Command Consoles</a></li>
  <li><a href="#cmdpgm">Common Programs</a></li>
  <li><a href="#cmdhack">Hacking Programs</a></li>
  <li><a href="#cmdacc">Accessories</a></li>
</ul>

<div id="my_command_consoles">

<span id="my_command_consoles_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="command_consoles">

<span id="command_consoles_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="cmdpgm">

<span id="cmdpgm_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="cmdhack">

<span id="cmdhack_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="cmdacc">

<span id="cmdacc_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="cyberdeck">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('cyberdeck-hide');">Cyberdeck Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('cyberdeck-help');">Help</a></th>
</tr>
</table>

<div id="cyberdeck-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Cyberdecks</strong></p>

<p>As a Decker, in order to properly utilize the Matrix, you need to have at least one Cyberdeck. You can use a Commlink but a Cyberdeck is more capable.
You'll select your Cyberdeck from the list in the <strong>Cyberdecks</strong> tab.</p>

<p>As always, book and page number is provided in case you want to get more details about a product or accessories.</p>

</div>

</div>

<div id="cyberdeck-hide" style="display: none">

<span id="cyberdeck_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="cyberdecktabs">

<ul>
  <li><a href="#my_cyberdecks">My Cyberdecks</a></li>
  <li><a href="#cyberdecks">Cyberdecks</a></li>
  <li><a href="#programs">Common Programs</a></li>
  <li><a href="#hacking">Hacking Programs</a></li>
  <li><a href="#agent">Agents</a></li>
  <li><a href="#deckacc">Accessories</a></li>
</ul>

<div id="my_cyberdecks">

<span id="my_cyberdeck_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="cyberdecks">

<span id="cyberdeck_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="programs">

<span id="program_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="hacking">

<span id="hacking_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="agent">

<span id="agent_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="deckacc">

<span id="deckacc_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="cyberjack">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('cyberjack-hide');">Cyberjack Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('cyberjack-help');">Help</a></th>
</tr>
</table>

<div id="cyberjack-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Cyberjacks</strong></p>

<p>As a Decker, in order to properly utilize the Matrix, you need to have a Cyberjack installed in your brain. In this section, 
you'll select your Cyberjack from the list in the Cyberjacks tab.</p>

<p>If you already have a Cyberjack installed, the Cyberjacks tab will be empty as you can only have one at a time. Delete the 
existing one to select a replacement.</p>

<p>As always, book and page number is provided in case you want to get more details about a product.</p>

</div>

</div>

<div id="cyberjack-hide" style="display: none">

<span id="cyberjack_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="cyberjacktabs">

<ul>
  <li><a href="#my_cyberjacks">My Cyberjacks</a></li>
  <li><a href="#cyberjacks">Cyberjacks</a></li>
</ul>

<div id="my_cyberjacks">

<span id="my_cyberjack_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="cyberjacks">

<span id="cyberjack_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="sprite">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('sprite-hide');">Sprites</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('sprite-help');">Help</a></th>
</tr>
</table>

<div id="sprite-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="sprite-hide" style="display: none">

<span id="sprites_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="spritetabs">

<ul>
  <li><a href="#my_sprites">My Sprites</a></li>
  <li><a href="#sprites">Sprites</a></li>
</ul>

<div id="my_sprites">

<span id="my_sprites_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="sprites">

<span id="sprites_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="complexform">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Complex Form Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('complexform-help');">Help</a></th>
</tr>
</table>

<div id="complexform-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="complexformtabs">

<ul>
  <li><a href="#my_complexform">My Complex Forms</a></li>
  <li><a href="#complexform">Complex Forms</a></li>
</ul>

<div id="my_complexform">

<span id="my_complexform_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="complexform">

<span id="complexform_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


</div>


<div id="meatspace">

<ul>
  <li><a href="#gear">Gear</a></li>
  <li><a href="#armor">Armor</a></li>
  <li><a href="#bioware">Bioware</a></li>
  <li><a href="#cyberware">Cyberware</a></li>
  <li><a href="#melee">Melee</a></li>
  <li><a href="#ammo">Ammunition</a></li>
  <li><a href="#projectile">Projectile</a></li>
  <li><a href="#firearms">Firearms</a></li>
  <li><a href="#vehicles">Vehicles</a></li>
</ul>



<div id="gear">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('gear-hide');">Gear</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('gear-help');">Help</a></th>
</tr>
</table>

<div id="gear-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<strong>Gear</strong>

<p>In this set of tabs, you see the <strong>My Gear</strong> tab, <strong>Gear</strong>, and <strong>Accessories</strong> tabs.</p>

<p>Under the <strong>My Gear</strong> tab is all the gear you've purchased. At the bottom of the list is the total cost in Nuyen. 
As you purchase <strong>Gear</strong> or <strong>Accessories</strong>, this number changes.<p>

<p>Under the <strong>Gear</strong> tab, is a list of all the gear that's available to you. In general the headings should provide all 
the information you need to make the purchase including the Shadowrun book and page number where you can find the gear. Based on the 
Shadowrun Edition, there will be highlights that identify the accessibility of the item. For example, in 6th Edition, the gear that is 
<span class="ui-state-highlight">highlighted like this</span>, require a <strong>License</strong> and <span class="ui-state-error">highlighted 
like this</span>, are <strong>Illegal</strong>.</p>

<p>Under the <strong>Accessories</strong> tab are the items that can be purchased or otherwise acquired for the specific piece of gear you purchased. 
You must first select the item under the <strong>My Gear</strong> tab. That will bring it up where it can edited to add a <strong>Number</strong> 
and <strong>Description</strong> that might further identify the item. I will add the vehicle name for example to make it easier to associate it 
with a vehicle. Then over under <strong>Accessories</strong>, will be a list of what is available for purchase for the selected item of <strong>Gear</strong>. 
The <strong>Capacity</strong> is calculated so the <strong>Accessories</strong> list may change when you select an <strong>Accessory</strong>.</p>

</div>

</div>


<div id="gear-hide" style="display: none">

<span id="gear_form"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="geartabs">

<ul>
  <li><a href="#my_gear">My Gear</a></li>
  <li><a href="#gearid">Gear</a></li>
  <li><a href="#gearacc">Accessories</a></li>
</ul>


<div id="my_gear">

<span id="my_gear_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="gearid">

<span id="gear_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="gearacc">

<span id="gearacc_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="armor">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('armor-hide');">Armor</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('armor-help');">Help</a></th>
</tr>
</table>

<div id="armor-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>


<div id="armortabs">

<ul>
  <li><a href="#my_armor">My Armor</a></li>
  <li><a href="#armored">Armor</a></li>
  <li><a href="#clothing">Clothing</a></li>
  <li><a href="#armoracc">Accessories</a></li>
</ul>

<div id="armor-hide" style="display: none">

<span id="armor_form"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="my_armor">

<span id="my_armor_mysql"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="armored">

<span id="armor_mysql"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="clothing">

<span id="clothing_mysql"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="armoracc">

<span id="armoracc_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="bioware">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('bioware-hide');">Bioware</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('bioware-help');">Help</a></th>
</tr>
</table>

<div id="bioware-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="bioware-hide" style="display: none">

<span id="bioware_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="biowaretabs">

<ul>
  <li><a href="#my_bioware">My Bioware</a></li>
  <li><a href="#basic">Basic Bioware</a></li>
  <li><a href="#biosculpting">Biosculpting</a></li>
  <li><a href="#cosmetic">Cosmetic</a></li>
  <li><a href="#cultured">Cultured Bioware</a></li>
  <li><a href="#endosymbiont">Endosymbiont</a></li>
  <li><a href="#leech">Leech Symbioant</a></li>
</ul>


<div id="my_bioware">

<span id="my_bioware_mysql"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="basic">

<span id="basic_mysql"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="biosculpting">

<span id="biosculpting_mysql"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="cosmetic">

<span id="cosmetic_mysql"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="cultured">

<span id="cultured_mysql"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="endosymbiont">

<span id="endosymbiont_mysql"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="leech">

<span id="leech_mysql"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="cyberware">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('cyberware-hide');">Cyberware</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('cyberware-help');">Help</a></th>
</tr>
</table>

<div id="cyberware-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>


<div id="cyberware-hide" style="display: none">

<span id="cyberware_form"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="cyberwaretabs">

<ul>
  <li><a href="#my_cyberware">My Cyberware</a></li>
  <li><a href="#earware">Earware</a></li>
  <li><a href="#eyeware">Eyeware</a></li>
  <li><a href="#headware">Headware</a></li>
  <li><a href="#bodyware">Bodyware</a></li>
  <li><a href="#cyberlimbs">Cyberlimbs</a></li>
  <li><a href="#cosmetic">Cosmetic Cyberware Modifications</a></li>
  <li><a href="#cyberacc">Accessories</a></li>
</ul>

<div id="my_cyberware">

<span id="my_cyberware_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="earware">

<span id="earware_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="eyeware">

<span id="eyeware_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="headware">

<span id="headware_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="bodyware">

<span id="bodyware_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="cyberlimbs">

<span id="cyberlimbs_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="cosmetic">

<span id="cosmetic_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="cyberacc">

<span id="cyberacc_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="melee">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('melee-hide');">Melee Weapons</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('melee-help');">Help</a></th>
</tr>
</table>

<div id="melee-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="melee-hide" style="display: none">

<span id="melee_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="meleetabs">

<ul>
  <li><a href="#my_melee">My Melee Weapons</a></li>
  <li><a href="#melee">Blades and Clubs</a></li>
  <li><a href="#melacc">Accessories</a></li>
</ul>

<div id="my_melee">

<span id="my_melee_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="melee">

<span id="melee_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="melacc">

<span id="melacc_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="ammo">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('ammo-hide');">Ammunition</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('ammo-help');">Help</a></th>
</tr>
</table>

<div id="ammo-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="ammo-hide" style="display: none">

<span id="ammo_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="ammotabs">

<ul>
  <li><a href="#my_ammo">My Ammunition</a></li>
  <li><a href="#ammo">Ammunition</a></li>
</ul>

<div id="my_ammo">

<span id="my_ammo_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="ammo">

<span id="ammo_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="projectile">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('projectile-hide');">Projectile Weapons</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('projectile-help');">Help</a></th>
</tr>
</table>

<div id="projectile-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="projectile-hide" style="display: none">

<span id="projectile_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="projectiletabs">

<ul>
  <li><a href="#my_projectile">My Projectiles</a></li>
  <li><a href="#bows">Projectiles</a></li>
  <li><a href="#projacc">Accessories</a></li>
</ul>

<div id="my_projectile">

<span id="my_projectile_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="bows">

<span id="bows_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="projacc">

<span id="projacc_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="firearms">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('firearms-hide');">Firearms</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('firearms-help');">Help</a></th>
</tr>
</table>

<div id="firearms-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="firearms-hide" style="display: none">

<span id="firearms_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="firearmtabs">

<ul>
  <li><a href="#my_firearms">My Firearms</a></li>
  <li><a href="#arms">Arms Dealer</a></li>
  <li><a href="#fireacc">Accessories</a></li>
</ul>

<div id="my_firearms">

<span id="my_firearms_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="arms">

<span id="arms_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="fireacc">

<span id="fireacc_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="vehicles">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('vehicles-hide');">Vehicles</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('vehicles-help');">Help</a></th>
</tr>
</table>

<div id="vehicles-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Notes</strong></p>

</div>

</div>

<div id="vehicles-hide" style="display: none">

<span id="vehicles_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="vehicletabs">

<ul>
  <li><a href="#my_vehicles">My Vehicles</a></li>
  <li><a href="#drone">Drones 'r' Us</a></li>
  <li><a href="#groundcraft">Uncle Bob's Used Cars</a></li>
  <li><a href="#watercraft">Vinnie's Yachts</a></li>
  <li><a href="#aircraft">Harry's Planes</a></li>
  <li><a href="#vehacc">Accessories</a></li>
</ul>

<div id="my_vehicles">

<span id="my_vehicles_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="drone">

<span id="drone_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="groundcraft">

<span id="groundcraft_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="watercraft">

<span id="watercraft_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="aircraft">

<span id="aircraft_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="vehacc">

<span id="vehacc_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


</div>

</div>

<?php
# the end of character details when managing an existing character
  }
?>

</div>

</form>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
