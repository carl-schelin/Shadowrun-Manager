<?php
# Script: add.complexform.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.complexform.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Complex Forms</title>

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
function delete_complexform( p_script_url ) {
  var question;
  var answer;

  question  = "The Complex Form may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Spell?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.complexform.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_complexform(p_script_url, update) {
  var ac_form = document.dialog;
  var ac_url;

  ac_url  = '?update='   + update;
  ac_url += "&id="       + ac_form.id.value;

  ac_url += "&form_name="        + encode_URI(ac_form.form_name.value);
  ac_url += "&form_target="      + encode_URI(ac_form.form_target.value);
  ac_url += "&form_duration="    + encode_URI(ac_form.form_duration.value);
  ac_url += "&form_level="       + encode_URI(ac_form.form_level.value);
  ac_url += "&form_fading="      + encode_URI(ac_form.form_fading.value);
  ac_url += "&form_book="        + encode_URI(ac_form.form_book.value);
  ac_url += "&form_page="        + encode_URI(ac_form.form_page.value);

  script = document.createElement('script');
  script.src = p_script_url + ac_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.complexform.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickForm' ).click(function() {
    $( "#dialogForm" ).dialog('open');
  });

  $( "#dialogForm" ).dialog({
    autoOpen: false,
    modal: true,
    height: 275,
    width:  600,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogForm" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_complexform('add.complexform.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Complex Form",
        click: function() {
          attach_complexform('add.complexform.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Complex Form",
        click: function() {
          attach_complexform('add.complexform.mysql.php', 0);
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

<form name="complexform">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Complex Form Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('complexform-help');">Help</a></th>
</tr>
</table>

<div id="spells-help" style="display: none">

<div class="complexform-help ui-widget-content">

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
  <td class="button ui-widget-content"><input type="button" name="addform" id="clickForm" value="Add complex Form"></td>
</tr>
</table>

</form>

<span id="complexform_table"><?php print wait_Process('Loading Complex Forms...')?></span>

</div>


</div>

</div>


<div id="dialogForm" title="Resonance Library">

<form name="dialog">

<input type="hidden" name="id" value="0">

<table class="ui-styled-table" width="100%">
<tr>
  <td class="ui-widget-content">Complex Form <input type="text" name="form_name" size="30"></td>
</tr>
<tr>
  <td class="ui-widget-content">Target <select name="form_target">
    <option value="0">Device</option>
    <option value="1">File</option>
    <option value="2">Persona</option>
    <option value="3">Self</option>
    <option value="4">Sprite</option>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Duration <select name="form_duration">
    <option value="0">Immediate</option>
    <option value="1">Permanent</option>
    <option value="2">Sustained</option>
</select></td>
</tr>
<tr>
  <td class="ui-widget-content">Use Complex Form Level? <input type="checkbox" name="form_level"> (sr5)</td>
</tr>
<tr>
  <td class="ui-widget-content">Fading Value <input type="text" name="form_fading" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Book  <select name="form_book">
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
</select>: <input type="text" name="form_page" size="3"></td>
</tr>
</table>

</form>

</div>

<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
