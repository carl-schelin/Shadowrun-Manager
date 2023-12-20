<?php
# Script: add.active.php
# Owner: Carl Schelin
# Coding Standard 3.0 Applied
# See: https://incowk01/makers/index.php/Coding_Standards
# Description: 

  include('settings.php');
  include($Loginpath . '/check.php');
  include($Sitepath . '/function.php');
  check_login($db, $AL_Johnson);

  $package = "add.active.php";

  logaccess($db, $_SESSION['username'], $package, "Accessing script");

# if help has not been seen yet,
  if (show_Help($db, $Dataroot . "/" . $package)) {
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

  if (check_userlevel($db, $AL_Johnson)) {
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
    height: 275,
    width:  600,
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

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Combat Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('combat-listing-help');">Help</a></th>
</tr>
</table>

<div id="combat-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="combat_table"><?php print wait_Process('Loading Combat Skills...')?></span>

</div>


<div id="magical">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Magic Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('magic-listing-help');">Help</a></th>
</tr>
</table>

<div id="magic-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="magical_table"><?php print wait_Process('Loading Magical Skills...')?></span>

</div>


<div id="physical">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Physical Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('physical-listing-help');">Help</a></th>
</tr>
</table>

<div id="ammo-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="physical_table"><?php print wait_Process('Loading Physical Skills...')?></span>

</div>


<div id="resonance">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Technomancer Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('technomancer-listing-help');">Help</a></th>
</tr>
</table>

<div id="technomancer-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="resonance_table"><?php print wait_Process('Loading Resonance Skills...')?></span>

</div>


<div id="social">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Social Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('social-listing-help');">Help</a></th>
</tr>
</table>

<div id="social-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="social_table"><?php print wait_Process('Loading Social Skills...')?></span>

</div>


<div id="technical">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Technical Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('technical-listing-help');">Help</a></th>
</tr>
</table>

<div id="technical-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="technical_table"><?php print wait_Process('Loading Technical Skills...')?></span>

</div>


<div id="vehicle">

<p></p>
<table class="ui-styled-table" width="100%">
<tr>
  <th class="ui-state-default">Vehicle Listing</th>
  <th class="ui-state-default" width="20"><a href="javascript:;" onmousedown="toggleDiv('vehicle-listing-help');">Help</a></th>
</tr>
</table>

<div id="vehicle-listing-help" style="<?php print $display; ?>">

<div class="main-help ui-widget-content">


</div>

</div>

<span id="vehicle_table"><?php print wait_Process('Loading Vehicle Skills...')?></span>

</div>


</div>

</div>



<div id="dialogActive" title="Active Skills">

<form name="dialog">

<input type="hidden" name="id" value="0">

<?php
include('add.active.dialog.php');
?>

</form>

</div>


<?php include($Sitepath . '/footer.php'); ?>

</body>
</html>
