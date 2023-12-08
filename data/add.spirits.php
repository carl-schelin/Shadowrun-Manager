<?php
# Script: add.spirits.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.spirits.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Spirits</title>

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
function delete_spirit( p_script_url ) {
  var question;
  var answer;

  question  = "The Spirit may be in use by existing characters. Only delete if you're sure it's unused.\n";
  question += "Delete this Spirit?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.spirits.mysql.php?update=-1');
  }
}

function delete_power( p_script_url ) {
  var question;
  var answer;

  question = "Remove this Power from this Spirit?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.spirits.mysql.php?update=-1');
  }
}

function delete_active( p_script_url ) {
  var question;
  var answer;

  question = "Remove this Active Skill from this Spirit?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.spirits.mysql.php?update=-1');
  }
}

function delete_weaknesses( p_script_url ) {
  var question;
  var answer;

  question = "Remove this Weakness from this Spirit?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.spirits.mysql.php?update=-1');
  }
}

<?php
  }
?>

function attach_power(p_script_url, update) {
  var ap_form = document.spirits;
  var ap_url;

  ap_url  = "&update="      + update;
  ap_url += "&r_spirit_id=" + ap_form.r_spirit_id.value;

  script = document.createElement('script');
  script.src = p_script_url + ap_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.spirits.mysql.php?update=-1');
}

function attach_active(p_script_url, update) {
  var aa_form = document.spirits;
  var aa_url;

  aa_url  = "&update="      + update;
  aa_url += "&r_spirit_id=" + aa_form.r_spirit_id.value;

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.spirits.mysql.php?update=-1');
}

function attach_weaknesses(p_script_url, update) {
  var aw_form = document.spirits;
  var aw_url;

  aw_url  = "&update="      + update;
  aw_url += "&r_spirit_id=" + aw_form.r_spirit_id.value;

  script = document.createElement('script');
  script.src = p_script_url + aw_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.spirits.mysql.php?update=-1');
}

function dialog_power(p_script_url, update) {
  var dp_form = document.power;
  var dp_url;

  dp_url  = "?update="      + update;
  dp_url += "&id="          + dp_form.id.value;

  dp_url += "&sp_power_specialize=" + encode_URI(dp_form.sp_power_specialize.value);

  script = document.createElement('script');
  script.src = p_script_url + dp_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.spirits.mysql.php?update=-1');
}

function dialog_active(p_script_url, update) {
  var da_form = document.active;
  var da_url;

  da_url  = "?update="      + update;
  da_url += "&id="          + da_form.id.value;

  da_url += "&sp_act_specialize=" + encode_URI(da_form.sp_act_specialize.value);

  script = document.createElement('script');
  script.src = p_script_url + da_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.spirits.mysql.php?update=-1');
}

function dialog_weakness(p_script_url, update) {
  var dw_form = document.weakness;
  var dw_url;

  dw_url  = "?update="      + update;
  dw_url += "&id="          + dw_form.id.value;

  dw_url += "&sp_weak_specialize=" + encode_URI(dw_form.sp_weak_specialize.value);

  script = document.createElement('script');
  script.src = p_script_url + dw_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.spirits.mysql.php?update=-1');
}

function attach_spirit(p_script_url, update) {
  var as_form = document.dialog;
  var as_url;

  as_url  = '?update='   + update;
  as_url += "&id="       + as_form.id.value;

  as_url += "&spirit_name="         + encode_URI(as_form.spirit_name.value);
  as_url += "&spirit_body="         + encode_URI(as_form.spirit_body.value);
  as_url += "&spirit_agility="      + encode_URI(as_form.spirit_agility.value);
  as_url += "&spirit_reaction="     + encode_URI(as_form.spirit_reaction.value);
  as_url += "&spirit_strength="     + encode_URI(as_form.spirit_strength.value);
  as_url += "&spirit_willpower="    + encode_URI(as_form.spirit_willpower.value);
  as_url += "&spirit_logic="        + encode_URI(as_form.spirit_logic.value);
  as_url += "&spirit_intuition="    + encode_URI(as_form.spirit_intuition.value);
  as_url += "&spirit_charisma="     + encode_URI(as_form.spirit_charisma.value);
  as_url += "&spirit_edge="         + encode_URI(as_form.spirit_edge.value);
  as_url += "&spirit_essence="      + encode_URI(as_form.spirit_essence.value);
  as_url += "&spirit_magic="        + encode_URI(as_form.spirit_magic.value);
  as_url += "&spirit_description="  + encode_URI(as_form.spirit_description.value);
  as_url += "&spirit_book="         + encode_URI(as_form.spirit_book.value);
  as_url += "&spirit_page="         + encode_URI(as_form.spirit_page.value);

  script = document.createElement('script');
  script.src = p_script_url + as_url;
  document.getElementsByTagName('head')[0].appendChild(script);
}

function clear_fields() {
  show_file('add.spirits.mysql.php?update=-3');
  show_file('active.mysql.php?update=-1');
  show_file('powers.mysql.php?update=-1');
  show_file('weaknesses.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickSpirit' ).click(function() {
    $( "#dialogSpirit" ).dialog('open');
  });

  $( "#dialogSpirit" ).dialog({
    autoOpen: false,

    modal: true,
    height: 215,
    width:  780,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogSpirit" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_spirit('add.spirits.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Spirit",
        click: function() {
          attach_spirit('add.spirits.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Spirit",
        click: function() {
          attach_spirit('add.spirits.mysql.php', 0);
          $( this ).dialog( "close" );
        }
      }
    ]
  });

  $( "#dialogActive" ).dialog({
    autoOpen: false,

    modal: true,
    height: 180,
    width:  780,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogActive" ).hide();
    },
    buttons: [
      {
        id: "active-cancel",
        text: "Cancel",
        click: function() {
          dialog_active('active.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "active-update",
        text: "Update Active Skill",
        click: function() {
          dialog_active('active.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      }
    ]
  });

  $( "#dialogPower" ).dialog({
    autoOpen: false,

    modal: true,
    height: 180,
    width:  780,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogPower" ).hide();
    },
    buttons: [
      {
        id: "power-cancel",
        text: "Cancel",
        click: function() {
          dialog_power('powers.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "power-update",
        text: "Update Power",
        click: function() {
          dialog_power('powers.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      }
    ]
  });

  $( "#dialogWeakness" ).dialog({
    autoOpen: false,

    modal: true,
    height: 180,
    width:  780,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogWeakness" ).hide();
    },
    buttons: [
      {
        id: "weakness-cancel",
        text: "Cancel",
        click: function() {
          dialog_weakness('weaknesses.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "weakness-update",
        text: "Update Weakness",
        click: function() {
          dialog_weakness('weaknesses.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      }
    ]
  });
});

</script>

</head>
<body onLoad="clear_fields();" class="ui-widget-content">

<?php include($Sitepath . "/topmenu.start.php"); ?>
<?php include($Sitepath . "/topmenu.end.php"); ?>

<div id="main">

<form name="spirits">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default"><a href="javascript:;" onmousedown="toggleDiv('spirit-hide');">Spirit Management</a></th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('spirit-help');">Help</a></th>
</tr>
</table>

<div id="spirit-help" style="display: none">

<div class="main-help ui-widget-content">

<ul>
  <li><strong>sprite Form</strong>
  <ul>
    <li><strong>Name</strong> - The name of the Metatype.</li>
    <li><strong>Walk</strong> - The Metatype walking speed.</li>
    <li><strong>Run</strong> - The Metatype run speed.</li>
    <li><strong>Swim</strong> - The Metatype swim speed.</li>
    <li><strong>Book</strong> - Select the book where this table is located.</li>
    <li><strong>Page</strong> - Identify the page number.</li>
  </ul></li>
</ul>

</div>

</div>

<div id="spirit-hide" style="display: none">

<span id="spirit_form"><?php print wait_Process("Please Wait"); ?></span>

</div>

<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addnew" id="clickSpirit" value="Add Spirit"></td>
</tr>
</table>

</form>


<div id="tabs">

<ul>
  <li><a href="#spirits">Spirits</a></li>
  <li><a href="#active">Active Skills</a></li>
  <li><a href="#powers">Powers</a></li>
  <li><a href="#optional">Optional Powers</a></li>
  <li><a href="#weakness">Weaknesses</a></li>
</ul>

<div id="spirits">

<span id="spirits_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="active">

<span id="active_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="powers">

<span id="powers_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="optional">

<span id="optional_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


<div id="weakness">

<span id="weakness_table"><?php print wait_Process("Please Wait"); ?></span>

</div>


</div>

</div>


<div id="dialogSpirit" title="Spirits">

<form name="dialog">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="7">Spirit Form</th>
</tr>
<tr>
  <td class="ui-widget-content" colspan="2">Name <input type="text" name="spirit_name" size="30"></td>
  <td class="ui-widget-content">Body <input type="text" name="spirit_body" size="3"></td>
  <td class="ui-widget-content">Agility <input type="text" name="spirit_agility" size="3"></td>
  <td class="ui-widget-content">Reaction <input type="text" name="spirit_reaction" size="3"></td>
  <td class="ui-widget-content">Strength <input type="text" name="spirit_strength" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Willpower <input type="text" name="spirit_willpower" size="3"></td>
  <td class="ui-widget-content">Logic <input type="text" name="spirit_logic" size="3"></td>
  <td class="ui-widget-content">Intuition <input type="text" name="spirit_intuition" size="3"></td>
  <td class="ui-widget-content">Charisma <input type="text" name="spirit_charisma" size="3"></td>
  <td class="ui-widget-content">Edge <input type="text" name="spirit_edge" size="3"></td>
  <td class="ui-widget-content">Essence <input type="text" name="spirit_essence" size="3"></td>
</tr>
<tr>
  <td class="ui-widget-content">Magic <input type="text" name="spirit_magic" size="3"></td>
  <td class="ui-widget-content" colspan="4">Special <input type="text" name="spirit_description" size="50"></td>
  <td class="ui-widget-content">Book <select name="spirit_book">
<?php
  $q_string  = "select ver_id,ver_short ";
  $q_string .= "from versions ";
  $q_string .= "where ver_admin = 1 ";
  $q_string .= "order by ver_short ";
  $q_versions = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_versions = mysqli_fetch_array($q_versions)) {
    print "<option value=\"" . $a_versions['ver_id'] . "\">" . $a_versions['ver_short'] . "</option>\n";
  }
?>
</select>: <input type="text" name="spirit_page" size="3"></td>
</tr>
</table>

</form>

</div>


<div id="dialogActive" title="Active Skills">

<form name="active">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Active Skill Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Description <input type="text" name="sp_act_specialize" size="40"></td>
</tr>
</table>

</form>

</div>


<div id="dialogPower" title="Powers">

<form name="power">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Power Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Description <input type="text" name="sp_power_specialize" size="40"></td>
</tr>
</table>

</form>

</div>


<div id="dialogWeakness" title="Weaknesses">

<form name="weakness">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Weakness Form</th>
</tr>
<tr>
  <td class="ui-widget-content">Description <input type="text" name="sp_weak_specialize" size="40"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
