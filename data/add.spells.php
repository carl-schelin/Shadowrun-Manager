<?php
# Script: add.spells.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.spells.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

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

  if (check_userlevel(1)) {
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
    height: 190,
    width:  940,
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
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="6">Spell Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Group <select name="spell_group">
<?php
  $q_string  = "select class_id,class_name ";
  $q_string .= "from class ";
  $q_string .= "left join subjects on subjects.sub_id = class.class_subjectid ";
  $q_string .= "where sub_name = \"Spells\" ";
  $q_string .= "order by class_name ";
  $q_class = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_class = mysql_fetch_array($q_class)) {
    print "<option value=\"" . $a_class['class_id'] . "\">" . $a_class['class_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content">Name <input type="text" name="spell_name" size="25"></td>
  <td class="ui-widget-content" colspan="2">Class <input type="text" name="spell_class" size="20"></td>
  <td class="ui-widget-content">Type <input type="text" name="spell_type" size="8"></td>
  <td class="ui-widget-content">Test <input type="text" name="spell_test" size="8"></td>
</tr>
<tr>
  <td class="ui-widget-content">Range <input type="text" name="spell_range" size="10"></td>
  <td class="ui-widget-content">Damage <input type="text" name="spell_damage" size="8"></td>
  <td class="ui-widget-content">Duration <input type="text" name="spell_duration" size="10"></td>
  <td class="ui-widget-content">Use Force? <input type="checkbox" name="spell_force"></td>
  <td class="ui-widget-content">Drain <input type="text" name="spell_drain" size="3"></td>
  <td class="ui-widget-content">Book  <select name="spell_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysql_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select>: <input type="text" name="spell_page" size="4"></td>
</tr>
</table>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
