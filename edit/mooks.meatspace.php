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

function delete_melacc( p_script_url ) {
  var answer = confirm("Delete this Melee Weapon Accessory?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('melee.mysql.php' + '?update=-1' + '&r_melee_character=<?php print $formVars['id']; ?>');
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

