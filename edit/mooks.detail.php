
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

function attach_finance(p_script_url, update) {
  var af_form = document.edit;
  var af_url;
  
  if (Number.isInteger(+af_form.fin_funds.value)) {

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
  } else {
    alert("Value: [" + af_form.fin_funds.value + "] is not a valid integer. The number cannot contain non-numeric values.");
  }

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

  if (Number.isInteger(+ak_form.kar_karma.value)) {
    ak_url  = '?update='    + update;
    ak_url += "&kar_id="    + ak_form.kar_id.value;

    ak_url += "&kar_character=" + <?php print $formVars['id']; ?>;
    ak_url += "&kar_karma="     + ak_form.kar_karma.value;
    ak_url += "&kar_date="      + encode_URI(ak_form.kar_date.value);
    ak_url += "&kar_notes="     + encode_URI(ak_form.kar_notes.value);

    script = document.createElement('script');
    script.src = p_script_url + ak_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('karma.mysql.php' + '?update=-1' + '&kar_character=<?php print $formVars['id']; ?>');
  } else {
    alert("Value: [" + ak_form.kar_karma.value + "] is not a valid integer. The number cannot contain non-numeric values.");
  }
}

function attach_street(p_script_url, update) {
  var as_form = document.edit;
  var as_url;
  
  if (Number.isInteger(+as_form.st_cred.value)) {
    as_url  = '?update='    + update;
    as_url += "&st_id="     + as_form.st_id.value;

    as_url += "&st_character=" + <?php print $formVars['id']; ?>;
    as_url += "&st_cred="      + as_form.st_cred.value;
    as_url += "&st_date="      + encode_URI(as_form.st_date.value);
    as_url += "&st_notes="     + encode_URI(as_form.st_notes.value);

    script = document.createElement('script');
    script.src = p_script_url + as_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('street.mysql.php' + '?update=-1' + '&st_character=<?php print $formVars['id']; ?>');
  } else {
    alert("Value: [" + as_form.st_cred.value + "] is not a valid integer. The number cannot contain non-numeric values.");
  }
}

function attach_notoriety(p_script_url, update) {
  var an_form = document.edit;
  var an_url;
  
  if (Number.isInteger(+an_form.not_notoriety.value)) {
    an_url  = '?update='    + update;
    an_url += "&not_id="    + an_form.not_id.value;

    an_url += "&not_character=" + <?php print $formVars['id']; ?>;
    an_url += "&not_notoriety=" + an_form.not_notoriety.value;
    an_url += "&not_date="      + encode_URI(an_form.not_date.value);
    an_url += "&not_notes="     + encode_URI(an_form.not_notes.value);

    script = document.createElement('script');
    script.src = p_script_url + an_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('notoriety.mysql.php' + '?update=-1' + '&not_character=<?php print $formVars['id']; ?>');
  } else {
    alert("Value: [" + an_form.not_notoriety.value + "] is not a valid integer. The number cannot contain non-numeric values.");
  }
}

function attach_publicity(p_script_url, update) {
  var ap_form = document.edit;
  var ap_url;
  
  if (Number.isInteger(+ap_form.pub_publicity.value)) {
    ap_url  = '?update='    + update;
    ap_url += "&pub_id="    + ap_form.pub_id.value;

    ap_url += "&pub_character=" + <?php print $formVars['id']; ?>;
    ap_url += "&pub_publicity=" + ap_form.pub_publicity.value;
    ap_url += "&pub_date="      + encode_URI(ap_form.pub_date.value);
    ap_url += "&pub_notes="     + encode_URI(ap_form.pub_notes.value);

    script = document.createElement('script');
    script.src = p_script_url + ap_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('publicity.mysql.php' + '?update=-1' + '&pub_character=<?php print $formVars['id']; ?>');
  } else {
    alert("Value: [" + ap_form.pub_publicity.value + "] is not a valid integer. The number cannot contain non-numeric values.");
  }
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






function check_runner() {
  var cr_form = document.edit;
  var cr_url;

  cr_url  = '?id='           + cr_form.id.value;
  cr_url += "&runr_name="    + encode_URI(cr_form.runr_name.value);
  cr_url += "&runr_version=" + cr_form.runr_version.value;

  show_file('validate.runner.php' + cr_url);
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

function delete_tags( p_script_url ) {
  var answer = confirm("Delete this Tag?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('tags.mysql.php' + '?update=-1' + '&tag_character=<?php print $formVars['id']; ?>');
  }
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

