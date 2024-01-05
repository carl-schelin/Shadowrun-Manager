// There are three main devices here for Matrix work.
// Commlink, Command Console (RCC), and Cyberdeck. Add in a Cyberjack for the Commlink and Cyberdeck.
// Eacy has a 'my[item].mysql.php file which updates and displays 'My [item]'. Other scripts fill out the 
// [item] tab, programs (common and hacking), Agents, and Accessories.

// Process flow is fill out my[item] then the various tabs.


function attach_commlink(p_script_url, update) {
  var ac_form = document.edit;
  var ac_url;
  
  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.r_link_id.value;

  ac_url += "&r_link_character="   + <?php print $formVars['id']; ?>;

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycommlink.mysql.php'  + '?update=-1&r_link_character=<?php print $formVars['id']; ?>');
}

function attach_linkacc( p_link_id ) {
  var al_form = document.edit;
  var al_url;

  show_file('commlink.fill.php'     + '?id=' + p_link_id);
  show_file('linkacc.mysql.php'     + '?update=-1&r_link_id=' + p_link_id);
  show_file('program.mysql.php'     + '?id=0&r_link_id=' + p_link_id);
  show_file('program.mysql.php'     + '?id=1&r_link_id=' + p_link_id);
  show_file('agent.mysql.php'       + '?id=0&update=-1&r_link_id=' + p_link_id);
  show_file('mycommlink.mysql.php'  + '?update=-1&r_link_character=<?php print $formVars['id']; ?>');
}

function attach_linkpgm(p_script_url, update) {
  var al_form = document.edit;
  var al_url;
  
  al_url  = '?update='   + update;
  al_url += "&id="       + al_form.r_pgm_id.value;

  al_url += "&r_link_character="   + <?php print $formVars['id']; ?>;
  al_url += "&r_link_id="          + al_form.r_link_id.value;

  script = document.createElement('script');
  script.src = p_script_url + al_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycommlink.mysql.php'  + '?update=-1&r_link_character=<?php print $formVars['id']; ?>');
}

function commlink_left(p_script_url, p_value) {
  var cl_form = document.edit;
  var cl_url;
  
  cl_url  = "?left="     + p_value;
  cl_url += "&id="       + cl_form.r_link_id.value;

  script = document.createElement('script');
  script.src = p_script_url + cl_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('commlink.fill.php'  + '?id=' + cl_form.r_link_id.value);
  show_file('mycommlink.mysql.php'  + '?update=-1&r_link_character=<?php print $formVars['id']; ?>');
}

function commlink_right(p_script_url, p_value) {
  var cr_form = document.edit;
  var cr_url;
  
  cr_url  = "?right="    + p_value;
  cr_url += "&id="       + cr_form.r_link_id.value;

  script = document.createElement('script');
  script.src = p_script_url + cr_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('commlink.fill.php'  + '?id=' + cr_form.r_link_id.value);
  show_file('mycommlink.mysql.php'  + '?update=-1&r_link_character=<?php print $formVars['id']; ?>');
}

function delete_commlink( p_script_url ) {
  var answer = confirm("Delete this Commlink?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycommlink.mysql.php' + '?update=-1' + '&r_link_character=<?php print $formVars['id']; ?>');
  }
}

function delete_linkacc( p_script_url ) {
  var answer = confirm("Delete this Commlink Accessory?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycommlink.mysql.php' + '?update=-1' + '&r_link_character=<?php print $formVars['id']; ?>');
  }
}

function delete_linkagt( p_script_url ) {
  var answer = confirm("Delete this Agent?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycommlink.mysql.php' + '?update=-1' + '&r_link_character=<?php print $formVars['id']; ?>');
  }
}

function delete_linkpgm( p_script_url ) {
  var answer = confirm("Delete this Program?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycommlink.mysql.php' + '?update=-1' + '&r_link_character=<?php print $formVars['id']; ?>');
  }
}

function select_commlink(p_script_url) {
  var sc_form = document.edit;
  var sc_url;

  sc_url  = "&r_link_character="   + <?php print $formVars['id']; ?>;

  script = document.createElement('script');
  script.src = p_script_url + sc_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycommlink.mysql.php'  + '?update=-1&r_link_character=<?php print $formVars['id']; ?>');
}

function select_linkagt(p_script_url) {
  var sl_form = document.edit;
  var sl_url;

  sl_url  = "&r_link_character="   + <?php print $formVars['id']; ?>;
  sl_url += "&r_link_id="          + sl_form.r_link_id.value;

  script = document.createElement('script');
  script.src = p_script_url + sl_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycommlink.mysql.php'  + '?update=-1&r_link_character=<?php print $formVars['id']; ?>');
}

function select_linkpgm(p_script_url) {
  var sl_form = document.edit;
  var sl_url;

  sl_url  = "&r_link_character="   + <?php print $formVars['id']; ?>;
  sl_url += "&r_link_id="          + sl_form.r_link_id.value;

  script = document.createElement('script');
  script.src = p_script_url + sl_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('mycommlink.mysql.php'  + '?update=-1&r_link_character=<?php print $formVars['id']; ?>');
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

  show_file('command.fill.php'     + '?id=' + p_cmd_id);
  show_file('cmdacc.mysql.php'     + '?update=-1&r_cmd_id=' + p_cmd_id);
  show_file('program.mysql.php'    + '?id=2&r_cmd_id=' + p_cmd_id);
  show_file('program.mysql.php'    + '?id=3&r_cmd_id=' + p_cmd_id);
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

function delete_command( p_script_url ) {
  var answer = confirm("Delete this Rigger Command Console?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycommand.mysql.php' + '?update=-1' + '&r_cmd_character=<?php print $formVars['id']; ?>');
  }
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




function cyberjack_left(p_script_url, p_value) {
  var cl_form = document.edit;
  var cl_url;
  
  cl_url  = "?left="     + p_value;
  cl_url += "&id="       + cl_form.r_jack_id.value;

  script = document.createElement('script');
  script.src = p_script_url + cl_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('cyberjack.fill.php'  + '?id=' + cl_form.r_jack_id.value);
  show_file('mycyberjack.mysql.php'  + '?update=-1&r_jack_character=<?php print $formVars['id']; ?>');
}

function cyberjack_right(p_script_url, p_value) {
  var cr_form = document.edit;
  var cr_url;
  
  cr_url  = "?right="    + p_value;
  cr_url += "&id="       + cr_form.r_jack_id.value;

  script = document.createElement('script');
  script.src = p_script_url + cr_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('cyberjack.fill.php'  + '?id=' + cr_form.r_jack_id.value);
  show_file('mycyberjack.mysql.php'  + '?update=-1&r_jack_character=<?php print $formVars['id']; ?>');
}

function attach_jackacc( p_jack_id ) {
  show_file('cyberjack.fill.php'  + '?id=' + p_jack_id);
  show_file('mycyberjack.mysql.php'  + '?update=-1&r_jack_character=<?php print $formVars['id']; ?>');
}

function delete_cyberjack( p_script_url ) {
  var answer = confirm("Delete this Cyberjack?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('cyberjack.mysql.php'   + '?update=-3&r_jack_character=<?php print $formVars['id']; ?>');
    show_file('mycyberjack.mysql.php' + '?update=-1' + '&r_jack_character=<?php print $formVars['id']; ?>');
  }
}






function attach_deckacc( p_deck_id ) {
  show_file('cyberdeck.fill.php'     + '?id=' + p_deck_id);
  show_file('deckacc.mysql.php'      + '?update=-1&r_deck_id=' + p_deck_id);
  show_file('program.mysql.php'      + '?id=4&r_deck_id=' + p_deck_id);
  show_file('program.mysql.php'      + '?id=5&r_deck_id=' + p_deck_id);
  show_file('agent.mysql.php'        + '?id=2&update=-1&r_deck_id=' + p_deck_id);
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

function delete_cyberdeck( p_script_url ) {
  var answer = confirm("Delete this Cyberdeck?")

alert(p_script_url);

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

function delete_deckpgm( p_script_url ) {
  var answer = confirm("Delete this Program?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycyberdeck.mysql.php' + '?update=-1' + '&r_deck_character=<?php print $formVars['id']; ?>');
  }
}

function delete_deckagt( p_script_url ) {
  var answer = confirm("Delete this Agent?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('mycyberdeck.mysql.php' + '?update=-1' + '&r_deck_character=<?php print $formVars['id']; ?>');
  }
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


function delete_complexform( p_script_url ) {
  var answer = confirm("Delete this Complex Form?")

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('complexform.mysql.php' + '?update=-1' + '&r_form_character=<?php print $formVars['id']; ?>');
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

