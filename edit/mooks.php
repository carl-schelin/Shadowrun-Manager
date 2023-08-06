<?php
# Script: mooks.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('3');

  $package = "mooks.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

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
  $q_runners = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  $a_runners = mysql_fetch_array($q_runners);

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

function delete_tags( p_script_url ) {
  var answer = confirm("Delete this Tag?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('tags.mysql.php' + '?update=-1' + '&tag_character=<?php print $formVars['id']; ?>');
  }
}

function delete_active( p_script_url ) {
  var answer = confirm("Delete this Active Skill?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('active.mysql.php' + '?update=-1' + '&r_act_character=<?php print $formVars['id']; ?>');
  }
}

function delete_history( p_script_url ) {
  var answer = confirm("Delete this Character History Event?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('history.mysql.php' + '?update=-1' + '&his_character=<?php print $formVars['id']; ?>');
  }
}

function delete_karma( p_script_url ) {
  var answer = confirm("You will lose this Karma, delete this Karma entry?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('karma.mysql.php' + '?update=-1' + '&kar_character=<?php print $formVars['id']; ?>');
  }
}

function delete_finance( p_script_url ) {
  var answer = confirm("You will lose this Nuyen, delete this Nuyen entry?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('finance.mysql.php' + '?update=-1' + '&fin_character=<?php print $formVars['id']; ?>');
  }
}

function delete_street( p_script_url ) {
  var answer = confirm("Delete this Street Cred Entry?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('street.mysql.php' + '?update=-1' + '&st_character=<?php print $formVars['id']; ?>');
  }
}

function delete_notoriety( p_script_url ) {
  var answer = confirm("Delete this Notoriety entry?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('notoriety.mysql.php' + '?update=-1' + '&not_character=<?php print $formVars['id']; ?>');
  }
}

function delete_publicity( p_script_url ) {
  var answer = confirm("Delete this Publicity entry?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('publicity.mysql.php' + '?update=-1' + '&pub_character=<?php print $formVars['id']; ?>');
  }
}

function delete_knowledge( p_script_url ) {
  var answer = confirm("Delete this Knowledge Skill?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('knowledge.mysql.php' + '?update=-1' + '&r_know_character=<?php print $formVars['id']; ?>');
  }
}

function delete_language( p_script_url ) {
  var answer = confirm("Delete this Language?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('language.mysql.php' + '?update=-1' + '&r_lang_character=<?php print $formVars['id']; ?>');
  }
}

function delete_lifestyle( p_script_url ) {
  var answer = confirm("Delete this Lifestyle?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('lifestyle.mysql.php' + '?update=-1' + '&r_life_character=<?php print $formVars['id']; ?>');
  }
}

function delete_qualities( p_script_url ) {
  var answer = confirm("Delete this Quality?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('qualities.mysql.php' + '?update=-1' + '&r_qual_character=<?php print $formVars['id']; ?>');
  }
}

function delete_contact( p_script_url ) {
  var answer = confirm("Delete this Contact?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('contact.mysql.php' + '?update=-1' + '&r_con_character=<?php print $formVars['id']; ?>');
  }
}

function delete_identity( p_script_url ) {
  var answer = confirm("This will delete all Licenses associated with this identity.\n\nDelete this Identity?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('identity.mysql.php' + '?update=-1' + '&id_character=<?php print $formVars['id']; ?>');
  }
}

function delete_license( p_script_url ) {
  var answer = confirm("Delete this License?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('identity.mysql.php'  + '?update=-1&id_character=<?php print $formVars['id']; ?>');
  }
}

function delete_commlink( p_script_url ) {
  var answer = confirm("Delete this Commlink?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('commlink.mysql.php' + '?update=-1' + '&r_link_character=<?php print $formVars['id']; ?>');
  }
}

function delete_linkacc( p_script_url ) {
  var answer = confirm("Delete this Commlink Accessory?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('commlink.mysql.php' + '?update=-1' + '&r_link_character=<?php print $formVars['id']; ?>');
  }
}

function delete_command( p_script_url ) {
  var answer = confirm("Delete this Rigger Command Console?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycommand.mysql.php' + '?update=-1' + '&r_cmd_character=<?php print $formVars['id']; ?>');
  }
}

function delete_cmdacc( p_script_url ) {
  var answer = confirm("Delete this Command Console Accessory?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycommand.mysql.php' + '?update=-1' + '&r_cmd_character=<?php print $formVars['id']; ?>');
  }
}

function delete_cmdpgm( p_script_url ) {
  var answer = confirm("Delete this Program?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycommand.mysql.php' + '?update=-1' + '&r_cmd_character=<?php print $formVars['id']; ?>');
  }
}

function delete_cyberdeck( p_script_url ) {
  var answer = confirm("Delete this Cyberdeck?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycyberdeck.mysql.php' + '?update=-1' + '&r_deck_character=<?php print $formVars['id']; ?>');
  }
}

function delete_deckacc( p_script_url ) {
  var answer = confirm("Delete this Cyberdeck Accessory?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycyberdeck.mysql.php' + '?update=-1' + '&r_deck_character=<?php print $formVars['id']; ?>');
  }
}

function delete_program( p_script_url ) {
  var answer = confirm("Delete this Program?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycyberdeck.mysql.php' + '?update=-1' + '&r_deck_character=<?php print $formVars['id']; ?>');
  }
}

function delete_agent( p_script_url ) {
  var answer = confirm("Delete this Agent?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycyberdeck.mysql.php' + '?update=-1' + '&r_deck_character=<?php print $formVars['id']; ?>');
  }
}

function delete_complexform( p_script_url ) {
  var answer = confirm("Delete this Complex Form?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('complexform.mysql.php' + '?update=-1' + '&r_form_character=<?php print $formVars['id']; ?>');
  }
}

function delete_spells( p_script_url ) {
  var answer = confirm("Delete this Spell?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('spells.mysql.php' + '?update=-1' + '&r_spell_character=<?php print $formVars['id']; ?>');
  }
}

function delete_alchemy( p_script_url ) {
  var answer = confirm("Delete this Alchemical Preparation?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('alchemy.mysql.php' + '?update=-1' + '&r_alc_character=<?php print $formVars['id']; ?>');
  }
}

function delete_adept( p_script_url ) {
  var answer = confirm("Delete this Power?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('adept.mysql.php' + '?update=-1' + '&r_adp_character=<?php print $formVars['id']; ?>');
  }
}

function delete_tradition( p_script_url ) {
  var answer = confirm("Delete Tradition?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('tradition.mysql.php' + '?update=-1' + '&r_trad_character=<?php print $formVars['id']; ?>');
  }
}

function delete_sprite( p_script_url ) {
  var answer = confirm("Derezz Sprite?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('sprite.mysql.php' + '?update=-1' + '&r_sprite_character=<?php print $formVars['id']; ?>');
  }
}

function delete_spirit( p_script_url ) {
  var answer = confirm("Banish Spirit?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('spirit.mysql.php' + '?update=-1' + '&r_spirit_character=<?php print $formVars['id']; ?>');
  }
}

function delete_melee( p_script_url ) {
  var answer = confirm("Delete this Melee Weapon?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('melee.mysql.php' + '?update=-1' + '&r_melee_character=<?php print $formVars['id']; ?>');
  }
}

function delete_projectile( p_script_url ) {
  var answer = confirm("Delete this Projectile Weapon?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('projectile.mysql.php' + '?update=-1' + '&r_proj_character=<?php print $formVars['id']; ?>');
  }
}

function delete_mentor( p_script_url ) {
  var answer = confirm("Delete this Mentor Spirit?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mentor.mysql.php' + '?update=-1' + '&r_mentor_character=<?php print $formVars['id']; ?>');
  }
}

function delete_firearms( p_script_url ) {
  var answer = confirm("Delete this Firearm?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('firearms.mysql.php' + '?update=-1' + '&r_fa_character=<?php print $formVars['id']; ?>');
  }
}

function delete_ammo( p_script_url ) {
  var answer = confirm("Delete this Ammunition?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('ammo.mysql.php' + '?update=-1' + '&r_ammo_character=<?php print $formVars['id']; ?>');
  }
}

function delete_fireammo( p_script_url ) {
  var answer = confirm("Remove this Ammunition from this Firearm?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('firearms.mysql.php' + '?update=-1' + '&r_fa_character=<?php print $formVars['id']; ?>');
    show_file('fireacc.mysql.php' + '?update=-1' + '&r_fa_character=<?php print $formVars['id']; ?>');
  }
}

function delete_vehauto( p_script_url ) {
  var answer = confirm("Remove this Autosoft from this Vehicle?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('vehicles.mysql.php' + '?update=-1' + '&r_veh_character=<?php print $formVars['id']; ?>');
    show_file('vehacc.mysql.php' + '?update=-1' + '&r_veh_character=<?php print $formVars['id']; ?>');
  }
}

function delete_vehfire( p_script_url ) {
  var answer = confirm("Remove this Firearm from this Vehicle?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('vehicles.mysql.php' + '?update=-1' + '&r_veh_character=<?php print $formVars['id']; ?>');
    show_file('vehacc.mysql.php' + '?update=-1' + '&r_veh_character=<?php print $formVars['id']; ?>');
  }
}

function delete_cyberfire( p_script_url ) {
  var answer = confirm("Remove this Firearm from this Cyberarm?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('cyberware.mysql.php' + '?update=-1' + '&r_ware_character=<?php print $formVars['id']; ?>');
    show_file('cyberacc.mysql.php' + '?update=-1' + '&r_ware_character=<?php print $formVars['id']; ?>');
  }
}

function delete_vehammo( p_script_url ) {
  var answer = confirm("Remove this Ammunition from this Vehicle?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('vehicles.mysql.php' + '?update=-1' + '&r_veh_character=<?php print $formVars['id']; ?>');
    show_file('vehacc.mysql.php' + '?update=-1' + '&r_veh_character=<?php print $formVars['id']; ?>');
  }
}

function delete_fireacc( p_script_url ) {
  var answer = confirm("Delete this Firearm Accessory?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('firearms.mysql.php' + '?update=-1' + '&r_fa_character=<?php print $formVars['id']; ?>');
  }
}

function delete_melacc( p_script_url ) {
  var answer = confirm("Delete this Melee Weapon Accessory?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('melee.mysql.php' + '?update=-1' + '&r_melee_character=<?php print $formVars['id']; ?>');
  }
}

function delete_projacc( p_script_url ) {
  var answer = confirm("Delete this Projectile Accessory?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('projectile.mysql.php' + '?update=-1' + '&r_proj_character=<?php print $formVars['id']; ?>');
    show_file('projacc.mysql.php' + '?update=-1' + '&r_proj_character=<?php print $formVars['id']; ?>');
  }
}

function delete_vehicles( p_script_url ) {
  var answer = confirm("Delete this Vehicle?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('vehicles.mysql.php' + '?update=-1' + '&r_veh_character=<?php print $formVars['id']; ?>');
  }
}

function delete_armor( p_script_url ) {
  var answer = confirm("Delete this Armor?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('armor.mysql.php' + '?update=-1' + '&r_arm_character=<?php print $formVars['id']; ?>');
  }
}

function delete_armoracc( p_script_url ) {
  var answer = confirm("Delete this Armor Accessory?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('armor.mysql.php' + '?update=-1' + '&r_arm_character=<?php print $formVars['id']; ?>');
  }
}

function delete_cyberware( p_script_url ) {
  var answer = confirm("Delete this Cyberware?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('cyberware.mysql.php' + '?update=-1' + '&r_ware_character=<?php print $formVars['id']; ?>');
  }
}

function delete_cyberacc( p_script_url ) {
  var answer = confirm("Delete this Cyberware Accessory?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('cyberware.mysql.php' + '?update=-1' + '&r_ware_character=<?php print $formVars['id']; ?>');
  }
}

function delete_bioware( p_script_url ) {
  var answer = confirm("Delete this Bioware?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('bioware.mysql.php' + '?update=-1' + '&r_bio_character=<?php print $formVars['id']; ?>');
  }
}

function delete_gear( p_script_url ) {
  var answer = confirm("Delete this Gear?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('gear.mysql.php' + '?update=-1' + '&r_gear_character=<?php print $formVars['id']; ?>');
  }
}

function delete_gearacc( p_script_url ) {
  var answer = confirm("Delete this Gear Accessory?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('gear.mysql.php' + '?update=-1' + '&r_gear_character=<?php print $formVars['id']; ?>');
  }
}

function delete_vehacc( p_script_url ) {
  var answer = confirm("Delete this Vehicle Accessory?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('vehicles.mysql.php' + '?update=-1' + '&r_veh_character=<?php print $formVars['id']; ?>');
  }
}


function attach_detail( p_script_url, update ) {
  var ad_form = document.edit;
  var ad_url;

  ad_url  = '?update='   + update;
  ad_url += '&id='       + ad_form.id.value;

  ad_url += "&runr_owner="          + encode_URI(ad_form.runr_owner.value);
  ad_url += "&runr_aliases="        + encode_URI(ad_form.runr_aliases.value);
  ad_url += "&runr_name="           + encode_URI(ad_form.runr_name.value);
  ad_url += "&runr_archetype="      + encode_URI(ad_form.runr_archetype.value);
  ad_url += "&runr_agility="        + encode_URI(ad_form.runr_agility.value);
  ad_url += "&runr_body="           + encode_URI(ad_form.runr_body.value);
  ad_url += "&runr_reaction="       + encode_URI(ad_form.runr_reaction.value);
  ad_url += "&runr_strength="       + encode_URI(ad_form.runr_strength.value);
  ad_url += "&runr_charisma="       + encode_URI(ad_form.runr_charisma.value);
  ad_url += "&runr_intuition="      + encode_URI(ad_form.runr_intuition.value);
  ad_url += "&runr_logic="          + encode_URI(ad_form.runr_logic.value);
  ad_url += "&runr_willpower="      + encode_URI(ad_form.runr_willpower.value);
  ad_url += "&runr_metatype="       + ad_form.runr_metatype.value;
  ad_url += "&runr_essence="        + encode_URI(ad_form.runr_essence.value);
  ad_url += "&runr_totaledge="      + encode_URI(ad_form.runr_totaledge.value);
  ad_url += "&runr_currentedge="    + encode_URI(ad_form.runr_currentedge.value);
  ad_url += "&runr_magic="          + encode_URI(ad_form.runr_magic.value);
  ad_url += "&runr_initiate="       + encode_URI(ad_form.runr_initiate.value);
  ad_url += "&runr_resonance="      + encode_URI(ad_form.runr_resonance.value);
  ad_url += "&runr_age="            + encode_URI(ad_form.runr_age.value);
  ad_url += "&runr_sex="            + ad_form.runr_sex.value;
  ad_url += "&runr_height="         + encode_URI(ad_form.runr_height.value);
  ad_url += "&runr_weight="         + encode_URI(ad_form.runr_weight.value);
  ad_url += "&runr_available="      + ad_form.runr_available.checked;
  ad_url += "&runr_desc="           + encode_URI(ad_form.runr_desc.value);
  ad_url += "&runr_sop="            + encode_URI(ad_form.runr_sop.value);
  ad_url += "&runr_version="        + encode_URI(ad_form.runr_version.value);

  script = document.createElement('script');
  script.src = p_script_url + ad_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function check_runner() {
  var cr_form = document.edit;
  var cr_url;

  cr_url  = '?id='        + cr_form.id.value;
  cr_url += "&runr_name=" + encode_URI(cr_form.runr_name.value);

  show_file('validate.runner.php' + cr_url);
}

function attach_tags(p_script_url, update) {
  var at_form = document.edit;
  var at_url;
  
  at_url  = '?update='   + update;
  at_url += "&id="       + at_form.tag_character.value;

  at_url += "&tag_character=" + <?php print $formVars['id']; ?>;
  at_url += "&tag_name="      + encode_URI(at_form.tag_name.value);
  at_url += "&tag_view="      + radio_Loop(at_form.tag_view, 2);

  script = document.createElement('script');
  script.src = p_script_url + at_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_history(p_script_url, update) {
  var ah_form = document.edit;
  var ah_url;
  
  ah_url  = '?update='    + update;
  ah_url += "&his_id="    + ah_form.his_id.value;

  ah_url += "&his_character=" + <?php print $formVars['id']; ?>;
  ah_url += "&his_date="      + encode_URI(ah_form.his_date.value);
  ah_url += "&his_notes="     + encode_URI(ah_form.his_notes.value);

  script = document.createElement('script');
  script.src = p_script_url + ah_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('history.mysql.php' + '?update=-1' + '&his_character=<?php print $formVars['id']; ?>');
}

function attach_karma(p_script_url, update) {
  var ak_form = document.edit;
  var ak_url;
  
  ak_url  = '?update='    + update;
  ak_url += "&kar_id="    + ak_form.kar_id.value;

  ak_url += "&kar_character=" + <?php print $formVars['id']; ?>;
  ak_url += "&kar_karma="     + encode_URI(ak_form.kar_karma.value);
  ak_url += "&kar_date="      + encode_URI(ak_form.kar_date.value);
  ak_url += "&kar_notes="     + encode_URI(ak_form.kar_notes.value);

  script = document.createElement('script');
  script.src = p_script_url + ak_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('karma.mysql.php' + '?update=-1' + '&kar_character=<?php print $formVars['id']; ?>');
}

function attach_finance(p_script_url, update) {
  var af_form = document.edit;
  var af_url;
  
  af_url  = '?update='    + update;
  af_url += "&fin_id="    + af_form.fin_id.value;

  af_url += "&fin_character=" + <?php print $formVars['id']; ?>;
  af_url += "&fin_funds="     + encode_URI(af_form.fin_funds.value);
  af_url += "&fin_date="      + encode_URI(af_form.fin_date.value);
  af_url += "&fin_notes="     + encode_URI(af_form.fin_notes.value);

  script = document.createElement('script');
  script.src = p_script_url + af_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('finance.mysql.php' + '?update=-1' + '&fin_character=<?php print $formVars['id']; ?>');
}

function attach_street(p_script_url, update) {
  var as_form = document.edit;
  var as_url;
  
  as_url  = '?update='    + update;
  as_url += "&st_id="     + as_form.st_id.value;

  as_url += "&st_character=" + <?php print $formVars['id']; ?>;
  as_url += "&st_cred="      + encode_URI(as_form.st_cred.value);
  as_url += "&st_date="      + encode_URI(as_form.st_date.value);
  as_url += "&st_notes="     + encode_URI(as_form.st_notes.value);

  script = document.createElement('script');
  script.src = p_script_url + as_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('street.mysql.php' + '?update=-1' + '&st_character=<?php print $formVars['id']; ?>');
}

function attach_notoriety(p_script_url, update) {
  var an_form = document.edit;
  var an_url;
  
  an_url  = '?update='    + update;
  an_url += "&not_id="    + an_form.not_id.value;

  an_url += "&not_character=" + <?php print $formVars['id']; ?>;
  an_url += "&not_notoriety=" + encode_URI(an_form.not_notoriety.value);
  an_url += "&not_date="      + encode_URI(an_form.not_date.value);
  an_url += "&not_notes="     + encode_URI(an_form.not_notes.value);

  script = document.createElement('script');
  script.src = p_script_url + an_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('notoriety.mysql.php' + '?update=-1' + '&not_character=<?php print $formVars['id']; ?>');
}

function attach_publicity(p_script_url, update) {
  var ap_form = document.edit;
  var ap_url;
  
  ap_url  = '?update='    + update;
  ap_url += "&pub_id="    + ap_form.pub_id.value;

  ap_url += "&pub_character=" + <?php print $formVars['id']; ?>;
  ap_url += "&pub_publicity=" + encode_URI(ap_form.pub_publicity.value);
  ap_url += "&pub_date="      + encode_URI(ap_form.pub_date.value);
  ap_url += "&pub_notes="     + encode_URI(ap_form.pub_notes.value);

  script = document.createElement('script');
  script.src = p_script_url + ap_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('publicity.mysql.php' + '?update=-1' + '&pub_character=<?php print $formVars['id']; ?>');
}

function attach_active(p_script_url, update) {
  var aa_form = document.edit;
  var aa_url;
  
  aa_url  = '?update='   + update;
  aa_url += "&id="       + aa_form.r_act_id.value;

  aa_url += "&r_act_character="    + <?php print $formVars['id']; ?>;
  aa_url += "&r_act_number="       + aa_form.r_act_number.value;
  aa_url += "&r_act_group="        + aa_form.r_act_group.value;
  aa_url += "&r_act_rank="         + encode_URI(aa_form.r_act_rank.value);
  aa_url += "&r_act_specialize="   + encode_URI(aa_form.r_act_specialize.value);
  aa_url += "&r_act_expert="       + aa_form.r_act_expert.checked;

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_knowledge(p_script_url, update) {
  var ak_form = document.edit;
  var ak_url;
  
  ak_url  = '?update='   + update;
  ak_url += "&id="       + ak_form.r_know_id.value;

  ak_url += "&r_know_character="    + <?php print $formVars['id']; ?>;
  ak_url += "&r_know_number="       + ak_form.r_know_number.value;
  ak_url += "&r_know_rank="         + encode_URI(ak_form.r_know_rank.value);
  ak_url += "&r_know_specialize="   + encode_URI(ak_form.r_know_specialize.value);
  ak_url += "&r_know_expert="       + ak_form.r_know_expert.checked;

  script = document.createElement('script');
  script.src = p_script_url + ak_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_knowledge_dialog( p_script_url, update ) {
  var ad_form = document.edit;
  var ad_url;

  ad_url  = '?update='   + update;
  ad_url += '&id='       + ad_form.id.value;

  ad_url += "&know_name="       + encode_URI(ad_form.know_name.value);
  ad_url += "&know_attribute="  + encode_URI(ad_form.know_attribute.value);

  script = document.createElement('script');
  script.src = p_script_url + ad_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_language(p_script_url, update) {
  var al_form = document.edit;
  var al_url;
  
  al_url  = '?update='   + update;
  al_url += "&id="       + al_form.r_lang_id.value;

  al_url += "&r_lang_character="    + <?php print $formVars['id']; ?>;
  al_url += "&r_lang_number="       + al_form.r_lang_number.value;
  al_url += "&r_lang_rank="         + encode_URI(al_form.r_lang_rank.value);
  al_url += "&r_lang_specialize="   + encode_URI(al_form.r_lang_specialize.value);
  al_url += "&r_lang_expert="       + al_form.r_lang_expert.checked;

  script = document.createElement('script');
  script.src = p_script_url + al_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_language_dialog( p_script_url, update ) {
  var ad_form = document.edit;
  var ad_url;

  ad_url  = '?update='   + update;
  ad_url += '&id='       + ad_form.id.value;

  ad_url += "&lang_name="       + encode_URI(ad_form.lang_name.value);

  script = document.createElement('script');
  script.src = p_script_url + ad_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_lifestyle(p_script_url, update) {
  var al_form = document.edit;
  var al_url;
  
  al_url  = '?update='   + update;
  al_url += "&id="       + al_form.r_life_id.value;

  al_url += "&r_life_character="       + <?php print $formVars['id']; ?>;
  al_url += "&r_life_number="          + al_form.r_life_number.value;
  al_url += "&r_life_comforts="        + encode_URI(al_form.r_life_comforts.value);
  al_url += "&r_life_necessities="     + encode_URI(al_form.r_life_necessities.value);
  al_url += "&r_life_security="        + encode_URI(al_form.r_life_security.value);
  al_url += "&r_life_neighborhood="    + encode_URI(al_form.r_life_neighborhood.value);
  al_url += "&r_life_entertainment="   + encode_URI(al_form.r_life_entertainment.value);
  al_url += "&r_life_space="           + encode_URI(al_form.r_life_space.value);
  al_url += "&r_life_desc="            + encode_URI(al_form.r_life_desc.value);
  al_url += "&r_life_months="          + encode_URI(al_form.r_life_months.value);

  script = document.createElement('script');
  script.src = p_script_url + al_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_qualities(p_script_url, update) {
  var aq_form = document.edit;
  var aq_url;
  
  aq_url  = '?update='   + update;
  aq_url += "&id="       + aq_form.r_qual_id.value;

  aq_url += "&r_qual_character=" + <?php print $formVars['id']; ?>;
  aq_url += "&r_qual_number="    + aq_form.r_qual_number.value;
  aq_url += "&r_qual_details="   + encode_URI(aq_form.r_qual_details.value);

  script = document.createElement('script');
  script.src = p_script_url + aq_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_contact(p_script_url, update) {
  var ac_form = document.edit;
  var ac_url;
  
  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.r_con_id.value;

  ac_url += "&r_con_character="    + <?php print $formVars['id']; ?>;
  ac_url += "&r_con_number="       + ac_form.r_con_number.value;
  ac_url += "&r_con_loyalty="      + encode_URI(ac_form.r_con_loyalty.value);
  ac_url += "&r_con_connection="   + encode_URI(ac_form.r_con_connection.value);
  ac_url += "&r_con_faction="      + encode_URI(ac_form.r_con_faction.value);

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_contact_dialog( p_script_url, update ) {
  var ad_form = document.edit;
  var ad_url;

  ad_url  = '?update='   + update;
  ad_url += '&id='       + ad_form.id.value;

  ad_url += "&con_name="       + encode_URI(ad_form.con_name.value);
  ad_url += "&con_archetype="  + encode_URI(ad_form.con_archetype.value);
  ad_url += "&con_book="       + encode_URI(ad_form.con_book.value);
  ad_url += "&con_page="       + encode_URI(ad_form.con_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ad_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_identity(p_script_url, update) {
  var ai_form = document.edit;
  var ai_url;
  
  ai_url  = '?update='   + update;
  ai_url += "&id="       + ai_form.id_id.value;

  ai_url += "&id_character="  + <?php print $formVars['id']; ?>;
  ai_url += "&id_name="       + encode_URI(ai_form.id_name.value);
  ai_url += "&id_type="       + radio_Loop(ai_form.id_type, 3);
  ai_url += "&id_rating="     + encode_URI(ai_form.id_rating.value);
  ai_url += "&id_background=" + encode_URI(ai_form.id_background.value);

  script = document.createElement('script');
  script.src = p_script_url + ai_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_license(p_script_url, update) {
  var al_form = document.edit;
  var al_url;
  
  if (al_form.id_id.value == 0) {
    alert("You must select an Identity before you can add a license.");
  } else {
    al_url  = '?update='   + update;
    al_url += "&id="       + al_form.lic_id.value;

    al_url += "&lic_character="  + <?php print $formVars['id']; ?>;
    al_url += "&lic_type="       + encode_URI(al_form.lic_type.value);
    al_url += "&lic_rating="     + encode_URI(al_form.lic_rating.value);
    al_url += "&lic_identity="   + al_form.id_id.value;

    script = document.createElement('script');
    script.src = p_script_url + al_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('identity.mysql.php'  + '?update=-1&id_character=<?php print $formVars['id']; ?>');
  }
}

function attach_commlink(p_script_url, update) {
  var ac_form = document.edit;
  var ac_url;
  
  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.r_link_id.value;

  ac_url += "&r_link_character="   + <?php print $formVars['id']; ?>;

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('commlink.mysql.php'  + '?update=-1&r_link_character=<?php print $formVars['id']; ?>');
}

function select_commlink(p_script_url) {
  var sc_form = document.edit;
  var sc_url;

  sc_url  = "&r_link_character="   + <?php print $formVars['id']; ?>;

  script = document.createElement('script');
  script.src = p_script_url + sc_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('commlink.mysql.php'  + '?update=-1&r_link_character=<?php print $formVars['id']; ?>');
}

function attach_linkacc( p_link_id ) {
  var al_form = document.edit;
  var al_url;

  show_file('commlink.fill.php'  + '?id=' + p_link_id);
  show_file('linkacc.mysql.php'  + '?update=-1&r_link_id=' + p_link_id);
  show_file('commlink.mysql.php'  + '?update=-1&r_link_character=<?php print $formVars['id']; ?>');
}

function attach_command(p_script_url, update) {
  var ac_form = document.edit;
  var ac_url;
  
  ac_url  = '?update='   + update;

  ac_url += "&r_cmd_id="          + ac_form.r_cmd_id.value;
  ac_url += "&r_cmd_character="   + <?php print $formVars['id']; ?>;
  ac_url += "&r_cmd_noise="       + encode_URI(ac_form.r_cmd_noise.value);
  ac_url += "&r_cmd_sharing="     + encode_URI(ac_form.r_cmd_sharing.value);

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycommand.mysql.php'  + '?update=-1&r_cmd_character=<?php print $formVars['id']; ?>');
}

function attach_cmdacc( p_cmd_id ) {
  var ac_form = document.edit;
  var ac_url;

  show_file('command.fill.php'  + '?id=' + p_cmd_id);
  show_file('cmdacc.mysql.php'  + '?update=-1&r_cmd_id=' + p_cmd_id);
  show_file('mycommand.mysql.php'  + '?update=-1&r_cmd_character=<?php print $formVars['id']; ?>');
}

function attach_cmdpgm(p_script_url, update) {
  var ac_form = document.edit;
  var ac_url;
  
  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.r_pgm_id.value;

  ac_url += "&r_cmd_character="   + <?php print $formVars['id']; ?>;
  ac_url += "&r_cmd_id="          + ac_form.r_cmd_id.value;

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycommand.mysql.php'  + '?update=-1&r_cmd_character=<?php print $formVars['id']; ?>');
}

function select_cmdpgm(p_script_url) {
  var sc_form = document.edit;
  var sc_url;

  sc_url  = "&r_cmd_character="   + <?php print $formVars['id']; ?>;
  sc_url += "&r_cmd_id="          + sc_form.r_cmd_id.value;

  script = document.createElement('script');
  script.src = p_script_url + sc_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycommand.mysql.php'  + '?update=-1&r_cmd_character=<?php print $formVars['id']; ?>');
}

function attach_cyberdeck(p_script_url, update) {
  var ac_form = document.edit;
  var ac_url;
  
  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.r_deck_id.value;

  ac_url += "&r_deck_character="  + <?php print $formVars['id']; ?>;
  ac_url += "&r_deck_attack="     + encode_URI(ac_form.r_deck_attack.value);
  ac_url += "&r_deck_sleaze="     + encode_URI(ac_form.r_deck_sleaze.value);
  ac_url += "&r_deck_data="       + encode_URI(ac_form.r_deck_data.value);
  ac_url += "&r_deck_firewall="   + encode_URI(ac_form.r_deck_firewall.value);

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycyberdeck.mysql.php'  + '?update=-1&r_deck_character=<?php print $formVars['id']; ?>');
}

function attach_deckacc( p_deck_id ) {
  var ac_form = document.edit;
  var ac_url;

  show_file('cyberdeck.fill.php'  + '?id=' + p_deck_id);
  show_file('deckacc.mysql.php'  + '?update=-1&r_deck_id=' + p_deck_id);
  show_file('mycyberdeck.mysql.php'  + '?update=-1&r_deck_character=<?php print $formVars['id']; ?>');
}

function cyberdeck_left(p_script_url, p_value) {
  var cl_form = document.edit;
  var cl_url;
  
  cl_url  = "?left="     + p_value;
  cl_url += "&id="       + cl_form.r_deck_id.value;

  script = document.createElement('script');
  script.src = p_script_url + cl_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('cyberdeck.fill.php'  + '?id=' + cl_form.r_deck_id.value);
  show_file('mycyberdeck.mysql.php'  + '?update=-1&r_deck_character=<?php print $formVars['id']; ?>');
}

function cyberdeck_right(p_script_url, p_value) {
  var cr_form = document.edit;
  var cr_url;
  
  cr_url  = "?right="    + p_value;
  cr_url += "&id="       + cr_form.r_deck_id.value;

  script = document.createElement('script');
  script.src = p_script_url + cr_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('cyberdeck.fill.php'  + '?id=' + cr_form.r_deck_id.value);
  show_file('mycyberdeck.mysql.php'  + '?update=-1&r_deck_character=<?php print $formVars['id']; ?>');
}

function attach_program(p_script_url, update) {
  var ap_form = document.edit;
  var ap_url;
  
  ap_url  = '?update='   + update;
  ap_url += "&id="       + ap_form.r_pgm_id.value;

  ap_url += "&r_deck_character="   + <?php print $formVars['id']; ?>;
  ap_url += "&r_deck_id="          + ap_form.r_deck_id.value;

  script = document.createElement('script');
  script.src = p_script_url + ap_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycyberdeck.mysql.php'  + '?update=-1&r_deck_character=<?php print $formVars['id']; ?>');
}

function select_program(p_script_url) {
  var sp_form = document.edit;
  var sp_url;

  sp_url  = "&r_deck_character="   + <?php print $formVars['id']; ?>;
  sp_url += "&r_deck_id="          + sp_form.r_deck_id.value;

  script = document.createElement('script');
  script.src = p_script_url + sp_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycyberdeck.mysql.php'  + '?update=-1&r_deck_character=<?php print $formVars['id']; ?>');
}

function attach_agent(p_script_url, update) {
  var aa_form = document.edit;
  var aa_url;
  
  aa_url  = '?update='   + update;
  aa_url += "&id="       + aa_form.r_agt_id.value;

  aa_url += "&r_agt_character="   + <?php print $formVars['id']; ?>;
  aa_url += "&r_agt_id="          + aa_form.r_agt_id.value;

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycyberdeck.mysql.php'  + '?update=-1&r_deck_character=<?php print $formVars['id']; ?>');
}

function select_agent(p_script_url) {
  var sa_form = document.edit;
  var sa_url;

  sa_url  = "&r_deck_character="   + <?php print $formVars['id']; ?>;
  sa_url += "&r_deck_id="          + sa_form.r_deck_id.value;

  script = document.createElement('script');
  script.src = p_script_url + sa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycyberdeck.mysql.php'  + '?update=-1&r_deck_character=<?php print $formVars['id']; ?>');
}

function attach_spells(p_script_url, update) {
  var as_form = document.edit;
  var as_url;
  
  as_url  = '?update='   + update;
  as_url += "&id="       + as_form.r_spell_id.value;

  as_url += "&r_spell_character=" + <?php print $formVars['id']; ?>;
  as_url += "&r_spell_number="    + as_form.r_spell_number.value;
  as_url += "&r_spell_special="   + encode_URI(as_form.r_spell_special.value);

  script = document.createElement('script');
  script.src = p_script_url + as_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_alchemy(p_script_url, update) {
  var aa_form = document.edit;
  var aa_url;
  
  aa_url  = '?update='   + update;

  aa_url += "&r_alc_id="        + aa_form.r_alc_id.value;
  aa_url += "&r_alc_character=" + <?php print $formVars['id']; ?>;
  aa_url += "&r_alc_number="    + aa_form.r_alc_number.value;
  aa_url += "&r_alc_special="   + encode_URI(aa_form.r_alc_special.value);

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_adept(p_script_url, update) {
  var aa_form = document.edit;
  var aa_url;
  
  aa_url  = '?update='   + update;
  aa_url += "&id="       + aa_form.r_adp_id.value;

  aa_url += "&r_adp_character="    + <?php print $formVars['id']; ?>;
  aa_url += "&r_adp_number="       + aa_form.r_adp_number.value;
  aa_url += "&r_adp_level="        + encode_URI(aa_form.r_adp_level.value);
  aa_url += "&r_adp_specialize="   + encode_URI(aa_form.r_adp_specialize.value);

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_tradition(p_script_url, update) {
  var at_form = document.edit;
  var at_url;
  
  at_url  = '?update='   + update;
  at_url += "&id="       + at_form.r_trad_id.value;

  at_url += "&r_trad_character="  + <?php print $formVars['id']; ?>;
  at_url += "&r_trad_number="     + encode_URI(at_form.r_trad_number.value);

  script = document.createElement('script');
  script.src = p_script_url + at_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_sprite(p_script_url, update) {
  var as_form = document.edit;
  var as_url;
  
  as_url  = '?update='   + update;
  as_url += "&id="       + as_form.r_sprite_id.value;

  as_url += "&r_sprite_character="    + <?php print $formVars['id']; ?>;
  as_url += "&r_sprite_number="       + encode_URI(as_form.r_sprite_number.value);
  as_url += "&r_sprite_level="        + encode_URI(as_form.r_sprite_level.value);
  as_url += "&r_sprite_tasks="        + encode_URI(as_form.r_sprite_tasks.value);
  as_url += "&r_sprite_registered="   + as_form.r_sprite_registered.checked;

  script = document.createElement('script');
  script.src = p_script_url + as_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_spirit(p_script_url, update) {
  var as_form = document.edit;
  var as_url;
  
  as_url  = '?update='   + update;
  as_url += "&id="       + as_form.r_spirit_id.value;

  as_url += "&r_spirit_character="    + <?php print $formVars['id']; ?>;
  as_url += "&r_spirit_number="       + encode_URI(as_form.r_spirit_number.value);
  as_url += "&r_spirit_force="        + encode_URI(as_form.r_spirit_force.value);
  as_url += "&r_spirit_services="     + encode_URI(as_form.r_spirit_services.value);
  as_url += "&r_spirit_bound="        + as_form.r_spirit_bound.checked;

  script = document.createElement('script');
  script.src = p_script_url + as_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_firearms( p_script_url, update ) {
  var af_form = document.edit;
  var af_url;

  af_url  = '?update='   + update;
  af_url += '&id='       + af_form.id.value;

  af_url += "&r_fa_character=" + <?php print $formVars['id']; ?>;

  script = document.createElement('script');
  script.src = p_script_url + af_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_melee( p_script_url, update ) {
  var am_form = document.edit;
  var am_url;

  am_url  = '?update='   + update;
  am_url += '&id='       + am_form.id.value;

  am_url += "&r_melee_character=" + <?php print $formVars['id']; ?>;

  script = document.createElement('script');
  script.src = p_script_url + am_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_projectile( p_script_url, update ) {
  var ap_form = document.edit;
  var ap_url;

  ap_url  = '?update='   + update;
  ap_url += '&id='       + ap_form.id.value;

  ap_url += "&r_proj_character=" + <?php print $formVars['id']; ?>;

  script = document.createElement('script');
  script.src = p_script_url + ap_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_ammo( p_script_url, update ) {
  var aa_form = document.edit;
  var aa_url;

  aa_url  = '?update='   + update;

  aa_url += '&r_ammo_id='           + aa_form.r_ammo_id.value;
  aa_url += "&r_ammo_character="    + <?php print $formVars['id']; ?>;
  aa_url += '&r_ammo_number='       + aa_form.r_ammo_number.value;
  aa_url += '&r_ammo_rounds='       + aa_form.r_ammo_rounds.value;

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_fireacc( p_fa_id ) {
  var af_form = document.edit;
  var af_url;

  show_file('firearms.fill.php'  + '?id=' + p_fa_id);
  show_file('fireacc.mysql.php'  + '?update=-1&r_fa_id=' + p_fa_id);
  show_file('firearms.mysql.php' + '?update=-1&r_fa_character=<?php print $formVars['id']; ?>');
}

function attach_melacc( p_melee_id ) {
  var am_form = document.edit;
  var am_url;

  show_file('melee.fill.php'  + '?id=' + p_melee_id);
  show_file('melacc.mysql.php'  + '?update=-1&r_melee_id=' + p_melee_id);
  show_file('melee.mysql.php' + '?update=-1&r_melee_character=<?php print $formVars['id']; ?>');
}

function attach_projacc( p_proj_id ) {
  var ap_form = document.edit;
  var ap_url;

  show_file('projectile.fill.php'   + '?id=' + p_proj_id);
  show_file('projacc.mysql.php'     + '?update=-1&r_proj_id=' + p_proj_id);
  show_file('projectile.mysql.php'  + '?update=-1&r_proj_character=<?php print $formVars['id']; ?>');
}

function attach_mentor( p_script_url, update ) {
  var am_form = document.edit;
  var am_url;

  am_url  = '?update='   + update;
  am_url += '&id='       + am_form.id.value;

  am_url += "&r_mentor_character="    + <?php print $formVars['id']; ?>;
  am_url += "&r_mentor_number="       + am_form.r_mentor_number.value;

  script = document.createElement('script');
  script.src = p_script_url + am_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_vehicles( p_script_url, update ) {
  var av_form = document.edit;
  var av_url;

  av_url  = '?update='   + update;
  av_url += '&id='       + av_form.id.value;

  av_url += "&r_veh_character=" + <?php print $formVars['id']; ?>;

  script = document.createElement('script');
  script.src = p_script_url + av_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_armor(p_script_url, update) {
  var aa_form = document.edit;
  var aa_url;
  
  aa_url  = '?update='   + update;

  aa_url += "&r_arm_id="           + aa_form.r_arm_id.value;
  aa_url += "&r_arm_character="    + <?php print $formVars['id']; ?>;
  aa_url += "&r_arm_number="       + aa_form.r_arm_number.value;
  aa_url += "&r_arm_details="      + aa_form.r_arm_details.value;

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_armoracc(p_arm_id) {
  var aa_form = document.edit;
  var aa_url;
  
  show_file('armor.fill.php'  + '?id=' + p_arm_id);
  show_file('armoracc.mysql.php'  + '?update=-1&r_arm_id=' + p_arm_id);
  show_file('armor.mysql.php'  + '?update=-1&r_arm_character=<?php print $formVars['id']; ?>');
}

function attach_cyberware( p_script_url, update ) {
  var ac_form = document.edit;
  var ac_url;

  ac_url  = '?update='   + update;
  ac_url += '&id='       + ac_form.r_ware_id.value;

  ac_url += "&r_ware_character="   + <?php print $formVars['id']; ?>;
  ac_url += "&r_ware_number="      + ac_form.r_ware_number.value;
  ac_url += "&r_ware_specialize="  + ac_form.r_ware_specialize.value;
  ac_url += '&r_ware_grade='       + ac_form.r_ware_grade.value;

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_cyberacc( p_ware_id ) {
  var ac_form = document.edit;
  var ac_url;

  show_file('cyberware.fill.php'  + '?id=' + p_ware_id);
  show_file('cyberacc.mysql.php'  + '?update=-1&r_ware_id=' + p_ware_id);
  show_file('cyberware.mysql.php'  + '?update=-1&r_ware_character=<?php print $formVars['id']; ?>');
}

function attach_bioware( p_script_url, update ) {
  var ab_form = document.edit;
  var ab_url;

  ab_url  = '?update='   + update;

  ab_url += '&r_bio_id='          + ab_form.r_bio_id.value;
  ab_url += "&r_bio_character="   + <?php print $formVars['id']; ?>;
  ab_url += "&r_bio_number="      + ab_form.r_bio_number.value;
  ab_url += "&r_bio_grade="       + ab_form.r_bio_grade.value;
  ab_url += "&r_bio_specialize="  + ab_form.r_bio_specialize.value;

  script = document.createElement('script');
  script.src = p_script_url + ab_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_metamagics( p_script_url, update ) {
  var am_form = document.edit;
  var am_url;

  am_url  = '?update='      + update;

  am_url += '&r_meta_id='            + am_form.r_meta_id.value;
  am_url += "&r_meta_character="     + <?php print $formVars['id']; ?>;
  am_url += "&r_meta_number="        + am_form.r_meta_number.value;

  script = document.createElement('script');
  script.src = p_script_url + am_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_gear( p_script_url, update ) {
  var ag_form = document.edit;
  var ag_url;

  ag_url  = '?update='      + update;

  ag_url += '&r_gear_id='            + ag_form.r_gear_id.value;
  ag_url += "&r_gear_character="     + <?php print $formVars['id']; ?>;
  ag_url += "&r_gear_number="        + ag_form.r_gear_number.value;
  ag_url += "&r_gear_amount="        + ag_form.r_gear_amount.value;
  ag_url += '&r_gear_details='       + ag_form.r_gear_details.value;

  script = document.createElement('script');
  script.src = p_script_url + ag_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function attach_gearacc( p_gear_id ) {
  var ag_form = document.edit;
  var ag_url;

  show_file('gear.fill.php'  + '?id=' + p_gear_id);
  show_file('gearacc.mysql.php'  + '?update=-1&r_gear_id=' + p_gear_id);
  show_file('gear.mysql.php'  + '?update=-1&r_gear_character=<?php print $formVars['id']; ?>');
}

function attach_vehacc( p_veh_id ) {
  var av_form = document.edit;
  var av_url;

  show_file('vehicles.fill.php'  + '?id=' + p_veh_id);
  show_file('vehacc.mysql.php'  + '?update=-1&r_veh_id=' + p_veh_id);
  show_file('vehicles.mysql.php'  + '?update=-1&r_veh_character=<?php print $formVars['id']; ?>');
}

function insert_tags(p_script_url, update) {
  var at_form = document.edit;
  var at_url;
  
  at_url  = '&update='   + update;
  at_url += "&id="       + at_form.tag_character.value;

  at_url += "&tag_character=" + <?php print $formVars['id']; ?>;

  script = document.createElement('script');
  script.src = p_script_url + at_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {

  show_file('mooks.fill.php'        + '?update=-3&id=<?php print $formVars['id']; ?>');
<?php
# load all the forms
  if ($formVars['id'] > 0) {
?>
  show_file('active.mysql.php'          + '?update=-3&r_act_character=<?php print $formVars['id']; ?>');
  show_file('adept.mysql.php'           + '?update=-3&r_adp_character=<?php print $formVars['id']; ?>');
  show_file('agent.mysql.php'           + '?update=-3&r_deck_character=<?php print $formVars['id']; ?>');
  show_file('alchemy.mysql.php'         + '?update=-3&r_alc_character=<?php print $formVars['id']; ?>');
  show_file('ammo.mysql.php'            + '?update=-3&r_ammo_character=<?php print $formVars['id']; ?>');
  show_file('armor.mysql.php'           + '?update=-3&r_arm_character=<?php print $formVars['id']; ?>');
  show_file('armoracc.mysql.php'        + '?update=-1&r_arm_id=0');
  show_file('bioware.mysql.php'         + '?update=-3&r_bio_character=<?php print $formVars['id']; ?>');
  show_file('contact.mysql.php'         + '?update=-3&r_con_character=<?php print $formVars['id']; ?>');
  show_file('commlink.mysql.php'        + '?update=-3&r_link_character=<?php print $formVars['id']; ?>');
  show_file('linkacc.mysql.php'         + '?update=-1&r_link_id=0');
  show_file('command.mysql.php'         + '?update=-3&r_cmd_character=<?php print $formVars['id']; ?>');
  show_file('cmdacc.mysql.php'          + '?update=-1&r_cmd_id=0');
  show_file('complexform.mysql.php'     + '?update=-3&r_form_character=<?php print $formVars['id']; ?>');
  show_file('cyberdeck.mysql.php'       + '?update=-3&r_deck_character=<?php print $formVars['id']; ?>');
  show_file('deckacc.mysql.php'         + '?update=-1&r_deck_id=0');
  show_file('cyberware.mysql.php'       + '?update=-3&r_ware_character=<?php print $formVars['id']; ?>');
  show_file('cyberacc.mysql.php'        + '?update=-1&r_ware_id=0');
  show_file('finance.mysql.php'         + '?update=-3&fin_character=<?php print $formVars['id']; ?>');
  show_file('firearms.mysql.php'        + '?update=-3&r_fa_character=<?php print $formVars['id']; ?>');
  show_file('fireacc.mysql.php'         + '?update=-3&r_fa_id=0');
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
  show_file('mycommand.mysql.php'       + '?update=-3&r_cmd_character=<?php print $formVars['id']; ?>');
  show_file('mycyberdeck.mysql.php'     + '?update=-3&r_deck_character=<?php print $formVars['id']; ?>');
  show_file('notoriety.mysql.php'       + '?update=-3&not_character=<?php print $formVars['id']; ?>');
  show_file('program.mysql.php'         + '?id=0');
  show_file('program.mysql.php'         + '?id=1');
  show_file('program.mysql.php'         + '?id=2');
  show_file('program.mysql.php'         + '?id=3');
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
  $( "#cyberwaretabs"    ).tabs( ).addClass( "tab-shadow" );
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

<div id="detail-help" style="display: none">

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
  $q_users = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_users = mysql_fetch_array($q_users)) {
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
  <td class="ui-widget-content">Metatype <select name="runr_metatype">
<option value="0">None</option>
<?php
  $q_string  = "select meta_id,meta_name ";
  $q_string .= "from metatypes ";
  $q_string .= "left join versions on versions.ver_id = metatypes.meta_book ";
  $q_string .= "where ver_active = 1 ";
  $q_string .= "order by meta_name ";
  $q_metatypes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_metatypes = mysql_fetch_array($q_metatypes)) {
    print "<option value=\"" . $a_metatypes['meta_id'] . "\">" . $a_metatypes['meta_name'] . "</option>\n";
  }
?>
</select></td>
</tr>
<tr>
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
  <td class="ui-widget-content">Willpower <input type="text" name="runr_willpower" size="3"></td>
  <td class="ui-widget-content">Logic <input type="text" name="runr_logic" size="3"></td>
  <td class="ui-widget-content">Intuition <input type="text" name="runr_intuition" size="3"></td>
  <td class="ui-widget-content">Charisma <input type="text" name="runr_charisma" size="3"></td>
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
  <td class="ui-widget-content">What Shadowrun Release? <select name="runr_version">
<option value="0">Any Version</option>
<?php
  $q_string  = "select ver_id,ver_book ";
  $q_string .= "from versions ";
  $q_string .= "where ver_core = 1 and ver_active = 1 ";
  $q_string .= "order by ver_book ";
  $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysql_fetch_array($q_versions)) {
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

<div id="history-listing-help" style="display: none">

<div class="main-help ui-widget-content">

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

<div id="karma-listing-help" style="display: none">

<div class="main-help ui-widget-content">

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

<div id="finance-listing-help" style="display: none">

<div class="main-help ui-widget-content">

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

<div id="street-listing-help" style="display: none">

<div class="main-help ui-widget-content">

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

<div id="notoriety-listing-help" style="display: none">

<div class="main-help ui-widget-content">

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

<div id="publicity-listing-help" style="display: none">

<div class="main-help ui-widget-content">

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

<div id="tags-help" style="display: none">

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

<div id="active-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="knowledge-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="language-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="qualities-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Buttons</strong></li>
</ul>

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

<div id="contact-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="lifestyle-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="identity-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="license-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="spells-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Firewall Listing</strong></li>
</ul>

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

<div id="tradition-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="mentor-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="spirit-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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







<div id="alchemy">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('alchemy-hide');">Alchemical Preparation Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('alchemy-help');">Help</a></th>
</tr>
</table>

<div id="alchemy-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Firewall Listing</strong></li>
</ul>

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

<div id="metamagics-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Metamagics Listing</strong></li>
</ul>

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

<div id="adept-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="commlink-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Possible</strong></li>
</ul>

</div>

</div>

<div id="commlink-hide" style="display: none">

<span id="commlink_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<div id="commlinktabs">

<ul>
  <li><a href="#my_commlinks">My Commlinks</a></li>
  <li><a href="#commlinks">Commlinks</a></li>
  <li><a href="#linkacc">Accessories</a></li>
</ul>

<div id="my_commlinks">

<span id="my_commlink_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="commlinks">

<span id="commlink_table"><?php print wait_Process("Please Wait"); ?></span>

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

<div id="command-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Possible</strong></li>
</ul>

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

<div id="cyberdeck-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Possible</strong></li>
</ul>

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


<div id="sprite">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('sprite-hide');">Sprites</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('sprite-help');">Help</a></th>
</tr>
</table>

<div id="sprite-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="complexform-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Possible</strong></li>
</ul>

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

<div id="gear-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="armor-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="bioware-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="cyberware-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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
  <li><a href="#cyberjack">Cyberjack</a></li>
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


<div id="cyberjack">

<span id="cyberjack_table"><?php print wait_Process("Please Wait"); ?></span>

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

<div id="melee-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="ammo-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="projectile-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="firearms-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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

<div id="vehicles-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Notes</strong></li>
</ul>

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
