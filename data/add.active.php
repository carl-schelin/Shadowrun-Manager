<?php
# Script: add.active.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login('1');

  $package = "add.active.php";

  logaccess($_SESSION['username'], $package, "Accessing script");

# if help has not been seen yet,
  if (show_Help($Dataroot . "/" . $package)) {
    $display = "display: block";
  } else {
    $display = "display: none";
  }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Manage Active Skills</title>

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
function delete_active( p_script_url ) {
  var question;
  var answer;

  question  = "The Skill will be removed from all characters that have it.\n";
  question += "Delete this Skill?";

  answer = confirm(question);

  if (answer) {
    script = document.createElement('script');
    script.src = p_script_url;
    document.getElementsByTagName('head')[0].appendChild(script);
    show_file('add.active.mysql.php?update=-1');
  }
}
<?php
  }
?>

function attach_active(p_script_url, update) {
  var aa_form = document.dialog;
  var aa_url;

  aa_url  = '?update='   + update;
  aa_url += "&id="       + aa_form.id.value;

  aa_url += "&act_type="      + encode_URI(aa_form.act_type.value);
  aa_url += "&act_name="      + encode_URI(aa_form.act_name.value);
  aa_url += "&act_group="     + encode_URI(aa_form.act_group.value);
  aa_url += "&act_attribute=" + encode_URI(aa_form.act_attribute.value);
  aa_url += "&act_default="   + aa_form.act_default.checked;
  aa_url += "&act_book="      + encode_URI(aa_form.act_book.value);
  aa_url += "&act_page="      + encode_URI(aa_form.act_page.value);

  script = document.createElement('script');
  script.src = p_script_url + aa_url;
  document.getElementsByTagName('head')[0].appendChild(script);
  show_file('add.active.mysql.php?update=-1');
}

function clear_fields() {
  show_file('add.active.mysql.php?update=-1');
}

$(document).ready( function() {
  $( "#tabs" ).tabs( ).addClass( "tab-shadow" );

  $( '#clickActive' ).click(function() {
    $( "#dialogActive" ).dialog('open');
  });

  $( "#dialogActive" ).dialog({
    autoOpen: false,
    modal: true,
    height: 200,
    width:  640,
    dialogClass: 'dialogWithDropShadow',
    close: function(event, ui) {
      $( "#dialogActive" ).hide();
    },
    buttons: [
      {
        id: "button-cancel",
        text: "Cancel",
        click: function() {
          attach_active('add.active.mysql.php', -1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-update",
        text: "Update Active Skill",
        click: function() {
          attach_active('add.active.mysql.php', 1);
          $( this ).dialog( "close" );
        }
      },
      {
        id: "button-add",
        text: "Add Active Skill",
        click: function() {
          attach_active('add.active.mysql.php', 0);
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

<form name="active">

<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Active Skills Management</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('active-help');">Help</a></th>
</tr>
</table>

<div id="active-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">

<p><strong>Active Skills</strong></p>

<p>Active Skills are selected by players to expand their character abilities. This editor lets you add the skills the players will be able to select. 
The main </p>

<p><strong>Active Skill Form</strong></p>

<ul>
  <li>Type - Enter in one of the tabbed names such as Combat to identify the skill type</li>
  <li>Name - The name of the skill</li>
  <li>Group - If the skill is a member of a skill group, enter the name here</li>
  <li>Linked Attribute - The attribute most associated with this skill</li>
  <li>Default - If the skill can be used without assigning points to it</li>
  <li>Book - Select the book where this skill can be found</li>
  <li>Page - Identify the page number where the skill description can be found</li>
</ul>

</div>

</div>


<table class="ui-styled-table" width="100%">
<tr>
  <td class="button ui-widget-content"><input type="button" name="addact" id="clickActive" value="Add Active Skill"></td>
</tr>
</table>

</form>

<div id="tabs">

<ul>
  <li><a href="#combat">Combat</a></li>
  <li><a href="#magical">Magical</a></li>
  <li><a href="#physical">Physical</a></li>
  <li><a href="#resonance">Resonance</a></li>
  <li><a href="#social">Social</a></li>
  <li><a href="#technical">Technical</a></li>
  <li><a href="#vehicle">Vehicle</a></li>
</ul>

<div id="combat">

<span id="combat_table"><?php print wait_Process('Loading Combat Skills...')?></span>

</div>


<div id="magical">

<span id="magical_table"><?php print wait_Process('Loading Magical Skills...')?></span>

</div>


<div id="physical">

<span id="physical_table"><?php print wait_Process('Loading Physical Skills...')?></span>

</div>


<div id="resonance">

<span id="resonance_table"><?php print wait_Process('Loading Resonance Skills...')?></span>

</div>


<div id="social">

<span id="social_table"><?php print wait_Process('Loading Social Skills...')?></span>

</div>


<div id="technical">

<span id="technical_table"><?php print wait_Process('Loading Technical Skills...')?></span>

</div>


<div id="vehicle">

<span id="vehicle_table"><?php print wait_Process('Loading Vehicle Skills...')?></span>

</div>


</div>

</div>



<div id="dialogActive" title="Active Skills">

<form name="dialog">

<input type="hidden" name="id" value="0">
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default" colspan="12">Active Skill Form</th>
</tr>
<tr>
  <td class="ui-widget-content" colspan="4">Type <input type="text" name="act_type" size="20"></td>
  <td class="ui-widget-content" colspan="4">Name <input type="text" name="act_name" size="20"></td>
  <td class="ui-widget-content" colspan="4">Group <input type="text" name="act_group" size="20"></td>
</tr>
<tr>
  <td class="ui-widget-content" colspan="3">Linked Attribute <select name="act_attribute">
<?php
  $q_string  = "select att_id,att_name ";
  $q_string .= "from attributes ";
  $q_string .= "order by att_id ";
  $q_attributes = mysql_query($q_string) or die(header("Location: " . $Siteroot . "/error.php?script=" . $package . "&error=" . $q_string . "&mysql=" . mysql_error()));
  while ($a_attributes = mysql_fetch_array($q_attributes)) {
    print "<option value=\"" . $a_attributes['att_id'] . "\">" . $a_attributes['att_name'] . "</option>\n";
  }
?>
</select></td>
  <td class="ui-widget-content" colspan="3">Default? <input type="checkbox" checked="false" name="act_default"></td>
  <td class="ui-widget-content" colspan="3">Book <select name="act_book">
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
</select></td>
  <td class="ui-widget-content" colspan="3">Page <input type="text" name="act_page" size="5"></td>
</tr>
</table>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
