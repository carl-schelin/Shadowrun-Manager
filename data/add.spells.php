<?php
# Script: add.spells.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.spells.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Spells</title>

<style type="text/css" title="currentStyle" media="screen">
<?php include($Sitepath . "/mobile.php"); ?>
</style>

<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/jquery.js"></script>
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.js"></script>
<link   rel="stylesheet" type="text/css"            href="<?php print $Siteroot; ?>/css/themes/<?php print $_SESSION['theme']; ?>/jquery-ui.css">
<script type="text/javascript" language="javascript" src="<?php print $Siteroot; ?>/functions/jquery.inventory.js"></script>

<script type="text/javascript">
<?php

  if (check_userlevel($db, $AL_Johnson)) {
?>
function delete_spells( p_script_url ) {
  var question;
  var answer;

  question  = "The Spell may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Spell?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.spells.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_spells(p_script_url, update) {
  var ad_form = document.dialog;
  var ad_url;

  ad_url  = '?update='   + update;
  ad_url += "&id="       + ad_form.id.value;

  ad_url += "&spell_group="       + encode_URI(ad_form.spell_group.value);
  ad_url += "&spell_name="        + encode_URI(ad_form.spell_name.value);
  ad_url += "&spell_class="       + encode_URI(ad_form.spell_class.value);
  ad_url += "&spell_type="        + encode_URI(ad_form.spell_type.value);
  ad_url += "&spell_test="        + encode_URI(ad_form.spell_test.value);
  ad_url += "&spell_range="       + encode_URI(ad_form.spell_range.value);
  ad_url += "&spell_damage="      + encode_URI(ad_form.spell_damage.value);
  ad_url += "&spell_duration="    + encode_URI(ad_form.spell_duration.value);
  ad_url += "&spell_force="       + ad_form.spell_force.checked;
  ad_url += "&spell_half="        + ad_form.spell_half.checked;
  ad_url += "&spell_drain="       + encode_URI(ad_form.spell_drain.value);
  ad_url += "&spell_book="        + encode_URI(ad_form.spell_book.value);
  ad_url += "&spell_page="        + encode_URI(ad_form.spell_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ad_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.spells.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickSpells' ).click(function() {
    $( "#dialogSpells" ).dialog('open');
  });

  $( "#dialogSpells" ).dialog({
    autoOpen: false,
    modal: true,
    height: 425,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogSpells" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_spells('add.spells.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Spell",
        click: function() {
          attach_spells('add.spells.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Spells",
        click: function() {
          attach_spells('add.spells.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });
});

$("#button-update").button("disable");

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . "/topmenu.start.php"); ?>
<?php include($Sitepath . "/topmenu.end.php"); ?>

<div id="main">

<form name="spells">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Spell Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('spells-help');">Help</a></th>
</tr>
</table>

<div id="spells-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>Spell Listing</strong>
  <ul>
    <li><strong>Name</strong> - The name of the spell.</li>
    <li><strong>Class</strong> - Spell class.</li>
    <li><strong>Type</strong> - Mana or Physical Spell.</li>
    <li><strong>Test</strong> - Test type; Opposed, which is resisted by attribute listed. S(OR) must beat the Object Resistance threshold.</li>
    <li><strong>Range</strong> - Select the book where this table is located.</li>
    <li><strong>Damage</strong> - Either Physical or Stun.</li>
    <li><strong>Duration</strong> - Spell duration. Instant, Sustained, or Permanent.</li>
    <li><strong>Drain</strong> - Drain Value.</li>
    <li><strong>Book/Page</strong> - Book and page number where you can find the spell. Core book (sr5) or Street Grimoire (sg).</li>
  </ul></li>
</ul>

<p>The Use Force and Use Force / 2 checkboxes are when the Drain Value is based on the strength of the cast spell in force. Some are half force, 
some are full force, and some are a hard value.</p>

</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addspell" id="clickSpells" value="Add Spells"></td>
</tr>
</table>

</form>

<div id="tabs">

<ul>
  <li><a href="#combat">Combat</a></li>
  <li><a href="#detection">Detection</a></li>
  <li><a href="#health">Health</a></li>
  <li><a href="#illusion">Illusion</a></li>
  <li><a href="#manipulation">Manipulation</a></li>
</ul>

<div id="combat">

<span id="combat_table"><?php print wait_Process('Loading Combat Spells...')?></span>

</div>


<div id="detection">

<span id="detection_table"><?php print wait_Process('Loading Detection Spells...')?></span>

</div>


<div id="health">

<span id="health_table"><?php print wait_Process('Loading Health Spells...')?></span>

</div>


<div id="illusion">

<span id="illusion_table"><?php print wait_Process('Loading Illusion Spells...')?></span>

</div>


<div id="manipulation">

<span id="manipulation_table"><?php print wait_Process('Loading Manipulation Spells...')?></span>

</div>


</div>

</div>


<div id="dialogSpells" title="Spell List">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.spells.dialog.php');
?>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
