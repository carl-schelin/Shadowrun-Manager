
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

function delete_spells( p_script_url ) {
  var answer = confirm("Delete this Spell?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('spells.mysql.php' + '?update=-1' + '&r_spell_character=<?php print $formVars['id']; ?>');
  }
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

function delete_alchemy( p_script_url ) {
  var answer = confirm("Delete this Alchemical Preparation?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('alchemy.mysql.php' + '?update=-1' + '&r_alc_character=<?php print $formVars['id']; ?>');
  }
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

function delete_adept( p_script_url ) {
  var answer = confirm("Delete this Power?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('adept.mysql.php' + '?update=-1' + '&r_adp_character=<?php print $formVars['id']; ?>');
  }
}



function delete_foci( p_script_url ) {
  var answer = confirm("Delete Foci?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('foci.mysql.php' + '?update=-1' + '&r_foci_character=<?php print $formVars['id']; ?>');
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

function delete_spirit( p_script_url ) {
  var answer = confirm("Banish Spirit?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('spirit.mysql.php' + '?update=-1' + '&r_spirit_character=<?php print $formVars['id']; ?>');
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

